@extends('layouts.scaffold')
{{-- main --}}
@section('main')
		
	<div class="jumbotron">
		<div class="container">
			<h1>bIS</h1>
			<big>Begleitendes Informationssystem</big></div>
    </div>

	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
					
				<!-- Notifications -->
				@include('notifications')
				<!-- ./ notifications -->

				<!-- Content -->
				@yield('content')
				<!-- ./ content -->
				
			</div>
		</div>
	</div>

@stop