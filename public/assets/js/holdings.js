$(function(){

	$('form#create-tag input[type="text"]').on('focus', function(){ 
		$(this).parent('div').find(':checkbox').attr('checked',true) 
	})

	$('#form-create-tags').on('hidden.bs.modal', function () {
	    $(this).removeData('bs.modal');
	});

$('#form-create-tags').on('hidden', '.modal', function () {
  $(this).removeData('modal');
});	

})