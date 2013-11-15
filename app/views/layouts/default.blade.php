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
	<div class="container">
		{{Session::get('info')}}
		<!-- Notifications -->
			@include('notifications')
		<!-- ./ notifications -->

		<!-- Content -->
			@yield('content')
		<!-- ./ content -->
	</div>
<!-- ./ container -->

@stop