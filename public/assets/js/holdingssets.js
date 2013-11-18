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

	$('.pop-over').popover()

	$('#hosg .accordion-toggle').each(function() {
  	$(this).on('click', function() {
  		if ($(this).attr('opened') == 0) {
  			// $($(this).attr('href') + ' .flexme').flexigrid();
  			getAsuccess();
  			$($(this).attr('href') + ' table').addClass('table');
	  			$($(this).attr('href') + ' .flexme span').each(function() {
	  			})
  			$(this).attr('opened', 1);
  		}
  	})
  })
})

var page
page = 1;
	$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		page++;
		if ($('#hosg').attr('group_id') > 0) url = "/sets?page="+page + "&group_id=" + $('#hosg').attr('group_id')
			else
				url = "/sets?page="+page;

	 	$.get(url,
		  function(data){
			  if (data != "") {
			    $("#hosg ul li:last").after(data);
					// $('#hosg').dataTable({
			  //     "bFilter": true,
			  //     "bPaginate": false,
			  //     "bDestroy": true
			  //   });
			    $('#hosg .accordion-toggle').each(function() {
			    	$(this).on('click', function() {
			    		if ($(this).attr('opened') == 0) {
			    			// $($(this).attr('href') + ' .flexme').flexigrid();
			    			$($(this).attr('href') + ' table').addClass('table');
			    			$(this).attr('opened', 1);
			    			$($(this).attr('href') + ' .flexme span').each(function() {
			    				$('.popover').each().css('display', 'none')
			    			})
			    		}
			    	})
			    })
				}
			});
	 	}	
	});
