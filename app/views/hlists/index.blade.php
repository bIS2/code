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
				<table class="table table-bordered table-condensed ">
					<thead>
						<tr>
							<th>{{ trans('tables.name') }}</th>
							<th><span class="fa fa-file-text"></span></th>
							<th><span class="fa fa-thumbs-up"></span></th>
							<th><span class="fa fa-tags"></span></th>
							<th> </th>
						</tr>
					</thead>

					<tbody>
						@foreach ($hlists as $list)
							<tr id="{{ $list->id }}" class="{{ $list->is_delivery ? 'success' : '' }}">
								<td>{{ link_to( route('holdings.index',['hlist'=>$list->id]), $list->name) }}</td>
								<td>{{{ $list->holdings->count() }}}</td>
								<td>
									@if ( ( $count = $list->holdings()->corrects()->count() )>0  )
										<a href="{{ route('holdings.index',['hlist'=>$list->id, 'ok2'=>true]) }}" >{{$count }}</a>
									@else
										{{{ $list->holdings()->corrects()->count() }}}
									@endif
								</td>
								<td>{{ $list->holdings()->annotated()->count() }}</td>
			          <td>

			          	<a href="{{ route('deliveries.store') }}" class="btn btn-success btn-xs" data-remote="true" data-method="post" data-params="hlist_id={{$list->id}}&user_id={{Auth::user()->id}}">
			          		<span class="fa  fa-truck fa-flip-horizontal" ></span> {{trans('general.delivery')}}
			          	</a>

			          	<a href="{{ route('lists.edit',$list->id) }}" class="btn btn-default btn-xs"><span class="fa fa-edit" ></span> {{trans('general.edit')}}</a>

			          	<a href="{{ route('lists.destroy',$list->id) }}" data-remote="true" data-method="delete" class="btn btn-danger btn-xs">
			          		<span class="fa fa-times"></span> {{trans('general.delete')}}
			          	</a>

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