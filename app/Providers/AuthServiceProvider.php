<?php

namespace App\Providers;

use App\Role;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

        // Auth gates for: Surveys
        Gate::define('survey_access', function ($user) {
            return in_array($user->role_id, [1,2,3]);
        });
        Gate::define('survey_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('survey_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('survey_view', function ($user) {
            return in_array($user->role_id, [1,2,3]);
        });
        Gate::define('survey_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Questionnaires
        Gate::define('questionnaire_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('questionnaire_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('questionnaire_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('questionnaire_view', function ($user) {
            return in_array($user->role_id, [1,2,3]);
        });
        Gate::define('questionnaire_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Responses
        Gate::define('response_access', function ($user) {
            return in_array($user->role_id, [1,2,3]);
        });
        Gate::define('response_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('response_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('response_view', function ($user) {
            return in_array($user->role_id, [1,2,3]);
        });
        Gate::define('response_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Design
        Gate::define('design_access', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Items
        Gate::define('item_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('item_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('item_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('item_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('item_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Questions
        Gate::define('question_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('question_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('question_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('question_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('question_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Answerlists
        Gate::define('answerlist_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('answerlist_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('answerlist_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('answerlist_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('answerlist_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Answers
        Gate::define('answer_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('answer_create', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('answer_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('answer_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('answer_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Content
        Gate::define('content_access', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });

        // Auth gates for: Content pages
        Gate::define('content_page_access', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_page_create', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_page_edit', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_page_view', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_page_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Content categories
        Gate::define('content_category_access', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_category_create', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_category_edit', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_category_view', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_category_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Content tags
        Gate::define('content_tag_access', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_tag_create', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_tag_edit', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_tag_view', function ($user) {
            return in_array($user->role_id, [1, 2]);
        });
        Gate::define('content_tag_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Config
        Gate::define('config_access', function ($user) {
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

        // Auth gates for: Activitylog
        Gate::define('activitylog_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('activitylog_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('activitylog_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('activitylog_delete', function ($user) {
            return in_array($user->role_id, [1]);
        });

        // Auth gates for: Loguseragent
        Gate::define('loguseragent_access', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('loguseragent_edit', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('loguseragent_view', function ($user) {
            return in_array($user->role_id, [1]);
        });
        Gate::define('loguseragent_delete', function ($user) {
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
    }
}
