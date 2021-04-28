<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            \App\Listeners\LogRegisteredUser::class,
        ],

        'Illuminate\Auth\Events\Login' => [
            \App\Listeners\LogSuccessfulLogin::class,
        ],

        'Illuminate\Auth\Events\Failed' => [
            \App\Listeners\LogFailedLogin::class,
        ],

        'Illuminate\Auth\Events\Logout' => [
            \App\Listeners\LogSuccessfulLogout::class,
        ],

        'Illuminate\Auth\Events\Lockout' => [
            \App\Listeners\LogLockout::class,
        ],

        'Illuminate\Auth\Events\PasswordReset' => [
            \App\Listeners\LogPasswordReset::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

        //
    }
}
