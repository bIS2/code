@extends('layouts.scaffold')

@section('main')

<h1>Edit Comments_category</h1>
{{ Form::model($comments_category, array('method' => 'PATCH', 'route' => array('comments_categories.update', $comments_category->id))) }}
	<ul>
        <li>
            {{ Form::label('name', 'Name:') }}
            {{ Form::text('name') }}
        </li>

		<li>
			{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
			{{ link_to_route('comments_categories.show', 'Cancel', $comments_category->id, array('class' => 'btn')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop
