$(function(){

	$('body').on('keypress','form#create-note input[type="text"]',function(){ 
		$(this)
			.addClass('focused')
			.parent('div')
			.find(':checkbox').attr('checked','true').end()
			.find('label').addClass('active')
	})

})