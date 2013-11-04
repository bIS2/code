$(function(){

$('#hosg').dataTable({
		         "bFilter": true,
		         "bPaginate": false,
		         "bDestroy": true
		     });

var pagina

pagina = 1;

$(window).scroll(function() {
if ($(window).scrollTop() == $(document).height() - $(window).height()) {
	pagina++;
 	$.get("/holdingssets/?page="+pagina + "&group_id=" + $('#hosg').attr('group_id'),
  function(data){
  if (data != "") {
    $("#hosg > tbody > tr:last").after(data);
		$('#hosg').dataTable({
      "bFilter": true,
      "bPaginate": false,
      "bDestroy": true
    });
	}
});
 }	
});

})

