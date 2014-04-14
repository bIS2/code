@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
	{{{ trans('title') }}} :: @parent
@stop

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			Role Management

			<div class="pull-right">
				<a href="{{{ URL::to('admin/roles/create') }}}" class="btn btn-small btn-info iframe">
					<span class="glyphicon glyphicon-plus-sign"></span> <?= trans('general.create') ?>
				</a>
			</div>
		</h3>
	</div>

	<table id="roles" class="table table-striped table-hover table-condensedr">
		<thead>
			<tr>
				<th class="col-md-6">{{{ Lang::get('admin/roles/table.name') }}}</th>
				<th class="col-md-2">{{{ Lang::get('admin/roles/table.description') }}}</th>
				<th class="col-md-2">{{{ Lang::get('admin/roles/table.users') }}}</th>
				<th class="col-md-2">{{{ Lang::get('admin/roles/table.created_at') }}}</th>
				<th class="col-md-2">{{{ Lang::get('table.actions') }}}</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($roles as $role) { ?>
				<tr>
					<td><?= $role->name  ?> </td>
					<td><?= $role->description  ?> </td>
					<td><?= $role->users->count()  ?> </td>
					<td><?= $role->created_at  ?> </td>
					<td>
						<a href="{{ URL::to('admin/roles/edit/'.$role->id) }} " >{{ trans('general.edit') }} </a>
					</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
@stop


@stop