$(function(){

	$(':checkbox#select-all').click(function(){
		
		$checkboxes = $('.table').find('input.hl:checkbox')
		if (this.checked)
			$checkboxes.attr('checked',true)
		else
			$checkboxes.removeAttr('checked')
	})

	$('#filter-btn').click(function(){
		$('#filter-well').toggle('slow')
		$(this).toggleClass('btn-primary','btn-default') 	
	})

	$(':checkbox:checked.sel').parents('tr').addClass("warning")

	$(':checkbox.sel').click( function(){
		if (this.checked) {
			$('a.link_bulk_action').removeClass('disabled')
			$(this).parents('tr').addClass("warning")
		}	else {
			$(this).parents('tr').removeClass("warning")
			if ( $(':checkbox:checked.sel').length==0)
				$('a.link_bulk_action').addClass('disabled')
		}
	})

	$('a.link_bulk_action').on('click', function(){
		$('.table input.hl:checkbox:checked').clone().attr('type','hidden').appendTo('form.bulk_action')
	})

	$('a.link_bulk_action[data-remote]').on('click',function(){
		$(this).attr( 'data-params', $('.table input.hl:checkbox:checked').serialize() )
	})

    $('.remote').on('hidden.bs.modal', '.modal', function()    {
        $(this).removeData('bs.modal').empty();

    }); 



  $('body').on( 'ajax:success', 'form,a', function(data, result, status){
    /* Holdings Tags */
    if ( result.tag ){
        $('tr#'+result.tag).find('.btn-tag').removeClass('btn-default').addClass('btn-danger');
        $('#form-create-tags').modal('hide')
    }
    if ( result.untag ){
        $('#'+result.untag).find('.btn-tag').removeClass('btn-danger').addClass('btn-default'); 
    } 
    if ( result.annotated ){
        $('#'+result.annotated).find('.well').addClass('well-danger').removeClass('well-success'); 
        $('tr#'+result.annotated).addClass('danger').removeClass('success'); 
        $('#form-create-notes').modal('hide')
    } 

    if ( result.correct ){
        $('#'+result.correct).find('.well').removeClass('well-danger').addClass('well-success'); 
        $('tr#'+result.correct).removeClass('danger').addClass('success'); 
    } 
    if ( result.blank ){
        $('#'+result.blank).find('.well').removeClass('well-danger').addClass('well-success'); 
        $('tr#'+result.blank).removeClass('danger').removeClass('success'); 
    } 
    
    })

  $('.h').on({
  	mouseover: function(){ $(this).find('.actions').show() },
  	mouseout: function(){ $(this).find('.actions').hide() }
  })

getAsuccess()
 
})

function getAsuccess() {
  $('a').on({
    'ajax:success': function(data, result, status){
        if ( result.remove )
            $.each(result.remove, function(index,id){
                $('#'+id).hide('slow', function(){ $(this).remove() }); 
            })
        // console.log(result);
        /* HOS ok to next step */
        if ( result.ok ){
            $('#'+result.ok).find('.btn-ok').addClass('btn-success').removeClass('btn-default');    
        }
        if ( result.ko ){
            $('#'+result.ko).find('.btn-ok').addClass('btn-default').removeClass('btn-success');    
        }

        /* Holdings locks */
        if ( result.lock ){
            $('#holding'+result.lock).addClass('locked').find('.btn-lock').addClass('btn-warning');    
        }
        if ( result.unlock ){
            $('#holding'+result.unlock).removeClass('locked').find('.btn-lock').removeClass('btn-warning');    
        }

        /* Holdings Tags */
        if ( result.tag ){
            $('tr#'+result.tag).find('.btn-tag').removeClass('btn-default').addClass('btn-danger');
            $('#form-create-tags').modal('hide')
        }
        if ( result.untag ){
            $('#'+result.untag).find('.btn-tag').removeClass('btn-danger').addClass('btn-default'); 
        } 
        /* Deleted Group */
        if ( result.groupDelete ){
            $('li#group'+result.groupDelete).remove(); 
        } 
      }
    })
	
}
