  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title"><?= $holdingsset->sys1.' :: '.$holdingsset->show('f245a'); ?></h3>
      </div>
      <div class="modal-body">
      	<table class="table table-bordered">
					<thead>
						<tr>		
							<th>sys</th>				
							<th>hbib</th>				
							<th class="hocrr_ptrn">
								<?php 
									$ptrn = (explode('|',$holdingsset -> ptrn)); 
									foreach ($ptrn as $key) { 
										echo '<i class="fa fa-square fa-lg pop-over" data-content="'.$key.'" data-placement="bottom" data-toggle="popover" class="btn btn-default" type="button" data-trigger="hover" data-original-title="" title=""></i>';
									 }
								?><br>
							</th>			
							<th>866a</th>				
							<th>245a :: 245b</th>				
							<th>852b</th>				
							<th>852h</th>				
						</tr>
					</thead>
					<tbody>
						@foreach ($holdingsset -> holdings as $holding)
						<?php $btnlock 	= ($holding->locked()->exists()) ? 'btn-warning ' : ''; ?>	
						<?php $trclass 	= ($holding->locked()->exists()) ? 'locked' : ''  ?>	
						<?php $ownertrclass = ($holding->is_owner == 't') ? ' is_owner' : '';  ?>	
						<?php $auxtrclass 	= ($holding->is_aux == 't') ? ' is_aux' : '';  ?>
						<?php if (isset($aux_ptrn[$i]))  $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; ?>
						<?php $preftrclass 	= ($holding->is_pref == 't') ? ' is_pref' : '';  ?>	
						<?php $librarianclass = ' '.substr($holding->sys2, 0, 4);  ?>	
							<tr id="holding{{ $holding -> id; }}" class="{{ $trclass }}{{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}{{ ($holding->is_annotated) ? ' text-warning' : '' }}">			
								<td>{{ $holding->show('sys2') }}</td>				
								<td>{{ $holding->library->code }}</td>				
								<td class="ocrr_ptrn">
									{{ $holding -> patrn_no_btn }}
								</td>				
								<td>{{ $holding->show('f866a') }}</td>				
								<td>{{ $holding->show('f245a') }} :: {{ $holding->show('f245b') }}</td>				
								<td>{{ $holding->show('f852b') }}</td>				
								<td>{{ $holding->show('f852h') }}</td>	
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
  <script type="text/javascript">
  	$('.pop-over').popover();
  </script>