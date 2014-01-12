<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">{{{ trans('comments.title-create') }}}</h4>
    </div>
    
		<form action="{{ route('comments.store') }}" method="post" data-remote="true" id='create-comment'>

				{{ Form::hidden( 'holding_id', $holding->id ) }}
				{{ Form::hidden( 'user_id', Auth::user()->id ) }}

				<div class="col-xs-12">
					<div class="form-group">
				    <label for="exampleInputEmail1">Comment</label>
				    <textarea  class="form-control" id="exampleInputEmail1" placeholder="Enter email" name="content" placeholder="{{ trans('comments.placeholder') }}"></textarea>
				  </div>
					@if ($errors->any())
						<ul>
							{{ implode('', $errors->all('<li class="error">:message</li>')) }}
						</ul>
					@endif
				</div>


	  <div class="modal-footer">
	    <button type="reset" class="btn btn-default{{ $consultnotes }}" ><?= trans('general.reset') ?></button>
	    <button type="submit" class="btn btn-success{{ $consultnotes }}" ><i class="fa fa-check"></i> <?= trans('general.save') ?></button>
	    <a href="#" class="btn btn-danger" data-dismiss="modal" ><i class="fa fa-times"></i> <?= trans('general.close') ?></a>
	  </div>

	{{ Form::close() }}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
