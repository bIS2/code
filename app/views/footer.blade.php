<!-- the footer includes credits and something else... -->
<footer id="footer">
	<div class="container">


			<div id="wrap_btn_create_feedback" class="btn-group dropup pull-right" >
			  <button id="btn_create_feedback" type="button" class="btn btn-default dropdown-toggle btn-xs" >
			  	<i class="fa fa-question-circle"></i>
			  	{{trans('general.feedback')}}
			  </button>
			</div>
		<div class="credit text-center row">
			@if (!Auth::guest())

				@if (Auth::user()->hasRole('bibuser'))
					@include('stats.bibuser')
				@endif

				@if (Auth::user()->hasRole('magvuser'))
					@include('stats.magvuser')
				@endif

			@endif

	   </div>
	</div>
</footer>

<div id="wrap_create_feedback" class="hide">
	<div id="content_create_feedback">
		@include('feedbacks._create')
	</div>
</div>

