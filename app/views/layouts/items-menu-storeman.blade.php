<li class="{{ (!Request::is('holdings*')) ?: 'active'}}" > 
	<a href="{{ route('holdings.index') }}" ><span class="fa fa-file-text"></span> {{ trans('holdings.title')}}</a>
</li>
<li class="{{ (Request::is('lists*')) ? 'active' : '' }}">
	<a href="{{ route('lists.index') }}" ><span class="fa fa-list"></span> {{ trans('titles.lists')}}</a>
</li>
