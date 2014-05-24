@extends('layouts.default')

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>{{{ trans('admin/users/title.create_a_new_user')}}} </h3>
	</div>
	{{ ends_with(URL::current(),'users/create') }}
	{{-- Create User Form --}}
	<form id="create_user" class="form-horizontal col-md-8 validate" method="post" action="@if ($user->exists){{ URL::to('admin/users/' . $user->id . '/edit') }}@endif" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

				<!-- username -->
				<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="username">{{ trans('general.username') }} </label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="username" id="username" value="{{{ Input::old('username', isset($user) ? $user->username : null) }}}" />
						{{{ $errors->first('username', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ username -->

				<!-- Email -->
				<div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="email">{{ trans('general.email') }}</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="email" id="email" value="{{{ Input::old('email', isset($user) ? $user->email : null) }}}" require />
						{{{ $errors->first('email', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ email -->

				<!-- name -->
				<div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="name">{{ trans('general.name') }}</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($user) ? $user->name : null) }}}" />
						{{{ $errors->first('name', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ name -->

				<!-- lastname -->
				<div class="form-group {{{ $errors->has('lastname') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="lastname">{{ trans('general.lastname') }}</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="lastname" id="lastname" value="{{{ Input::old('lastname', isset($user) ? $user->lastname : null) }}}" />
						{{{ $errors->first('lastname', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ lastname -->

				<!-- Password -->
				<div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="password">{{ trans('general.password') }}</label>
					<div class="col-md-6">
						<input class="form-control" type="password" name="password" id="password" value="" />
						{{{ $errors->first('password', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ password -->

				<!-- Password Confirm -->
				<div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="password_confirmation">{{ trans('general.password_confirm') }}</label>
					<div class="col-md-6">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
						{{{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ password confirm -->

				<!-- Activation Status -->
				<div class="form-group {{{ $errors->has('activated') || $errors->has('confirm') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="confirm">{{ trans('general.activate_user') }}?</label>
					<div class="checkbox col-md-6">
						<label >
							<input type="radio" name="confirm" id="confirm" value="1"  {{ ($user->activated()) ? 'checked="checked"' : '' }} />
							{{{ Lang::get('general.yes') }}}
						</label>
						<label >
							<input type="radio" name="confirm" id="confirm" value="0"  {{ (!$user->activated()) ? 'checked="checked"' : '' }} />
							{{{ Lang::get('general.no') }}}	
						</label>
					</div>
				</div>
				<!-- ./ activation status -->

					<!-- library_id -->
					<div class="form-group {{{ $errors->has('library_id') ? 'error' : '' }}}">
	          <label class="col-md-2 control-label" for="library_id">{{ trans('general.library') }}</label>
	          <div class="col-md-6">
	            <select class="form-control" name="library_id" id="library_id" >
	              @foreach ($libraries as $library)
	            		<option value="<?= $library->id  ?>" {{ ( $user->library_id == $library->id ) ? 'selected' : '' }} >
	            			{{ $library->code }} &raquo;
	            			{{ $library->name }}
	            		</option>
	              @endforeach
							</select>
	      		</div>
					</div>

				<!-- Groups -->
<!-- 				<div class="form-group {{{ $errors->has('roles') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="roles">{{ trans('general.role') }}</label>
            <div class="col-md-6">
	            <select class="form-control" name="roles[]" id="roles[]" >
	              @foreach ($roles as $role)
									<option value="{{{ $role->id }}}" {{ $user->hasRole($role->name) ? 'selected' : ''}}>
										{{{ $role->name }}}
										&raquo;
										{{{ $role->description }}}
									</option>
	              @endforeach
							</select>

							<span class="help-block">
							</span>
	          </div>
				</div>
 -->				<!-- ./ groups -->

				<!-- Groups -->
				<div class="form-group {{{ $errors->has('roles') ? 'error' : '' }}}">
            <label class="col-md-2 control-label" for="roles">{{ trans('general.role') }}</label>
            <div class="col-md-6 ">
            	<fieldset id="roles_user">
	              @foreach ($roles as $role)
		              <label>
			              <input type="checkbox" name="roles[]" value="{{$role->id}}" {{ ($user->hasRole($role->name) || Input::old('roles')) ? 'checked="checked"' : ($first) ? 'checked="checked"' : '' }}>
			              {{ $role->name }}
		              </label>
		            @endforeach
							</select>
							</fieldset>
							<label for="roles[]" class="error"></label>
							<span class="help-block">
								<!-- Select a group to assign to the user, remember that a user takes on the permissions of the group they are assigned. -->
							</span>
	          </div>
				</div>
				<!-- ./ groups -->

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-6">
				<button type="submit" class="btn btn-success"><i class="fa fa-check"></i> {{ trans('general.ok') }}</button>
				<a class="btn btn-default" href="{{{ URL::to('admin/users') }}}"><i class="fa fa-times"></i> {{ trans('general.cancel') }}</a>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop