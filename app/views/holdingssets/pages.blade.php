	<?php 

	function truncate($str, $length, $trailing = '...') {
	    // take off chars for the trailing
	    $length-=strlen($trailing);
	    if (strlen($str) > $length) {
	        // string exceeded length, truncate and add trailing dots
	        $res = substr($str, 0, $length);
	        $res .= $trailing;
	    } else {
	        // string was already short enough, return the string
	        $res = $str;
	    }
	    return $res;
	}

	function the_truncate($str, $length, $trailing) {
	    echo truncate($str, $length, $trailing);
	}

?>
	@foreach ($holdingssets as $holdingsset)
		<?php $ok 	= ($holdingsset->ok) ? 'ok' : ''  ?>
		<?php $btn 	= ($holdingsset->ok) ? 'btn-success' : 'btn-default'  ?>
		<?php $link = ($holdingsset->ok) ? 'HoldingssetsController@putOK' : 'HoldingssetsController@putKO'  ?>
		<tr class="panel {{ $ok }}" id="<?= $holdingsset -> id; ?>">
			<td class="list-group-item">
			  <div class="panel-heading row">
		  		<input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="<?= $holdingsset->id ?>">
		      <div href="#<?= $holdingsset -> sys1; ?>" data-parent="#group-xx" title="<?= $holdingsset->f245a; ?>" data-toggle="collapse" class="accordion-toggle collapsed col-lg-10" opened="0">
		      	<?= $holdingsset->sys1.' :: '.htmlspecialchars(truncate($holdingsset->f245a, 50),ENT_QUOTES); ?>
		      	@if ($holdingsset->has('holdings') && $count1 = $holdingsset -> holdings -> count()) 
		      		<span class="badge">{{ $count1.' Holdings' }} </span>
		      	@endif
		      	@if ($holdingsset->has('groups') && ($count=$holdingsset->groups->count()>1)) 
		      		<span class="badge" title = "<?php 
		      			$currentgroups = $holdingsset->groups;
		      			$count = 0;
			      		foreach ($currentgroups as $currentgroup) {			
			      		$count++;      			
			      			if (($currentgroup['id']) != $group_id) echo strtoupper($currentgroup['name']).';';
			      		} 
		      		?>"
		      		><?= trans('general.included_in') ?> {{ $count }} <?= trans('holdingssets.groups') ?></span>
		      	@endif
		      </div>
		      <div class="text-right action-ok">
		      	<a id="holdingsset<?= $holdingsset -> sys1; ?>" href="{{ action('HoldingssetsController@putOk',[$holdingsset->id]) }}" class="btn  btn-xs {{ $btn }}" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="...">
		      			<span class="glyphicon glyphicon-ok"></span>
		      	</a>
		      </div>
			  </div>
	  		<div class="panel-collapse collapse container" id="<?= $holdingsset -> sys1; ?>">
			     <div class="panel-body">
						<table class="table table-striped table-hover flexme">
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