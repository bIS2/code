
<div class="navbar navbar-default navbar-fixed-top">
	 <div class="container">
	 	<a class="navbar-brand" href="#">BIS</a>
	  <ul class="nav navbar-nav">
		  <li>
			  	<a href="#form-create-cabinet" data-toggle="modal" class='link_bulk_action'><?= trans('holdings.create_cabinet')  ?></a>
		  </li>
			<li class="dropdown">
        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?= trans('holdings.cabinets')  ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
        	<li><a href="<?= route('holdings.index')  ?>"><?= trans('general.all')  ?></a></li>
        	<li class="divider"></li>
        	<?php foreach ($cabinets as $cabinet) { ?>
        		<li>
        			<a href="<?= route('holdings.index',['cabinet_id' => $cabinet->id ])  ?>"><?= $cabinet->name  ?></a>
        		</li>
        	<?php } ?>
        </ul>
		  </li>
		  <li>
        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><?= trans('holdings.move_to_cabinet')  ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
        	<?php foreach ($cabinets as $cabinet) { ?>
        		<li>
        			<a href="<?= action('CabinetsController@postAttach',[$cabinet->id]) ?>" data-remote="true" data-method="put" class="link_bulk_action"><?= $cabinet->name  ?></a>
        		</li>
        	<?php } ?>
        </ul>		  	
		  </li>
		  <li>
		  	<?= link_to( route('comments.create'), trans('holdings.ok2'));  ?>
		  </li>
		  <li>
		  	<?= link_to( route('comments.create'), trans('holdings.comment'));  ?>
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