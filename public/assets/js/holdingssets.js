$(function(){
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
				$('#current_quantity > div').addClass('fa fa-cog fa-spin');
				$('#current_quantity > div').html('');
				$('#current_quantity > div').css('width', '100%');
				page++;
				url = "/sets?"+ window.location.search.substring(1) + '&page=' + page
			 	$.get(url,
				  function(data) {
			  		last_result = data
				    // console.log(last_result);
					  if (data != "") {
					    $("#hosg ul li:last").after(data);
			 				$('.pop-over').popover()
			 				getAsuccess()
			 				setDatatable()
			 				doEditable()
			 				makehosdivisibles()
			 				$('#hosg').removeClass('paginating');
			 				$('#current_quantity').fadeOut('slow', function() {
			 					$(this).find('div').removeAttr('style');
			 					$(this).find('div').removeClass('fa fa-cog fa-spin');
			 					$(this).find('div').html($('#hosg ul.hol-sets li').length);
			 					if (parseInt($('#total_quantity').html()) == ($('#hosg ul.hol-sets li').length)) $('#hosg').addClass('nopaginate');
			 					$(this).fadeIn();
			 				})
						}
					}
				);
		 	}
	 	}
	});

	$('#currentfiltersoption label').on('click', function() {
		filter = $(this).attr('href')
		if ($(this).hasClass('active')) {
			$(filter).appendTo('#fieldstosearchhidden')
		}
		else	{
			$(filter).appendTo('#currentfilters')
		}
	})
	setDraggoption()
	makehosdivisibles()
	$( "#FieldsShow .btn-group" ).sortable()
	$( "#FieldsShow .btn-group" ).disableSelection()
})

function setDatatable() {
	$('#hosg .accordion-toggle').each(function() {
		$(this).on('click', function() {
			if ($(this).attr('opened') == 0) {
				This = $(this);
				url = "/sets?holcontent=1&holdingsset_id="+ $(This).attr('id')
				$($(This).attr('href') + ' .panel-body').html('<div class="fa fa-cog fa-spin"></div>');
			 	$.get(url,
				  function(data) {
			  		last_result = data
				    // console.log(last_result);
					  if (data != "") {
					  	// console.log(data)
					  	$($(This).attr('href') + ' .panel-body').html(data);
							var aoColumns = []
					    ths = $($(This).attr('href') + ' .flexme th');
					    for (var i = 0; i < $(ths).length; i++) {
					    	(($(ths[i]).hasClass('hocrr_ptrn')) || ($(ths[i]).hasClass('actions')) || ($(ths[i]).hasClass('table_order'))) ? aoColumns.push({ "asSorting": [ "" ] }) : aoColumns.push(null) 
					    }
							$($(This).attr('href') + ' .flexme').dataTable({
						    "bFilter": false,
						    "bPaginate": false,  
				        "bLengthChange": true,
				        "bInfo": true,
				        "bAutoWidth": true,
				        "aoColumns": aoColumns,
						  });
							getAsuccess()
							countThs()
							doEditable()
							makehosdivisibles()
							$('[data-toggle=tooltip]').tooltip()
							$('[data-toggle=popover]').popover()
							$($(This).attr('href') + ' table').addClass('table');
							$(This).attr('opened', 1);
							$($(This).attr('href') + ' a.forceaux').each(function() {
								$(this).on('click', function() {
									hol = $(this).parent().attr('holding');
									actives = $('tr#holding'+hol + ' td.ocrr_ptrn .fa.active').length;
									if (actives > 0) {
										var newptrn = '';
										var count = 0;
										$('tr#holding'+hol + ' td.ocrr_ptrn .fa').each(function() {
											if ($(this).hasClass('active') || $(this).hasClass('fa-square')) {
												newptrn = newptrn + '1'
												count++;
											} 
											else { 
												newptrn = newptrn + '0'
											}
										})
										// console.log(newptrn);
										$(this).attr('data-params', $(this).attr('data-params') + '&newptrn='+newptrn + '&count=' + count);
										console.log($(this).attr('data-params'));
										return false;
									} 
									else {
										// $('tr#holding'+hol + ' td.actions input:first-child + a').click();
										return false;
									}
								});
							})
						}
					}
				);
			}
		})

  })
}

function setDraggoption() {
	$( "#hosg .hol-sets li" ).draggable({ 	
		handle: ".move",
		helper: 'clone',
		activeClass: "btn-primary",
		cancel: "a.ui-icon",
		revert: "invalid",
	});

	$( "#groups-tabs .accepthos" ).droppable({
		accept: "#hosg .hol-sets li",
		hoverClass: "activedrop",
		tolerance: "pointer", 
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