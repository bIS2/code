$(function(){

// $('#hosg').dataTable({
//   "bFilter": true,
//   "bPaginate": false,
//   "bDestroy": true
// });



$('.pop-over').popover()
	setDatatable();
})

var page
var last_result = '-1'
page = 1;

	$(window).scroll(function() {
		if (last_result != '') {
			if ($(window).scrollTop() == $(document).height() - $(window).height()) {
				page++;
				url = "/sets?"+ window.location.search.substring(1) + '&page=' + page
				
			 	$.get(url,
				  function(data){
			  		last_result = data
				    console.log(last_result);
					  if (data != "") {
					    $("#hosg ul li:last").after(data);
			 				$('.pop-over').popover()
			 				getAsuccess()
			 				setDatatable()
						}
				});
		 	}
	 	}
	});

function setDatatable() {
	$('#hosg .accordion-toggle').each(function() {
		$(this).on('click', function() {
			if ($(this).attr('opened') == 0) {
				$($(this).attr('href') + ' .flexme').dataTable({
				    "bFilter": false,
				    "bPaginate": false,
						//"sScrollX": "100%",
						//"sScrollXInner": "110%",
						//"bScrollCollapse": true    
				  });
				getAsuccess();
				$($(this).attr('href') + ' table').addClass('table');
	  			$($(this).attr('href') + ' .flexme span').each(function() {
	  			})
				$(this).attr('opened', 1);
			}
		})
  })
}