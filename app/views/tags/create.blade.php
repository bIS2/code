 <div class="modal fade" id="form-create-tags">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">{{{ trans('tags.title-create') }}}</h4>
        </div>
        <div class="modal-body">

			{{ Form::open(array( 'action' => ['HoldingsController@postTagged',$holding->id]),['data-remote'=>'true']) }}

				@foreach (Tag::all() as $tag)

			    <div class="input-group" data-toggle="buttons">
			      <label class="input-group-addon btn btn-primary">
			      	<span class="glyphicon glyphicon-ok-sign"></span>
			        <input type="checkbox" name="tags[{{ $tag->id }}][tag_id]" value="{{ $tag->id }} ">{{ $tag->name }}
			      </label>
			      <input type="text" class="form-control" name="tags[{{ $tag->id }}][content]" >
			    </div><!-- /input-group -->

				@endforeach

			@if ($errors->any())
				<ul>
					{{ implode('', $errors->all('<li class="error">:message</li>')) }}
				</ul>
			@endif

        </div>
        <div class="modal-footer">
          <a href="#" class="btn btn-default" data-dismiss="modal" ><?= trans('general.close') ?></a>
          <button type="submit" class="btn btn-primary" ><?= trans('general.save') ?></button>
        </div>
				{{ Form::close() }}
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->