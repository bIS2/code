@extends('layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-xs-12">
			<ul class="list-inline">
				<li>
					<strong>
						{{ trans('holdings.title') }} 
						@if ($hlist)
							<small>&raquo; {{ $hlist->name }}</small>
						@endif				
					</strong>
				</li>
			  <li>
				  <div class="btn-group">
				  	<div class="btn-group">
					  	<a href="#" class="btn btn-sm dropdown-toggle {{ (Input::has('hlist_id')) ? 'btn-primary' : 'btn-default'}}" data-toggle="dropdown">
					  		<span class="glyphicon glyphicon-th-list"> </span> 
					  		@if (Input::has('hlist_id'))
					  			{{ Hlist::find(Input::get('hlist_id'))->name }}
					  		@else
					  			{{{ trans('holdings.lists') }}} 
					  		@endif
					  		<span class="caret"></span>
					  	</a>
					  	<!-- Show list if exists -->
							@if (Auth::user()->has('hlists')) 
								<?php $lists = Auth::user()->hlists() ?>
								<ul class="dropdown-menu" role="menu">
									@foreach (Auth::user()->hlists as $list) 
									<li>
										<a href="{{ route('holdings.index',['hlist_id'=>$list->id]) }}"> {{ $list->name }} <span class="badge">{{ $list->holdings()->count() }} </span></a>
									</li>
									@endforeach
								</ul>
							@endif
					  </div>
			  		<a href="#" class="btn btn-default btn-sm disabled link_bulk_action" data-toggle="modal" data-target="#form-create-list" >
			  			<span class="glyphicon glyphicon-plus-sign"></span>
			  		</a>
				  </div>
			  <li>
				  <div class="btn-group">
				  	<a href="{{ route('holdings.index',['state'=>'ok2']) }}" class="btn <?= (Input::has('state')=='ok2') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="glyphicon glyphicon-thumbs-up"></span> {{{ trans('holdings.ok2') }}}
				  	</a>
				  	<a href="{{ route('holdings.index',['state'=>'tagged']) }}" class="btn <?= (Input::has('ok2')) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="glyphicon glyphicon-tags"></span> {{{ trans('holdings.tagged') }}}
				  	</a>
				  	<a href="{{ route('holdings.index') }}" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="glyphicon glyphicon-warning-sign"></span> {{{ trans('holdings.pending') }}}
				  	</a>
				  	<a href="{{ route('holdings.index',['state'=>'orphan']) }}" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="glyphicon glyphicon-question-sign"></span> {{{ trans('holdings.orphan') }}}
				  	</a>
				  	<a href="#" id="filter-btn" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="glyphicon glyphicon-filter"></span> {{{ trans('holdings.filter') }}}
				  	</a>
				  	<a href="{{ route('holdings.index') }}" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="glyphicon glyphicon-print"></span> {{{ trans('holdings.printer') }}}
				  	</a>

				  </div>
			  </li>
			</ul>
		</div>
	</div> <!-- /.row -->
	<div class="row">
		<div class="col-xs-12">
			<div class="well well-sm" id="filter-well" style="display:none">
				<form class="form-inline" role="form" method="get">
					<div class="form-group">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">245a</label>
						  <input type="text" class="form-control" name="f245a">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">245b</label>
						  <input type="text" class="form-control" name="f245b">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">245c</label>
						  <input type="text" class="form-control" name="f245c">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">260b</label>
						  <input type="text" class="form-control" name="f260b">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">362a</label>
						  <input type="text" class="form-control" name="f362a">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">866a</label>
						  <input type="text" class="form-control" name="f866a">
						</div>
					</div>

				  <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span>{{ trans('general.search') }}</button>
				</form>
			</div>
		</div>
	</div>
</div> <!-- /.page-header -->	
<div class="row">
	<div class="col-lg-12">
		<table id="holdings-items" class="table table-bordered table-condensed ">
		<thead>
			<tr> 
				<th></th>
				<th><?= '245a'; ?></th>
				<th><?= '245b'; ?></th>
				<th><?= '245c'; ?></th>
				<th><?= '260b'; ?></th>
				<th><?= '362a'; ?></th>
				<th><?= '866a'; ?></th>
				<th><?= '852h'; ?></th>
				<td></td>
			</tr>
		</thead>
		<tbody class="selectable">
		@foreach ($holdings as $holding)
			<tr id="<?= $holding->id ?>">
<!-- 				<td>
					<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
						<span class="glyphicon glyphicon-eye-open"></span>
					</a>
					<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
											<span class="glyphicon glyphicon-ok"></span>
					</a>
				</td>

 -->		<td><input type="checkbox" value="{{ $holding->id }}" name="holding_id[]" class="sel"/></td>
 					
 				<td>
 					{{ link_to_route('holdings.show', $holding->holdingsset->f245a,[ $holding->id ]) }}
 				</td>
				<td><?= $holding->f245b; ?></td>
				<td><?= $holding->f245c; ?></td>
				<td><?= $holding->f260b; ?></td>
				<td><?= $holding->f362a; ?></td>
				<td><?= $holding->f866a; ?></td>
				<td><?= $holding->f852h; ?></td>
				<td id="{{ $holding->id }}" class="col-lg-1">
					<div class="btn-group">
					  <a href="{{ action('HoldingsController@putOK',[$holding->id]) }}" class="btn {{ ($holding->ok2) ? 'btn-success' : 'btn-default' }} btn-xs btn-ok" data-method="put" data-remote="true" >
					  	<span class="glyphicon glyphicon-thumbs-up"></span>
					  </a>
					  <?php $is_tagged = ( ($count=$holding->tags->count())>0)  ?>
					  <a href="{{ route('tags.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-tags" class="btn {{ ($is_tagged) ? 'btn-danger' : 'btn-default' }} btn-xs btn-tag {{ ($holding->ok2) ? 'disabled' : '' }}">
					  	<span class="glyphicon glyphicon-tags"></span> 
					  </a>
					</div>
				</td>

			</tr>
		@endforeach

		</tbody>
	</table>

	<?= $holdings->links()  ?>


	</div>
</div>


 <div class="modal" id="form-create-tags">

  </div><!-- /.modal -->

	@include('hlists.create')
@stop

