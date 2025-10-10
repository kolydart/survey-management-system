@extends('layouts.app')

@section('title', trans('quickadmin.surveys.title') . ' | ' . trans('quickadmin.qa_view') . ' | ' . $survey->id)

@section('content')
<h3 class="page-title hidden-print">@lang('quickadmin.surveys.title')</h3>
<div class="panel panel-default">
	<div class="panel-heading">
		{{ config('app.name') }} <span class="hidden-print">@lang('quickadmin.qa_view')</span>
	</div>

	<div class="panel-body table-responsive">
		<div class="row" style="margin-bottom:10px;">

			@can('survey_create')
				<div class="col-md-6">
					<table class="table table-bordered table-striped hidden-print">
						<tr>
							<th>@lang('quickadmin.surveys.fields.title')</th>
							<td field-key='title'>{{ $survey->title }}</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.alias')</th>
							<td field-key='alias'>{{ $survey->alias }}</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.category')</th>
							<td field-key='category'>
								@foreach ($survey->category as $singleCategory)
									<span class="label label-info label-many">{{ $singleCategory->title }}</span>
								@endforeach
							</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.group')</th>
							<td field-key='group'>
								@foreach ($survey->group as $singleGroup)
									<span class="label label-info label-many">{{ $singleGroup->title }}</span>
								@endforeach
							</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.introduction')</th>
							<td field-key='introduction'>{!! $survey->introduction !!}</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.javascript')</th>
							<td field-key='javascript'>{!! $survey->javascript !!}</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.notes')</th>
							<td field-key='notes'>{!! $survey->notes !!}</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.inform')</th>
							<td field-key='inform'>{{ Form::checkbox("inform", 1, $survey->inform == 1 ? true : false, ["disabled"]) }}</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.access')</th>
							<td field-key='access'>{{ $survey->access }}</td>
						</tr>
						<tr>
							<th>@lang('quickadmin.surveys.fields.completed')</th>
							<td field-key='completed'>{{ Form::checkbox("completed", 1, $survey->completed == 1 ? true : false, ["disabled"]) }}</td>
						</tr>
						<tr>
							<th>@lang('Filled Questionnaires')</th>
							<td field-key='filled'><strong>{{$survey->questionnaires->count()}}</strong></td>
						</tr>
						<x-dates-in-show :model="$survey" />
					</table>
				</div>
			@endcan
			<div class="col-sm-2">
				<a href="{{route('frontend.create',$survey->alias)}}" class="btn btn-success hidden-print" target="_blank"><i class="fa fa-street-view"></i> @lang('User view')</a>
			</div>
			@can('survey_create')
				<div class="col-sm-2">
					<a href="{{route('admin.surveys.clone',$survey->id)}}" class="btn btn-warning hidden-print"><i class="fa fa-copy"></i> @lang('Clone')</a>
				</div>
			@endcan
		</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs hidden-print" role="tablist">
	<li role="presentation" class="active"><a href="#report" aria-controls="report" role="tab" data-toggle="tab">Report</a></li>
	<li role="presentation" class=""><a href="#questionnaires" aria-controls="questionnaires" role="tab" data-toggle="tab">Questionnaires</a></li>
	@can('item_edit')
	<li role="presentation" class=""><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Items</a></li>
	@endcan
	@can('activitylog_view')
	<li role="presentation" class=""><a href="#duplicates" aria-controls="duplicates" role="tab" data-toggle="tab">Duplicates @if(!request()->has('check_duplicates'))@endif</a></li>
	@endcan
</ul>

<!-- Tab panes -->
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="report">
		@include('partials.questionnaireRender')
		</div>
	<div role="tabpanel" class="tab-pane" id="questionnaires">
		<table class="table table-bordered table-striped {{ count($questionnaires) > 0 ? 'datatable' : '' }}">
			<thead>
				<tr>
					<th style="width: 10px;">@lang('id')</th>
					<th>@lang('%')</th>
					<th>@lang('Date')</th>
								<th>@lang('quickadmin.questionnaires.fields.name')</th>
								@if( request('show_deleted') == 1 )
								<th>&nbsp;</th>
								@else
								<th>&nbsp;</th>
								@endif
				</tr>
			</thead>

			<tbody>
				@if (count($questionnaires) > 0)
					@foreach ($questionnaires as $questionnaire)
						<tr data-entry-id="{{ $questionnaire->id }}">
										<td field-key='id'><a href="{{route('admin.questionnaires.show',$questionnaire->id)}}">{{ $questionnaire->id }}</a></td>
										<td field-key='completed'> {{ $questionnaire->filled_percent }} </td>
										<td field-key='date'>{{ $questionnaire->created_at->toFormattedDateString() }}</td>
										<td field-key='name'>{{ $questionnaire->name }}</td>
										@if( request('show_deleted') == 1 )
										<td>
											@can('questionnaire_delete')
																				{!! Form::open(array(
												'style' => 'display: inline-block;',
												'method' => 'POST',
												'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
												'route' => ['admin.questionnaires.restore', $questionnaire->id])) !!}
											{!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
											{!! Form::close() !!}
										@endcan
											@can('questionnaire_delete')
																				{!! Form::open(array(
												'style' => 'display: inline-block;',
												'method' => 'DELETE',
												'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
												'route' => ['admin.questionnaires.perma_del', $questionnaire->id])) !!}
											{!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
											{!! Form::close() !!}
										@endcan
										</td>
										@else
										<td>
											@can('questionnaire_view')
											<a href="{{ route('admin.questionnaires.show',[$questionnaire->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
											@endcan
											@can('questionnaire_edit')
											<a href="{{ route('admin.questionnaires.edit',[$questionnaire->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
											@endcan
											@can('questionnaire_delete')
											{!! Form::open(array(
												'style' => 'display: inline-block;',
												'method' => 'DELETE',
												'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
												'route' => ['admin.questionnaires.destroy', $questionnaire->id])) !!}
												{!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
											{!! Form::close() !!}
											@endcan
										</td>
										@endif
						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="7">@lang('quickadmin.qa_no_entries_in_table')</td>
					</tr>
				@endif
			</tbody>
		</table>
		</div>
	@can('item_edit')
	<div role="tabpanel" class="tab-pane " id="items">
		<table class="table table-bordered table-striped {{ count($items) > 0 ? 'datatable' : '' }}">
			<thead>
				<tr>
								<th style="width: 10px;">@lang('id')</th>
								<th>@lang('quickadmin.items.fields.order')</th>
								<th>@lang('quickadmin.items.fields.question')</th>
								<th>@lang('Answerlist')</th>
								<th>@lang('Responses')</th>
								<th>@lang('quickadmin.items.fields.label')</th>
								@if( request('show_deleted') == 1 )
								<th>&nbsp;</th>
								@else
								<th>&nbsp;</th>
								@endif
				</tr>
			</thead>

			<tbody>
				@if (count($items) > 0)
					@foreach ($items as $item)
						<tr data-entry-id="{{ $item->id }}">
										<td field-key='id'><a href="{{route('admin.items.show',$item->id)}}">{{ $item->id }}</a></td>
										<td field-key='order'>{{ $item->order }}</td>
										<td field-key='question'>@if (!$item->label)
											<a href="{{route('admin.questions.show',$item->question->id)}}">{{ $item->question->title ?? '' }}</a>
										@endif</td>
										<td field-key='answerlist'>@if (!$item->label)
											<a href="{{route('admin.answerlists.show',$item->question->answerlist->id)}}">{{ $item->question->answerlist->title ?? '' }}</a>
										@endif</td>
										<td field-key='responses'>@if (!$item->label)
											{{App\Response::whereIn('questionnaire_id',$survey->questionnaires->pluck('id'))->where('question_id',$item->question->id)->count()}}
										@endif</td>
										<td field-key='label'>{{ Form::checkbox("label", 1, $item->label == 1 ? true : false, ["disabled"]) }}</td>
										@if( request('show_deleted') == 1 )
										<td>
											@can('item_delete')
																				{!! Form::open(array(
												'style' => 'display: inline-block;',
												'method' => 'POST',
												'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
												'route' => ['admin.items.restore', $item->id])) !!}
											{!! Form::submit(trans('quickadmin.qa_restore'), array('class' => 'btn btn-xs btn-success')) !!}
											{!! Form::close() !!}
										@endcan
											@can('item_delete')
																				{!! Form::open(array(
												'style' => 'display: inline-block;',
												'method' => 'DELETE',
												'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
												'route' => ['admin.items.perma_del', $item->id])) !!}
											{!! Form::submit(trans('quickadmin.qa_permadel'), array('class' => 'btn btn-xs btn-danger')) !!}
											{!! Form::close() !!}
										@endcan
										</td>
										@else
										<td>
											@can('item_view')
											<a href="{{ route('admin.items.show',[$item->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
											@endcan
											@can('item_edit')
											<a href="{{ route('admin.items.edit',[$item->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
											@endcan
											@can('item_delete')
											{!! Form::open(array(
												'style' => 'display: inline-block;',
												'method' => 'DELETE',
												'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
												'route' => ['admin.items.destroy', $item->id])) !!}
												{!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
											{!! Form::close() !!}
											@endcan
										</td>
										@endif
						</tr>
					@endforeach
				@else
					<tr>
						<td colspan="9">@lang('quickadmin.qa_no_entries_in_table')</td>
					</tr>
				@endif
			</tbody>
		</table>
		</div>
	@endcan
	@can('activitylog_view')
	<div role="tabpanel" class="tab-pane " id="duplicates">
		@if(!request()->has('check_duplicates'))
			<div class="alert alert-info">
				<h4><i class="fa fa-info-circle"></i> Select Duplicate Detection Method</h4>
				<p>Duplicate detection is resource-intensive. Choose a detection method below:</p>

				<div class="" role="group" style="margin-bottom: 15px;">
					<a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'activity_log']) }}#duplicates"
					   class="btn btn-primary">
						<i class="fa fa-hand-o-up"></i> IP + Browser Fingerprint
					</a>
					<a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'similarity']) }}#duplicates"
					   class="btn btn-warning">
						<i class="fa fa-clone"></i> Content Similarity ({{ config('app.duplicate_similarity_threshold') }}%+)
					</a>
				</div>

				<div class="" style="margin-top: 10px;">
					<p><strong><i class="fa fa-hand-o-up"></i> IP + Browser Fingerprint:</strong></p>
					<ul>
						<li>Fast detection based on IP address and browser fingerprint</li>
						<li>Detects submissions from the same device/browser</li>
						<li>Best for: Quick checks and same-device duplicates</li>
					</ul>

					<p><strong><i class="fa fa-clone"></i> Content Similarity ({{ config('app.duplicate_similarity_threshold') }}%+):</strong></p>
					<ul>
						<li>Slower but more accurate - compares actual response content</li>
						<li>Detects duplicates even if IP/browser changes</li>
						<li>Best for: Thorough analysis and sophisticated duplicate detection</li>
					</ul>
				</div>
			</div>
		@else
			<div class="alert alert-success" style="margin-bottom: 15px;">
				<strong><i class="fa fa-check-circle"></i> Detection Method:</strong>
				@if(request('method') === 'similarity')
					<span class="label label-warning">Content Similarity ({{ config('app.duplicate_similarity_threshold') }}%+ threshold)</span>
					<p style="margin-top: 10px; margin-bottom: 0;">
						<small>Comparing response content using Levenshtein similarity algorithm.</small>
					</p>
				@else
					<span class="label label-primary">IP + Browser Fingerprint</span>
					<p style="margin-top: 10px; margin-bottom: 0;">
						<small>Grouping submissions by IP address and user agent fingerprint from activity logs.</small>
					</p>
				@endif

				<div style="margin-top: 10px;">
					@if(request('method') === 'similarity')
						<a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'activity_log']) }}#duplicates" class="btn btn-sm btn-primary">
							<i class="fa fa-hand-o-up"></i> Switch to IP + Browser Fingerprint
						</a>
					@else
						<a href="{{ request()->fullUrlWithQuery(['check_duplicates' => 1, 'method' => 'similarity']) }}#duplicates" class="btn btn-sm btn-warning">
							<i class="fa fa-clone"></i> Switch to Content Similarity
						</a>
					@endif
				</div>
			</div>
		<table class="table table-bordered table-striped {{ count($duplicates) > 0 ? 'datatable' : '' }}">
			<thead>
				<tr>
					<th style="width: 10px;">id</th>
					<th style="width: 10px;">qstnr</th>
					<th>date</th>
					<th>ip</th>
					<th>os</th>
					<th style="width: 10px;">os_version</th>
					<th>browser</th>
					<th>browser_version</th>
					<th>device</th>
					<th style="width: 10px;">language</th>
					<th>uri</th>
					<th>subm</th>
					<th>user</th>
				</tr>
			</thead>
			<tbody>
				@if (count($duplicates) > 0)
					@foreach ($duplicates as $duplicate)
						<tr>
							<td field-key='id' class="@if($duplicate['type']=='ipsw') font-weight-bold @endif">{{$duplicate['type']}}</td>
				            <td field-key='item_id' class="@if ($duplicate['type']=='ipsw') font-weight-bold @endif">{{$duplicate['count']}}</td>
				            <td field-key='created_at'>
				            	@if($duplicate['type'] === 'similarity' && isset($duplicate['similarity_score']))
				            		<span class="label label-warning" style="font-size: 14px; padding: 6px 10px;">
				            			<i class="fa fa-clone"></i> Similarity: {{ $duplicate['similarity_score'] }}%
				            		</span>
				            	@endif
				            </td>
				            <td field-key='ipv6'></td>
				            <td field-key='os'></td>
				            <td field-key='os_version'></td>
				            <td field-key='browser'></td>
				            <td field-key='browser_version'></td>
				            <td field-key='device'></td>
				            <td field-key='language'></td>
				            <td field-key='uri'></td>
				            <td field-key='form_submitted'></td>
				            <td field-key='user'></td>
						</tr>
						@foreach ($duplicate['loguseragents'] as $loguseragent)
							<tr>
								<td field-key='id'>{{ $loguseragent->id }}</td>
					            <td field-key='item_id'><a href="{{route('admin.questionnaires.show',$loguseragent->item_id)}}">{{ $loguseragent->item_id }}</a></td>
					            <td field-key='created_at'>{{ $loguseragent->created_at }}</td>
					            <td field-key='ipv6'>
					            	@unless(request('method') === 'similarity')
					            		<x-ip-converter :hex="$loguseragent->ipv6" />
					            	@endunless
					            </td>
					            <td field-key='os'>{{ $loguseragent->os }}</td>
					            <td field-key='os_version'>{{ $loguseragent->os_version }}</td>
					            <td field-key='browser'>{{ $loguseragent->browser }}</td>
					            <td field-key='browser_version'>{{ $loguseragent->browser_version }}</td>
					            <td field-key='device'>{{ $loguseragent->device }}</td>
					            <td field-key='language'>{{ $loguseragent->language }}</td>
					            <td field-key='uri'>{{ $loguseragent->uri }}</td>
					            <td field-key='form_submitted'>{{ Form::checkbox("form_submitted", 1, $loguseragent->form_submitted == 1 ? true : false, ["disabled"]) }}</td>
					            <td field-key='user'>{{ $loguseragent->user->name ?? '' }}</td>
							</tr>
						@endforeach
					@endforeach
				@else
					<tr>
						<td colspan="13">@lang('quickadmin.qa_no_entries_in_table')</td>
					</tr>
				@endif
			</tbody>
		</table>
		@endif



		</div>
	@endcan
</div>

{{-- back to list btn --}}
	<a href="{{ route('admin.surveys.index') }}" class="btn btn-default mt-5 hidden-print">@lang('quickadmin.qa_back_to_list')</a>

{{-- View mode switcher buttons --}}
	<div class="btn-group hidden-print" role="group" style="margin-left: 10px;">
		<a href="{{route('admin.surveys.show', ['survey'=>$survey])}}"
		   class="btn btn-{{ (!request('view') || request('view') == 'graph') ? 'primary' : 'default' }}">
			<i class="fa fa-bar-chart"></i> Charts
		</a>
		<a href="{{route('admin.surveys.show', ['survey'=>$survey, 'view'=>'text'])}}"
		   class="btn btn-{{ request('view') == 'text' ? 'primary' : 'default' }}">
			<i class="fa fa-list"></i> Text Results
		</a>
		<a href="{{route('admin.surveys.show', ['survey'=>$survey, 'view'=>'json'])}}"
		   class="btn btn-default"
		   title="Export all data including individual responses">
			<i class="fa fa-download"></i> JSON
		</a>
		<a href="{{route('admin.surveys.show', ['survey'=>$survey, 'view'=>'json-results'])}}"
		   class="btn btn-default"
		   title="Export aggregated results only (no individual responses)">
			<i class="fa fa-download"></i> JSON Results
		</a>
		<a href="{{route('admin.surveys.show', ['survey'=>$survey, 'view'=>'csv'])}}"
		   class="btn btn-default"
		   title="Export all individual responses in CSV format">
			<i class="fa fa-download"></i> CSV
		</a>
		<a href="{{route('admin.surveys.show', ['survey'=>$survey, 'view'=>'csv-results'])}}"
		   class="btn btn-default"
		   title="Export aggregated results in CSV format (perfect for pivot tables)">
			<i class="fa fa-download"></i> CSV Results
		</a>
	</div>
		</div>
</div>

@stop

@section('javascript')
<script>
// Auto-activate duplicates tab and scroll when hash is present
$(document).ready(function() {
    if (window.location.hash === '#duplicates') {
        // Activate the duplicates tab
        $('.nav-tabs a[href="#duplicates"]').tab('show');

        // Scroll to the duplicates section after a short delay to ensure tab is loaded
        setTimeout(function() {
            $('html, body').animate({
                scrollTop: $('#duplicates').offset().top - 100
            }, 500);
        }, 100);
    }
});
</script>
@endsection