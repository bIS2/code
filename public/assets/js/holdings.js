$(function(){

	$('body').on('keypress','form#create-note input[type="text"]',function(){ 
		$(this)
			.addClass('focused')
			.parent('div')
			.find(':checkbox').attr('checked','true').end()
			.find('label').addClass('active')
	})
	var aoColumns = []
  ths = $('#holdings-items th');
  for (var i = 0; i < $(ths).length; i++) {
  	(($(ths[i]).hasClass('hocrr_ptrn')) || ($(ths[i]).hasClass('actions')) || ($(ths[i]).hasClass('size'))) ? aoColumns.push({ "asSorting": [ "" ] }) : aoColumns.push(null) 
  }
	$('#holdings-items').dataTable({
    "bFilter": false,
    "bPaginate": false,  
    "bLengthChange": true,
    "bInfo": true,
    "bAutoWidth": true,
    // "aoColumns": aoColumns,
    // "aoColumnDefs": [	{ "sWidth": "3px", "aTargets": [ 0,1 ] },{ "sWidth": "10px", "aTargets": [ 2 ] } ]
  });
  tds = $('#holdings-items tbody > tr:first-child td');
  ths = $('#holdings-items th');
  for (var i = 0; i < $(ths).length; i++) {
  	$(ths[i]).css('min-width', parseInt($(ths[i]).width()) + 25);
  	$(ths[i]).css('background', '#cccccc');
  	$(tds[i]).css('min-width', parseInt($(ths[i]).width()) + 25);
  }
  tds = $('#holdings-items tbody > tr td:first-child').css('min-width', '25px').css('max-width', '25px').css('padding-right', '5px');
  tds = $('#holdings-items thead > tr th:first-child').css('min-width', '25px').css('max-width', '25px').css('padding-right', '5px');
  $('#new-table').append($('#holdings-items thead').clone());
  $('#holdings-items_wrapper').prepend($('#new-table'));
  console.log($('#new-table').attr('id'));
  if ($('#new-table').attr('id') != undefined) yy = setInterval(moveTogether, 10);
  $('pop-over').popover();
})

function moveTogether() {
	$('#new-table').offset({ left: $('#holdings-items').offset().left + 2});
	if ($('#holdings-items_wrapper').offset().top - $('#holdings-items').offset().top > 13) { $('#new-table').css('display', 'block') } else { $('#new-table').css('display', 'none') }
}