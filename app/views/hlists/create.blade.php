<?php 

$maguser = Role::whereName('maguser')
				->first()
				->users()
				->whereLibraryId( Auth::user()->library_id )
				->select('username','users.id')
				->lists('username','id'); 

$postuser = Role::whereName('postuser')
				->first()
				->users()
				->whereLibraryId( Auth::user()->library_id )
				->select('username','users.id')
				->lists('username','id'); 

$users = $maguser+$postuser;
$types = [ 'control'=>trans('lists.type-control'), 'unsolve'=>trans('lists.type-unsolve'), 'delivery'=>trans('lists.type-delivery')  ]
?>

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
		          {{ Form::label('name', trans('lists.name')) }}	
		          {{ Form::text('name','',['placeholder'=>'Type a brief description', 'class'=>"form-control"]) }}
				    </div>
				    
				    @if (Auth::user()->hasRole('magvuser'))	
					    <div class="form-group">
			          {{ Form::label('worker_id', trans('lists.worker')) }}
			          {{ Form::select('worker_id',$users,'',['class'=>"form-control"] ) }}
					    </div>				
					    <div class="form-group">
			          {{ Form::label('type', trans('lists.type')) }}
			          {{ Form::select('type',$types,'',['class'=>"form-control"] ) }}
					    </div>				
				    @endif

					@if ($errors->any())
						<ul>
							{{ implode('', $errors->all('<li class="error">:message</li>')) }}
						</ul>
					@endif
	      </div>
	      <div class="modal-footer">
	        <button class="btn btn-success" type="submit" data-disable-with="<?= trans('general.disable_with')  ?>">
	        	<i class="fa fa-check"></i>
	        	<?= trans('general.save') ?>
	        </button>
	        <a href="#" class="btn btn-danger" data-dismiss="modal">
	        	<i class="fa fa-times"></i>
	        	<?= trans('general.close') ?>
	        </a>
	      </div>
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->