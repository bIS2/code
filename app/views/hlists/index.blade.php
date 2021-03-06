@extends('layouts.default')

{{-- Content --}}
@section('content')

@include('hlists.filters')

<div class="panel panel-info">
	<div class="panel-heading">
		<!-- 		{{ $query }} -->
		<div class="row">
			<div class="col-xs-8">
				<div class="lead"> 
					{{ trans('lists.title') }} 
				</div >
			</div> <!-- /.col-xs-12 -->
		</div> <!-- /.row -->
	</div> <!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">	
			@if ($hlists->count())
			<table class="table table-bordered table-condensed datatablelists">
				<thead>
					<tr>
						<th>No.</th>
						<th>{{ trans('table.date') }}</th>
						<th>{{ trans('table.name') }}</th>
						<th>{{ trans('table.state') }}</th>
						<th>{{ trans('table.type') }}</th>
						<th>{{ trans('table.asigned') }}</th>
						<th>{{ trans('holdings.title') }}</span></th>
						<!-- <th>{{ trans('table.details') }}</span></th> -->
						<th> </th>
					</tr>
				</thead>

				<tbody id="hlists-targets">
					<?php 
					$ll = 0; 
					?>
					@foreach ($hlists as $list) <?php $ll++; ?>
					<?php 
						$initHide = false;
						if ((Auth::user()->hasRole('resuser')) || (Auth::user()->hasRole('bibuser')) || ($list->type=="elimination")) $initHide = true; 
					?>
					<tr id="{{ $list->id }}" class="{{ $list->revised ? 'revised' : '' }} {{ $list->is_delivery ? 'delivered' : '' }}">
						<!-- <td>{{ $list->user->library->code }}</td> -->
						<td>{{ $ll }}</td>
						<td>{{ $list->created_at }}</td>
						<td>
							<a href="{{ route('holdings.index', [ 'hlist_id' => $list->id ] ) }}" >{{ $list->name }} </a>
						</td>
						<td>
							<span class="label label-primary state-list "<?php if ((Auth::user()->hasRole('resuser')) || (Auth::user()->hasRole('bibuser')) || ($list->type=="elimination")) echo ' style="display: none;"'?>>
								{{ trans('states.'.$list->state) }}
							</span>
						</td>
						<td>
							@if(($initHide) && ($list->type != "elimination")) 
							<i class="fa fa-edit"></i>
							@else
							{{ $list->type_icon }}
							@endif
							{{ trans('lists.type-'.$list->type) }}
						</td>
						<td>
							{{ $list->worker->username }} 	
						</td>
						<td>
							{{ $list->holdings->count() }}
						</td>       
						<td>
							@if (!$initHide)
								@if (($list->type=='control') && ( !$list->revised ))
								<span class="">
									<span class="label label-default"><i class="fa fa-check"></i> {{ $list->holdings_reviseds }}</span>  
								</span>
								@endif

								@if (Authority::can('delivery','Hlist'))

								<a href="{{ route('deliveries.store') }}" class="btn btn-success btn-xs btn-delivery" data-remote="true" data-method="post" data-params="hlist_id={{$list->id}}&user_id={{Auth::user()->id}}" {{ $list->is_delivery ? 'disabled' : '' }}>
									<span class="fa  fa-truck fa-flip-horizontal" ></span> {{trans('holdings.delivery')}}
								</a>

								@endif

								@if (Authority::can('receive_list',$list))

								<a href="{{ route('lists.update',$list->id) }}" class="btn btn-success btn-receive btn-xs" data-params="state=received" data-method="put" data-remote="true">
									<span class="fa fa-download"></span> {{trans('holdings.receive')}}
								</a>

								@endif

								@if (Authority::can('revise',$list))

								<a href="{{ route('lists.update',$list->id) }}" class="btn btn-success btn-xs btn-revise" data-remote="true" data-method="put" data-params="revised=1" data-disabled-with="...">
									<span class="fa fa-check" ></span> {{trans('holdings.revised')}}
								</a>

								@endif
							@endif

							@if ( Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('bibuser')  )
								@if (!$initHide)
									<a href="{{ route('lists.edit',$list->id) }}" data-toggle="modal" data-target="#form-edit-list" class="btn btn-default btn-xs">
										<span class="fa fa-edit"></span> {{trans('general.edit')}}
									</a>
								@endif
								<a href="{{ route('lists.destroy',$list->id) }}" data-remote="true" data-method="delete" class="btn btn-danger btn-xs" >
									<span class="fa fa-times"></span> {{trans('general.delete')}}
								</a>
								@if(Auth::user()->hasRole('bibuser'))
									<a href="{{ route('lists.destroy',$list->id) }}?holstotrash=1" data-remote="true" data-method="delete" class="btn btn-success btn-xs" >
										<span class="fa fa-edit"></span> {{ trans('general.Hols updated') }}
									</a>
								@endif
							@endif
							@if((Auth::user()->hasRole('maguser')) && ($list->type=='elimination'))
							<a href="{{ route('lists.destroy',$list->id) }}?holstoburn=1" data-remote="true" data-method="delete" class="btn btn-success btn-xs" >
							<span class="fa fa-fire"></span> {{ trans('general.Hols burned') }}
							</a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			<div class="pagination">{{ $hlists->appends(Input::except('page'))->links()  }}</div>
		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->

	<div class="remote">
		<div class="modal" id="form-edit-list"></div><!-- /.modal -->
	</div>


	@else
	<h2 class="text-info  text-center">
		<span class="fa fa-info-circle text-danger"></span> {{ trans('lists.nolists') }}
	</h2>
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

