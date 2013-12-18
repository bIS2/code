@extends('layouts.scaffold')

@section('main')

<h1>Show Received</h1>

<p>{{ link_to_route('receiveds.index', 'Return to all receiveds') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Holding_id</th>
				<th>User_id</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $received->holding_id }}}</td>
					<td>{{{ $received->user_id }}}</td>
                    <td>{{ link_to_route('receiveds.edit', 'Edit', array($received->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('receiveds.destroy', $received->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
