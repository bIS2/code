<?php $activehos = (Request::is('sets*')) ? 'active' : '' ?>
<?php $activegroups = (Request::is('groups*')) ? 'active' : '' ?>

<li> 
  {{ link_to( '/', trans('titles.home') ) }}
</li>
<li class="{{ $activehos }}" > 
  {{ link_to( route('sets.index'), trans('holdingssets.title') ) }}
</li>
<li class="{{ $activegroups }}" > 
  {{ link_to( route('groups.index'), trans('general.groups') ) }}
</li>