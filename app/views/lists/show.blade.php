@extends('layouts.scaffold')

@section('main')

<h1>Show List</h1>

<p>{{ link_to_route('lists.index', 'Return to all lists') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Name</th>
				<th>User_id</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $list->name }}}</td>
					<td>{{{ $list->user_id }}}</td>
                    <td>{{ link_to_route('lists.edit', 'Edit', array($list->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('lists.destroy', $list->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
