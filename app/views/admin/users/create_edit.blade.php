@extends('layouts.admin')

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>{{{ $title }}} </h3>
	</div>
	{{-- Create User Form --}}
	<form class="form-horizontal col-md-8" method="post" action="@if (isset($user)){{ URL::to('admin/users/' . $user->id . '/edit') }}@endif" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

				<!-- username -->
				<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="username">Username</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="username" id="username" value="{{{ Input::old('username', isset($user) ? $user->username : null) }}}" />
						{{{ $errors->first('username', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ username -->

				<!-- Email -->
				<div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="email">Email</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="email" id="email" value="{{{ Input::old('email', isset($user) ? $user->email : null) }}}" />
						{{{ $errors->first('email', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ email -->

				<!-- name -->
				<div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="name">Name</label>
					<div class="col-md-6">
						<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', isset($user) ? $user->name : null) }}}" />
						{{{ $errors->first('name', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ name -->

				<!-- lastname -->
				<div class="form-group {{{ $errors->has('lastname') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="lastname">Lastname</label>
					<div class="col-md-6">
						<input class="form-control" type="text" lastname="lastname" id="lastname" value="{{{ Input::old('lastname', isset($user) ? $user->lastname : null) }}}" />
						{{{ $errors->first('lastname', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ lastname -->

				<!-- Password -->
				<div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="password">Password</label>
					<div class="col-md-6">
						<input class="form-control" type="password" name="password" id="password" value="" />
						{{{ $errors->first('password', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ password -->

				<!-- Password Confirm -->
				<div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="password_confirmation">Password Confirm</label>
					<div class="col-md-6">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
						{{{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}}
					</div>
				</div>
				<!-- ./ password confirm -->

				<!-- Activation Status -->
				<div class="form-group {{{ $errors->has('activated') || $errors->has('confirm') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="confirm">Activate User?</label>
					<div class="col-md-6">
						<label class="">
							<input class="form-control" type="radio" name="confirm" id="confirm" value="1"  />
							{{{ Lang::get('general.yes') }}}
						</label>
						<label class="">
							{{{ Lang::get('general.no') }}}	
							<input class="form-control" type="radio" name="confirm" id="confirm" value="0"  checked />
												
						</label>
					</div>
				</div>
				<!-- ./ activation status -->

				<!-- library_id -->
				<div class="form-group {{{ $errors->has('library_id') ? 'error' : '' }}}">
	                <label class="col-md-2 control-label" for="library_id">Library</label>
	                <div class="col-md-3">
		                <select class="form-control" name="library_id" id="library_id" >
		                        @foreach (Library::all() as $library)
									@if ($mode == 'create')
		                        		<option value="{{{ $library->id }}}"{{{ ( in_array($library->id, $selectedRoles) ? ' selected="selected"' : '') }}}>{{{ $library->title }}}</option>
		                        	@else
										<option value="{{{ $library->id }}}"{{{ ( $library->id == $user->library_id ? ' selected="selected"' : '') }}}>{{{ $library->title }}}</option>
									@endif
		                        @endforeach
						</select>
	            	</div>
				</div>
				<!-- ./ library_id -->

				<!-- Groups -->
				<div class="form-group {{{ $errors->has('roles') ? 'error' : '' }}}">
	                <label class="col-md-2 control-label" for="roles">Role</label>
	                <div class="col-md-3">
		                <select class="form-control" name="roles[]" id="roles[]" >
		                        @foreach ($roles as $role)
									@if ($mode == 'create')
		                        		<option value="{{{ $role->id }}}"{{{ ( in_array($role->id, $selectedRoles) ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
		                        	@else
										<option value="{{{ $role->id }}}"{{{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
									@endif
		                        @endforeach
						</select>

						<span class="help-block">
							<!-- Select a group to assign to the user, remember that a user takes on the permissions of the group they are assigned. -->
						</span>
	            	</div>
				</div>
				<!-- ./ groups -->

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-6">
				<button type="submit" class="btn btn-success">OK</button>
				<a class="btn-cancel close_popup" href=="">Cancel</a>
				<button type="reset" class="btn btn-default">Reset</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop