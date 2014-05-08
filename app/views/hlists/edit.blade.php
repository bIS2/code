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
					    	 {{ Form::label('type', trans('lists.name')) }}
					    	<div>
						    	@foreach ($types as $key => $value)
									<label class="checkbox-inline">
									  <input type="radio" value="{{$key}}" name="type" {{ ($list->type==$key) ? 'checked' : '' }} disabled> {{ $value }}
									</label>				    	
						    	@endforeach
					    	</div>
					    </div>		

					    <div class="form-group">
			          {{ Form::label('worker_id', 'Worker:') }}
			          <div class="row">
				          <div class="col-sm-4">
				          	{{ Form::select('worker_id',$users,null,['class'=>"form-control ", 'disabled'=>'disabled'] ) }}
				          </div>
			          </div>
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
	        <a href="#" class="btn btn-default" data-dismiss="modal">
	        	<i class="fa fa-times"></i>
	        	<?= trans('general.close') ?>
	        </a>
	      </div>

			{{ Form::close() }}

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
