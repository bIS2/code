// Contains all the logic on the client

$(function(){


	$('input#size').on('change',function(){
		console.log($(this).serialize())
		data = $('a.btn-ok').data('params')
		$('a.btn-ok').attr('data-params', data + '&' + $(this).serialize() )
	})

  //Cuando se cambio el tipo de lista que se quiere crear se actualiza los usuarios a los que se les puede asignar ese tipo de lista
  $('body').on( 'click', '#form_list :radio', function(){
    $('#form_list select#worker_id').val([])
    typeList()
  })

  //manipulates the elements marked with the css class .draggable
  $( ".draggable" ).draggable({   
    handle: ".move",
    appendTo: 'body',
    zIndex: 100,
    helper: 'clone',
    revert: "invalid"
  });   

  //manipulates the elements <LI> marked with the css class .draggable
  $( "li.droppable" ).droppable({   
    accept: "tr.draggable",
    tolerance: "pointer", 
    hoverClass: "activedrop",
    drop: function(event, ui){
      $to= $(this)
      $from = $(ui.draggable)
      // alert($from.attr('id'))
      var call = $.post( $to.data('attach-url'), { holding_id: $from.attr('id') } )
      call.done(function(result) { 
      	if ( result.error ) {
      		alert( result.error ) 
      	} else{
      		$('.counter').text( result.counter )
      		alert( 'OK'  )
      	}
      })

    }

  });  

  // Manipulate tooltip. Use the bootstrap plugin
  $('[data-toggle=tooltip]').tooltip()

  $('.stats .label-default').hover(
    function(){
      $(this).removeClass('label-default')
      $(this).addClass('label-primary')
    },
    function(){
      $(this).removeClass('label-primary')
      $(this).addClass('label-default')
    }
  )


  $(document).on('keypress', function(event) {
    if (event.keyCode == 27) $('.modal .close').click();
  }) 

  // Show form to create feedback
  $('#btn_create_feedback').popover({
  	html: true,
  	content: function(){ return $('#wrap_create_feedback').html() },
  	placement: 'top',
  	container: 'body'

  })

  $('[data-toggle=popover]').popover()

  //Click in button with class .close-popover close de form to create feedback
	$('body').on( 'click', '.close-popover', function(e){
		e.preventDefault()
  	$('#btn_create_feedback').popover('hide')
  })

  // $.fn.editable.defaults.inputclass = 'input-';
  $('.editable').editable({
  	mode: 'inline',
  	ajaxOptions: {type: 'PUT'},
  });

  doEditable();
	
  $('.datatable').dataTable({
    "bFilter": false,
    "bPaginate": false , 
  });

  bulkActions();

  $('body').on('click', '#select-all',function() {
    $($(this).data('target')).find('input.hl:checkbox').prop('checked',this.checked)
    $('div.select-all p').toggleClass('active')
    if (this.checked) {
      $(':checkbox.sel').parents('tr').addClass("warning")
      $(':checkbox.sel').parents('li').addClass("warning")
      $('a.link_bulk_action').removeClass('disabled')
    }
    else {
      $(':checkbox.sel').parents('tr').removeClass("warning")
      $(':checkbox.sel').parents('li').removeClass("warning")
      $('a.link_bulk_action').addClass('disabled')
    }
	})
  
$('a.link_bulk_action').on('click', function(){
  $('.table input.hl:checkbox:checked').clone().attr('type','hidden').appendTo('form.bulk_action')

})

$('a.link_bulk_action[data-remote]').on('click',function(){
  $(this).attr( 'data-params', $('.table input.hl:checkbox:checked').serialize() )
})

	$('#filter-btn').click(function(){
		$('#filter-well').toggle('fast')
		$(this).toggleClass('active btn-primary','') 	
	})


  $('.remote').on('hidden.bs.modal', '.modal', function() {
      $(this).removeData('bs.modal').empty();
  }); 

  $('body').on('submit', '#form-create-list form', function() {
	 
	  // typeList()
	  $('#form_list').append( $('#holdings-items :checkbox:checked').clone().attr('type','hidden') )

  	console.log( $('#holdings-items :checkbox:checked').clone().length  )
      
  }); 


  // Manipula all reponse ajax json
  $('body').on( 'ajax:success', 'form,a', function(data, result, status){

    if($(this).attr('id') == 'recalled') window.location.reload()

    // Response for annotate action over holding
    if ( result.tag ){
      $('tr#'+result.tag).find('.btn-tag').removeClass('btn-default').addClass('btn-danger');
      $('#form-create-tags').modal('hide')
    }

    // Response for delete annotate action over holding
    if ( result.untag ){
      $('#'+result.untag).find('.btn-tag').removeClass('btn-danger').addClass('btn-default'); 
    } 

    // Response for annotate action over holding. Change class, hide modal form and change slide
    if ( result.annotated ){
      $('#'+result.annotated).addClass('danger').removeClass('success'); 
      $('#form-create-notes').modal('hide')
      $('#slider').carousel('next')
    } 

    // Response for mark correct a Holding: Change class, 
    if ( result.correct ){
      $('#'+result.correct).removeClass('danger').addClass('success'); 
      $('#slider').carousel('next');
    } 

    if ( result.list_revised ){
      $('#'+result.list_revised).addClass('revised');
      $('.state-list').text( result.state );
      $('.btn-revise').hide();
    } 

    if ( result.blank ){
      $('#'+result.blank).removeClass('danger').removeClass('success'); 
    } 

    if ( result.state ){

    	obj = $('#'+result.id)

    	if (result.state=='ok') {
    		obj.addClass( 'success' ).removeClass('danger')

        $('form#create-note-'+result.id+' input[name^="notes"]').val("")
        $('form#create-note-'+result.id)
          .find(':checkbox:checked').prop('checked',false).end()
          .find('label.active').removeClass('active')

        $('a[data-slide="next"]').click();
      }
    	
    	if (result.state=='annotated'){
    		obj.addClass( 'danger' ).removeClass('success')
	      $('#form-create-notes').modal('hide')
        $('a[data-slide="next"]').click();
	      // $('#slider').carousel('next')
    	}


      $('#'+result.id)
      	.addClass(result.state)
      	.find('.state span.label')
      	.text(result.state_title ); 
    }

  	if (result.created_list ){
      $('#form-create-list').modal('hide')
  	}
  	
    if ( result.remove ){
      $('#'+result.remove).hide('slow', function(){ $(this).remove() }); 
      if (result.counter)
      	$('.counter').text(result.counter)
    	
    }

    if ( result.received )
      $('#'+result.receive).hide('slow', function(){ $(this).remove() }); 

    if ( result.trash )
      $('#'+result.trash).hide('slow', function(){ $(this).remove() }); 

    if ( result.burn )
      $('#'+result.trash).hide('slow', function(){ $(this).remove() }); 

    if ( result.success )
      $('#'+result.success).addClass('success'); 

    if ( result.received ){
      $('#'+result.received).addClass('received'); 
    }

    if ( result.disabled ){
      $('#'+result.received).addClass('text-muted'); 
    }

    if ( result.delivered ){
      $('#'+result.delivered).addClass('delivered'); 
    }

    if ( result.commented ){
    	$('#form-create-comments').modal('hide')
    	$('#'+result.commented).hide('slow')
    }

    if ( result.error ){
    	alert(result.error)
    }

    if ( result.hide_feedback )
      $('#btn_create_feedback').popover('hide'); 

    if ( result.remove_by_holdingsset )
      $('tr[data-holdingsset='+ result.remove_by_holdingsset +']').hide('slow', function(){ $(this).remove() }); 
  })
  countThs();
	getAsuccess()
})

function typeList(){

	$select = $('#form_list select#worker_id')

  $('#form_list select#worker_id option').hide()

  if ($('#form_list :radio:checked').val()=='delivery'){
    $('#form_list select#worker_id option[data-role=postuser]').show()
  } else {
    $('#form_list select#worker_id option[data-role=maguser]').show()
  }

  // alert($select.find('option:visible:first').attr('value'))
  $select.val( $select.find('option:visible:first').attr('value') )
}	


function getAsuccess() {
    $('a').on({
    'ajax:success': function(data, result, status) {        
        set = $(this).attr('set')
        if ($(this).attr('ajaxsuccess') != 1) {
          $(this).attr('ajaxsuccess', 1)          
          if (set > 0) {          
            accordion = $('#hosg .hol-sets li#'+set).find('a.accordion-toggle');
            open = ($(accordion).hasClass('collapsed') == true) ? 0 : 1
            // console.log(open);
            reload_set(set, result, open);          
          }
          if ( result.remove )
            $('#'+result.remove).hide('fast', function(){ $(this).remove() }); 

          // Set HOS to CONFIRM
          if ( result.ok ){
            value = result.ok;
            $('#'+value).find('.btn-ok').addClass('btn-success').removeClass('btn-default').removeClass('btn-warning');
            $('#'+value).find('td.actions').html('');
            if (($('a#filter_pending').hasClass('active')) || ($('a#filter_annotated').hasClass('active'))) {
              $('li#'+value).remove();  
            }
            else {
              $('#'+value).find('#holdingsset'+value+'incorrect').fadeOut();
            }
          }

          // Set HOS to UNCONFIRM
          if ( result.ko ){
            value = result.ko;
            $('#'+value).find('.btn-ok').addClass('btn-default').removeClass('btn-success');
            if (!($('a#filter_all').hasClass('active'))) {
              $('li#'+value).remove();
            }
            else {
             $('#'+value).find('#holdingsset'+value+'incorrect').fadeIn();
            }
          }
          // Set HOS to CONFIRM
          if ( result.correct ){
            value = result.correct;
            $('#holdingsset'+value+'incorrect').removeClass('btn-danger').addClass('btn-default').find('span.fa').removeClass('text-warning').addClass('text-danger');
            if (!($('a#filter_all').hasClass('active'))) {            
              $('li#'+value).remove();   
            }          
            else {
             $('#'+value).find('#holdingsset'+value+'confirm').removeClass('btn-danger').addClass('btn-default');
             $('#'+value).find('#holdingsset'+value+'confirm').fadeIn();
            }
          }
          // Set HOS to UNCONFIRM
          if ( result.incorrect ){
            value = result.incorrect;
            $('#holdingsset'+value+'incorrect').addClass('btn-danger').removeClass('btn-default')
            $('#holdingsset'+value+'incorrect span.fa').each(function() {
              $(this).removeClass('text-warning').removeClass('text-danger')
            })
            if (!$('a#filter_all').hasClass('active')) { 
              $('li#'+value).remove();
            }          
            else {
              $('#'+value).find('#holdingsset'+value+'confirm').fadeOut();
            }
          }
          
          if ( result.removefromgroup ){
            value = result.removefromgroup;
            $('li#'+value).fadeOut('fast', function() {
              $('li#'+value).remove();
            })
          }

          //
          if ( result.newhosok ){
              $('tr#holding' + result.newhosok).remove(); 
          }

          /* Holdings locks */
          if ( result.lock ){
              $('#holding'+result.lock+'').addClass('locked').find('a#holding' + result.lock + 'lock').addClass('btn-warning')
          }
          if ( result.unlock ){
              $('#holding'+result.unlock).removeClass('locked').find('a#holding' + result.unlock + 'lock').removeClass('btn-warning')    
          }

          /* Holdings Tags */
          if ( result.tag ){
              $('tr#'+result.tag).find('.btn-tag').removeClass('btn-default').addClass('btn-danger')
              $('#form-create-tags').modal('hide')
          }
          if ( result.untag ){
              $('#'+result.untag).find('.btn-tag').removeClass('btn-danger').addClass('btn-default')
          }
          /* Deleted Group */
          if ( result.groupDelete ){
              $('li#group'+result.groupDelete).remove()
          }   
          /* Deleted Hlist */
          if ( result.hlistDelete ){
              $('li#hlist'+result.hlistDelete).remove()
          } 
        }
      }
    })
	 $('[data-toggle=tooltip]').tooltip()
}

function countThs() {
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
          trigger:    'hover',
          placement:  'bottom',
          html:       true,
          content:    content
        })
    //}
  })
}

function reload_set(set, data, open) {
  Set = $('#hosg .hol-sets li#'+set);
  $('#hosg .hol-sets li#'+set).find('div.accordion-toggle').click();
  $('#hosg .hol-sets li#'+set).fadeOut('fast', function() {
    $(Set).replaceWith(data);
    setDatatable();
    $('#hosg .hol-sets li#'+set).css('visibility', 'hidden')              
    $('#hosg .hol-sets li#'+set).fadeOut('fast', function() {
      $('#hosg .hol-sets li#'+set).css('visibility', 'visible')
      $('#hosg .hol-sets li#'+set).fadeIn('fast', function() {
        accordion = $('#hosg .hol-sets li#'+set).find('a.accordion-toggle');
        setDraggoption();
        $('.pop-over').popover();
        doEditable()
        getAsuccess()
        bulkActions()       
        if (open == 1) { $(accordion).click() }
      })
    })
  })
}

function doEditable() {
  $.fn.editable.defaults.mode = 'inline';
  // $.fn.editable.defaults.inputclass = 'input-';
  $.fn.editable.defaults.ajaxOptions = {type: "PUT"};
  $('.editable').editable({
  success: function(data, result, status){ 
    if ($(this).attr('set') > 0) {
      reload_set($(this).attr('set'), data, 1);      
    }
  }
});
}

function removedangerclass(value) {
  $('#incorrect' + value + 'text').removeClass('text-danger').addClass('text-warning');
}

function makehosdivisibles(table) {
  console.log(table);
    $(table + ' :checkbox.selhld').click( function(){
    // console.log('click');
    if (this.checked) {
      // console.log('CHECKED');
      if ( $(this).parents('li').find(':checkbox:checked.selhld').length>=2)
        $(this).parents('li').find('.newhos').css('display','block')
    } else {
      // console.log('NO-CHECKED');
      if ( $(this).parents('li').find(':checkbox:checked.selhld').length<2)
      $(this).parents('li').find('.newhos').css('display','none')
    }
  })

  $(table).parents('li').find('.newhos').on('click',function(){
    $(this).attr('href', $(this).attr('href') + '?'+$('#' + $(this).attr('set') + ' input.selhld:checkbox:checked').serialize());
    return true;
  })
}

function bulkActions() {

  $(':checkbox:checked.sel').parents('tr').addClass("warning")
  $(':checkbox:checked.sel').parents('li').addClass("warning")

  $(':checkbox.sel').click( function(){
    if (this.checked) {
      $('a.link_bulk_action').removeClass('disabled')
      $(this).parents('tr').addClass("warning")
      $(this).parents('li').addClass("warning")
    } else {
      $(this).parents('li').removeClass("warning")
      $(this).parents('tr').removeClass("warning")
      if ( $(':checkbox:checked.sel').length==0)
        $('a.link_bulk_action').addClass('disabled')
    }
  })


}


