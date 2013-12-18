@extends('layouts.scaffold')

@section('main')

<h1>Edit Received</h1>
{{ Form::model($received, array('method' => 'PATCH', 'route' => array('receiveds.update', $received->id))) }}
	<ul>
        <li>
            {{ Form::label('holding_id', 'Holding_id:') }}
            {{ Form::input('number', 'holding_id') }}
        </li>

        <li>
            {{ Form::label('user_id', 'User_id:') }}
            {{ Form::input('number', 'user_id') }}
        </li>

		<li>
			{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
			{{ link_to_route('receiveds.show', 'Cancel', $received->id, array('class' => 'btn')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop
