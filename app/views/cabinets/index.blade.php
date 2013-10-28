@extends('layouts.default')

@section('content')

<h1>All Cabinets</h1>

<p>{{ link_to_route('cabinets.create', 'Add new cabinet') }}</p>

@if ($cabinets->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Name</th>
				<th>User_id</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($cabinets as $cabinet)
				<tr>
					<td>{{{ $cabinet->name }}}</td>
					<td>{{{ $cabinet->user_id }}}</td>
                    <td>{{ link_to_route('cabinets.edit', trans('general.edit'), [$cabinet->id], ['class' => 'btn btn-info'] ) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('cabinets.destroy', $cabinet->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no cabinets
@endif

@stop
