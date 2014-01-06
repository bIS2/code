@extends('layouts.scaffold')

{{-- main --}}
@section('main')

@include('navbar-default')
<div id="toolbar">
	@section('toolbar')
	@show
</div>
<!-- Navbar -->
<!-- ./ navbar -->
<!-- Container -->
	<section class="container">
		{{Session::get('info')}}
		<!-- Notifications -->
			@include('notifications')
		<!-- ./ notifications -->

		<!-- Content -->
			@yield('content')
		<!-- ./ content -->

	</section>
<!-- ./ container -->

@stop