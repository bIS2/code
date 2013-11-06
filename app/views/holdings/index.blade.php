@extends('layouts.default')


{{-- Content --}}
@section('content')

<br><br>
<div class="row">
	<div class="col-lg-12">
		<h2 class="brand">
			<?= trans('holdings.title') ?>
			<small>
				<!-- <span class="glyphicon glyphicon-arrow-right"></span> -->
				@if ( $hlist )
					&raquo;
					{{ $hlist->name }}
					<span class="label"></span>
				@endif
			</small>
		</h2>
	</div>
</div>	

<hr>


<div class="row">
	<div class="col-lg-2">
		<a href="#" data-toggle="modal" data-target="#form-create-list" class='link_bulk_action btn btn-default btn-block'>
		  	<span class="glyphicon glyphicon-list"></span> {{ trans('holdings.create_list') }} 
		</a><br>	
		<input type="text" class="form-control" placeholder="Search List">
		<ul class="nav nav-pills nav-stacked">
    	@foreach ($hlists as $hlist)
    		<li class="<?= !(Input::get('hlist_id')== $hlist->id ) ?: 'active'  ?>">
      	<a  href="<?= route('holdings.index',['hlist_id'=>$hlist->id])  ?>">
      		{{ $hlist->name }} 
      		<span class="badge pull-right">{{ $hlist->holdings->count() }} </span>
      	</a>   
    		</li>
    	@endforeach
		</ul>


	</div>

	<div class="col-lg-10">

		<table id="holdings-items" class="table table-hover table-condensed ">
		<thead>
			<tr> 
				<th><input id="select-all" name="select-all" type="checkbox" value="1" /> </th>
				<th><?= '245a'; ?></th>
				<th><?= '245b'; ?></th>
				<th><?= '245c'; ?></th>
				<th><?= 'ocrr_ptrn'; ?></th>
				<th><?= '022a'; ?></th>
				<th><?= '260a'; ?></th>
				<th><?= '260b'; ?></th>
				<th><?= '710a'; ?></th>
				<th><?= '780t'; ?></th>
				<th><?= '362a'; ?></th>
				<td></td>
			</tr>
		</thead>
		<tbody>
		@foreach ($holdings as $holding)
			<tr id="<?= $holding->id ?>">
				<td><input id="holding_id" name="holding_id[]" type="checkbox" value="<?= $holding->id ?>" /></td>
<!-- 				<td>
					<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
						<span class="glyphicon glyphicon-eye-open"></span>
					</a>
					<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
											<span class="glyphicon glyphicon-ok"></span>
					</a>
				</td>

 -->		
 				<td><?= $holding->holdingsset->f245a; ?></td>
				<td><?= $holding->f245b; ?></td>
				<td><?= $holding->f245c; ?></td>
				<td><?= $holding->ocrr_ptrn; ?></td>
				<td><?= $holding->f022a; ?></td>
				<td><?= $holding->f260a; ?></td>
				<td><?= $holding->f260b; ?></td>
				<td><?= $holding->f710a; ?></td>
				<td><?= $holding->f780t; ?></td>
				<td>
					<a href="#" class="btn btn-primary btn-xs"><span class="caret"></span></a>
				</td>

			</tr>
		@endforeach

		</tbody>
	</table>

	<?= $holdings->links()  ?>


	</div>
</div>

	@include('hlists.create')
	<div id="modal-show" class="modal face"></div>
@stop
