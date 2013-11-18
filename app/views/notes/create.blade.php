

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">{{{ trans('notes.title-create') }}}</h4>
        </div>
        	<form action="{{ route('notes.store') }}" method="post" data-remote="true" id='create-note'>

		        <div class="modal-body">

        				<div class="well well-sm warning">{{trans('notes.help')}}</div>

        				<div class="row">
        					<div class="col-xs-5">
        						<dl>
										  <dt>852b</dt>
										  <dd>{{$holding->f852b}}</dd>
										  <dt>852h</dt>
										  <dd>{{$holding->f852h}}</dd>
										  <dt>245a</dt>
										  <dd>{{$holding->holdingsset->f245a}}</dd>
										  <dt>362a</dt>
										  <dd>{{$holding->f362a}}</dd>
										  <dt>866a</dt>
										  <dd>{{$holding->f866a}}</dd>
										</dl>
        					</div>
        					<div class="col-xs-7">

										@foreach ( Tag::all() as $tag)

											<?php $note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note ?>

											{{ Form::hidden('holding_id',$holding->id) }}
											<div class="form-group">
										    <div class="input-group" data-toggle="buttons">
										      <label class="input-group-addon btn btn-primary btn-sm {{ ($note->tag_id) ? 'active' : '' }}">
										      	<span class="glyphicon glyphicon-ok-sign"></span>
										        <input type="checkbox" name="notes[{{ $tag->id }}][tag_id]" value="{{ $tag->id }}">{{ $tag->name }}
										      </label>
										      <input type="text"  name="notes[{{ $tag->id }}][content]" value="{{ $note->content }}" class="form-control input-sm" placeholder="{{ trans('placeholders.notes_'.$tag->name) }}">
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

	        </div>

	        <div class="modal-footer">
	          <a href="#" class="btn btn-default" data-dismiss="modal" ><?= trans('general.cancel') ?></a>
	          <button type="submit" class="btn btn-default" ><?= trans('general.reset') ?></button>
	          <button type="submit" class="btn btn-success" ><?= trans('general.save') ?></button>
	          <button type="submit" class="btn btn-success" ><?= trans('general.save_and_delivery') ?></button>
	        </div>

				</form>

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
