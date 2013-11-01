@foreach ($holdingssets as $holdingsset)
		<tr class="panel" id="<?= $holdingsset -> id; ?>">
			<td>
			  <div class="panel-heading">
			  <input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="<?= $holdingsset->id ?>" />
			      <h4 href="#<?= $holdingsset -> sys1; ?>" data-parent="#group-xx" data-toggle="collapse" class="accordion-toggle collapsed" style="width: 240px; display: inline-block;"><?= $holdingsset -> sys1; ?>
			      <h4 href="#<?= $holdingsset -> sys1; ?>" data-parent="#group-xx" data-toggle="collapse" class="accordion-toggle collapsed" style="display: inline-block;"><?= $holdingsset -> f245a; ?></h4>
			  </div>
	  		<div class="panel-collapse collapse container" id="<?= $holdingsset -> sys1; ?>" style="height: 0px;">
			     <div class="panel-body">
						<table class="table table-striped table-hover flexme">
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
							@foreach ($holdingsset -> holdings as $post)		
								<tr>
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