<div class="modal fade" id="form-create-list">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?= trans('holdings.title_create_group')  ?></h4>
      </div>
      <form action="<?= route('lists.store') ?>" method="post" class="bulk_action">
	      <div class="modal-body">

				    <div class="form-group">
		          {{ Form::label('name', 'Name:') }}	
		          {{ Form::text('name','',['placeholder'=>'Type a brief description', 'class'=>"form-control"]) }}
				    </div>
				    @if (Auth::user()->hasRole('magvuser'))	
					    <div class="form-group">
			          {{ Form::label('worker_id', 'Worker:') }}
			          {{ Form::select('worker_id', User::whereLibraryId(Auth::user()->library_id)->orderby('username')->lists('username','user_id'),'',['class'=>"form-control"]  ) }}
					    </div>				
				    @endif

					@if ($errors->any())
						<ul>
							{{ implode('', $errors->all('<li class="error">:message</li>')) }}
						</ul>
					@endif

	      </div>
	      <div class="modal-footer">
	        <a href="#" class="btn btn-danger" data-dismiss="modal"><?= trans('general.close') ?></a>
	        <button class="btn btn-primary" type="submit" data-disable-with="<?= trans('general.disable_with')  ?>"><?= trans('general.save') ?></button>
	      </div>
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->