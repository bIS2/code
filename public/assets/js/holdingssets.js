$(function(){

$('#hosg').dataTable({
  "bFilter": true,
  "bPaginate": false,
  "bDestroy": true
});

var page
page = 1;
	$(window).scroll(function() {
	if ($(window).scrollTop() == $(document).height() - $(window).height()) {
		page++;
	 	$.get("/holdingssets/?page="+page + "&group_id=" + $('#hosg').attr('group_id'),
		  function(data){
			  if (data != "") {
			    $("#hosg > tbody > tr:last").after(data);
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
			    		}
			    	})
			    })
				}
			});
	 	}	
	});

	$('#hosg .accordion-toggle').each(function() {
  	$(this).on('click', function() {
  		console.log('click');
  		if ($(this).attr('opened') == 0) {
  			$($(this).attr('href') + ' .flexme').flexigrid();
  			$($(this).attr('href') + ' table').addClass('table');
  			$(this).attr('opened', 1);
  		}
  	})
  })
})