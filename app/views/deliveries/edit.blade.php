@extends('layouts.scaffold')

@section('main')

<h1>Edit Delivery</h1>
{{ Form::model($delivery, array('method' => 'PATCH', 'route' => array('deliveries.update', $delivery->id))) }}
	<ul>
        <li>
            {{ Form::label('holding_id', 'Holding_id:') }}
            {{ Form::input('number', 'holding_id') }}
        </li>

		<li>
			{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
			{{ link_to_route('deliveries.show', 'Cancel', $delivery->id, array('class' => 'btn')) }}
		</li>
	</ul>
{{ Form::close() }}

@if ($errors->any())
	<ul>
		{{ implode('', $errors->all('<li class="error">:message</li>')) }}
	</ul>
@endif

@stop
