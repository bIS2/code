<li> 
  {{ link_to( '/', trans('titles.home') ) }}
</li>
<li class="{{ (!Request::is('holdings*')) ?: 'active'}}" > 
	{{ link_to( route('holdings.index') ) }}
</li>
<li>
	<a href="#" data-toggle="modal" data-target="#form-create-list" class='link_bulk_action'>
		{{ trans('holdings.create_list') }} 
	</a>
</li>
<li><a href="#" data-toggle="modal" data-target="#myModal">{{ trans('title.list')}}</a></li>
