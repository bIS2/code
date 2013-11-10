<li> 
  {{ link_to( '/', trans('titles.home') ) }}
</li>
<li class="{{ (!Request::is('holdings*')) ?: 'active'}}" > 
	{{ link_to( route('holdings.index'), trans('holdings.title') ) }}
</li>
<li class="{{ (Request::is('lists*')) ? 'active' : '' }}">
	<a href="{{ route('lists.index') }}" >{{ trans('titles.lists')}}</a>
</li>
