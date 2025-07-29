<?php
/**
 * https://github.com/dczajkowski/auth-tests
 * @see README.md
 */
namespace Tests\Feature\app\Http\Controllers\Auth;

use App\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function getValidToken($user)
    {
        return Password::broker()->createToken($user);
    }

    protected function getInvalidToken()
    {
        return 'invalid-token';
    }

    protected function passwordResetGetRoute($token)
    {
        return route('password.reset', $token);
    }

    protected function passwordResetPostRoute()
    {
        return '/password/reset';
    }

    /**
     * @test
     */
    public function UserCanViewAPasswordResetForm()
    {
        $user = $this->create_user();

        $response = $this->get($this->passwordResetGetRoute($token = $this->getValidToken($user)));

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    /**
     * @test
     */
    public function UserCanViewAPasswordResetFormWhenAuthenticated()
    {
        $user = $this->create_user();

        $response = $this->actingAs($user)->get($this->passwordResetGetRoute($token = $this->getValidToken($user)));

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.reset');
        $response->assertViewHas('token', $token);
    }

    /**
     * @test
     */
    public function UserCanResetPasswordWithValidToken()
    {

        \Mail::fake();

        $user = $this->create_user();

        $response = $this->post($this->passwordResetPostRoute(), [
                'token' => $this->getValidToken($user),
                'email' => $user->email,
                'password' => 'new-awesome-password',
                'password_confirmation' => 'new-awesome-password',
            ]);

        $response->assertRedirect();
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('new-awesome-password', $user->fresh()->password));

        $response = $this->actingAs($user)->get('/');
        $this->assertAuthenticatedAs($user);

    }

    /**
     * @test
     */
    public function UserCannotResetPasswordWithInvalidToken()
    {

        $this->seed_permissions();

        $user = $this->create_user('User',[
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($this->getInvalidToken()))->post($this->passwordResetPostRoute(), [
                'token' => $this->getInvalidToken(),
                'email' => $user->email,
                'password' => 'new-awesome-password',
                'password_confirmation' => 'new-awesome-password',
            ]);

        $response->assertRedirect($this->passwordResetGetRoute($this->getInvalidToken()));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function UserCannotResetPasswordWithoutProvidingANewPassword()
    {
        $this->seed_permissions();

        $user = $this->create_user('User',[
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
                'token' => $token,
                'email' => $user->email,
                'password' => '',
                'password_confirmation' => '',
            ]);

        $response->assertRedirect($this->passwordResetGetRoute($token));
        $response->assertSessionHasErrors('password');
        $this->assertTrue(session()->hasOldInput('email'));
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function UserCannotResetPasswordWithoutProvidingAnEmail()
    {

        $this->seed_permissions();

        $user = $this->create_user('User',[
            'password' => Hash::make('old-password'),
        ]);

        $response = $this->from($this->passwordResetGetRoute($token = $this->getValidToken($user)))->post($this->passwordResetPostRoute(), [
                'token' => $token,
                'email' => '',
                'password' => 'new-awesome-password',
                'password_confirmation' => 'new-awesome-password',
            ]);

        $response->assertRedirect($this->passwordResetGetRoute($token));
        $response->assertSessionHasErrors('email');
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertEquals($user->email, $user->fresh()->email);
        $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
        $this->assertGuest();
    }
}