@extends('layouts.scaffold')

@section('main')

<h1>Show Delivery</h1>

<p>{{ link_to_route('deliveries.index', 'Return to all deliveries') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Holding_id</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $delivery->holding_id }}}</td>
                    <td>{{ link_to_route('deliveries.edit', 'Edit', array($delivery->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('deliveries.destroy', $delivery->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
