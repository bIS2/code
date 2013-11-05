@extends('layouts.scaffold')

@section('main')

<h1>All Lists</h1>

<p>{{ link_to_route('hlists.create', 'Add new list') }}</p>

@if ($hlists->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Name</th>
				<th>User_id</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($hlists as $list)
				<tr>
					<td>{{{ $list->name }}}</td>
					<td>{{{ $list->user_id }}}</td>
                    <td>{{ link_to_route('hlists.edit', 'Edit', array($list->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('hlists.destroy', $list->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no hlists
@endif

@stop
