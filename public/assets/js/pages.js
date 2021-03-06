// Contains all the logic on the client

$(function(){
  pages.init();
})

var pages = {
	init: function() {
		pages.setFilterActions();
		// $('#filterContainer.extract .btn').on('mouseup', function() {
		// 	pages.getNewQuery();	
		// })
		$('#filterContainer input').on('keyup', function() {
			pages.getNewQuery();	
		})
		$('#filterContainer input').on('change', function() {
			pages.getNewQuery();	
		})
		$('#filterContainer select').on('change', function() {
			pages.getNewQuery();	
		})
		$('#buttonsearch').on('click', function(event) {
			event.preventDefault();
			$('#queryerror').fadeOut(500);
			$.ajax({  type: "GET",  url: '/admin/extract-data?checkforerror=1&' + $('#advanced-search-form').serialize(),  success: pages.checkforErrorQuery,  dataType: 'HTML'});
			return false;	
		})
	},
	checkforErrorQuery: function(data) {
		if (data == 'OK') {
			$('#advanced-search-form').submit();
		}
		else {
			$('#queryerror').html(data).fadeIn(500);
		}
	},
	getNewQuery: function() {
		var timer = setInterval(function() {
			$.ajax({  type: "GET",  url: '/admin/extract-data?fromajax=1&' + $('#advanced-search-form').serialize(),  success: pages.updateQuery,  dataType: 'HTML'});
			window.clearInterval(timer);
		}, 100)
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
			else	{
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
