@extends('layouts.scaffold')

@section('main')

<h1>Show Revised</h1>

<p>{{ link_to_route('reviseds.index', 'Return to all reviseds') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Holding_id</th>
				<th>User_id</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $revised->holding_id }}}</td>
					<td>{{{ $revised->user_id }}}</td>
                    <td>{{ link_to_route('reviseds.edit', 'Edit', array($revised->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('reviseds.destroy', $revised->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
