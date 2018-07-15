@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

            <li>
                <a href="{{ url('/') }}">
                    <i class="fa fa-wrench"></i>
                    <span class="title">@lang('quickadmin.qa_dashboard')</span>
                </a>
            </li>

            <li v-if="$can('survey_access')">
                <router-link :to="{ name: 'surveys.index' }">
                    <i class="fa fa-pie-chart"></i>
                    <span>@lang('quickadmin.surveys.title')</span>
                </router-link>
            </li>
            <li v-if="$can('questionnaire_access')">
                <router-link :to="{ name: 'questionnaires.index' }">
                    <i class="fa fa-clipboard"></i>
                    <span>@lang('quickadmin.questionnaires.title')</span>
                </router-link>
            </li>
            <li v-if="$can('response_access')">
                <router-link :to="{ name: 'responses.index' }">
                    <i class="fa fa-comments-o"></i>
                    <span>@lang('quickadmin.responses.title')</span>
                </router-link>
            </li>
            <li class="treeview" v-if="$can('design_access')">
                <a href="#">
                    <i class="fa fa-paint-brush"></i>
                    <span>@lang('quickadmin.design.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li v-if="$can('item_access')">
                        <router-link :to="{ name: 'items.index' }">
                            <i class="fa fa-commenting-o"></i>
                            <span>@lang('quickadmin.items.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('question_access')">
                        <router-link :to="{ name: 'questions.index' }">
                            <i class="fa fa-comment-o"></i>
                            <span>@lang('quickadmin.questions.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('answerlist_access')">
                        <router-link :to="{ name: 'answerlists.index' }">
                            <i class="fa fa-chain"></i>
                            <span>@lang('quickadmin.answerlists.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('answer_access')">
                        <router-link :to="{ name: 'answers.index' }">
                            <i class="fa fa-comment"></i>
                            <span>@lang('quickadmin.answers.title')</span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="treeview" v-if="$can('entity_access')">
                <a href="#">
                    <i class="fa fa-gears"></i>
                    <span>@lang('quickadmin.entities.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li v-if="$can('institution_access')">
                        <router-link :to="{ name: 'institutions.index' }">
                            <i class="fa fa-institution"></i>
                            <span>@lang('quickadmin.institution.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('group_access')">
                        <router-link :to="{ name: 'groups.index' }">
                            <i class="fa fa-group"></i>
                            <span>@lang('quickadmin.group.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('category_access')">
                        <router-link :to="{ name: 'categories.index' }">
                            <i class="fa fa-sitemap"></i>
                            <span>@lang('quickadmin.category.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('user_access')">
                        <router-link :to="{ name: 'users.index' }">
                            <i class="fa fa-user"></i>
                            <span>@lang('quickadmin.users.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('role_access')">
                        <router-link :to="{ name: 'roles.index' }">
                            <i class="fa fa-briefcase"></i>
                            <span>@lang('quickadmin.roles.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('permission_access')">
                        <router-link :to="{ name: 'permissions.index' }">
                            <i class="fa fa-briefcase"></i>
                            <span>@lang('quickadmin.permissions.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('user_action_access')">
                        <router-link :to="{ name: 'user_actions.index' }">
                            <i class="fa fa-th-list"></i>
                            <span>@lang('quickadmin.user-actions.title')</span>
                        </router-link>
                    </li>
                </ul>
            </li>
            <li class="treeview" v-if="$can('content_access')">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>@lang('quickadmin.content.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li v-if="$can('content_category_access')">
                        <router-link :to="{ name: 'content_categories.index' }">
                            <i class="fa fa-folder"></i>
                            <span>@lang('quickadmin.content-categories.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('content_page_access')">
                        <router-link :to="{ name: 'content_pages.index' }">
                            <i class="fa fa-file-o"></i>
                            <span>@lang('quickadmin.content-pages.title')</span>
                        </router-link>
                    </li>
                    <li v-if="$can('content_tag_access')">
                        <router-link :to="{ name: 'content_tags.index' }">
                            <i class="fa fa-tags"></i>
                            <span>@lang('quickadmin.content-tags.title')</span>
                        </router-link>
                    </li>
                </ul>
            </li>

            <li>
                <router-link :to="{ name: 'auth.change_password' }">
                    <i class="fa fa-key"></i>
                    <span class="title">@lang('quickadmin.qa_change_password')</span>
                </router-link>
            </li>

            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('quickadmin.qa_logout')</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
