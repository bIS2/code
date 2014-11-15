<!-- navbar by default: includes the brand and commun functions -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation"<?php if (strpos(Request::url(),'bistest.trialog.ch') !== false) { echo ' style="background-color: blue; border-color:blue;"'; } ?><?php if (strpos(Request::url(),'bisdev.trialog.ch') !== false) { echo ' style="background-color: blue; border-color:green;"'; } ?>>
	<div class="container">
		<a class="navbar-brand" href="/" title="Begleitendes Informationssystem" data-toogle="tooltip"><?php if (strpos(Request::url(),'bistest.trialog.ch') !== false) { echo 'bIStest'; } elseif(strpos(Request::url(),'bisdev.trialog.ch') !== false) { echo 'bISdev'; } else { echo 'bIS'; } ?></a>
		<ul class="nav navbar-nav">
			@include( 'layouts.items-menu' )
		</ul>

		<ul class="nav navbar-nav pull-right">
			@if (Auth::check())
					<li>
							<div class="navbar-text" data-toggle="tooltip" data-placement="bottom" data-original-title="{{ 'Sublibraries: '.Auth::user()->library->sublibraries}}">
								<span class="text-warning">
									<i class="fa fa-book"></i>
									{{Auth::user()->library->code}}
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