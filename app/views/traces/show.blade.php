@extends('layouts.scaffold')

@section('main')

<h1>Show Trace</h1>

<p>{{ link_to_route('traces.index', 'Return to all traces') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>User_id</th>
				<th>Action</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $trace->user_id }}}</td>
					<td>{{{ $trace->action }}}</td>
                    <td>{{ link_to_route('traces.edit', 'Edit', array($trace->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('traces.destroy', $trace->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
