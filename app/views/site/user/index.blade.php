@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.settings') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')
<div class="row">

	<div class="col-xs-6">
		<div class="page-header">
			<h3>
				<i class="fa fa-user"></i>
				{{trans('table.title_edit_user')}}
			</h3>
			<hr>
		</div>
		{{ Form::model($user, [ 'url' => URL::to('user/' . $user->id . '/edit'), 'class'=>'form-horizontal', 'id'=>'edit_user' ] ) }}
		<!-- <form class="form-horizontal" method="post" action="{{ URL::to('user/' . $user->id . '/edit') }}"  autocomplete="off"> -->	

		    <!-- General tab -->
		    <div class="tab-pane active" id="tab-general">
		        <!-- username -->
		        <div class="row form-group {{{ $errors->has('username') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="username">{{trans('table.user')}}</label>
		            <div class="col-md-8">
		                <input class="form-control" disabled type="text" name="username" id="username" value="{{{ Input::old('username', $user->username) }}}" />
		                <input class="form-control" type="hidden" name="username" id="username" value="{{{ Input::old('username', $user->username) }}}" />

		                {{{ $errors->first('username', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ username -->

		        <!-- name -->
		        <div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="name">{{trans('table.name')}}</label>
		            <div class="col-md-8">
		            	{{ Form::text('name',null,['class'=>"form-control"]) }}
	                {{{ $errors->first('name', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ name -->

		        <!-- lastname -->
		        <div class="form-group {{{ $errors->has('lastname') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="lastname">{{trans('table.lastname')}}</label>
		            <div class="col-md-8">
		            	{{ Form::text('lastname',null,['class'=>"form-control"]) }}
	                {{{ $errors->first('lastname', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ lastname -->

		        <!-- Email -->
		        <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="email">{{trans('table.email')}}</label>
		            <div class="col-md-8">
		            	{{Form::email('email',null,['class'=>"form-control"])}}
	                {{{ $errors->first('email', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ email -->

		        <!-- Password -->
		        <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="password">{{trans('table.password')}}</label>
		            <div class="col-md-8">
		            	{{Form::password('password',['class'=>"form-control",'id'=>'password'])}}
	                {{{ $errors->first('password', '<span class="help-inline">:message</span>') }}}
		            </div>
		        </div>
		        <!-- ./ password -->

		        <!-- Password Confirm -->
		        <div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
		            <label class="col-md-4 control-label" for="password_confirmation">{{trans('table.password_confirmation')}}</label>
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
