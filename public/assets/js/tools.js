$(function(){

  $.fn.editable.defaults.mode = 'popup';
  $.fn.editable.defaults.ajaxOptions = {type: "PUT"};
  $('.editable').editable();

	$('.datatable').dataTable({
    "bFilter": false,
    "bPaginate": false,

  });

	$(':checkbox#select-all').on('click',function(){
		$('.table').find('input.hl:checkbox').prop('checked',this.checked)
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
      $('#'+result.annotated).addClass('danger').removeClass('success'); 
      $('#form-create-notes').modal('hide')
    } 

    if ( result.correct ){
      $('#'+result.correct).removeClass('danger').addClass('success'); 
    } 
    if ( result.blank ){
      $('#'+result.blank).removeClass('danger').removeClass('success'); 
    } 

    if ( result.remove )
      $('#'+result.remove).hide('slow', function(){ $(this).remove() }); 
    

  })

  $('th').each(function(){

  	var a = {}, l = 0, i = $(this).index() + 1, $this=$(this);

  	tds = $(this).parents('table').find('tr td:nth-child('+i+')' );
  	tds.each(function(){

	   (!a[$(this).text()]) ? a[$(this).text()] = 1 : a[$(this).text()]++;

  	})

  	content = ''
  	$.each(a,function(key, value){
  		content += '<span class="label label-info">'+value+'</span> '+key+'</br>'
  	})
  	// alert(a.length)
    //if (a.length>0){
      	$(this).find("span.fa").popover({
      		trigger: 		'hover',
      		placement: 	'bottom',
      		html: 			true,
      		content: 		content
      	})
    //}
  })

getAsuccess()
 
})

function getAsuccess() {
  $('a').on({
    'ajax:success': function(data, result, status){
        if ( result.remove )
          $('#'+result.remove).hide('slow', function(){ $(this).remove() }); 

        // Set HOS to CONFIRM
        if ( result.ok ){
          $('#'+result.ok).find('.btn-ok').addClass('btn-success disabled').removeClass('btn-default');
          if (($('a#filter_pending').hasClass('btn-primary')) || ($('a#filter_annotated').hasClass('btn-primary'))) {
            $('li#'+result.ok).remove();  
          }  
        }

        // Set HOS to UNCONFIRM
        if ( result.ko ){
            $('#'+result.ko).find('.btn-ok').addClass('btn-default').removeClass('btn-success');
            if ($('a#filter_confirmed').hasClass('btn-primary'))
                $('li#'+result.ko).remove();
        }
        
        if ( result.removefromgroup ){
          $('li#'+result.removefromgroup).fadeOut('slow', function() {
            $('li#'+result.removefromgroup).remove();
          })
        }

        //
        if ( result.newhosok ){
            $('tr#holding' + result.newhosok).remove(); 
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
