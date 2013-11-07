@extends('layouts.modal')

@section('modal')

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


  <div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">{{{ trans('title.lists') }}}</h4>
        </div>
        <div class="modal-body">
        	<ul>
						@foreach ($hlists as $list)
							<li>
								{{ link_to_route('holdings.index', $list->name,['hlist_id'=>$list->id] ) }}
			          {{ link_to_route('hlists.edit', trans('general.edit'), [$list->id]) }}
			          {{ link_to_route('hlists.destroy', trans('general.delete'), [$list->id], ['data-method' => 'DELETE', 'data-remote'=>true ]) }}
							</li>
						@endforeach
        	</ul>
        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal" ><?= trans('general.close') ?></a>
          <a href="#" class="btn btn-primary"><?= trans('general.save') ?></a>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->