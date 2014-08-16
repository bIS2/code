<!-- the footer includes credits and something else... -->
<footer id="footer" class="affix-bottom" data-spy="affix" data-offset-bottom="0" style="background: transparent !important">
	<div class="container">


			<div id="wrap_btn_create_feedback" class="btn-group dropup pull-right" >
			  <button id="btn_create_feedback" type="button" class="btn btn-default dropdown-toggle btn-xs" >
			  	<i class="fa fa-bug"></i>
			  	{{trans('general.feedback')}}
			  </button>
			</div>
		<div class="credit text-center row stats">
  	</div>
	</div>
</footer>

<div id="wrap_create_feedback" class="hide">
	<div id="content_create_feedback"> BIS OK
		@include('feedbacks._create')
	</div>
</div>