  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?= trans('holdings.title_move_to_list')  ?></h4>
      </div>
      <form id="form_list" action="<?= action('HlistsController@postAttach')?>" method="post" class="bulk_action" data-remote="true">
	      <div class="modal-body">
				    <div class="form-group">
				    	<label>{{trans('lists.all-lists')}}</label>
							<select id="hlist_id" name="hlist_id" class="form-control">
								@foreach ($lists as $list) 
									<option value="{{$list->id}}">{{$list->name}} ({{ trans('lists.type-'.$list->type) }})</option>
								@endforeach
								
							</select>
				    </div>

	      </div>
	      <div class="modal-footer">
	        <button id="submit_create_list" class="btn btn-success" type="submit" data-disable-with="<?= trans('general.disable_with')  ?>">
	        	<i class="fa fa-check"></i>
	        	<?= trans('general.save') ?>
	        </button>
	        <a href="#" class="btn btn-default" data-dismiss="modal">
	        	<i class="fa fa-times"></i>
	        	<?= trans('general.close') ?>
	        </a>
	      </div>
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
<!-- </div>/.modal -->




