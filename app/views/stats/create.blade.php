@extends('layouts.scaffold')

@section('main')

<h1>Create Stat</h1>

{{ Form::open(array('route' => 'stats.store')) }}
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


