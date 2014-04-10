<div class="modal fade" id="form-bulkdelete-group">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?= trans('groups.delete_selected_groups')  ?></h4>

      </div>
      <form action="<?= route('groups.store') ?>" method="post" class="bulk_action">
	      <div class="modal-body text-center">
	      	{{ Form::hidden('user_id', Auth::user()->id)}}
          {{ Form::hidden('deleting', '1')}}
          {{ Form::hidden('name', 'nonamed')}}
	        <button class="btn btn-primary" type="submit" data-disable-with="<?= trans('general.disable_with_del')  ?>"><?= trans('groups.delete_groups') ?></button>
	        <a href="#" class="btn btn-danger" data-dismiss="modal"><?= trans('general.cancel') ?></a>
	      </div>
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->