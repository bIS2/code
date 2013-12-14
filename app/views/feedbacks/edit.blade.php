@extends('layouts.scaffold')

@section('main')

<h1>Edit Feedback</h1>
{{ Form::model($feedback, array('method' => 'PATCH', 'route' => array('feedbacks.update', $feedback->id))) }}
	<ul>
        <li>
            {{ Form::label('user_id', 'User_id:') }}
            {{ Form::input('number', 'user_id') }}
        </li>

        <li>
            {{ Form::label('client', 'Client:') }}
            {{ Form::text('client') }}
        </li>

        <li>
            {{ Form::label('content', 'Content:') }}
            {{ Form::textarea('content') }}
        </li>

		<li>
			{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
			{{ link_to_route('feedbacks.show', 'Cancel', $feedback->id, array('class' => 'btn')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop
