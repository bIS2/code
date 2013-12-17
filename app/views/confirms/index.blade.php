@extends('layouts.scaffold')

@section('main')

<h1>All Confirms</h1>

<p>{{ link_to_route('confirms.create', 'Add new confirm') }}</p>

@if ($confirms->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Holdingsset_id</th>
				<th>User_id</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($confirms as $confirm)
				<tr>
					<td>{{{ $confirm->holdingsset_id }}}</td>
					<td>{{{ $confirm->user_id }}}</td>
                    <td>{{ link_to_route('confirms.edit', 'Edit', array($confirm->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('confirms.destroy', $confirm->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no confirms
@endif

@stop
