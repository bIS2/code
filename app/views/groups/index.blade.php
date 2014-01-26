@extends('layouts.default')

{{-- Content --}}
@section('content')

<div class="panel panel-info">
	<div class="panel-heading">
		<h1>{{ trans('titles.groups')}}</h1>
		<a style="padding: 6px 11px;" class="btn btn-default link_bulk_action disabled" data-toggle="modal" href="#form-join-group">
	  	<i style="font-size: 26px; padding: 0px;" class="fa fa-magnet"></i>
	  </a>
	  <a style="padding: 6px 11px;" class="btn btn-default link_bulk_action disabled" data-toggle="modal" href="#form-bulkdelete-group">
	  	<i style="font-size: 26px; padding: 0px;" class="fa fa-trash-o"></i>
	  </a>
	</div>
<!-- <p>{{ link_to_route('groups.create', 'Add new group') }}</p> -->
	<div class="panel-body">
		@if ($groups->count())
			<table id="groups-list" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>
							<label>
								<input id="select-all" class="select-all" name="select-all" type="checkbox" value="1" data-target="#groups-targets">
								<p class="btn btn-xs btn-primary pop-over"data-content="{{ trans('groups.select_all_groups') }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-check"></i></p>
							</label>
							
						</th>
						<th>{{ trans('table.group_name') }}</th>
						<th>{{ trans('table.user') }}</th>
						<th>{{ HOS }}</th>
						<th>{{ trans('table.actions') }}</th>
					</tr>
				</thead>

				<tbody id="groups-targets">
					@foreach ($groups as $group)
						<tr id="{{$group->id}}">
							<td><input id="group_id" name="group_id[]" type="checkbox" value="{{ $group->id }}" class="hl sel"></td>
							<td>{{{ $group-> name }}}</td>
							<td>{{{ $group-> user -> username }}}</td>
							<td>{{{ $group-> holdingssets() -> count() }}}</td>
		          <td>
		          	{{ link_to_route('groups.edit', trans('general.edit'), array($group->id), array('class' => 'btn btn-info btn-xs')) }}
			          {{ link_to_route('groups.destroy', trans('general.delete'), [$group->id], ['data-method' => 'DELETE', 'data-remote'=>true,'class' => 'btn btn-danger btn-xs' ]) }}
		          </td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
			<h2 class="text-info"><span class="fa fa-info-circle text-danger"></span> {{ trans('groups.nogroups') }}</h2>
		@endif
	</div>
</div>

@include('groups.join')
@include('groups.bulkdelete')
@stop
