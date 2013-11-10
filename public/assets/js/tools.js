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


	$('#modal-show').on('show.bs.modal', function () {
	  // $(this).load($(this).options.remote)
	})

  getAsuccess();
})

function getAsuccess() {
  $('a').on({
    'ajax:success': function(data, result, status){
        if ( result.remove )
            $.each(result.remove, function(index,id){
                $('#'+id).hide('slow', function(){ $(this).remove() }); 
            })
        console.log(result);
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
            $('#'+result.tag).find('.btn-tag').addClass('btn-default').removeClass('btn-danger');   
        }
        if ( result.untag ){
            $('#'+result.untag).find('.btn-tag').addClass('btn-danger').removeClass('btn-default'); 
        } 
        /* Deleted Group */
        if ( result.groupDelete ){
            console.log('aquiii');
            $('li#group'+result.groupDelete).remove(); 
        } 
      }
    })
}
