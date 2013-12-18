@extends('layouts.scaffold')

@section('main')

<h1>All Receiveds</h1>

<p>{{ link_to_route('receiveds.create', 'Add new received') }}</p>

@if ($receiveds->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Holding_id</th>
				<th>User_id</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($receiveds as $received)
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
			@endforeach
		</tbody>
	</table>
@else
	There are no receiveds
@endif

@stop
