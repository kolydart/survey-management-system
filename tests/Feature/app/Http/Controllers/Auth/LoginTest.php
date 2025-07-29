<?php
/**
 * https://github.com/dczajkowski/auth-tests
 * @see README.md
 */
namespace Tests\Feature\app\Http\Controllers\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Route;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function loginGetRoute()
    {
        return route('login');
    }

    protected function loginPostRoute()
    {
        return route('login');
    }

    protected function logoutRoute()
    {
        if (Route::has('auth.logout')) {

            return route('auth.logout');

        }

        if (Route::has('logout')) {

            return route('logout');

        }

    }

    protected function getTooManyLoginAttemptsMessage()
    {
        return sprintf('/^%s$/', str_replace('\:seconds', '\d+', preg_quote(__('auth.throttle'), '/')));
    }

    /**
     * @test
     */
    public function UserCanViewALoginForm()
    {
        $response = $this->get($this->loginGetRoute());

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
    }

    /**
     * @test
     */
    public function UserCannotViewALoginFormWhenAuthenticated()
    {
        $user = $this->create_user();

        $response = $this->actingAs($user)->get($this->loginGetRoute());

        $response->assertRedirect();
    }

    /**
     * @test
     */
    public function UserCanLoginWithCorrectCredentials()
    {
        $user = $this->create_user('User',[
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $response = $this->post($this->loginPostRoute(), [
                'email' => $user->email,
                'password' => $password,
            ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function RememberMeFunctionality()
    {
        $user = $this->create_user('User',[
            'id' => random_int(1, 100),
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        $response = $this->post($this->loginPostRoute(), [
                'email' => $user->email,
                'password' => $password,
                'remember' => 'on',
            ]);

        $user = $user->fresh();

        $response->assertRedirect();
        $response->assertCookie(Auth::guard()->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function UserCannotLoginWithIncorrectPassword()
    {
        $user = $this->create_user('User',[
            'password' => Hash::make('i-love-laravel'),
        ]);

        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
                'email' => $user->email,
                'password' => 'invalid-password',
            ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function UserCannotLoginWithEmailThatDoesNotExist()
    {
        $response = $this->from($this->loginGetRoute())->post($this->loginPostRoute(), [
                'email' => 'nobody@example.com',
                'password' => 'invalid-password',
            ]);

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function UserCanLogout()
    {
        $this->be($this->create_user());

        $response = $this->post($this->logoutRoute());

        $response->assertRedirect();
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function UserCannotLogoutWhenNotAuthenticated()
    {
        $response = $this->post($this->logoutRoute());

        $response->assertRedirect();
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function UserCannotMakeMoreThanFiveAttemptsInOneMinute()
    {
        $user = $this->create_user('User',[
            'password' => Hash::make($password = 'i-love-laravel'),
        ]);

        foreach (range(0, 5) as $_) {
            $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
                ->from($this->loginGetRoute())->post($this->loginPostRoute(), [
                    'email' => $user->email,
                    'password' => 'invalid-password',
                ]);
        }

        $response->assertRedirect($this->loginGetRoute());
        $response->assertSessionHasErrors('email');
        $this->assertMatchesRegularExpression(
            $this->getTooManyLoginAttemptsMessage(),
            collect(
                $response
                    ->baseResponse
                    ->getSession()
                    ->get('errors')
                    ->getBag('default')
                    ->get('email')
            )->first()
        );
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }
}