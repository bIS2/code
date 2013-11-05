$(function(){

	$(':checkbox#select-all').click(function(){
		
		$checkboxes = $('table').find('tbody :checkbox')

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
        	$('#'+result.ok).find('.btn-default').addClass('btn-success').removeClass('btn-default');	
        }
        if ( result.ko ){
        	$('#'+result.ko).find('.btn-success').addClass('btn-default').removeClass('btn-success');	
        }
          
      }
    })
	
	$('#modal-show').on('show.bs.modal', function () {
	  // $(this).load($(this).options.remote)
	})
})

