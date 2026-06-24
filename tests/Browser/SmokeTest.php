<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Support\Str;
use Kolydart\Laravel\App\Testing\InteractsWithSmokeCrawler;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;

class SmokeTest extends DuskTestCase
{
    use InteractsWithSmokeCrawler;

    /** Route names to skip (logout, destructive ops, redirects). */
    protected array $skipNames = [
        'admin.logout',
        'admin.users.massDestroy',
        'admin.globalSearch',
        // The seeded Admin role usually does NOT have permission_create/edit or
        // role_create/edit permissions, so these pages return 403. Remove them
        // here only if Admin is granted those perms in this project.
        'admin.permissions.create',
        'admin.permissions.edit',
        'admin.roles.create',
        'admin.roles.edit',
        'debugbar.assets.js',
        'debugbar.assets.css',
        'debugbar.openhandler',
        'ignition.healthCheck',
        'dusk.login',
        'dusk.logout',
        'dusk.user',
    ];

    /** URI prefixes that are not page endpoints. */
    protected array $skipUriPrefixes = [
        '_ignition', '_debugbar', '_dusk', '_boost',
        'livewire/', 'sanctum/', 'api/', 'storage/',
        'oauth/', 'horizon/', 'telescope/',
    ];

    /** Console messages to ignore (noisy / 3rd-party). */
    protected array $ignoredConsolePatterns = [
        'favicon.ico',
        'chrome-extension://',
        'DevTools',
    ];

    /** Model namespace used to resolve show/edit records. */
    protected string $modelNamespace = 'App\\';

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
    public function admin_show_routes_smoke_pass(): void
    {
        $admin = $this->getAdminUser();

        $routes = $this->discoverRoutes(fn ($name) => Str::is('admin.*.show', $name), allowParameters: true);

        $this->assertNotEmpty($routes, 'No admin *.show routes discovered.');

        $failures = [];

        $this->browse(function (Browser $browser) use ($admin, $routes, &$failures) {
            $browser->loginAs($admin->id);

            foreach ($routes as $route) {
                $uri = $this->resolveUri($route);
                if ($uri === null) {
                    continue;
                }

                $error = $this->visitUri($browser, $route->getName(), $uri);
                if ($error !== null) {
                    $failures[] = $error;
                }
            }
        });

        $this->assertEmpty(
            $failures,
            count($failures)." admin show route(s) failed:\n\n".implode("\n\n", $failures)
        );
    }

    #[Test]
    public function admin_edit_routes_smoke_pass(): void
    {
        $admin = $this->getAdminUser();

        $routes = $this->discoverRoutes(fn ($name) => Str::is('admin.*.edit', $name), allowParameters: true);

        $this->assertNotEmpty($routes, 'No admin *.edit routes discovered.');

        $failures = [];

        $this->browse(function (Browser $browser) use ($admin, $routes, &$failures) {
            $browser->loginAs($admin->id);

            foreach ($routes as $route) {
                $uri = $this->resolveUri($route);
                if ($uri === null) {
                    continue;
                }

                $error = $this->visitUri($browser, $route->getName(), $uri);
                if ($error !== null) {
                    $failures[] = $error;
                }
            }
        });

        $this->assertEmpty(
            $failures,
            count($failures)." admin edit route(s) failed:\n\n".implode("\n\n", $failures)
        );
    }

    #[Test]
    public function admin_create_routes_smoke_pass(): void
    {
        $admin = $this->getAdminUser();

        $routes = $this->discoverRoutes(fn ($name) => Str::is('admin.*.create', $name));

        $this->assertNotEmpty($routes, 'No admin *.create routes discovered.');

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
            count($failures)." admin create route(s) failed:\n\n".implode("\n\n", $failures)
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

    protected function getAdminUser(): User
    {
        $admin = User::whereHas('role', fn ($q) => $q->where('title', 'Admin'))->first();

        if (! $admin) {
            $this->fail('No Admin user found in dusk database. Run: cphp artisan migrate:fresh --seed --env=dusk.local');
        }

        return $admin;
    }
}
