@extends('layouts.default')

@section('content')

<div class="page-header">
	<h1>Groups</h1>
</div>
<!-- <p>{{ link_to_route('groups.create', 'Add new group') }}</p> -->

@if ($groups->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Name</th>
				<th>User</th>
				<th>HOS</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($groups as $group)
				<tr>
					<td>{{{ $group-> name }}}</td>
					<td>{{{ $group-> user -> username }}}</td>
					<td>{{{ $group-> holdingssets() -> count() }}}</td>
          <td>{{ link_to_route('groups.edit', 'Edit', array($group->id), array('class' => 'btn btn-info')) }}</td>
          <td>
              {{ Form::open(array('method' => 'DELETE', 'route' => array('groups.destroy', $group->id))) }}
                  {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
              {{ Form::close() }}
          </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	{{ trans('groups.nogroups') }}
@endif

@stop
