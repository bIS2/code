@extends('layouts.default')

{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@stop

{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>{{{ $title }}} </h3>
	</div>
	<div>
		<div class="">
			<a href="{{{ URL::to('admin/users/create') }}}" class="btn btn-sm btn-info "><span class="glyphicon glyphicon-plus-sign"></span> Create</a>
		</div>
	</div>
	<table id="users" class="table table-condensed table-hover datatable">
		<thead>
			<tr>
				<th >{{{ Lang::get('admin/users/table.username') }}}</th>
				<th >{{{ Lang::get('admin/users/table.email') }}}</th>
				<th >{{{ Lang::get('admin/users/table.roles') }}}</th>
				<th >{{{ Lang::get('admin/users/table.library') }}}</th>
				<th >{{{ Lang::get('admin/users/table.activated') }}}</th>
				<th >{{{ Lang::get('admin/users/table.created_at') }}}</th>
				<th >{{{ Lang::get('table.actions') }}}</th>
			</tr>
		</thead>
			<?php foreach ($users as $user) { ?>
				<tr>
					<td><?= $user->username ?> </td>
					<td><?= $user->email ?> </td>
					<td><?= $user->roles[0]->name ?> </td>
					<td><?= @$user->library->name ?> </td>
					<td><?= $user->activated() ?> </td>
					<td><?= $user->joined() ?> </td>
					<td>
          	<a href="{{ URL::to('admin/users/edit/'.$user->id) }}" class="btn btn-success btn-xs"><span class="fa fa-edit" ></span> {{trans('general.edit')}}</a>
          	<a href="{{ URL::to('admin/users/delete/'.$user->id) }}" data-remote="true" data-method="delete" class="btn btn-danger btn-xs"><span class="fa fa-times"></span> {{trans('general.delete')}}</a>
					</td>
				</tr>
			<?php } ?>
		<tbody>
		</tbody>
	</table>
	<?= $users->links()  ?>
@stop

{{-- Scripts --}}
@section('scripts')
	<script type="text/javascript">
		var oTable;
		$(document).ready(function() {
				oTable = $('#userss').dataTable( {
				"sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
				"sPaginationType": "bootstrap",
				"oLanguage": {
					"sLengthMenu": "_MENU_ records per page"
				},
				"bProcessing": true,
		        "bServerSide": true,
		        "sAjaxSource": "{{ URL::to('admin/users/data') }}",
		        "fnDrawCallback": function ( oSettings ) {
	           		$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
	     		}
			});
		});
	</script>
@stop