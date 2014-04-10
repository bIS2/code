@extends('layouts.scaffold')

@section('main')

<h1>Show Library</h1>

<p>{{ link_to_route('libraries.index', 'Return to all libraries') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Name</th>
				<th>Code</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $library->name }}}</td>
					<td>{{{ $library->code }}}</td>
                    <td>{{ link_to_route('libraries.edit', 'Edit', array($library->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('libraries.destroy', $library->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
