<!-- Form create Comment to Holdings -->

<?php $url = ($comment->exists) ? route('comments.update',$comment->id) : route('comments.store')  ?>
<?php $method = ($comment->exists) ? 'PUT' : 'POST' ?>

<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">{{{ trans('comments.title-create') }}}</h4>
    </div>
    
		<!-- <form action="{{ route('comments.store') }}" method="post" data-remote="true" id='create-comment'> -->
		{{ Form::model($comment,[ 'url'=>$url,'method'=>$method, "data-remote"=>"true", "id"=>'create-comment' ]) }}

				{{ Form::hidden( 'holding_id' ) }}
				{{ Form::hidden( 'user_id' ) }}

				<div class="col-xs-12">
					<div class="form-group">
				    <label for="content">Comment</label>
				    {{ Form::textarea('content',null,['class'=>"form-control",'placeholder'=>trans('comments.placeholder'), 'rows'=>4 ]) }}
				  </div>
					@if ($errors->any())
						<ul>
							{{ implode('', $errors->all('<li class="error">:message</li>')) }}
						</ul>
					@endif
				</div>


	  <div class="modal-footer">
	    <button type="reset" class="btn btn-default{{ $consultnotes }}" ><?= trans('general.reset') ?></button>
	    <button type="submit" class="btn btn-success{{ $consultnotes }}" data-disabled-with="{{trans('general.disable_with')}}"><i class="fa fa-check"></i> <?= trans('general.save') ?></button>
	    <a href="#" class="btn btn-danger" data-dismiss="modal" ><i class="fa fa-times"></i> <?= trans('general.close') ?></a>
	  </div>

	{{ Form::close() }}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
