@extends('layouts.admin')

{{-- Content --}}
@section('content')

	<div class="page-header">
		<h3>
			<span class="glyphicon glyphicon-lock"></span>
			{{ trans('roles.title-create') }}
		</h3>
	</div>

	{{-- Create Role Form --}}
	<div class="row">
		<div class="col-md-12">
			@include('admin.roles._form')
		</div>
	</div>

@stop
