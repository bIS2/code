<?php setcookie(Auth::user()->username.'_last_route', route('sets.index', Input::except(['xxx'])), time() + (86400 * 30)); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>
			@section('title')
				bIS Project
			@show
		</title>
		<meta name="keywords" content="" />
		<meta name="author" content="" />
		<meta name="description" content="" />

		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
    @stylesheets('public')
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">

		<style type="text/css">
			#hosg ul.hol-sets tr.locked td .fa-times:before {
				content: "\f00d" !important;
			}
			.ocrr_ptrn .fa .fa {
				color: #FFFFFF;
				font-size: 10px;
				margin-left: -14px;
				margin-top: 2px;
				position: absolute;
				z-index: 9;
			}
		</style>
		<style>
		@section('styles')
		@show
		</style>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<!-- <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}"> -->
		<link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.ico') }}}">
	</head>
	<body<?php if (strpos(Request::url(),'bistest.trialog.ch') !== false) { echo ' class="test"'; }?><?php if (strpos(Request::url(),'bisdev.trialog.ch') !== false) { echo ' class="dev"'; }?>>
		<!-- Main -->
			@yield('main')
		<!-- ./ Main -->

		<!-- Footer -->
			@include('footer')
		<!-- ./Footer -->

		<!-- Javascripts
		================================================== -->
    @javascripts('public')
    @javascripts('holdingssets')
    @javascripts('validate')
		@if (App::getLocale()=='de')
			{{HTML::script('assets/js/locale/messages_de.js')}}
		@endif

	</body>

</html>