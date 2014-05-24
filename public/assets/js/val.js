$(function(){

	$('form.validate').validate({
		rules: {
			username: {
				required: true,
				minlength: 2
			},
			password: {
				required: true,
				minlength: 5
			},
			confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			},
			"roles[]": {
				required: true,
				minlength: 1
			},
		}		
	})

	$('form#edit_user').validate({
		rules: {
			username: {
				required: true,
				minlength: 2
			},
			password: {
				minlength: 5
			},
			password_confirmation: {
				minlength: 5,
				equalTo: "#password"
			},
			email: {
				required: true,
				email: true
			}
		}
	})	

})