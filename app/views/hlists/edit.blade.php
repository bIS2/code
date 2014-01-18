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

?>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?= trans('holdings.title_create_group')  ?></h4>
      </div>
      {{ Form::model($list,['url'=>route('lists.update',$list->id),'method'=>'put']) }}
	      <div class="modal-body">

				    <div class="form-group">
		          {{ Form::label('name', 'Name:') }}	
		          {{ Form::text('name',null,['placeholder'=>'Type a brief description', 'class'=>"form-control"]) }}
				    </div>
				    @if (Auth::user()->hasRole('magvuser'))	
					    <div class="form-group">
			          {{ Form::label('worker_id', 'Worker:') }}
			          {{ Form::select('worker_id',$users,null,['class'=>"form-control"] ) }}
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

			{{ Form::close() }}

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
