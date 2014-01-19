@extends('layouts.default')

{{-- Content --}}
@section('content')

<div class="panel panel-info">
	<div class="panel-heading">
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
			<?php //echo var_dump( $users ) ?>
			@if ($hlists->count())
				<table class="table table-bordered table-condensed ">
					<thead>
						<tr>
							<th>{{ trans('table.name') }}</th>
							<th>{{ trans('table.date') }}</th>
							<th>{{ trans('table.asigned') }}</th>
							<th>{{ trans('holdings.title') }}</span></th>
							<!-- <th>{{ trans('table.details') }}</span></th> -->
							<th> </th>
						</tr>
					</thead>

					<tbody id="hlists-targets">
						@foreach ($hlists as $list)
							<tr id="{{ $list->id }}" class="{{ $list->is_delivery ? 'success' : '' }}">
								<td>
									<a href="{{ route('holdings.index', Input::except(['hlist_id', 'page'])) }}" >{{ $list->name }} </a>
								</td>
								<td>{{ $list->created_at }}</td>
								<td>
									{{ $list->worker->username }} 	
								</td>
								<td>
									{{ link_to( route('holdings.index',['hlist'=>$list->id]), $total = $list->holdings->count() ) }}
								</td>
<!-- 								<td>
									{{ trans('holdings.corrects') }}:{{{ $corrects = $list->holdings()->corrects()->count() }}},
									{{ trans('holdings.annotated') }}:{{ $annotated = $list->holdings()->annotated()->count() }},
									{{ trans('holdings.pending') }}:{{ $total -  ($corrects + $annotated)  }}
								</td>
 -->			          <td>

			          	@if (Authority::can('delivery','Hlist'))

				          	<a href="{{ route('deliveries.store') }}" class="btn btn-success btn-xs" data-remote="true" data-method="post" data-params="hlist_id={{$list->id}}&user_id={{Auth::user()->id}}" {{ $list->is_delivery ? 'disabled' : '' }}>
				          		<span class="fa  fa-truck fa-flip-horizontal" ></span> {{trans('holdings.delivery')}}
				          	</a>

			          	@endif

			          	@if (Authority::can('revise',$list))

				          	<a href="{{ route('lists.update',$list->id) }}" class="btn btn-success btn-xs" data-remote="true" data-method="put" data-params="revised=1" data-disabled-with="...">
				          		<span class="fa fa-check" ></span> {{trans('holdings.revised')}}
				          	</a>

			          	@endif

			          	@if ( Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('bibuser')  )
				          	<a href="{{ route('lists.edit',$list->id) }}" data-toggle="modal" data-target="#form-edit-list" class="btn btn-default btn-xs">
				          		<span class="fa fa-edit"></span> {{trans('general.edit')}}
				          	</a>
				          	<a href="{{ route('lists.destroy',$list->id) }}" data-remote="true" data-method="delete" class="btn btn-danger btn-xs">
				          		<span class="fa fa-times"></span> {{trans('general.delete')}}
				          	</a>
			          	@endif

				        </td>
							</tr>
						@endforeach
					</tbody>
				</table>

		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->

<div class="remote">
	<div class="modal" id="form-edit-list"></div><!-- /.modal -->
</div>


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
</div><!-- /.modal -->

