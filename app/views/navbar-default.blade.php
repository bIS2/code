<!-- navbar by default: includes the brand and commun functions -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<a class="navbar-brand" href="/" title="Begleitendes Informationssystem">bIS</a>
		<ul class="nav navbar-nav">
			<li> 
				<a href="/" ><span class="fa fa-home"></span> {{ trans('titles.home')}}</a>
			</li>

			<!-- admin menu ROLE::SYSADMIN-->
			@if (Auth::user()->hasRole('sysadmin') || Auth::user()->hasRole('superuser') )
				@include( 'layouts.items-menu-admin' )
			@endif
			
			<!-- admin storeman -->
			@if (Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser') || Auth::user()->hasRole('postuser')) 
				@include( 'layouts.items-menu-storeman' )
			@endif	

			<!-- admin librarian -->
			@if ((Auth::user()->hasRole('bibuser')) || (Auth::user()->hasRole('resuser'))) 		
				@include( 'layouts.items-menu-librarian' )
		  @endif
		  
		</ul>

		<ul class="nav navbar-nav pull-right">
			@if (Auth::check())
	       <li>
	       		<div class="navbar-text" data-toggle="tooltip" data-original-title="{{ 'Sublibraries: '.Auth::user()->library->sublibraries}}">
							{{Auth::user()->library->code}} {{{ Auth::user()->library->name }}}
	       		</div>
	       </li>
					<li>
					<a href="{{{ URL::to('user') }}}" data-toggle="tooltip" data-original-title="{{ 'Sublibraries: '.Auth::user()->library->sublibraries}}">
						<span class="fa fa-user"></span> {{{ Auth::user()->username }}}
					</a>
	       </li>
	      <?php if (Session::get('locale') == 'de') { ?>

	      	<li><a href="{{{ URL::to('?lang=en') }}}"></span>{{{ trans('general.lang_en') }}}</a></li>
	      <?php } else { ?>
	      	<li><a href="{{{ URL::to('?lang=de') }}}"></span>{{{ trans('general.lang_de') }}}</a></li>

	      <?php } ?>
	      <li><a href="{{{ URL::to('user/logout') }}}"><span class="fa fa-sign-out"></span>{{{ trans('general.logout') }}}</a></li>
			@else
    		<li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
    	@endif
<!-- 	    	<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-cog fa-lg"></i></a>
		    	<ul class="dropdown-menu" role="menu">
						<li><a href="#"><i class="fa fa-camera-retro"></i> my Groups</a></li>
						<li><a href="#"><i class="fa fa-camera-retro"></i> my Languaje</a></li>
						<li><a href="#"><i class="fa fa-camera-retro"></i> Something else here</a></li>
						<li class="divider"></li>
						<li><a href="{{{ URL::to('user/logout') }}}">{{{ trans('general.logout') }}}</a></li>
					</ul>
				</li> -->
    </ul>
			<!-- ./ nav-collapse -->
	</div>
</div>