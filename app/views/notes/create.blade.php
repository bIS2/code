<?php $consultnotes = (Input::get('consult') == 1) ? ' disabled' : ''; ?>

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">{{{ trans('notes.title-create') }}}</h4>
        </div>
        	<form action="{{ route('notes.store') }}" method="post" data-remote="true" id='create-note'>

		        <div class="modal-body">

        				<div class="row">
        					<div class="col-xs-5 ">
        						<dl>
										  <dt>hbib</dt>
										  <dd>{{htmlspecialchars($holding->library->code,ENT_QUOTES)}}</dd>
										  <dt>852b</dt>
										  <dd>{{htmlspecialchars($holding->f852b,ENT_QUOTES)}}</dd>
										  <dt>852h</dt>
										  <dd>{{htmlspecialchars($holding->f852h,ENT_QUOTES)}}</dd>
										  <dt>Patr</dt>
										  <?php $ownertrclass 	= ($holding->is_owner == 't') ? ' is_owner' : '';  ?>
										  <dd class="ocrr_ptrn{{$ownertrclass}}">{{$holding->patrn}}</dd>
										  <dt>245a</dt>
										  <dd>{{htmlspecialchars($holding->f245a,ENT_QUOTES)}}</dd>
										  <dt>362a</dt>
										  <dd>{{htmlspecialchars($holding->f362a,ENT_QUOTES)}}</dd>
										  <dt>866a</dt>
										  <dd>{{htmlspecialchars($holding->f866a,ENT_QUOTES)}}</dd>
										</dl>
        					</div>
        					<div class="col-xs-7">

										@foreach ( Tag::all() as $tag)

											<?php
												$note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note;
												if ($username == '') $username = $note->user->name;
												// var_dump($note->user->name);
											?>

											{{ Form::hidden('holding_id',$holding->id) }}
											<div class="form-group">
										    <div class="input-group" data-toggle="buttons">
										      <label class="input-group-addon btn btn-primary btn-sm {{ ($note->tag_id) ? 'active' : '' }}{{ $consultnotes }}">
										      	<span class="glyphicon glyphicon-ok-sign"></span>
										        <input type="checkbox" name="notes[{{ $tag->id }}][tag_id]" value="{{ $tag->id }}">{{ $tag->name }}
										      </label>
										      <input type="text"  name="notes[{{ $tag->id }}][content]" value="{{ $note->content }}" class="form-control input-sm"{{ $consultnotes }} placeholder="{{ trans('placeholders.notes_'.$tag->name) }}">
										    </div><!-- /input-group -->
										  </div><!-- /input-group -->

										@endforeach

										@if ($errors->any())
											<ul>
												{{ implode('', $errors->all('<li class="error">:message</li>')) }}
											</ul>
										@endif
        						
        					</div>

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
	        </div>

	        <div class="modal-footer">
	          <button type="reset" class="btn btn-default{{ $consultnotes }}" ><?= trans('general.reset') ?></button>
	          <button type="submit" class="btn btn-success{{ $consultnotes }}" ><?= trans('general.save') ?></button>
	          <a href="#" class="btn btn-danger" data-dismiss="modal" ><?= trans('general.close') ?></a>
	        </div>

				</form>

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
