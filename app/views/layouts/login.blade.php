@extends('layouts.scaffold')
{{-- main --}}
@section('main')
		<?php var_dump(Request::url()) ?>
	<div class="jumbotron {{ Request::url() }}" <?php if (strpos(Request::url(),'bistest.trialog.ch') !== false) { echo ' style="background-color: blue; border-color:blue;"'; } ?><?php if (strpos(Request::url(),'bisdev.trialog.ch') !== false) { echo ' style="background-color: green; border-color:green;"'; } ?>>
		<div class="container">
			<div class="lang">
				<?php if (Session::get('locale') == 'de') { ?>
		      	<a class="btn btn-info" href="{{{ URL::to('?lang=en') }}}"><i class="fa fa-flag"></i> {{ trans('general.lang_en') }}</a>
		      <?php } else { ?>
		      	<a class="btn btn-info" href="{{{ URL::to('?lang=de') }}}"><i class="fa fa-flag"></i> {{ trans('general.lang_de') }}</a>
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