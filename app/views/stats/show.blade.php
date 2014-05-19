@extends('layouts.scaffold')

@section('main')

<h1>Show Stat</h1>

<p>{{ link_to_route('stats.index', 'Return to all stats') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Hodings_count</th>
				<th>Sets_count</th>
				<th>Sets_grouped</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $stat->hodings_count }}}</td>
					<td>{{{ $stat->sets_count }}}</td>
					<td>{{{ $stat->sets_grouped }}}</td>
                    <td>{{ link_to_route('stats.edit', 'Edit', array($stat->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('stats.destroy', $stat->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
