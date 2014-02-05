var last_result = '-1'
var page
var openallindex = 0;
var lis;

$(function() {
	
	$('.pop-over').popover();

	setDatatable();
	page = 1;
  	xx = setInterval(adjustPaddingTop, 10);

	$(window).scroll(function() {
		if ($(window).scrollTop() == $(document).height() - $(window).height()) {
			HOS_paginate();
		}
	});

	$('#next-page').on('click', function() {
		HOS_paginate();
	});
	
	$('#currentfiltersoption label').on('click', function() {
		filter = $(this).attr('href')
		if ($(this).hasClass('active')) {
			$(filter).appendTo('#fieldstosearchhidden')
		}
		else	{
			if ($(filter).attr('id') == 'ffsys1') { 
				$(filter).prependTo('#currentfilters')
			}
			else { 
				$(filter).appendTo('#currentfilters')
			}
		}
	})
	setDraggoption()
	$( "#FieldsShow .btn-group" ).sortable()
	$( "#FieldsShow .btn-group" ).disableSelection()

	$('#open-all-hos').on('click', function() {
		lis = $('#hosg ul.hol-sets li .accordion-toggle.collapsed');
		openAll(lis, 0);
	});
})

function setDatatable() {
	$('#hosg .accordion-toggle').each(function() {
		if ($(this).attr('anchored') == 0) {
			$(this).attr('anchored', 1)
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
								getAsuccess()
								$($(This).attr('href') + ' button.dropdown-toggle').mouseenter( function() { $(this).click() });
								countThs()
								doEditable()
								setDraggoption()
								makehosdivisibles($(This).attr('href'))
								$(This).attr('opened', 1);
								$('[data-toggle=tooltip]').tooltip()
								$('[data-toggle=popover]').popover()
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
								$($(This).attr('href') + ' i.fa').each(function() {
									$(this).on('mouseout', function() {
										current = $(this)
										newptrn = '';
										newauxptrn = '';
										count = 0;
										actives = 0;
										hol = $(this).parents('tr').attr('holding');
										$('tr#holding'+hol + ' td.ocrr_ptrn i.fa').each(function() {
											if ($(this).hasClass('active') || $(this).hasClass('fa-square')) {												
												newauxptrn = ($(this).hasClass('aux') || $(this).hasClass('active')) ? newauxptrn + '1' : newauxptrn + '0'
												newptrn = newptrn + '1'
												count++;
												actives  = $(this).hasClass('active') ? actives + 1 : actives
											} 
											else { 
												newauxptrn = newauxptrn + '0'
												newptrn = newptrn + '0'
											}
										})
										dataparam = 'http://bis.trialog.ch/sets/force-aux/' + hol;
										dataparam = dataparam + "?holdingsset_id=" + $(this).parents('tr').find(' a.forceaux').attr('set')
										dataparam = dataparam + "&newptrn=" + newptrn
										dataparam = dataparam + "&newauxptrn=" + newauxptrn
										dataparam = dataparam + "&count=" + count
										if (actives > 0 )	$(this).parents('tr').find(' a.forceaux').attr('href', dataparam);
									});
								})

								$($(This).attr('href') + ' a.forceaux').each(function() {
									$(this).on('click', function() {
										hol = $(this).parent().attr('holding');
										actives = $('tr#holding'+hol + ' td.ocrr_ptrn .fa.active').length;
										if (actives > 0) {
											return true;
										} 
										else {
											// $('tr#holding'+hol + ' td.actions input:first-child + a').click();
											// return false;
										}
									});
								})
								if (openallindex != 0) openAll(lis, openallindex);
							}
						}
					);
				}
			})
		}
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
						console.log($(ui.draggable).attr('id'));
						if ($(badge).hasClass('ingroups') == true) {
							$(badge).fadeOut('slow', function() {
								$(badge).html('<i class="fa fa-folder-o"></i> ' + result.ingroups);
								$(badge).fadeIn();
							});
						}
						else {
							otherbadge = $(ui.draggable).find('div.col-sm-12');
							$('<span style="display: none;" class="badge ingroups" title="Reload to update" ><i class="fa fa-folder-o"></i> ' + result.ingroups + '</span>').appendTo(otherbadge);
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
	$('body > div#toolbar + section.container').css('padding-top', parseInt($('#toolbar > section.container').height()) + 60);
}

function HOS_paginate() {
	if ($('#hosg').attr('infinitepagination') == '1') {
			if (last_result != '') {
				if ((!($('#hosg').hasClass('paginating'))) && (!($('#hosg').hasClass('nopaginate')))) {
					$('#hosg').addClass('paginating');
					$('#current_quantity > div').addClass('fa fa-cog fa-spin');
					$('#next-page').addClass('disabled');
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
				 				setDatatable()
				 				setDraggoption()
				 				getAsuccess();
				 				bulkActions();
				 				$('#hosg').removeClass('paginating');
				 				$('#current_quantity').fadeOut('slow', function() {
				 					$(this).find('div').removeAttr('style');
				 					$(this).find('div').removeClass('fa fa-cog fa-spin');
				 					$('#next-page').removeClass('disabled');
				 					$(this).find('div').html($('#hosg ul.hol-sets li').length);
				 					if (parseInt($('#total_quantity').html()) == ($('#hosg ul.hol-sets li').length)) $('#hosg').addClass('nopaginate');
				 					$(this).fadeIn();
				 				})
							}
						}
					);
			 	}
			 	// else {
			 	// 	$('#hosg').attr('infinitepagination', '0');
			 	// 	$('#next-page').css('visibility', 'hidden');
			 	// 	last_result = '';
		 		// }
		 	}
		 	// else {
		 	// 	$('#hosg').attr('infinitepagination', '0');
		 	// 	$('#next-page').css('visibility', 'hidden');
		 	// }
		}
		// else {
		// 	$('#next-page').css('visibility', 'hidden');
		// }
}


function openAll(lis, index) {
	if ($(lis).length > index) {  
		openallindex = index + 1;
		console.log(lis[index]);
		$(lis[index]).click();
	}
	else {
		openallindex = 0;
	}
}