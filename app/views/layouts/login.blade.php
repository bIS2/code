@extends('layouts.scaffold')
{{-- main --}}
@section('main')
		
	<div class="jumbotron">
		<div class="container">
			<h1>bIS</h1>
			<big>Begleitendes Informationssystem</big>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui.</p>
		</div>
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