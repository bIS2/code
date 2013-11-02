@extends('layouts.scaffold')
{{-- main --}}
@section('main')

<!-- Navbar -->
	@include('navbar-default')
<!-- ./ navbar -->

<!-- Container -->
	<div class="container">
	
	<!-- Notifications -->
		@include('notifications')
	<!-- ./ notifications -->

	<!-- Content -->
		@yield('content')
	<!-- ./ content -->
	
	</div>
<!-- ./ container -->

@stop