// Contains all the logic on the client

$(function(){
  pages.init();
})

var pages = {
	init: function() {
		$('#filterContainer.extract .btn.btn-xs').on('mouseup', function() {
			var timer = setInterval(function() {
				$.ajax({  type: "GET",  url: '/admin/extract-data?fromajax=1&' + $('#advanced-search-form').serialize(),  success: pages.updateQuery,  dataType: 'HTML'});
				window.clearInterval(timer);
			}, 100)			
		})
		pages.setFilterActions();
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
