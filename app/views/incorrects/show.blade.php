@extends('layouts.scaffold')

@section('main')

<h1>Show Incorrect</h1>

<p>{{ link_to_route('incorrects.index', 'Return to all incorrects') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Holdingsset_id</th>
				<th>User_id</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $incorrect->holdingsset_id }}}</td>
					<td>{{{ $incorrect->user_id }}}</td>
                    <td>{{ link_to_route('incorrects.edit', 'Edit', array($incorrect->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('incorrects.destroy', $incorrect->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
