@extends('layouts.scaffold')

@section('main')

<h1>Show Reserf</h1>

<p>{{ link_to_route('reserves.index', 'Return to all reserves') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Hoss_id</th>
				<th>User_id</th>
				<th>Description</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $reserve->hoss_id }}}</td>
					<td>{{{ $reserve->user_id }}}</td>
					<td>{{{ $reserve->description }}}</td>
                    <td>{{ link_to_route('reserves.edit', 'Edit', array($reserve->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('reserves.destroy', $reserve->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
