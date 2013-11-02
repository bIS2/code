<!-- navbar by default: includes the brand and commun functions -->
<div class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<a class="navbar-brand" href="/" title="Begleitendes Informationssystem">bIS</a>
		<ul class="nav navbar-nav">
		  <li>
		  	<a href="#form-create-group" data-toggle="modal" class='link_bulk_action'><?= trans('holdingssets.create_group')  ?></a>
		  </li>
			<li class="dropdown">
        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?= trans('holdingssets.groups')  ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
        	<li><a href="<?= route('holdingssets.index')  ?>"><?= trans('general.all')  ?></a></li>
        	<li class="divider"></li>
        	<?php foreach ($groups as $group) { ?>
        		<li>
        			<a href="<?= route('holdingssets.index',['group_id' => $group->id ])  ?>"><?= $group->name  ?></a>
        		</li>
        	<?php } ?>
        </ul>
		  </li>
		  <li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?= trans('holdingssets.move_to_group')  ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
        	<?php foreach ($groups as $group) { ?>
        		<li>
        			<a href="<?= action('CabinetsController@postAttach',[$group->id]) ?>" data-remote="true" data-method="put" class="link_bulk_action"><?= $group->name  ?></a>
        		</li>
        	<?php } ?>
        </ul>		  	
		  </li>
		  <li>
		  	<?= link_to( route('comments.create'), trans('holdingssets.ok'));  ?>
		  </li>
		</ul>

		<ul class="nav navbar-nav pull-right">
			@if (Auth::check())
				<li>
					<a data-toggle="dropdown" href="{{{ URL::to('user') }}}"><span class="glyphicon glyphicon-user"></span> {{{ Auth::user()->username }}}</a>
	          	</li>
	        @else
	        	<li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
          	@endif
          	<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" ><span class="glyphicon glyphicon-cog"></span></a>
          		<ul class="dropdown-menu" role="menu">
    				<li><a href="#">my Groups</a></li>
    				<li><a href="#">my Languaje</a></li>
    				<li><a href="#">Something else here</a></li>
    				<li class="divider"></li>
    				<li><a href="{{{ URL::to('user/logout') }}}">{{{ trans('general.logout') }}}</a></li>
  				</ul>
  			</li>
      </ul>
			<!-- ./ nav-collapse -->
	</div>
</div>