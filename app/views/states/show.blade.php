@extends('layouts.scaffold')

@section('main')

<h1>Show State</h1>

<p>{{ link_to_route('states.index', 'Return to all states') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Holding_id</th>
				<th>User_id</th>
				<th>State</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $state->holding_id }}}</td>
					<td>{{{ $state->user_id }}}</td>
					<td>{{{ $state->state }}}</td>
                    <td>{{ link_to_route('states.edit', 'Edit', array($state->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('states.destroy', $state->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
