$(function(){

	$(':checkbox#select-all').click(function(){
		
		$checkboxes = $('.table').find(':checkbox')
		if (this.checked)
			$checkboxes.trigger('click')
		else
			$checkboxes.removeAttr('checked')
	})

	$('a.link_bulk_action').on('click', function(){
		$('.table :checkbox:checked').clone().attr('type','hidden').appendTo('form.bulk_action')
	})

	$('a.link_bulk_action[data-remote]').on('click',function(){
		$(this).attr( 'data-params', $('.table :checkbox:checked').serialize() )
	})


  $('a').on({
    'ajax:success': function(data, result, status){
        if ( result.remove )
        	$.each(result.remove, function(index,id){
        		$('#'+id).hide('slow', function(){ $(this).remove() });	
        	})

        if ( result.ok ){
        	$('#'+result.ok).find('.btn-ok').addClass('btn-success').removeClass('btn-default');	
        }
        if ( result.ko ){
        	$('#'+result.ko).find('.btn-ok').addClass('btn-default').removeClass('btn-success');	
        }

        if ( result.tag ){
        	$('#'+result.tag).find('.btn-tag').addClass('btn-default').removeClass('btn-danger');	
        }
        if ( result.untag ){
        	$('#'+result.untag).find('.btn-tag').addClass('btn-danger').removeClass('btn-default');	
        }
          
      }
    })
	
	
	$('#modal-show').on('show.bs.modal', function () {
	  // $(this).load($(this).options.remote)
	})
})

