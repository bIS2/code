@extends('layouts.scaffold')

@section('main')

<h1>All Deliveries</h1>

<p>{{ link_to_route('deliveries.create', 'Add new delivery') }}</p>

@if ($deliveries->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Holding_id</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($deliveries as $delivery)
				<tr>
					<td>{{{ $delivery->holding_id }}}</td>
                    <td>{{ link_to_route('deliveries.edit', 'Edit', array($delivery->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('deliveries.destroy', $delivery->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no deliveries
@endif

@stop
