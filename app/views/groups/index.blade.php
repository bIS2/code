@extends('layouts.default')

@section('content')

<div class="page-header">
	<h1>{{ trans('titles.groups')}}</h1>
</div>
<!-- <p>{{ link_to_route('groups.create', 'Add new group') }}</p> -->

@if ($groups->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>{{ trans('table.name')}}</th>
				<th>{{ trans('table.user')}}</th>
				<th>{{ trans('table.holdingssets')}}</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($groups as $group)
				<tr id="{{$group->id}}">
					<td>{{{ $group-> name }}}</td>
					<td>{{{ $group-> user -> username }}}</td>
					<td>{{{ $group-> holdingssets() -> count() }}}</td>
          <td>
          	{{ link_to_route('groups.edit', 'Edit', array($group->id), array('class' => 'btn btn-info btn-xs')) }}
	          {{ link_to_route('groups.destroy', trans('general.delete'), [$group->id], ['data-method' => 'DELETE', 'data-remote'=>true,'class' => 'btn btn-danger btn-xs' ]) }}

          </td>
				</tr>
			@endforeach
		</tbody>
	</table>
	</div>
	<p>
		{{ $groups->appends(Input::except('page'))->links()  }}
	</p>
</div>

@else
	{{ trans('groups.nogroups') }}
@endif

@stop
