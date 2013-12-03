<li class="{{ (!Request::is('sets*')) ?: 'active'}}" > 
	<a href="{{ route('sets.index') }}" >
		<strong><span class="fa fa-file-text"></span> {{ trans('holdingssets.title')}}</strong>
	</a>
</li>
<li class="{{ (Request::is('groups*')) ? 'active' : '' }}">
	<a href="{{ route('groups.index') }}" ><strong><span class="fa fa-list"></span> {{ trans('holdingssets.groups')}}</strong> </a>
</li>
