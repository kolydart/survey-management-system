@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

             

            <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="fa fa-wrench"></i>
                    <span class="title">@lang('quickadmin.qa_dashboard')</span>
                </a>
            </li>

            @can('survey_access')
            <li>
                <a href="{{ route('admin.surveys.index') }}">
                    <i class="fa fa-pie-chart"></i>
                    <span>@lang('quickadmin.surveys.title')</span>
                </a>
            </li>@endcan
            
            @can('questionnaire_access')
            <li>
                <a href="{{ route('admin.questionnaires.index') }}">
                    <i class="fa fa-clipboard"></i>
                    <span>@lang('quickadmin.questionnaires.title')</span>
                </a>
            </li>@endcan
            
            @can('response_access')
            <li>
                <a href="{{ route('admin.responses.index') }}">
                    <i class="fa fa-comments-o"></i>
                    <span>@lang('quickadmin.responses.title')</span>
                </a>
            </li>@endcan
            
            @can('design_access')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-paint-brush"></i>
                    <span>@lang('quickadmin.design.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @can('item_access')
                    <li>
                        <a href="{{ route('admin.items.index') }}">
                            <i class="fa fa-commenting-o"></i>
                            <span>@lang('quickadmin.items.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('question_access')
                    <li>
                        <a href="{{ route('admin.questions.index') }}">
                            <i class="fa fa-comment-o"></i>
                            <span>@lang('quickadmin.questions.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('answerlist_access')
                    <li>
                        <a href="{{ route('admin.answerlists.index') }}">
                            <i class="fa fa-chain"></i>
                            <span>@lang('quickadmin.answerlists.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('answer_access')
                    <li>
                        <a href="{{ route('admin.answers.index') }}">
                            <i class="fa fa-comment"></i>
                            <span>@lang('quickadmin.answers.title')</span>
                        </a>
                    </li>@endcan
                    
                </ul>
            </li>@endcan
            
            @can('config_access')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gears"></i>
                    <span>@lang('quickadmin.config.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @can('institution_access')
                    <li>
                        <a href="{{ route('admin.institutions.index') }}">
                            <i class="fa fa-institution"></i>
                            <span>@lang('quickadmin.institutions.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('group_access')
                    <li>
                        <a href="{{ route('admin.groups.index') }}">
                            <i class="fa fa-group"></i>
                            <span>@lang('quickadmin.groups.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('category_access')
                    <li>
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="fa fa-sitemap"></i>
                            <span>@lang('quickadmin.categories.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('role_access')
                    <li>
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span>@lang('quickadmin.roles.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('user_access')
                    <li>
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user"></i>
                            <span>@lang('quickadmin.users.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('activitylog_access')
                    <li>
                        <a href="{{ route('admin.activitylogs.index') }}">
                            <i class="fa fa-heartbeat"></i>
                            <span>@lang('quickadmin.activitylog.title')</span>
                        </a>
                    </li>@endcan
                    
                </ul>
            </li>@endcan
            
            @can('content_access')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-book"></i>
                    <span>@lang('quickadmin.content.title')</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    @can('content_page_access')
                    <li>
                        <a href="{{ route('admin.content_pages.index') }}">
                            <i class="fa fa-file-o"></i>
                            <span>@lang('quickadmin.content-pages.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('content_category_access')
                    <li>
                        <a href="{{ route('admin.content_categories.index') }}">
                            <i class="fa fa-folder"></i>
                            <span>@lang('quickadmin.content-categories.title')</span>
                        </a>
                    </li>@endcan
                    
                    @can('content_tag_access')
                    <li>
                        <a href="{{ route('admin.content_tags.index') }}">
                            <i class="fa fa-tags"></i>
                            <span>@lang('quickadmin.content-tags.title')</span>
                        </a>
                    </li>@endcan
                    
                </ul>
            </li>@endcan
            

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-line-chart"></i>
                    <span class="title">Generated Reports</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                   <li class="{{ $request->is('/reports/questionnaires') }}">
                        <a href="{{ url('/admin/reports/questionnaires') }}">
                            <i class="fa fa-line-chart"></i>
                            <span class="title">Questionnaires</span>
                        </a>
                    </li>
                </ul>
            </li>

            



            <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">@lang('quickadmin.qa_change_password')</span>
                </a>
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

