

    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">{{{ trans('tags.title-create') }}}</h4>
        </div>
		{{ Form::open(array( 'route' => ['tags.store']),[],['data-remote'=>'true', 'id'=>'create-tag']) }}

	        <div class="modal-body">

					{{ Form::hidden('holding_id',$holding->id) }}

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
	          <button type="submit" class="btn btn-warning" ><?= trans('general.reset') ?></button>
	          <button type="submit" class="btn btn-success" ><?= trans('general.save') ?></button>
	        </div>

		{{ Form::close() }}

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
