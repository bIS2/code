<?php 
$options = '<option></option>';
$magusers = Role::whereName('maguser')
				->first()
				->users()
				->whereLibraryId( Auth::user()->library_id )
				->select('username','users.id')
				->lists('username','id'); 
foreach ($magusers as $key=>$value) {
	$options .= '<option value="'.$key.'" data-role="maguser">'.$value.'</option>';
}
$postusers = Role::whereName('postuser')
				->first()
				->users()
				->whereLibraryId( Auth::user()->library_id )
				->select('username','users.id')
				->lists('username','id'); 

foreach ($postusers as $key=>$value) {
	$options .= '<option value="'.$key.'" data-role="postuser">'.$value.'</option>';
}


$types = [ 
	'control'=>	'<i class="fa fa-tachometer"></i> '.trans('lists.type-control'), 
	'delivery'=>'<i class="fa fa-truck"></i> '.trans('lists.type-delivery'),  
	'unsolve'=>	'<i class="fa fa-fire"></i> '.trans('lists.type-unsolve'), 
	'elimination'=>'<i class="fa fa-trash-o"></i> '.trans('lists.type-elimination') ,
];

?>

<!-- <div class="modal fade" id="form-create-list"> -->
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title"><?= trans('holdings.title_create_group')  ?></h4>
      </div>
      <form id="form_list" action="<?= route('lists.store') ?>" method="post" class="bulk_action" data-remote="true">
	      <div class="modal-body">

			    <div class="form-group">
		          {{ Form::label('name', trans('lists.name')) }}	
		          {{ Form::text('name','',['placeholder'=>'Type a brief description', 'class'=>"form-control"]) }}
			    </div>
				    
			    @if (Auth::user()->hasRole('magvuser'))	

				    <div class="form-group">
				    	 {{ Form::label('type', trans('lists.name')) }}
				    	<div>
					    	@foreach ($types as $key => $value)
								<label class="checkbox-inline">
								  <input type="radio" value="{{$key}}" name="type" {{ ($key=='control') ? 'checked' : '' }}> {{ $value }}
								</label>				    	
					    	@endforeach
				    	</div>
				    </div>				
				    
				    <div class="form-group">
							{{ Form::label('worker_id', trans('lists.worker')) }}
							<select id="worker_id" name="worker_id" class="form-control">
								{{ $options }}
							</select>
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
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
<!-- </div>/.modal -->

<script type="text/javascript">

$(function(){

	typeList()

	$('#form_list radio').on('click', function(){
		alert('hola')
		$('#form_list select#worker_id').val([])
		typeList()	
	})

	function typeList(){

		$select = $('#form_list select#worker_id')

	  $('#form_list select#worker_id option').hide()

	  if ($('#form_list :radio:checked').val()=='delivery'){
	    $('#form_list select#worker_id option[data-role=postuser]').show()
	  } else {
	    $('#form_list select#worker_id option[data-role=maguser]').show()
	  }

	  // alert($select.find('option:visible:first').attr('value'))
	  $select.val( $select.find('option:visible:first').attr('value') )
	}	

})

</script>

