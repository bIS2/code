// Contains all the logic on the client
var updatingProfile = 0;

$(function(){
  bIS.init();
  bIS.updateProfile();
  $('#per-page').on('change',function(){
    $(this).parents('form').submit()
  })

  var totalItems = $('.carousel .item').length;
  var currentIndex = $('.carousel div.active').index() + 1;
  $('.carousel #num').html(''+currentIndex+'/'+totalItems+'')
  if (currentIndex==1)
    $(this).find('[data-slide="prev"]').attr('disabled',"disabled")
  else
    $(this).find('[data-slide="prev"]').removeAttr('disabled')

  $('.carousel').on('slid.bs.carousel', function(){
    var totalItems = $(this).find('.item').length;
    var currentIndex = $(this).find('div.active').index() + 1;
    $(this).find('#num').html(''+currentIndex+'/'+totalItems+'')
    if (currentIndex==totalItems)
      $(this).find('[data-slide="next"]').attr('disabled',"disabled")
    else
      $(this).find('[data-slide="next"]').removeAttr('disabled')

    if (currentIndex==1)
      $(this).find('[data-slide="prev"]').attr('disabled',"disabled")
    else
      $(this).find('[data-slide="prev"]').removeAttr('disabled')
  })

/* $('form#create_user').on('submit', function(e){

if ($('input[name="roles[]"]:checked').size()==0 ){
$('input[name="roles[]"]').parents('div.form-group').addClass('has-error')
e.preventDefault()
}


})*/

/*	$('input[name="roles[]"]').on('click', function(){

if ($('input[name="roles[]"]:checked').size()>0 )
$(this).parents('.has-error').removeClass('has-error')

})*/


// update related user for selected list type
$('body').on('click','#form_list :radio', function(){

  var options = $.parseJSON( $('.options').text() )

  o = ($('#form_list :radio:checked').val()=='delivery') ? options.postuser : options.maguser;

  $select = $('select#worker_id').empty()
  $.each(o, function(k,v){
    $select.append( $('<option></option>').val(k).html(v) )
  })    

})


// validatio off create annotates holding 
$('body').on('click','#submit-create-notes', function(e){

  var check_notes = $('form.create-note :checkbox:checked');

  if (check_notes.size()==0){

// bootbox.alert( $('#select_notes_is_0').text() )
$('.alert-error').removeClass('hide').text( $('#field_note_in_blank').text())
e.preventDefault()

} else {

  check_notes.each(function(){
    var content = $(this).parents('.input-group').find('input.content').val();
    if ( (content.length==0) ){
      $(this).parents('.form-group')
      .addClass('has-error')
      .find('.error').text( $('#field_note_in_blank').text() )
      e.preventDefault()
    }

  })

}

})

$('body').on('keyup','form.create-note .content', function(e){
  var $this = $(this)

  if ( $this.val() ){
    $this.parents('.form-group').removeClass('has-error').find('.error').text('')
    $('.alert-error').addClass('hide')
    if ( $this.parents('.input-group').find(':checkbox:checked').size()==0 )
      $this.parents('.input-group').find('label').trigger('click')
  }
  else {
    console.log( $this.parents('.input-group').find(':checkbox:checked').size() )
    if ( $this.parents('.input-group').find(':checkbox:checked').size()>0 ){
      $this.parents('.form-group')
      .addClass('has-error')
      .find('.error').text( $('#field_note_in_blank').text() )
    }
  }	

})

$('.form-group .input-group-addon.btn.btn-primary.btn-sm' ).each(function() {
  $(this).on('mousedown', function() {
    if ($(this).hasClass('active')) {
      $(this).parents('.form-group').removeClass('has-error')
      $(this).parents('.form-group').find('.error').text('')

    }
  })
})

$('.btn-ok, .btn-tag').each(function() {
  $(this).on('click',function(e){

    // size_in_form = $(this).parents('form').find('input#size').val()
    // size_in_a = parseFloat($(this).parents('tr').find('.editable.size').text() )
    // size = (size_in_form) ? size_in_form : size_in_a
    // if (!( size > 0 )) {
    //   bootbox.alert( $('#field_size_in_blank').text() )
    //   return false
    // } 

    size_dispatchable_in_form = $(this).parents('form').find('input#size_dispatchable').val()
    size_dispatchable_in_a = parseFloat($(this).parents('tr').find('.editable.size_dispatchable').text() )
    size_dispatchable = (size_dispatchable_in_form) ? size_dispatchable_in_form : size_dispatchable_in_a
    if (!( size_dispatchable > 0 )) {
      bootbox.alert( $('#field_size_dispatchable_in_blank').text() )
      return false
    } 
  })
})

var originhref = $('a.btn-ok').attr('href');
$('input#size').on('keyup',function(){
// console.log($(this).serialize())
data = $('a.btn-ok').data('params')
$('a.btn-ok').attr('href', originhref  + '?' + $(this).serialize() )
})
$('input#size_dispatchable').on('keyup',function(){
// console.log($(this).serialize())
data = $('a.btn-ok').data('params')
$('a.btn-ok').attr('href', originhref  + '?' + $(this).parents('form').serialize() )
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
var call = $.post( $to.data('attach-url'), { 'holding_id[]': $from.attr('id'), hlist_id: $to.data('id') } )
call.done(function(result) { 
  if ( result.error ) {
    bootbox.alert( result.error )
  } else{
    $to.find('.counter').text( result.counter )
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
  columnDefs: [ { targets: 0, orderable: false },{ targets: 1, orderable: false },{ targets: 2, orderable: false } ],
  order:[],
  bFilter: false,
  bPaginate: false , 
  bStateSave: true
});

$('.datatablelists').dataTable({
  columnDefs: [ { targets: 0, orderable: false },{ targets: 1, orderable: false }],
  order:[],
  bFilter: false,
  bPaginate: false , 
  bStateSave: true
});

tds = $('#holdings-items tbody > tr:first-child td');
ths = $('#holdings-items th');
for (var i = 0; i < $(ths).length; i++) {
  $(ths[i]).css('min-width', parseInt($(ths[i]).width()) + 25);
// $(ths[i]).css('background', '#cccccc');
$(tds[i]).css('min-width', parseInt($(ths[i]).width()) + 25);
}
tds = $('#holdings-items tbody > tr td:first-child').css('min-width', '25px').css('max-width', '25px').css('padding-right', '5px');
tds = $('#holdings-items thead > tr th:first-child').css('min-width', '25px').css('max-width', '25px').css('padding-right', '5px');
$('#new-table').append($('#holdings-items thead').clone());
$('#holdings-items_wrapper').prepend($('#new-table'));
if ($('#new-table').attr('id') != undefined) yy = setInterval(moveTogether, 10);

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

$('body').on('click', '.select',function(e) {
  e.preventDefault()
  var $elements = $( 'tr'+$(this).data('target')+' :checkbox.sel' )
  var value = ($(this).attr('data-check')=='true') ? true : false
  console.log($(this).attr('data-check'))

  $('#holdings-targets :checkbox.sel')
  .prop('checked',false)
  .parents('tr')
  .removeClass("warning")  

  $('a.link_bulk_action').addClass('disabled')

  $(this).attr('data-check', (!value) ? 'true' : 'false' )

// $('div.select-all p').toggleClass('active')
if ( !value && ($elements.length>0) ) {
$elements.prop('checked',!value)                              // marck holdings selected
$elements.parents('tr').addClass("warning")
$('a.link_bulk_action').removeClass('disabled')
}

})


$('a.link_bulk_action[data-remote]').on('click',function(){
  $(this).attr( 'data-params', $('.table input.hl:checkbox:checked').serialize() )
})

$('a#join-the-hos').on('click',function(event){
  event.preventDefault();
  $(this).attr( 'data-params', $('.table input.hl:checkbox:checked').serialize() + '&nocache=' + Math.random(10000, 30000) )


  var joinhos = $.ajax({
    url   : $('a#join-the-hos').attr( 'href') + '?' + $('a#join-the-hos').attr('data-params'),
    type  : "PUT",
    data  : {
      dataType    : "HTML",
      cache     : false
    }
  });
  joinhos.done(function( data ) { 
    var oldhos = $(':checkbox:checked.sel');
    set = $(oldhos[0]).val();
    for (var i = 1; i < $(oldhos).length; i++) {
      $('li#'+ $(oldhos[i]).val()).fadeOut(500, function() {
        $(this).remove();
      });
    };
    open = 1;
    reload_set(set, data, open);
    $('a#join-the-hos').html('<i class="fa fa-magnet"></i>');
  })

})

$('a.link_bulk_action').on('click', function(){
// alert( $('.table input.hl:checkbox:checked').clone(true).prop('type','hidden') )
$('.table input.hl:checkbox:checked').clone(true).prop('type','hidden').appendTo('form.bulk_action')
})


$('#filter-btn').click(function(){
  $('#filter-well').toggle('fast')
  $(this).toggleClass('active btn-primary','') 	
})


$('.remote').on('hidden.bs.modal', '.modal', function() {
  $(this).removeData('bs.modal').empty();
}); 

$('.remote').on('click', '#submit_create_list', function() {

  $('form#form_list').append( $('<div>').addClass('hide').append( $('#holdings-items :checkbox:checked').clone(true) ) )

}); 

// $('body').on('show.bs.modal', '#form-create-list', function(){ typeList() })

handleAjaxSucces('body');
countThs();
getAsuccess()
$('.modal').on('shown.bs.modal', function() {
  $(this).removeAttr('ajaxsuccess');
})
})

function handleAjaxSucces(parent) {
// Manipula all reponse ajax json
$(parent).on( 'ajax:success', 'form,a', function(data, result, status){

  if (result.location) {
    window.location.href = result.location;
    return false;
  }      

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
  $('#'+result.list_revised).addClass('revised').hide('slow');
  $('#hos_actions_and_filters .state-list').text( result.state ).removeClass('hide');
  $('#hos_actions_and_filters .btn-revise').hide();
} 

if ( result.blank ){
  $('#'+result.blank).removeClass('danger').removeClass('success'); 
} 

// show btn to revise list if completed
if ( result.list_completed ) {
  $('.btn-revise').removeClass('hide') 
  $('.label.label-primary.state-list').addClass('hide') 

}
// show btn to revise list if completed
if ( result.list_received  ) {
  $('#'+result.list_received )
// .hide('slow') 
.find('.state-list').text(result.state)
.end()
.find('.btn-receive').hide()
}

if ( result.list_completed == false) {
  $('.btn-revise').addClass('hide')
  $('.label.label-primary.state-list').removeClass('hide') 
}

if ( result.state ){

  obj = $('#'+result.id)

  if ( result.state=='trash' || result.state=='received' || result.state=='commented' || result.state=='deleted' ) {
    obj.hide('slow')
  }

  if (result.state=='not_ok' )
    obj.removeClass('success')

  if (result.state=='ok' ) {
    obj.addClass( 'success' ).removeClass('danger')

    /* $('form#create-note-'+result.id+' input[name^="notes"]').val("") */
    $('form#create-note-'+result.id)
    .find(':checkbox:checked').prop('checked',false).end()
    .find('label.active').removeClass('active')

    $('a[data-slide="next"]').click();
  }

  if (result.state=='annotated') {
    obj.addClass( 'danger' ).removeClass('success')
    $('#form-create-notes').modal('hide')
    $('a[data-slide="next"]').click();
    /* $('#slider').carousel('next') */
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
  if (result.counter) {
    if ($('li.active.droppable.ui-droppable') != undefined) {
      $('li.active.droppable.ui-droppable .counter').text(result.counter)
    }
    else {
      $('.counter').text(result.counter)
    }
  }
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
  $this = 
  $('#'+result.delivered)
  .addClass('delivered')
  .find('.state-list')
  .text( result.state );
}

if ( result.commented ){
  $('#form-create-comments').modal('hide')
  $('#'+result.commented).hide('slow', function(){ $(this).remove() });
}

if ( result.error ){
  bootbox.alert(result.error)
// alert(result.error)/
}

if ( result.hide_feedback )
  $('#btn_create_feedback').popover('hide'); 

if ( result.remove_by_holdingsset )
  $('tr[data-holdingsset='+ result.remove_by_holdingsset +']').hide('slow', function(){ $(this).remove() }); 
})
}

function getAsuccess() {
  $('a, #modal-show').on({
    'ajax:success': function(data, result, status) {

      set = ($(this).hasClass('modal')) ? $('#f866aeditablesave').attr('set') : $(this).attr('set')

      if ($(this).attr('ajaxsuccess') != 1) {
        $(this).attr('ajaxsuccess', 1)          
        if (set > 0) {  

          accordion = $('#hosg .hol-sets li#'+set).find('a.accordion-toggle');
          open = ($(accordion).hasClass('collapsed') == true) ? 0 : 1
          reload_set(set, result, open);

          if ($(this).hasClass('modal')) { 
            bootbox.alert('866a Merken OK') 
          }
        }
        if ( result.remove )
          $('#'+result.remove).hide('fast', function(){ $(this).remove() }); 

        if ( result.ok ) {
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

        /* Set HOS to UNCONFIRM*/
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

        /* Set HOS to CONFIRM */
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
        /* Set HOS to UNCONFIRM */
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
  success: function(data, result, status) { 
    var set = $(this).attr('set')
    set = ((set > 0) == false) ? $(this).parents('li').attr('id') : set ;
    if (!$(this).parent().hasClass('field_fx866a')) {      
      if (set > 0) {
        reload_set(set, data, 1);      
      }
    }
  }
});
}

function removedangerclass(value) {
  $('#incorrect' + value + 'text').removeClass('text-danger').addClass('text-warning');
}

function makehosdivisibles(table) {
// console.log(table);
$(table + ' :checkbox.selhld').click( function(){
// // console.log('click');
if (this.checked) {
// // console.log('CHECKED');
if ( $(this).parents('li').find(':checkbox:checked.selhld').length>=2)
  $(this).parents('li').find('.newhos').css('display','block')
} else {
// // console.log('NO-CHECKED');
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

  // if exists holdings selected then ative button to create list
  if ( $(':checkbox:checked.sel').size()>0 ) {
    $('a.link_bulk_action').removeClass('disabled')
  }
  if ( $(':checkbox:checked.sel').size()>1 ) {

    $('a#join-the-hos').removeClass('disabled')
    $('a#join-the-hos').attr('set', $($(':checkbox:checked.sel')[0]).val())

  }
  else {
    $('a#join-the-hos').addClass('disabled')
    $('a#join-the-hos').removeAttr('set');
  }

  $(':checkbox.sel').click( function() {
    if ( $(':checkbox:checked.sel').length>1) {
      $('a#join-the-hos').removeClass('disabled')
      $('a#join-the-hos').attr('set', $($(':checkbox:checked.sel')[0]).val())
    }
    else {
      $('a#join-the-hos').addClass('disabled')
      $('a#join-the-hos').removeAttr('set');      
    }
    if (this.checked) {
      $('a.link_bulk_action').removeClass('disabled')
      $(this).parents('tr').addClass("warning")
      $(this).parents('li').addClass("warning")
    } else {
      $(this).parents('li').removeClass("warning")
      $(this).parents('tr').removeClass("warning")
      if ( $(':checkbox:checked.sel').length==0) {
        $('a.link_bulk_action').addClass('disabled')
      }
    }
  })


}

function moveTogether() {
  $('#new-table').offset({ left: $('#holdings-items').offset().left});
  if ($('#holdings-items').offset().top < $('#toolbar > .container').offset().top + parseInt($('#toolbar > .container').height()) + 30) { $('#new-table').css('display', 'block') } else { $('#new-table').css('display', 'none') }
}

var Changing = null;
var ColumnEdited = null;
var CurrentField = null;

var bIS = {
  init: function() {
    bIS.changeFieldSize();
    bIS.changeOfProfile();
    bIS.makeOderableGroups();
    bIS.makeSortableGroups();
  },
  changeFieldSize: function() {

    /* Profile Code */

    $('.expand').on('mouseover', function() {
      CurrentField = $(this).parents('.change-size-controls').attr('target');
      ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
      window.clearInterval(Changing);
      $(ColumnEdited).removeAttr('touched');
      if (($(ColumnEdited).attr('touched') == undefined) || ($(ColumnEdited).attr('touched') == '')) {  
        $(ColumnEdited).attr('touched', 0); 
        Changing = window.setInterval(function() {
          if (($(ColumnEdited).width() > 2000) || ($(ColumnEdited).attr('touched') > 67)) {
            window.clearInterval(Changing);
            $(ColumnEdited).removeAttr('touched'); 
            return false
          }
          else {
            $(ColumnEdited).css('width', $(ColumnEdited).width() + 6);
            $('#'+ CurrentField + '_size').val($(ColumnEdited).width() + 6);
            var temp = $(ColumnEdited).attr('touched');
            temp++;
            $(ColumnEdited).attr('touched', temp);
          }
        }, 100);
      }

    })

$('.expand').on('mouseout', function() {
  window.clearInterval(Changing);
  ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
  $(ColumnEdited).removeAttr('touched'); 
})

$('.compress').on('mouseover', function() {
  window.clearInterval(Changing);
  $(ColumnEdited).removeAttr('touched'); 
  CurrentField = $(this).parents('.change-size-controls').attr('target');
  ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
  if (($(ColumnEdited).attr('touched') == undefined) || ($(ColumnEdited).attr('touched') == '')) {  
    $(ColumnEdited).attr('touched', 0); 
    Changing = window.setInterval(function() {
      if (($(ColumnEdited).width() <= 40) || ($(ColumnEdited).attr('touched') > 65)) {
        window.clearInterval(Changing);
        $(ColumnEdited).removeAttr('touched'); 
        return false
      }
      else {
        $(ColumnEdited).css('width', $(ColumnEdited).width() - 6);
        $('#'+ CurrentField + '_size').val($(ColumnEdited).width() - 6);
        var temp = $(ColumnEdited).attr('touched');
        temp++;
        $(ColumnEdited).attr('touched', temp);
      }
    }, 100);
  }
})

$('.compress').on('mouseout', function() {
  window.clearInterval(Changing);
  ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
  $(ColumnEdited).removeAttr('touched'); 
})
},
changeOfProfile: function() {
  $profiles =  $('#btn-profiles > label');

  $profiles.each(function() {
    $(this).on('click', function() {
      var clicked = $(this);
      Changing = window.setInterval(function() {
        $(clicked).parents('form').find('input[name="new_profile"]').val('');
        $(clicked).parents('form').submit();
        window.clearInterval(Changing); 
      }, 100);
      return true;
    })
  }) 
},
updateProfile: function() {
  $('.btn.btn-xs.btn-primary').on('click', function(e) {
    console.log($(this).attr('reload'));

    reload = ($(this).attr('reload') == 1) ? 1 : 0;
    $('#reload').val(reload);
    if (reload == 1) {
      $('#urltoredirect').val($('#profiles-form').attr('action'));
    }
  })

  $('#profiles-form').on('submit', function() {

    if ($('#profiles-form #reload').val() == 0) {

      var profiling = $.post( $(this).attr('action'), $(this).serialize() );
      $('.tooltip.fade.in').remove();
      profiling.done(function( data ) { 

        var updating = $.get( $('#profiles-form').attr('ajax-post'));

        updating.done(function( info ) {
          $('#profiles-container').html(info);
          bIS.init();
          var $opened = $('#hos-targets li a.anchored');
          lis = $('#hosg ul.hol-sets li a.anchored');
          $opened.each(function() {
            $(this).removeAttr('opened');
            $(this).attr('opened', 0);
            // $(this).click();           
          })
          var current = -1;
          var end = $opened.length;
          updatingProfile = 1;
          openAll(lis, 0);


          // var timer = setInterval(function() {
          //   current += 1;
          //   if (current == end) {
          //     clearInterval(timer);
          //   }
          //   else {
          //     $($opened[current]).click();
          //   }
          // }, 500);

      })
      })
      return false;
    }
  })
},
makeOderableGroups: function() {
  $('.datatablegroups').dataTable({
    columnDefs: [ { targets: 0, orderable: false }],
    order:[],
    bFilter: false,
    bPaginate: false, 
    bStateSave: true
  });
},
makeSortableGroups: function() {
  $( "#FieldsShow .btn-group" ).sortable()
},
}

var pages = {
  init: function() {
    $('#filterContainer.extract .btn.btn-xs').on('mouseup', function() {
      var timer = setInterval(function() {
        $.ajax({  type: "GET",  url: '/admin/extract-data?fromajax=1&' + $('#advanced-search-form').serialize(),  success: pages.updateQuery,  dataType: 'HTML'});
        window.clearInterval(timer);
      }, 100)     
    })
  },
  updateQuery: function(data) {
    $('#query').val(data)
  },
  setFilterActions: function() {    
    $('#currentfiltersoption label').on('click', function() {
      filter = $(this).attr('href')
      if ($(this).hasClass('active')) {
        $(filter).appendTo('#fieldstosearchhidden')
      }
      else  {
        if ($(filter).attr('id') == 'ffsys1') { 
          $(filter).prependTo('#currentfilters')
        }
        else { 
          $(filter).appendTo('#currentfilters')
        }
      }
    })
  }
}
