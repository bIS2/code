@extends('layouts.holdingssets')

{{-- Content --}}
@section('content')
<?php 
	$i = 0;
	$currentgroup = -1;
?>

<div class="page-header">
	<h3><?= trans('holdingssets.title') ?></h3>
</div>

<table id="hosg" class="table table-striped table-hover dataTabl">
	<thead>
		<tr>
			<th>
			  <!-- <h3 style="width: 240px; display: inline-block;">Sys1</h3>
			  <h3 style="display: inline-block;">f245a</h3> -->
			  <input id="select-all" name="select-all" type="checkbox" value="1" /> Select all Holdingsetssss
			</th>

		</tr>
	</thead>
	<tbody>
	@foreach ($holdingssets as $holdingsset)
		<tr class="panel" id="<?= $holdingsset -> id; ?>">
			<td>
			  <div class="panel-heading">
			  		<input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="<?= $holdingsset->id ?>" />
			      <h4 href="#<?= $holdingsset -> sys1; ?>" data-parent="#group-xx" data-toggle="collapse" class="accordion-toggle collapsed" style="width: 240px; display: inline-block;"><?= $holdingsset -> sys1; ?>
			      <h4 href="#<?= $holdingsset -> f245a; ?>" data-parent="#group-xx" data-toggle="collapse" class="accordion-toggle collapsed" style="display: inline-block;"><?= $holdingsset -> f245a; ?></h4>
			  </div>
	  		<div class="panel-collapse collapse container" id="<?= $holdingsset -> sys1; ?>" style="height: 0px;">
			     <div class="panel-body">
						<table class="table table-striped table-hover flexme flexme<?php if ($i == 1) echo $i;  ?>">
							<thead>
								<tr>
									<th><?php echo 'f245b'; ?></th>
									<th><?php echo 'f245c'; ?></th>
									<th><?php echo 'ocrr_ptrn'; ?></th>
									<th><?php echo 'f022a'; ?></th>
									<th><?php echo 'f260a'; ?></th>
									<th><?php echo 'f260b'; ?></th>
									<th><?php echo 'f710a'; ?></th>
									<th><?php echo 'f780t'; ?></th>
									<th><?php echo 'f362a'; ?></th>
									<th><?php echo 'f866a'; ?></th>
									<th><?php echo 'f866z'; ?></th>
									<th><?php echo 'f310a'; ?></th>
								</tr>
							</thead>
							<tbody>
						<? $k = 0; $k++; ?>
						
							</tbody>
						</table>
					</div>
				</div>
			</td>
		</tr>
	@endforeach
	</tbody>
</table>	

@include('groups.create')

@stop