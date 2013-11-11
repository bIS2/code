$(function(){

	$('body').on('keypress','form#create-tag input[type="text"]',function(){ 
		$(this).parent('div')
			.find(':checkbox').attr('checked','true').end()
			.find('label').addClass('active')
	})

	$('body').on('hidden.bs.modal', '.modal', function () {
		$(this).removeData('modal');
	});	

})