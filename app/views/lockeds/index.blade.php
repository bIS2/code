@extends('layouts.scaffold')

@section('main')

<h1>All Lockeds</h1>

<p>{{ link_to_route('lockeds.create', 'Add new locked') }}</p>

@if ($lockeds->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Holding_id</th>
				<th>User_id</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($lockeds as $locked)
				<tr>
					<td>{{{ $locked->holding_id }}}</td>
					<td>{{{ $locked->user_id }}}</td>
                    <td>{{ link_to_route('lockeds.edit', 'Edit', array($locked->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('lockeds.destroy', $locked->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no lockeds
@endif

@stop
