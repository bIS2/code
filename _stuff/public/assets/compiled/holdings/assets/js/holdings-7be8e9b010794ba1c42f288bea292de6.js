$(function() {

	$('#holdings-items').dataTable({
		         "bFilter": true,
		         "bPaginate": false,
		         "bDestroy": true
		     });

	$('.flexme').dataTable({
    "bFilter": false,
    "bPaginate": false
   });

	// $('#holdings-items').flexigrid();
})