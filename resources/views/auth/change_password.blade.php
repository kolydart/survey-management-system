@extends('layouts.app')

@section('content')
	<h3 class="page-title">@lang('quickadmin.qa_change_password')</h3>

	@if(session('success'))
		<!-- If password successfully show message -->
		<div class="row">
			<div class="alert alert-success">
				{{ session('success') }}
			</div>
		</div>
	@else
		<form action="{{ route('auth.change_password') }}" method="POST">
			@csrf
			@method('PATCH')
		<!-- If no success message in flash session show change password form  -->
		<div class="panel panel-default">
			<div class="panel-heading">
				@lang('quickadmin.qa_edit')
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 form-group">
						<label for="current_password" class="control-label">{{ trans('quickadmin.qa_current_password') }}</label>
						<input type="password" name="current_password" id="current_password" class="form-control" placeholder="">
						<p class="help-block"></p>
						@if($errors->has('current_password'))
							<p class="help-block">
								{{ $errors->first('current_password') }}
							</p>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 form-group">
						<label for="new_password" class="control-label">{{ trans('quickadmin.qa_new_password') }}</label>
						<input type="password" name="new_password" id="new_password" class="form-control" placeholder="">
						<p class="help-block"></p>
						@if($errors->has('new_password'))
							<p class="help-block">
								{{ $errors->first('new_password') }}
							</p>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 form-group">
						<label for="new_password_confirmation" class="control-label">{{ trans('quickadmin.qa_password_confirm') }}</label>
						<input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="">
						<p class="help-block"></p>
						@if($errors->has('new_password_confirmation'))
							<p class="help-block">
								{{ $errors->first('new_password_confirmation') }}
							</p>
						@endif
					</div>
				</div>
			</div>
		</div>

		<button type="submit" class="btn btn-danger">{{ trans('quickadmin.qa_save') }}</button>
		</form>
	@endif
@stop

