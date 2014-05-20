<?php $librarianclass = ' '.substr($holding->sys2, 0, 4); ?>
  <div class="modal-dialog{{ $librarianclass }}">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      	<h3 class="modal-title">{{ $holding-> library->code }} :: {{ htmlspecialchars($holding->f245a,ENT_QUOTES) }}</h3>
      </div>
      <div class="modal-body">

				<table class="table table-striped table-condensed">
					<thead>
						<tr>
						  <th class="col-xs-3">{{ trans('holdings.field') }}</th>
							<th class="col-xs-9">{{ trans('holdings.value') }}</th>
						</tr>
					</thead>				
					<tbody>
						<tr>
						  <td>sys2</td>
							<td><?= $holding->sys2; ?></td>
						</tr>
						<tr>
						  <td>022a</td>
							<td><?= $holding->show('f022a'); ?></td>
						</tr>
						<tr>
						  <td>245a</td>
							<td><?= $holding->show('f245a'); ?></td>
						</tr>					
						<tr>
						  <td>245b</td>
							<td><?= $holding->show('f245b'); ?></td>
						</tr>
						<tr>
						  <td>245c</td>
							<td><?= $holding->show('f245c'); ?></td>
						</tr>
						<tr>
						  <td>245n</td>
							<td><?= $holding->show('f245n'); ?></td>
						</tr>
						<tr>
						  <td>245p</td>
							<td><?= $holding->show('f245p'); ?></td>
						</tr>
						<tr>
						  <td>246a</td>
							<td><?= $holding->show('f246a'); ?></td>
						</tr>						
						<tr><?php $ownertrclass = ($holding->is_owner == 't') ? ' is_owner' : '';  ?>
						  <td>{{ trans('holdingssets.ocurrence_patron') }}</td>
							<td class="ocrr_ptrn {{$ownertrclass}}"><?= $holding->patrn_no_btn; ?></td>
						</tr>
						<tr>
						  <td>866a</td>
							<td><?= $holding->f866a; ?></td>
						</tr>		
						<?php if (Auth::user()->hasRole('bibuser')) { ?>					
						<tr>
						  <td class="text-danger">{{ trans('general.edit') }} 866a</td>
							<td>						
							<div id="f866aeditablecontainer" class="input-group">
								<?php $editable866a = ($holding->f866aupdated == '') ?  $holding->f866a : $holding->f866aupdated ?>
					      <input type="text" value="<?= $editable866a; ?>" name="f866a" id="f866aeditable" class="form-control">
					      <a id="f866aeditablesave" class="btn btn-primary input-group-btn" set="<?= $holding->holdingsset_id ?>" href="{{ action('HoldingssetsController@putUpdateField866aHolding',[$holding->id]) }}" data-params="new866a={{ $holding->f866a }}" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-save"></i></a>
					    </div><!-- /input-group -->
							</td>
						</tr>
						<?php }
						//else { 
							//if ($holding->f866aupdated != $holding->f866a)  {
							?>
							<tr>
							  <td class="text-danger">{{ trans('general.edited') }} 866a</td>
								<td><?= $holding->f866aupdated; ?></td>
							</tr>	
							<?php //}  ?>
						<?php //}  ?>
						@if ($holding -> notes()-> exists())
							<tr>
								<td>{{ trans('holdings.notes') }}</td>
								<td>
									@foreach ( Tag::all() as $tag)
										<?php
											$note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note;
											if ($username == '') $username = $note->user->name;
											// var_dump($note->user->name);
										?>
										{{ Form::hidden('holding_id',$holding->id) }}
									    <div class="input-group" data-toggle="buttons">
									      <label class="input-group-addon btn btn-primary btn-sm {{ ($note->tag_id) ? 'active' : '' }}{{ $consultnotes }} disabled">
									      	<span class="glyphicon glyphicon-ok-sign"></span>
									        <input type="checkbox" name="notes[{{ $tag->id }}][tag_id]" disabled value="{{ $tag->id }}">{{ $tag->name }}
									      </label>
									      <input type="text" disabled name="notes[{{ $tag->id }}][content]" value="{{ $note->content }}" class="form-control input-sm"{{ $consultnotes }} placeholder="{{ trans('placeholders.notes_'.$tag->name) }}">
									    </div>
									@endforeach
									<strong>{{ trans('holdings.annotated_by') }}: </strong>{{ $username }}
								</td>
							</tr>
						@endif	
						<tr>
						  <td>260a</td>
							<td><?= $holding->show('f260a'); ?></td>
						</tr>
						<tr>
						  <td>300a</td>
							<td><?= $holding->show('f300a'); ?></td>
						</tr>
						<tr>
						  <td>300b</td>
							<td><?= $holding->show('f300b'); ?></td>
						</tr>
						<tr>
						  <td>300c</td>
							<td><?= $holding->show('f300c'); ?></td>
						</tr>
						<tr>
						  <td>310a</td>
							<td><?= $holding->show('f310a'); ?></td>
						</tr>
						<tr>
						  <td>710a</td>
							<td><?= $holding->show('f710a'); ?></td>
						<tr>
						  <td>362a</td>
							<td><?= $holding->show('f362a'); ?></td>
						</tr>
						<tr>
						  <td>500a</td>
							<td><?= $holding->show('f500a'); ?></td>
						</tr>
						<tr>
						  <td>505a</td>
							<td><?= $holding->show('f505a'); ?></td>
						</tr>
						<tr>
						  <td>770t</td>
							<td><?= $holding->show('f770t'); ?></td>
						</tr>
						<tr>
						  <td>772t</td>
							<td><?= $holding->show('f772t'); ?></td>
						</tr>
						<tr>
						  <td>780t</td>
							<td><?= $holding->show('f780t'); ?></td>
						</tr>
						<tr>
						  <td>785t</td>
							<td><?= $holding->show('f785t'); ?></td>
						</tr>				
						<tr>
						  <td>852b</td>
							<td><?= $holding->show('f852b'); ?></td>
						</tr>
						<tr>
						  <td>852h</td>
							<td><?= $holding->show('f852h'); ?></td>
						</tr>
						<tr>
						  <td>852j</td>
							<td><?= $holding->show('f852j'); ?></td>
						</tr>
						<tr>
						  <td>866c</td>
							<td><?= $holding->show('f866c'); ?></td>
						</tr>
						<tr>
						  <td>866z</td>
							<td><?= $holding->show('f866z'); ?></td>
						</tr>
						@if ($holding->has('comment'))
							<tr>
							  <td>{{ trans('holdings.comment') }}</td>
								<td>
										{{ $holding->comment->content }}
										<i class="fa fa-angle-double-right"></i>
										<i>{{ $holding->comment->user->username }}</i>
								</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>
			@if ($consultnotes)
				<div class="row">
					<div class="col-xs-5 text-right">
						{{ trans('holdingssets.notes_made_by') }}
					</div>
					<div class="col-xs-5 text-left">
						{{ $username }}
					</div>
				</div>
			@endif
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
  <script type="text/javascript">
	$('#f866aeditable').keyup(function() {
  	$('#f866aeditablesave').attr('data-params', 'new866a='+$('#f866aeditable').val());
  })
  $('#f866aeditablesave').on('click', function() {
		$(this).attr('data-params', 'new866a='+$('#f866aeditable').val());
	}) 
  </script>


