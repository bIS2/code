@extends('layouts.default')

@section('toolbar')

<div class="container page-header">

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
					  		<i class="fa fa-list-ul"> </i> 
					  		@if (Input::has('hlist_id'))
					  			<?php $list = Hlist::find(Input::get('hlist_id')) ?>
					  			{{ $list->name}} 
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
			  			<span class="fa fa-plus-circle"></span>
			  		</a>
				  </div>
			  <li>
				  <div class="btn-group">
				  	<a href="{{ route('holdings.index',['state'=>'corrects']) }}" class="btn <?= (Input::get('state')=='corrects') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="glyphicon glyphicon-thumbs-up"></span> {{{ trans('holdings.ok2') }}}
				  	</a>
				  	<div class="btn-group">
					  	<a href="{{ route('holdings.index',['state'=>'tagged']) }}" class="btn <?= ( Input::get('state')=='tagged' ) ? 'btn-primary' : 'btn-default' ?> btn-sm" data-toggle="dropdown">
					  		<span class="glyphicon glyphicon-tags"></span> {{{ trans('holdings.tagged') }}} 
					  	</a>
						  <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>					  	
					  	<ul class="dropdown-menu" role="menu">
					  		@foreach (Tag::all() as $tag)
					  			<li> <a href="">{{ $tag->name }}</a> </li>
					  		@endforeach
					  	</ul>
				  	</div>
				  	<a href="{{ route('holdings.index',['state'=>'pendings']) }}" class="btn <?= ( Input::get('state')=='pendings') ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="glyphicon glyphicon-warning-sign"></span> {{{ trans('holdings.pending') }}}
				  	</a>
				  	<a href="{{ route('holdings.index',['state'=>'orphans']) }}" class="btn <?= ( Input::get('state')=='orphans') ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="glyphicon glyphicon-question-sign"></span> {{{ trans('holdings.orphan') }}}
				  	</a>
				  	<div class="btn-group">
					  	<a href="#" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm dropdown-toggle" data-toggle="dropdown">
					  		<span class="fa fa-filter"></span> {{{ trans('holdings.filter') }}} <span class="caret"></span>
					  	</a>
					  	<ul class="dropdown-menu" role="menu">
					  		<li> <a href="{{ Request::url() }}">{{ trans('filter.icomplete') }}</a> </li>
					  		<li> <a href="">{{ trans('filter.complete') }}</a> </li>
					  		<li> <a href="">{{ trans('filter.insane') }}</a> </li>
					  		<li> <a href="">{{ trans('filter.sane') }}</a> </li>
					  	</ul>
				  	</div>
				  	<a href="#" id="filter-btn" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm dropdown-toggle" data-toggle="dropdown">
				  		<span class="fa fa-question-circle"></span> {{{ trans('holdings.advanced_filter') }}} 
				  	</a>
				  	<a href="{{ route('holdings.index') }}" class="btn btn-default btn-sm" >
				  		<span class="fa fa-times"></span> 
				  	</a>
				  </div>
				  <div class="btn-group">
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

			</div> <!-- /.well -->	
		</div> <!-- /.col -->	
	</div> <!-- /.row -->	

</div> <!-- /.page-header -->	


@stop

{{-- Content --}}
@section('content')


<div class="row">
	<div class="col-lg-12">
		<table id="holdings-items" class="table table-bordered table-condensed ">
		<thead>
			<tr> 
				<th></th>
				<th>852b</th>
				<th>852h</th>
				<th>245a</th>
				<th>362a</th>
				<th>866a</th>
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

 -->		<td><input type="checkbox" value="{{ $holding->id }}" name="holding_id[]" class="sel hl"/></td>
 					
				<td><?= $holding->f852b; ?></td>
				<td><?= $holding->f852h; ?></td>
 				<td>
 					{{ link_to_route('holdings.show', $holding->holdingsset->f245a,[ $holding->id ]) }}
 				</td>
				<td><?= $holding->f362a; ?></td>
				<td><?= $holding->f866a; ?></td>
				<td id="{{ $holding->id }}" class="col-lg-1">
				  <a href="{{ action('HoldingsController@putOK',[$holding->id]) }}" class="btn {{ ($holding->ok2) ? 'btn-success' : 'btn-default' }} btn-xs btn-ok" data-method="put" data-remote="true" >
				  	<span class="fa fa-thumbs-up"></span>
				  </a>
				  <?php $is_tagged = ( ($count=$holding->notes->count())>0)  ?>
				  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn {{ ($is_tagged) ? 'btn-danger' : 'btn-default' }} btn-xs btn-tag {{ ($holding->ok2) ? 'disabled' : '' }}">
				  	<span class="fa fa-tags"></span> 
				  </a>
				  @if (Authority::can('delivery',$holding))

					  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn btn-default btn-xs">
					  	<span class="fa fa-arrow-right"></span> 
					  </a>

					@endif

				</td>

			</tr>
		@endforeach

		</tbody>
	</table>
	<p>
		<?= $holdings->appends(Input::except('page'))->links()  ?>
	</p>

	</div>
</div>

<div class="remote">
 <div class="modal" id="form-create-notes"></div><!-- /.modal -->
</div>

	@include('hlists.create')
@stop

