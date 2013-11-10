<?php $active = (Request::is('holdingssets*')) ? 'active' : '' ?>

<li> 
  {{ link_to( '/', trans('titles.home') ) }}
</li>
<li class="{{ $active }}" > 
  {{ link_to( route('sets.index'), trans('sets.title') ) }}
</li>

@if (Request::is('holdingssets*')) 

  <li>
    <a href="#form-create-group" data-toggle="modal" class='link_bulk_action'><?= trans('sets.create_group')  ?></a>
  </li>		  
  <li>
  <a href="<?= route('sets.index')  ?>"><?= trans('general.all')  ?> Holdings Sets</a>
  </li>
  <li class="dropdown">
    <a data-toggle="modal" data-target="#myModal" href="#"><?= trans('sets.groups')  ?></a>
  </li>
  <li>

  </li>

@endif