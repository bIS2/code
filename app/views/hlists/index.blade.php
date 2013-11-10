@extends('layouts.default')

@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-xs-12">
			<h2> 
				{{ trans('lists.title') }} 
			</h2>
		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->
</div> <!-- /.page-header -->

	<div class="row">
		<div class="col-xs-12">

			@if ($hlists->count())
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>{{ trans('tables.name') }}</th>
							<th>{{ trans('tables.amount-lists') }}</th>
							<th><span class="glyphicon glyphicon-thumbs-up"></span></th>
							<th><span class="glyphicon glyphicon-tags"></span></th>
						</tr>
					</thead>

					<tbody>
						@foreach ($hlists as $list)
							<tr id="{{ $list->id }}">
								<td>{{ link_to( route('holdings.index',['hlist'=>$list->id]), $list->name) }}</td>
								<td>{{{ $list->holdings->count() }}}</td>
								<td>
									@if ( ( $count = $list->holdings()->ok2()->count() )>0  )
										<a href="{{ route('holdings.index',['hlist'=>$list->id, 'ok2'=>true]) }}" >{{$count }}</a>
									@else
										{{{ $list->holdings()->ok2()->count() }}}
									@endif
								</td>
								<td></td>
			          <td>
			          	{{ link_to_route('lists.edit', trans('general.edit'), [$list->id], ['class' => 'btn btn-info btn-xs'] ) }}
			          	{{ link_to_route('lists.destroy', trans('general.delete'), [$list->id], ['class' => 'btn btn-danger btn-xs', 'data-remote'=>'true', 'data-method'=>'delete'] ) }}
				        </td>
							</tr>
						@endforeach
					</tbody>
				</table>

		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->


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
			          {{ link_to_route('lists.edit', trans('general.edit'), [$list->id]) }}
			          {{ link_to_route('lists.destroy', trans('general.delete'), [$list->id], ['data-method' => 'DELETE', 'data-remote'=>true ]) }}
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