<!-- the footer includes credits and something else... -->
<footer id="footer">
	<div class="container">


			<div id="wrap_btn_create_feedback" class="btn-group dropup pull-right" >
			  <button id="btn_create_feedback" type="button" class="btn btn-default dropdown-toggle btn-xs" >
			  	<i class="fa fa-bug"></i>
			  	{{trans('general.feedback')}}
			  </button>
			</div>
		<div class="credit text-center row stats">
			@include('stats.index')
  	</div>
  	<div class="row">
  		<div class="col-sm-12">
  			{{ $git_log }}
  		</div>
  	</div>
	</div>
</footer>

<div id="wrap_create_feedback" class="hide">
	<div id="content_create_feedback"> BIS OK
		@include('feedbacks._create')
	</div>
</div>