@extends('layouts.scaffold')
{{-- main --}}
@section('main')
		
	<div class="jumbotron">
		<div class="container">
			<div class="pull-right">
				<?php if (Session::get('locale') == 'de') { ?>
		      	<a class="btn btn-info" href="{{{ URL::to('?lang=en') }}}">{{{ trans('general.lang_en') }}}</a>
		      <?php } else { ?>
		      	<a class="btn btn-info" href="{{{ URL::to('?lang=de') }}}">{{{ trans('general.lang_de') }}}</a>
		      <?php } ?>
		    </div>
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