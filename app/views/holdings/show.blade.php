  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
				<table class="table table-striped table-condensed">
					<tr>
					  <td>f245a</td>
						<td><?= htmlspecialchars($holding->f245a,ENT_QUOTES); ?></td>
					</tr>					
					<tr>
					  <td>f245b</td>
						<td><?= $holding->f245b; ?></td>
					</tr>
					<tr>
					  <td>f245c</td>
						<td><?= $holding->f245c; ?></td>
					</tr>
					<tr><?php $ownertrclass 	= ($holding->is_owner == 't') ? ' is_owner' : '';  ?>
					  <td>ocrr_ptrn</td>
						<td class="ocrr_ptrn {{$ownertrclass}}"><?= $holding->patrn; ?></td>
					</tr>
					<tr>
					  <td>f022a</td>
						<td><?= $holding->f022a; ?></td>
					</tr>
					<tr>
					  <td>f260a</td>
						<td><?= $holding->f260a; ?></td>
					</tr>
					<tr>
					  <td>f260b</td>
						<td><?= $holding->f260b; ?></td>
					</tr>
					<tr>
					  <td>f710a</td>
						<td><?= $holding->f710a; ?></td>
					</tr>
					<tr>
					  <td>f780t</td>
						<td><?= $holding->f780t; ?></td>
					</tr>
					<tr>
					  <td>f362a</td>
						<td><?= $holding->f362a; ?></td>
					</tr>
					<tr>
					  <td>f866a</td>
						<td>						
						<div class="input-group">
				      <input type="text" value="<?= $holding->f866a; ?>" name="f866a" id="f866aeditable" class="form-control">
				      <a id="f866aeditablesave" class="btn btn-primary input-group-btn" href="{{ action('HoldingssetsController@putUpdateField866aHolding',[$holding->id]) }}" data-params="new866a={{ $holding->f866a }}" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-save"></i></a>
				    </div><!-- /input-group -->
						</td>
					</tr>
					<tr>
					  <td>Notes</td>
						<td>
						@if ($holding -> notes()-> exists())
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
							<strong>{{ trans('holdingssets.notes_made_by') }}: </strong>{{ $username }}
						@else
							{{ trans('holdingssets.no_notes') }}	
						@endif	
						</td>
					</tr>
					<tr>
					  <td>f866z</td>
						<td><?= $holding->f866z; ?></td>
					</tr>
					<tr>
					  <td>f310a</td>
						<td><?= $holding->f310a; ?></td>
					</tr>
					<tr>
					  <td>f852b</td>
						<td><?= $holding->f852b; ?></td>
					</tr>
					<tr>
					  <td>f852h</td>
						<td><?= $holding->f852h; ?></td>
					</tr>
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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


