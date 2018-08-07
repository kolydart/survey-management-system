<?php

namespace App\Providers;

use App\Role;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $user = \Auth::user();

        
        // Auth gates for: Entities
        Gate::define('entity_access', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Institutions
        Gate::define('institution_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('institution_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('institution_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('institution_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('institution_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Groups
        Gate::define('group_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('group_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('group_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('group_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('group_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Categories
        Gate::define('category_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('category_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('category_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('category_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('category_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Roles
        Gate::define('role_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('role_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('role_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('role_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('role_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Users
        Gate::define('user_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('user_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('user_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('user_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('user_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Design
        Gate::define('design_access', function ($user) {
            return in_array($user->role_id, [1]);
        });

    }
}
