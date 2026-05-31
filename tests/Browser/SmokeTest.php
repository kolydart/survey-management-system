<?php

namespace Tests\Browser;

use App\User;
use Facebook\WebDriver\Exception\NoSuchAlertException;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

class SmokeTest extends DuskTestCase
{
    /** Route names να εξαιρεθούν (logout, destructive ops, redirects). */
    protected array $skipNames = [
        'admin.logout',
        'admin.users.massDestroy',
        'admin.globalSearch',
        'debugbar.assets.js',
        'debugbar.assets.css',
        'debugbar.openhandler',
        'ignition.healthCheck',
        'dusk.login',
        'dusk.logout',
        'dusk.user',
    ];

    /** URI prefixes που δεν είναι page endpoints. */
    protected array $skipUriPrefixes = [
        '_ignition', '_debugbar', '_dusk', '_boost',
        'livewire/', 'sanctum/', 'api/', 'storage/',
        'oauth/', 'horizon/', 'telescope/',
    ];

    /** Console messages που αγνοούνται (noisy / 3rd-party). */
    protected array $ignoredConsolePatterns = [
        'favicon.ico',
        'chrome-extension://',
        'DevTools',
    ];

    #[Test]
    public function admin_index_routes_smoke_pass(): void
    {
        $admin = $this->getAdminUser();

        $routes = $this->discoverRoutes(fn ($name) => Str::is('admin.*.index', $name));

        $this->assertNotEmpty($routes, 'No admin *.index routes discovered.');

        $failures = [];

        $this->browse(function (Browser $browser) use ($admin, $routes, &$failures) {
            $browser->loginAs($admin->id);

            foreach ($routes as $route) {
                $error = $this->visitAndCollect($browser, $route);
                if ($error !== null) {
                    $failures[] = $error;
                }
            }
        });

        $this->assertEmpty(
            $failures,
            count($failures)." admin route(s) failed:\n\n".implode("\n\n", $failures)
        );
    }

    #[Test]
    public function frontend_routes_smoke_pass(): void
    {
        $routes = $this->discoverRoutes(fn ($name) => Str::startsWith($name, 'frontend.'));

        if ($routes->isEmpty()) {
            $this->markTestSkipped('No frontend routes discovered.');
        }

        $failures = [];

        $this->browse(function (Browser $browser) use ($routes, &$failures) {
            foreach ($routes as $route) {
                $error = $this->visitAndCollect($browser, $route);
                if ($error !== null) {
                    $failures[] = $error;
                }
            }
        });

        $this->assertEmpty(
            $failures,
            count($failures)." frontend route(s) failed:\n\n".implode("\n\n", $failures)
        );
    }

    protected function discoverRoutes(callable $nameMatcher): \Illuminate\Support\Collection
    {
        return collect(Route::getRoutes())
            ->filter(fn ($r) => in_array('GET', $r->methods(), true))
            ->filter(fn ($r) => $r->getName() !== null)
            ->filter(fn ($r) => $nameMatcher($r->getName()))
            ->filter(fn ($r) => ! str_contains($r->uri(), '{'))
            ->filter(fn ($r) => ! in_array($r->getName(), $this->skipNames, true))
            ->filter(fn ($r) => ! Str::startsWith($r->uri(), $this->skipUriPrefixes))
            ->values();
    }

    /** Επιστρέφει error message string σε failure, ή null σε success. */
    protected function visitAndCollect(Browser $browser, RoutingRoute $route): ?string
    {
        $name = $route->getName();
        $uri = '/'.ltrim($route->uri(), '/');

        fwrite(STDOUT, "  → {$name} ({$uri}) ... ");

        try {
            $browser->visit($uri)->pause(1200);
        } catch (\Throwable $e) {
            fwrite(STDOUT, "ERROR\n");

            return "Route {$name} ({$uri}): visit threw ".get_class($e).': '.$e->getMessage();
        }

        // 1. Browser-level alert (DataTables warnings, custom JS alerts)
        try {
            $alertText = $browser->driver->switchTo()->alert()->getText();
            $browser->driver->switchTo()->alert()->accept();
            $browser->driver->manage()->getLog('browser'); // drain so logs don't leak to next route

            return "Route {$name} ({$uri}): unexpected browser alert: {$alertText}";
        } catch (NoSuchAlertException $e) {
            // expected — no alert
        }

        // 2. Console SEVERE errors
        $logs = collect($browser->driver->manage()->getLog('browser'))
            ->where('level', 'SEVERE')
            ->reject(function ($entry) {
                foreach ($this->ignoredConsolePatterns as $pattern) {
                    if (Str::contains($entry['message'] ?? '', $pattern)) {
                        return true;
                    }
                }
                return false;
            })
            ->values();

        if ($logs->isNotEmpty()) {
            fwrite(STDOUT, "FAIL (console errors)\n");

            return "Route {$name} ({$uri}): console errors:\n  - ".$logs->pluck('message')->implode("\n  - ");
        }

        // 3. Rendered server-error indicators
        try {
            $browser->assertMissing('.alert-danger');
        } catch (\Throwable $e) {
            fwrite(STDOUT, "FAIL (.alert-danger)\n");

            return "Route {$name} ({$uri}): rendered .alert-danger on page";
        }

        fwrite(STDOUT, "ok\n");

        return null;
    }

    protected function getAdminUser(): User
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('title', 'Admin'))->first();

        if (! $admin) {
            $this->fail('No Admin user found in dusk database. Run: cphp artisan migrate:fresh --seed --env=dusk.local');
        }

        return $admin;
    }
}
