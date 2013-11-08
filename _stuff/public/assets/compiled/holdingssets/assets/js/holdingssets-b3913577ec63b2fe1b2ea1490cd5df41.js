$(function(){

// $('#hosg').dataTable({
//   "bFilter": true,
//   "bPaginate": false,
//   "bDestroy": true
// });

	$('.flexme').dataTable({
    "bFilter": false,
    "bPaginate": false
  });

var page
page = 1;
	$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		page++;
	 	$.get("/holdingssets/?page="+page + "&group_id=" + $('#hosg').attr('group_id'),
		  function(data){
			  if (data != "") {
			    $("#hosg ul li:last").after(data);
					$('#hosg').dataTable({
			      "bFilter": true,
			      "bPaginate": false,
			      "bDestroy": true
			    });
			    $('#hosg .accordion-toggle').each(function() {
			    	$(this).on('click', function() {
			    		if ($(this).attr('opened') == 0) {
			    			$($(this).attr('href') + ' .flexme').flexigrid();
			    			$($(this).attr('href') + ' table').addClass('table');
			    			$(this).attr('opened', 1);
			    			$($(this).attr('href') + ' .flexme span.btn').each(function() {
									$(this).popover()
									$(this).on('click', function() {
										return false;
									})
			    			})
			    		}
			    	})
			    })
				}
			});
	 	}	
	});

	$('#hosg .accordion-toggle').each(function() {
  	$(this).on('click', function() {
  		if ($(this).attr('opened') == 0) {
  			$($(this).attr('href') + ' .flexme').flexigrid();
  			$($(this).attr('href') + ' table').addClass('table');
		    $($(this).attr('href') + ' .flexme span.btn').each(function() {
				$(this).popover()
					$(this).on('click', function() {
						return false;
					})
				})
  			$(this).attr('opened', 1);
  		}
  	})
  })
})