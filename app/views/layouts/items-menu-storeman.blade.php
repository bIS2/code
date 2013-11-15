<li class="{{ (!Request::is('holdings*')) ?: 'active'}}" > 
	<a href="{{ route('holdings.index') }}" >
		<strong><span class="fa fa-file-text"></span> {{ trans('holdings.title')}}</strong>
	</a>
</li>
<li class="{{ (Request::is('lists*')) ? 'active' : '' }}">
	<a href="{{ route('lists.index') }}" ><strong><span class="fa fa-list"></span> {{ trans('titles.lists')}}</strong> </a>
</li>
