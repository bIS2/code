@extends('layouts.default')

@section('content')

<div class="page-header">
	<h1>Edit Group</h1>
</div>
{{ Form::model($group, array('method' => 'PATCH', 'route' => array('groups.update', $group->id), 'role' => 'form')) }}
	<div class="input-group text-center">
        <div class="form-group">
            {{ Form::label('name', 'Name:') }}
            {{ Form::text('name')}}
        </div>
		<div class="form-group">
			{{ Form::submit('Update', array('class' => 'btn btn-info form-group')) }}
			{{ link_to_route('groups.show', 'Cancel', $group->id, array('class' => 'btn')) }}
		</div>
	</div>
{{ Form::close() }}

@if ($errors->any())
	<div>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</div>
@endif

@stop
