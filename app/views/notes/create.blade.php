

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">{{{ trans('notes.title-create') }}}</h4>
        </div>
        	<form action="{{ route('notes.store') }}" method="post" data-remote="true" id='create-note'>

		        <div class="modal-body">

        				<div class="well well-sm warning">{{trans('notes.help')}}</div>

								{{ Form::hidden('holding_id',$holding->id) }}

								
								<?php $notes_ids=[]; ?>

								@foreach ( Tag::all() as $tag)
									<?php $note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note ?>

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

	        <div class="modal-footer">
	          <a href="#" class="btn btn-default" data-dismiss="modal" ><?= trans('general.cancel') ?></a>
	          <button type="submit" class="btn btn-default" ><?= trans('general.reset') ?></button>
	          <button type="submit" class="btn btn-success" ><?= trans('general.save') ?></button>
	          <button type="submit" class="btn btn-success" ><?= trans('general.save_and_delivery') ?></button>
	        </div>

				</form>

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
