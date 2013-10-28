@extends('layouts.holdings')


{{-- Content --}}
@section('content')
<div class="row">
	<div class="col-sm-12">

	<div class="page-header">
		<h3><?= trans('holdings.title') ?></h3>
	</div>

		<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr> 
				<th><input id="select-all" name="select-all" type="checkbox" value="1" /> </th>
				<th></th>
				<th><?= 'f245a'; ?></th>
				<th><?= 'f245b'; ?></th>
				<th><?= 'f245c'; ?></th>
				<th><?= 'ocrr_ptrn'; ?></th>
				<th><?= 'f022a'; ?></th>
				<th><?= 'f260a'; ?></th>
				<th><?= 'f260b'; ?></th>
				<th><?= 'f710a'; ?></th>
				<th><?= 'f780t'; ?></th>
				<th><?= 'f362a'; ?></th>
			</tr>
		</thead>
		<tbody>
		@foreach ($holdings as $holding)
			<tr id="<?= $holding->id ?>">
				<td><input id="holding_id" name="holding_id[]" type="checkbox" value="<?= $holding->id ?>" /></td>
				<td><a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
					<span class="glyphicon glyphicon-eye-open"></span>
				</a>
				<td><?= $holding->holdingsset->f245a; ?></td>
				<td><?= $holding->f245b; ?></td>
				<td><?= $holding->f245c; ?></td>
				<td><?= $holding->ocrr_ptrn; ?></td>
				<td><?= $holding->f022a; ?></td>
				<td><?= $holding->f260a; ?></td>
				<td><?= $holding->f260b; ?></td>
				<td><?= $holding->f710a; ?></td>
				<td><?= $holding->f780t; ?></td>
				<td><?= $holding->f362a; ?></td>
			</tr>
		@endforeach

		</tbody>
	</table>

	<?= $holdings->links()  ?>


	</div>
</div>

	@include('cabinets.create')
	<div id="modal-show" class="modal face"></div>
@stop
