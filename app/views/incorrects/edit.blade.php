@extends('layouts.scaffold')

@section('main')

<h1>Edit Incorrect</h1>
{{ Form::model($incorrect, array('method' => 'PATCH', 'route' => array('incorrects.update', $incorrect->id))) }}
	<ul>
        <li>
            {{ Form::label('holdingsset_id', 'Holdingsset_id:') }}
            {{ Form::input('number', 'holdingsset_id') }}
        </li>

        <li>
            {{ Form::label('user_id', 'User_id:') }}
            {{ Form::input('number', 'user_id') }}
        </li>

		<li>
			{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
			{{ link_to_route('incorrects.show', 'Cancel', $incorrect->id, array('class' => 'btn')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop
