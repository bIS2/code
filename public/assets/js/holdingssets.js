$(function(){

// $('#hosg').dataTable({
//   "bFilter": true,
//   "bPaginate": false,
//   "bDestroy": true
// });

	$('.pop-over').popover();
	setDatatable();
	var page
	var last_result = '-1'
	page = 1;
  xx = setInterval(adjustPaddingTop, 10); 
	$(window).scroll(function() {
		if (last_result != '') {
			if (($(window).scrollTop() == $(document).height() - $(window).height()) && (!($('#hosg').hasClass('paginating'))) && (!($('#hosg').hasClass('nopaginate')))) {
				$('#hosg').addClass('paginating');
				$('#current_quantity > div').addClass('progress-bar');
				$('#current_quantity > div').css('color', 'transparent');
				$('#current_quantity > div').css('width', '100%');
				page++;
				url = "/sets?"+ window.location.search.substring(1) + '&page=' + page
			 	$.get(url,
				  function(data){
			  		last_result = data
				    // console.log(last_result);
					  if (data != "") {
					    $("#hosg ul li:last").after(data);
			 				$('.pop-over').popover()
			 				getAsuccess()
			 				setDatatable()
			 				$('#hosg').removeClass('paginating');
			 				$('#current_quantity').fadeOut('slow', function() {
			 					$(this).find('div').removeAttr('style');
			 					$(this).find('div').removeClass('progress-bar');
			 					$(this).find('div').html($('#hosg ul.hol-sets li').length);
			 					if (parseInt($('#total_quantity').html()) == ($('#hosg ul.hol-sets li').length)) $('#hosg').addClass('nopaginate');
			 					$(this).fadeIn();
			 				})
						}
				});
		 	}
	 	}
	});

	$('#currentfiltersoption label').on('click', function() {
		filter = $(this).attr('href');
		if ($(this).hasClass('active')) {
			$(filter).appendTo('#fieldstosearchhidden');
		}
		else	{
			$(filter).appendTo('#currentfilters');
		}
	})

	setDraggoption();

	$( "#FieldsShow .btn-group" ).sortable();
	$( "#FieldsShow .btn-group" ).disableSelection();
	// $( "#hosg .hol-sets" ).disableSelection();
})

function setDatatable() {
	$('#hosg .accordion-toggle').each(function() {
		$(this).on('click', function() {
			if ($(this).attr('opened') == 0) {
				var aoColumns = []
		    ths = $($(this).attr('href') + ' .flexme th');
		    for (var i = 0; i < $(ths).length; i++) {
		    	(($(ths[i]).hasClass('hocrr_ptrn')) || ($(ths[i]).hasClass('actions')) || ($(ths[i]).hasClass('table_order'))) ? aoColumns.push({ "asSorting": [ "" ] }) : aoColumns.push(null) 
		    }
				$($(this).attr('href') + ' .flexme').dataTable({
				    "bFilter": false,
				    "bPaginate": false,  
		        "bLengthChange": true,
		        "bInfo": true,
		        "bAutoWidth": true,
		        "aoColumns": aoColumns,
				  });
				getAsuccess();
				$($(this).attr('href') + ' table').addClass('table');
	  			$($(this).attr('href') + ' .flexme span').each(function() {
	  			})
				$(this).attr('opened', 1);
			}
		})
  })
}

function setDraggoption() {
	$( "#hosg .hol-sets li" ).draggable({ 	
		handle: ".move",
		helper: 'clone',
		activeClass: "btn-primary",
		cancel: "a.ui-icon", // clicking an icon won't initiate dragging
		revert: "invalid", // when not dropped, the item will revert back to its initial position
	});

	$( "#groups-tabs .accepthos" ).droppable({
		accept: "#hosg .hol-sets li",
		hoverClass: "activedrop",
		drop: function( event, ui ) {
			recipient = $(this)
			Url = "/sets/move-hos-to-othergroup/" + $(ui.draggable).attr('id')
			var changegroup = $.ajax({
			  url 	: Url,
			  type 	: "PUT",
			  data 	: {
			  	origingroup : $('#groups-tabs li.active').attr('id'),
			  	newgroup : $(recipient).attr('id'),
				  dataType		: "json",
				  cache			: false
				}
			});
			changegroup.done(function(result) {
				if (!($('#groups-tabs > li:first-child').hasClass('active'))) { 
					$(ui.draggable).fadeOut('slow', function() {
						$(ui.draggable).remove();	
					});
				}
				else {
					if (result.ingroups > 0) {
						badge = $(ui.draggable).find('span.badge.ingroups');
						// console.log();
						if ($(badge).hasClass('ingroups') == true) {
							$(badge).fadeOut('slow', function() {
								$(badge).html('<i class="fa fa-folder-o"></i> ' + result.ingroups);
								$(badge).fadeIn();
							});
						}
						else {
							otherbadge = $(ui.draggable).find('span.badge + p');
							$('<span style="display: none;" class="badge ingroups" title="Reload to update" ><i class="fa fa-folder-o"></i> ' + result.ingroups + '</span>').insertAfter(otherbadge);
							newbadge = $(ui.draggable).find('span.badge.ingroups');
							$(newbadge).fadeOut('slow', function() {
								$(newbadge).removeAttr('style');
								$(newbadge).fadeIn('slow');
							});
						}
					}					
				}
			}); 
		}
}); 
}

function adjustPaddingTop() {
	$('body > div#toolbar + div.container').css('padding-top', parseInt($('section.container').height()) + 15);
}