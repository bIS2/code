<?php $activehos = (Request::is('sets*')) ? 'active' : '' ?>
<?php $activegroups = (Request::is('groups*')) ? 'active' : '' ?>

<li class="{{ $activehos }}" > 
  {{ link_to( route('sets.index'), trans('holdingssets.title') ) }}
</li>
<li class="{{ $activegroups }}" > 
  {{ link_to( route('groups.index'), trans('general.groups') ) }}
</li>
<li {{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}">
	<span class="fa fa-users"></span>{{{ trans('titles.users') }}}</a>
</li>
