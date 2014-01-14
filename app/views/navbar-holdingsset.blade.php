
<div class="navbar navbar-default navbar-fixed-top">
	 <div class="container">
	 	<a class="navbar-brand" href="#">bIS</a>
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
        			<a href="<?= action('GroupsController@postAttach',[$group->id]) ?>" data-remote="true" data-method="put" class="link_bulk_action">zzz<?= $group->name  ?></a>
        		</li>
        	<?php } ?>
        </ul>		  	
		  </li>
		</ul>
		<form method="" target="" class="navbar-form pull-right">
			<div class="input-group">
		      <input type="search" name="q" id="q" class="form-control" placeholder="<?= trans('general.type_criteria') ?>" >
		      <span class="input-group-btn">
		        <button class="btn btn-default btn-primary" type="submit" ?><?= trans('general.search') ?></button>
		      </span>
		    </div>
		 </form>		
		 <?php holdingsset::deliveries(); ?>
     <ul class="nav navbar-nav pull-right">
          @if (Auth::check())

	          <li>
	          	<a data-toggle="dropdown" class="dropdown-toggle" href="#">{{{ Auth::user()->username }}} <b class="caret"></b></a>
	          	<ul class="dropdown-menu">
	          		<li><a href="{{{ URL::to('user') }}}" >{{{ trans('general.profile') }}}</a></li>
			          @if (Auth::user()->hasRole('speiuser'))
				          <li><a href="{{{ URL::to('admin') }}}">{{{ trans('general.config') }}}</a></li>
				        @endif
				        <li><a href="{{{ URL::to('user/logout') }}}">{{{ trans('general.logout') }}}</a></li>
	          	</ul>
	          </li>

	          @else

		          <li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
		          <li {{ (Request::is('user/register') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/create') }}}">{{{ Lang::get('site.sign_up') }}}</a></li>

          @endif
      </ul>
			<!-- ./ nav-collapse -->
	</div>
</div>