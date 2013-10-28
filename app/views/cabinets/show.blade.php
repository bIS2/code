@extends('layouts.scaffold')

@section('main')

<h1>Show Cabinet</h1>

<p>{{ link_to_route('cabinets.index', 'Return to all cabinets') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Name</th>
				<th>User_id</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $cabinet->name }}}</td>
					<td>{{{ $cabinet->user_id }}}</td>
                    <td>{{ link_to_route('cabinets.edit', 'Edit', array($cabinet->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('cabinets.destroy', $cabinet->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
