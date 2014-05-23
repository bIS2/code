@extends('layouts.scaffold')

@section('main')

<h1>Edit Stat</h1>
{{ Form::model($stat, array('method' => 'PATCH', 'route' => array('stats.update', $stat->id))) }}
	<ul>
        <li>
            {{ Form::label('hodings_count', 'Hodings_count:') }}
            {{ Form::input('number', 'hodings_count') }}
        </li>

        <li>
            {{ Form::label('sets_count', 'Sets_count:') }}
            {{ Form::input('number', 'sets_count') }}
        </li>

        <li>
            {{ Form::label('sets_grouped', 'Sets_grouped:') }}
            {{ Form::input('number', 'sets_grouped') }}
        </li>

		<li>
			{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
			{{ link_to_route('stats.show', 'Cancel', $stat->id, array('class' => 'btn')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop
