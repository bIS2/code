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
							<td><?= $holding->f022a; ?></td>
						</tr>
						<tr>
						  <td>245a</td>
							<td><?= htmlspecialchars($holding->f245a,ENT_QUOTES); ?></td>
						</tr>					
						<tr>
						  <td>245b</td>
							<td><?= $holding->f245b; ?></td>
						</tr>
						<tr>
						  <td>245c</td>
							<td><?= $holding->f245c; ?></td>
						</tr>
						<tr>
						  <td>246a</td>
							<td><?= $holding->f246a; ?></td>
						</tr>						
						<tr><?php $ownertrclass 	= ($holding->is_owner == 't') ? ' is_owner' : '';  ?>
						  <td>{{ trans('holdingssets.ocurrence_patron') }}</td>
							<td class="ocrr_ptrn {{$ownertrclass}}"><?= $holding->patrn_no_btn; ?></td>
						</tr>
						<tr>
						  <td>866a</td>
							<td><?= $holding->f866a; ?></td>
						</tr>		
						<?php if (Auth::user()->hasRole('bibuser')) { ?>					
						<tr>
						  <td class="text-danger">{{ trans('general.edit') }} f866a</td>
							<td>						
							<div id="f866aeditablecontainer" class="input-group">
								<?php $editable866a = ($holding->f866aupdated == '') ?  $holding->f866a : $holding->f866aupdated ?>
					      <input type="text" value="<?= $editable866a; ?>" name="f866a" id="f866aeditable" class="form-control">
					      <a id="f866aeditablesave" class="btn btn-primary input-group-btn" set="<?= $holding->holdingsset_id ?>" href="{{ action('HoldingssetsController@putUpdateField866aHolding',[$holding->id]) }}" data-params="new866a={{ $holding->f866a }}" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-save"></i></a>
					    </div><!-- /input-group -->
							</td>
						</tr>
						<?php }  ?>
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
						  <td>f260a</td>
							<td><?= $holding->f260a; ?></td>
						</tr>
						<tr>
						  <td>f300a</td>
							<td><?= $holding->f300a; ?></td>
						</tr>
						<tr>
						  <td>f300b</td>
							<td><?= $holding->f300b; ?></td>
						</tr>
						<tr>
						  <td>f300c</td>
							<td><?= $holding->f300c; ?></td>
						</tr>
						<tr>
						  <td>f310a</td>
							<td><?= $holding->f310a; ?></td>
						</tr>
						<tr>
						  <td>f710a</td>
							<td><?= $holding->f710a; ?></td>
						<tr>
						  <td>f362a</td>
							<td><?= $holding->f362a; ?></td>
						</tr>
						<tr>
						  <td>f500a</td>
							<td><?= $holding->f500a; ?></td>
						</tr>
						<tr>
						  <td>f505a</td>
							<td><?= $holding->f505a; ?></td>
						</tr>
						<tr>
						  <td>f770t</td>
							<td><?= $holding->f770t; ?></td>
						</tr>
						<tr>
						  <td>f772t</td>
							<td><?= $holding->f772t; ?></td>
						</tr>
						<tr>
						  <td>f780t</td>
							<td><?= $holding->f780t; ?></td>
						</tr>
						<tr>
						  <td>f785t</td>
							<td><?= $holding->f785t; ?></td>
						</tr>				
						<tr>
						  <td>f852b</td>
							<td><?= $holding->f852b; ?></td>
						</tr>
						<tr>
						  <td>f852c</td>
							<td><?= $holding->f852c; ?></td>
						</tr>
						<tr>
						  <td>f852h</td>
							<td><?= $holding->f852h; ?></td>
						</tr>
						<tr>
						  <td>f852j</td>
							<td><?= $holding->f852j; ?></td>
						</tr>
						<tr>
						  <td>f866z</td>
							<td><?= $holding->f866z; ?></td>
						</tr>
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


