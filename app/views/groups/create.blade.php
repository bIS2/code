<div class="modal fade" id="form-create-group">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?= trans('holdingssets.title_create_group')  ?></h4>
      </div>
      <form action="<?= route('groups.store') ?>" method="post" data-remote="true" class="bulk_action">
	      <div class="modal-body">

				    <div class="form-group">
		          {{ Form::label('name', 'Name:') }}
		          {{ Form::text('name','',['placeholder'=>'Type a brief description', 'class'=>"form-control"]) }}
				    </div>				

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