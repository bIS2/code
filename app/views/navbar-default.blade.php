<!-- navbar by default: includes the brand and commun functions -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<a class="navbar-brand" href="/" title="Begleitendes Informationssystem">bIS</a>
		<ul class="nav navbar-nav">

			<!-- admin menu ROLE::SYSADMIN-->
			@if (Auth::user()->hasRole('sysadmin'))
				@include( 'layouts.items-menu-admin' )
			@endif
			
			<!-- admin storeman -->
			@if (Auth::user()->hasRole('maguser')) 
				@include( 'layouts.items-menu-storeman' )
			@endif	

			<!-- admin librarian -->
			@if (Auth::user()->hasRole('bibuser')) 		
				@include( 'layouts.items-menu-librarian' )
		  @endif
		  
		</ul>

		<ul class="nav navbar-nav pull-right">
			@if (Auth::check())
				<li>
					<a data-toggle="dropdown" href="{{{ URL::to('user') }}}"><span class="glyphicon glyphicon-user"></span> {{{ Auth::user()->username }}}</a>
	          	</li>
	        @else
	        	<li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
          	@endif
          	<li><a href="#" class="dropdown-toggle" data-toggle="dropdown" ><i class="fa fa-cog fa-lg"></i></a>
          		<ul class="dropdown-menu" role="menu">
    				<li><a href="#"><i class="fa fa-camera-retro"></i> my Groups</a></li>
    				<li><a href="#"><i class="fa fa-camera-retro"></i> my Languaje</a></li>
    				<li><a href="#"><i class="fa fa-camera-retro"></i> Something else here</a></li>
    				<li class="divider"></li>
    				<li><a href="{{{ URL::to('user/logout') }}}">{{{ trans('general.logout') }}}</a></li>
  				</ul>
  			</li>
      </ul>
			<!-- ./ nav-collapse -->
	</div>
</div>