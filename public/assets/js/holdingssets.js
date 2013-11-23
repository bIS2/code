$(function(){

// $('#hosg').dataTable({
//   "bFilter": true,
//   "bPaginate": false,
//   "bDestroy": true
// });

	// $('.flexme, .datatable').dataTable({
 //    "bFilter": false,
 //    "bPaginate": false,
	// 	//"sScrollX": "100%",
	// 	//"sScrollXInner": "110%",
	// 	//"bScrollCollapse": true    
 //  });

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
				if ($('#hosg').attr('group_id') > 0) 
					url = "/sets?page="+page + "&group_id=" + $('#hosg').attr('group_id')
				else
					url = "/sets?page="+page;

				if ($('#main-filters a#filter_pending').hasClass('btn-primary'))
					url = url + '&state=pending';
				if ($('#main-filters a#filter_confirmed').hasClass('btn-primary'))
					url = url + '&state=ok';
				
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