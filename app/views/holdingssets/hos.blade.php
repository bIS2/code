@foreach ($holdingssets as $holdingsset)
		<?php $ok 	= ($holdingsset->ok) ? 'ok' : ''  ?>
		<?php $btn 	= ($holdingsset->ok) ? 'btn-success' : 'btn-default'  ?>
		<!-- <li class="panel list-group-item {{ $ok }}" id="<?= $holdingsset -> id; ?>"> -->
		<li id="<?= $holdingsset -> id; ?>">
			  <div class="panel-heading row">
		  		<input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="<?= $holdingsset->id ?>" class="pull-left hl sel">
		      <div href="#<?= $holdingsset -> sys1; ?>" data-parent="#group-xx" title="<?= $holdingsset->f245a; ?>" data-toggle="collapse" class="accordion-toggle collapsed col-xs-10" opened="0">
		      	<?= $holdingsset->sys1.' :: '.htmlspecialchars(truncate($holdingsset->f245a, 100),ENT_QUOTES); ?>
		      	@if ($holdingsset->has('holdings') && $count1 = $holdingsset -> holdings -> count()) 
		      		<span class="badge"><i class="fa fa-files-o"></i> {{ $count1 }} </span>
		      	@endif
		      	@if ($holdingsset->has('groups') && ($count=$holdingsset->groups->count()>0)) 
		      		<span class="badge" title = "<?php 
		      			$currentgroups = $holdingsset->groups;
		      			$count = 0;
			      		foreach ($currentgroups as $currentgroup) {			
			      		$count++;      			
			      			if (($currentgroup['id']) == $group_id) echo strtoupper($currentgroup['name']).';';
			      			else
			      				echo strtolower($currentgroup['name']).';';
			      		} 
		      		?>"
		      		><i class="fa fa-folder-o"></i> {{ $count }}</span>
		      	@endif
		      </div>
		      <div class="text-right action-ok col-xs-1">
		      <!-- 	<a id="holdingsset<?= $holdingsset -> sys1; ?>add" class="btn btn-ok btn-xs {{ $btn }}" title="{{ trans('holdingssets.add_holdings') }}">
		      			<span class="glyphicon glyphicon-download-alt"></span>
		      	</a> -->
		      	@if (Auth::user()->hasRole('resuser')) 
		      	@else
<!-- 		      	<a id="holdingsset<?= $holdingsset -> sys1; ?>" href="{{ action('HoldingssetsController@putOk',[$holdingsset->id]) }}" class="btn btn-ok btn-xs {{ $btn }}" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..." title="{{ trans('holginssets.add_HOL_to_this_HOS') }}">
		      			<span class="fa fa-plus"></span>
		      	</a> -->		
		      	<a id="holdingsset<?= $holdingsset -> sys1; ?>" href="{{ action('HoldingssetsController@putOk',[$holdingsset->id]) }}" class="btn btn-ok btn-xs {{ $btn }}" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..." title="{{ trans('holginssets.confirm_ok_HOS') }}">
		      			<span class="glyphicon glyphicon-thumbs-up"></span>
		      	</a>		
		      	@endif      	
		      </div>
			  </div>	
	  		<div class="panel-collapse collapse container" id="<?= $holdingsset -> sys1; ?>">
			    <div class="panel-body">
						<?php 
							$k = 0; 
							$k++; 
							$fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show');
							$fieldstoshow = explode(';',$fieldstoshow);
						?>
						<table class="table table-hover flexme table-bordered draggable">
							<thead>
								<tr>
									<th>Actions</th>
									<?php
										$k = 0;
										foreach ($fieldstoshow as $field) {
											if ($field != 'ocrr_ptrn') { $k++; ?>											
												<th><?= $field; ?> <span class="fa fa-info-circle"></span></th> 
												<?php if ($k == 1) { ?>
													<th class="hocrr_ptrn"><?php echo 'ocrr_ptrn'; ?></th>
												<?php	} ?>
											<?php } 
										}
									?>						
								</tr>
							</thead>
							<tbody>
							@foreach ($holdingsset -> holdings as $holding)
							<?php $btnlock 	= ($holding->locked) ? 'btn-warning ' : 'btn-default '; ?>	
							<?php $trclass 	= ($holding->locked) ? 'locked' : ''  ?>	
							<?php $ownertrclass 	= ($holding->is_owner == 't') ? ' is_owner' : '';  ?>	
							<?php $auxtrclass 	= ($holding->is_aux == 't') ? ' is_aux' : '';  ?>
							<?php $preftrclass 	= ($holding->is_pref == 't') ? ' is_pref' : '';  ?>	
							<?php $librarianclass = ' '.substr($holding->sys2, 0, 4);  ?>	
								<tr id="holding{{ $holding -> id; }}" class="{{ $trclass }}{{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}">
									<td>
		      					@if (Auth::user()->hasRole('resuser')) 
						      	<a id="holding<?= $holding -> id; ?>lock" href="{{ action('HoldingssetsController@putLock',[$holding->id]) }}" class="{{ $btnlock }}" data-params="locked=true" data-remote="true" data-method="put" data-disable-with="..."><span class="glyphicon glyphicon-lock"></span></a>
										@else
										<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal"><span class="glyphicon glyphicon-eye-open" title="{{ trans('holdingssets.see_more_information') }}"></span></a>
										<a href="http://bis.trialog.ch/sets/from-library/<?= $holding->id; ?>" data-target="#modal-show" data-toggle="modal" title="{{ trans('holdingssets.see_information_from_original_system') }}"><span class="glyphicon glyphicon-list-alt"></span></a>

						      	<a id="holding<?= $holding -> id; ?>delete" href="{{ action('HoldingssetsController@putNewHOS',[$holding->id]) }}" data-params="" data-remote="true" data-method="put" data-disable-with="..." title="{{ trans('holdingssets.remove_from_HOS') }}"><span class="glyphicon glyphicon-trash"></span></a>

										<a href="http://bis.trialog.ch/sets/from-library/<?= $holding->id; ?>" data-target="#modal-show" data-toggle="modal" title="{{ trans('holdingssets.move_to_other_group') }}"><span class="glyphicon glyphicon-move"></span></a>
						      @endif 
									</td>
									<?php $k = 0;
										foreach ($fieldstoshow as $field) {
											if ($field != 'ocrr_ptrn') { $k++;
												if ($field != 'sys2') $field = 'f'.$field;
											 ?>											
												<td><?= htmlspecialchars($holding->$field); ?></td>
												<?php if ($k == 1) { ?>
													<td class="ocrr_ptrn">
														<?php											
															$ocrr_ptrn 	= str_split($holding->ocrr_ptrn);
															$j_ptrn 		= str_split($holding->j_ptrn);
															$aux_ptrn 	= str_split($holding->aux_ptrn);
															$i 					= 0;
															foreach ($ocrr_ptrn as $ocrr) { 
															 	switch ($ocrr) {
															 		case '0':
															 			echo '<i class="fa fa-square-o fa-lg"></i>';
															 			break;	

															 		case '1':
															 			$classj = '';
															 			$classaux = '';
															 		 	if (isset($j_ptrn[$i])) $classj = ($j_ptrn[$i] == '1') ? ' j' : ''; 
															 			if (isset($aux_ptrn[$i]))  $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
															 			echo '<i class="fa fa-square fa-lg'.$classj.$classaux.'"></i>';
															 			break;
															 	}
															 $i++; 
															} 
														?>
													</td>
												<?php	} ?>
											<?php } }									?>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
		</li>
	@endforeach