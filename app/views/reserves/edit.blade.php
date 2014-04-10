@extends('layouts.scaffold')

@section('main')

<h1>Edit Reserf</h1>
{{ Form::model($reserve, array('method' => 'PATCH', 'route' => array('reserves.update', $reserve->id))) }}
	<ul>
        <li>
            {{ Form::label('hoss_id', 'Hoss_id:') }}
            {{ Form::input('number', 'hoss_id') }}
        </li>

        <li>
            {{ Form::label('user_id', 'User_id:') }}
            {{ Form::input('number', 'user_id') }}
        </li>

        <li>
            {{ Form::label('description', 'Description:') }}
            {{ Form::textarea('description') }}
        </li>

		<li>
			{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
			{{ link_to_route('reserves.show', 'Cancel', $reserve->id, array('class' => 'btn')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop
