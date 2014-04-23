<!-- navbar by default: includes the brand and commun functions -->
<<<<<<< HEAD
<div class="navbar navbar-default navbar-fixed-top" role="navigation"  style="background: blue">
	<div class="container">
		<a class="navbar-brand" href="/" title="Begleitendes Informationssystem">bIStest</a>
=======
<div class="navbar navbar-default navbar-fixed-top" role="navigation"<?php if (strpos(Request::url(),'bistest.trialog.ch') !== false) { echo ' style="background-color: blue; border-color:blue;"'; } ?>>
	<div class="container">
		<a class="navbar-brand" href="/" title="Begleitendes Informationssystem"><?php if (strpos(Request::url(),'bistest.trialog.ch') !== false) { echo 'bIStest'; } else { echo 'bIS'; } ?></a>
>>>>>>> ed03ca73ac71c1c0d2a6b682906f63ea73be049f
		<ul class="nav navbar-nav">
			<li data-toggle="tooltip" data-placement="bottom" data-original-title="{{ trans('titles.help')}}"> 
				<a href="/help" ><span class="fa fa-question"></span> </a>
			</li>
			<li data-toggle="tooltip" data-placement="bottom" data-original-title="{{ trans('titles.home')}}"> 
				<a href="/" ><span class="fa fa-home"></span> </a>
			</li>

			<!-- admin menu ROLE::SYSADMIN-->
			@if (Auth::user()->hasRole('sysadmin') || Auth::user()->hasRole('superuser') )
				@include( 'layouts.items-menu-admin' )
			@endif
			
			<!-- admin storeman -->
			@if ( Authority::can('work','Holding') )
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
							<div class="navbar-text" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ 'Sublibraries: '.Auth::user()->library->sublibraries}}">
								<span class="text-warning">
									<i class="fa fa-book"></i>
									{{Auth::user()->library->code}} 
									&raquo;
									{{{ Auth::user()->library->name }}}
								</span>
							</div>
					</li>
					<li>
					<a href="{{{ URL::to('user') }}}" >
						<span class="fa fa-user"></span> 
						{{{ Auth::user()->username }}} 
						<small>({{ Auth::user()->roles()->first()->name }})</small>
					</a>
	       </li>
	      <?php if (Session::get('locale') == 'de') { ?>
	      	<li><a href="{{{ URL::to('?lang=en') }}}"><i class="fa fa-globe"></i> {{{ trans('general.lang_en') }}}</a></li>
	      <?php } else { ?>
	      	<li><a href="{{{ URL::to('?lang=de') }}}"><i class="fa fa-globe"></i> {{{ trans('general.lang_de') }}}</a></li>
	      <?php } ?>
	      <li><a href="{{{ URL::to('user/logout') }}}"><span class="fa fa-sign-out"></span>{{{ trans('general.logout') }}}</a></li>
			@else
    		<li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
    	@endif
<!--   		<li {{ (Request::is('help') ? ' class="active"' : '') }}>

  			<a href="{{{ URL::to('help') }}}"><i class="fa fa-question-circle"></i> {{ trans('general.help') }}</a>
  		</li>
 -->    </ul>
			<!-- ./ nav-collapse -->
	</div>
</div>