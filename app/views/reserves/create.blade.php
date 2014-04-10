@extends('layouts.scaffold')

@section('main')

<h1>Create Reserf</h1>

{{ Form::open(array('route' => 'reserves.store')) }}
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
			{{ Form::submit('Submit', array('class' => 'btn btn-info')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop


