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

<table id="hosg" class="">
	<thead>
		<tr>
			<th>
			  <!-- <h3 style="width: 240px; display: inline-block;">Sys1</h3>
			  <h3 style="display: inline-block;">f245a</h3> -->
			  <input id="select-all" name="select-all" type="checkbox" value="1" /> Select all Holdingsets
			</th>
		</tr>
	</thead>
	<tbody class="list-group">
	@foreach ($holdingssets as $holdingsset)
		<?php $ok 	= ($holdingsset->ok) ? 'ok' : ''  ?>
		<?php $btn 	= ($holdingsset->ok) ? 'btn-danger' : 'btn-success'  ?>
		<?php $icon = ($holdingsset->ok) ? 'glyphicon-remove' : 'glyphicon-ok'  ?>
		<?php $link = ($holdingsset->ok) ? 'HoldingssetsController@putOK' : 'HoldingssetsController@putKO'  ?>
		<tr class="panel {{ $ok }}" id="<?= $holdingsset -> id; ?>">
			<td class="list-group-item">
			  <div class="panel-heading row">
		      <div href="#<?= $holdingsset -> sys1; ?>" data-parent="#group-xx" data-toggle="collapse" class="accordion-toggle collapsed col-lg-11">
		  		<input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="<?= $holdingsset->id ?>" />
		      	<?= $holdingsset -> sys1.' :: '.$holdingsset -> f245a; ?>
		      </div>
		      <div class="col-lg-1 text-right">
		      	<a id="holdingsset<?= $holdingsset -> sys1; ?>" href="{{ route('holdingssets.update',[$holdingsset->id]) }}" class="btn  btn-sm {{ $btn }}" data-params="ok=true" data-remote="true" data-method="put">
		      			<span class="glyphicon {{ $icon }}"></span>
		      	</a>
		      </div>
			  </div>
	  		<div class="panel-collapse collapse container" id="<?= $holdingsset -> sys1; ?>" style="height: 0px;">
			     <div class="panel-body">
						<table class="table table-striped table-hover flexme flexme<?php if ($i == 1) echo $i;  ?>">
							<thead>
								<tr>
									<th>Actions</th>
									<th><?php echo '245a'; ?></th>
									<th><?php echo '245b'; ?></th>
									<th><?php echo '245c'; ?></th>
									<th><?php echo 'ocrr_ptrn'; ?></th>
									<th><?php echo '022a'; ?></th>
									<th><?php echo '260a'; ?></th>
									<th><?php echo '260b'; ?></th>
									<th><?php echo '710a'; ?></th>
									<th><?php echo '780t'; ?></th>
									<th><?php echo '362a'; ?></th>
									<th><?php echo '866a'; ?></th>
									<th><?php echo '866z'; ?></th>
									<th><?php echo '310a'; ?></th>
								</tr>
							</thead>
							<tbody>
						<? $k = 0; $k++; ?>
							@foreach ($holdingsset -> holdings as $post)		
								<tr>
									<td>
										<a href="<?= route('holdings.show', $post->id) ?>" data-target="#modal-show" data-toggle="modal">
											<span class="glyphicon glyphicon-eye-open"></span>
										</a>
										<a href="" data-target="#modal-show-external" data-toggle="modal" data-remote="<?= route('holdings.show', $post->id) ?>">
											<span class="glyphicon glyphicon-list-alt"></span>
										</a>
									</td>
									<td><?php echo $post->f245a; ?></td>
									<td><?php echo $post->f245b; ?></td>
									<td><?php echo $post->f245c; ?></td>
									<td><?php echo $post->ocrr_ptrn; ?></td>
									<td><?php echo $post->f022a; ?></td>
									<td><?php echo $post->f260a; ?></td>
									<td><?php echo $post->f260b; ?></td>
									<td><?php echo $post->f710a; ?></td>
									<td><?php echo $post->f780t; ?></td>
									<td><?php echo $post->f362a; ?></td>
									<td><?php echo $post->f866a; ?></td>
									<td><?php echo $post->f866z; ?></td>
									<td><?php echo $post->f310a; ?></td>
								</tr>
							@endforeach
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
<div id="modal-show" class="modal face"><div class="modal-body"></div></div>
<div id="modal-show-external" class="modal face"><div class="modal-body"></div></div>
@stop