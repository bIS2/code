@extends('layouts.login')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.forgot_password') }}} ::
@parent
@stop

{{-- Content --}}
@section('content')
<div class="page-header">
    <h1>{{{ Lang::get('user/user.forgot_password') }}}</h1>
</div>
{{ Confide::makeForgotPasswordForm() }}
<a href="{{ action('UserController@getLogin') }}">{{trans('general.back')}}</a>
@stop
