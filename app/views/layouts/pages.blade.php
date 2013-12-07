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
		<meta name="keywords" content="your, awesome, keywords, here" />
		<meta name="author" content="Jon Doe" />
		<meta name="description" content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei." />

		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
        {{ Basset::show('pages.css') }}

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

	<body>
		<!-- Main -->
			@include('navbar-default')
		
		<div class="container">
			<div class="row">
				<div class="col-xs-3">
					@include('layouts.sidebar-stats')
				</div>
				<div class="col-xs-9">
					@yield('main')  <!-- ./ Main -->
				</div>
			</div>
		</div> <!-- ./ container -->

		

		<!-- Footer -->
			@include('footer')
		<!-- ./Footer -->

		<!-- Javascripts
		================================================== -->
        {{ Basset::show('pages.js') }}
	</body>
</html>