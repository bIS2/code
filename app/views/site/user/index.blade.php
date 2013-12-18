@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.settings') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')
<div class="row">

	<div class="col-xs-4">
		<div class="page-header">
			<h3>Edit your settings</h3>
		</div>
		{{ Form::model($user, [ 'url' => URL::to('user/' . $user->id . '/edit'), 'class'=>'form-horizontal' ] ) }}
		<!-- <form class="form-horizontal" method="post" action="{{ URL::to('user/' . $user->id . '/edit') }}"  autocomplete="off"> -->
		    <!-- CSRF Token -->
		    <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		    <!-- ./ csrf token -->
		    <!-- General tab -->
		    <div class="tab-pane active" id="tab-general">
		        <!-- username -->
		        <div class="form-group {{{ $errors->has('username') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="username">Username</label>
		            <div class="col-md-8">
		                <input class="form-control" disabled type="text" name="username" id="username" value="{{{ Input::old('username', $user->username) }}}" />
		                {{{ $errors->first('username', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ username -->

		        <!-- name -->
		        <div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="name">Name</label>
		            <div class="col-md-8">
		            	{{ Form::text('name',null,['class'=>"form-control"]) }}
	                {{{ $errors->first('name', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ name -->

		        <!-- lastname -->
		        <div class="form-group {{{ $errors->has('lastname') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="lastname">Lastname</label>
		            <div class="col-md-8">
		            	{{ Form::text('lastname',null,['class'=>"form-control"]) }}
	                {{{ $errors->first('lastname', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ lastname -->

		        <!-- Email -->
		        <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="email">Email</label>
		            <div class="col-md-8">
		            	{{Form::email('email',null,['class'=>"form-control"])}}
	                {{{ $errors->first('email', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ email -->

		        <!-- Password -->
		        <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="password">Password</label>
		            <div class="col-md-8">
		            	{{Form::password('password',['class'=>"form-control"])}}
	                {{{ $errors->first('password', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ password -->

		        <!-- Password Confirm -->
		        <div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="password_confirmation">Password Confirm</label>
		            <div class="col-md-8">
		            	{{Form::password('password_confirmation',['class'=>"form-control"])}}
	                {{{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ password confirm -->
		    </div>
		    <!-- ./ general tab -->

		    <!-- Form Actions -->
		    <div class="form-group">
		        <div class="col-md-offset-4 col-md-8">
		            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> {{trans('general.save')}}</button>
		            <a href="{{ Request::header('referer') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> {{trans('general.back')}}</a>
		        </div>
		    </div>
		    <!-- ./ form actions -->
		{{ Form::close() }}

	</div>
</div>
@stop
