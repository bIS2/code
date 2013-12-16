@extends('layouts.scaffold')

@section('main')

<h1>All Incorrects</h1>

<p>{{ link_to_route('incorrects.create', 'Add new incorrect') }}</p>

@if ($incorrects->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Holdingsset_id</th>
				<th>User_id</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($incorrects as $incorrect)
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
			@endforeach
		</tbody>
	</table>
@else
	There are no incorrects
@endif

@stop
