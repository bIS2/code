@extends('layouts.scaffold')

@section('main')

<h1>Edit Comment</h1>
{{ Form::model($comment, array('method' => 'PATCH', 'route' => array('comments.update', $comment->id))) }}
	<ul>
        <li>
            {{ Form::label('holding_id', 'Holding_id:') }}
            {{ Form::input('number', 'holding_id') }}
        </li>

        <li>
            {{ Form::label('category_id', 'Category_id:') }}
            {{ Form::input('number', 'category_id') }}
        </li>

        <li>
            {{ Form::label('user_id', 'User_id:') }}
            {{ Form::input('number', 'user_id') }}
        </li>

        <li>
            {{ Form::label('comments', 'Comments:') }}
            {{ Form::textarea('comments') }}
        </li>

		<li>
			{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
			{{ link_to_route('comments.show', 'Cancel', $comment->id, array('class' => 'btn')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop
