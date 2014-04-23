<div class="modal fade" id="form-join-group">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?= trans('groups.new_group_from_joined_groups')  ?></h4>
      </div>
      <form action="<?= route('groups.store') ?>" method="post" class="bulk_action">
	      <div class="modal-body">
					<div class="input-group text-center col-xs-12">
						<div class="form-group">
			        <div class="input-group">
			            {{ Form::label('name', trans('groups.name'), array('class' => "input-group-addon")) }}
			            {{ Form::text('name', '', array('placeholder'=>trans('groups.type_a_group_name'), 'class' => "form-control"))}}
			            {{ Form::hidden('user_id', Auth::user()->id)}}
			            {{ Form::hidden('joining', '1')}}
			        </div>
			      </div>		
			    </div>	
	      </div>
	      <div class="modal-footer">
	        <button class="btn btn-primary" type="submit" data-disable-with="<?= trans('general.disable_with')  ?>"><?= trans('general.save') ?></button>
	        <a href="#" class="btn btn-danger" data-dismiss="modal"><?= trans('general.close') ?></a>
	      </div>
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->