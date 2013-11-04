$(function(){

$('#hosg').dataTable({
		         "bFilter": true,
		         "bPaginate": false,
		         "bDestroy": true
		     });

$('#holdings-items').dataTable({
		         "bFilter": true,
		         "bPaginate": false,
		         "bDestroy": true
		     });


	$(':checkbox#select-all').click(function(){
		
		$checkboxes = $(this).parents('table').find('tbody :checkbox')

		if (this.checked)
			$checkboxes.trigger('click')
		else
			$checkboxes.removeAttr('checked')

	})

	$('a.link_bulk_action').on('click', function(){
		$('tbody :checkbox:checked').clone().attr('type','hidden').appendTo('form.bulk_action')
	})

	$('a.link_bulk_action[data-remote]').on('click',function(){
		$(this).attr( 'data-params', $('tbody :checkbox:checked').serialize() )
	})

  $('a').on({
    'ajax:success': function(data, result, status){
        if ( result.remove )
        	$.each(result.remove, function(index,id){
        		$('#'+id).hide('slow', function(){ $(this).remove() });	
        	})

        if ( result.ok ){
        	ok = result.ok
        	$('#'+ok.id+' btn-info').addClass('btn-danger').removeClass('btn-info');	
        }
          
      }
    })
	

	$('#modal-show').on('show.bs.modal', function () {
	  // $(this).load($(this).options.remote)
	})	

	// $('.flexme').dataTable({
 //    "bFilter": false,
 //    "bPaginate": false
 //   });

	// $('.flexme').flexigrid();

var pagina

pagina = 1;

$(window).scroll(function(){
if ($(window).scrollTop() == $(document).height() - $(window).height()) {
	pagina++;
 	$.get("/?page="+pagina,
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

