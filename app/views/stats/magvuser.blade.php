<?php $total= Holding::confirms()->inLibrary()->count() ?>

<div class="col-xs-2">
	<div class="progress ">

	  <div class="progress-bar progress-bar-success" style="width: {{ (string) $percent=round((Ok::whereUserId( Auth::user()->id )->count()/$total)*100,2)}}%;">
	    <span class="fa fa-thumbs-up"></span> <span class=""> <?= (string) $percent ?>% </span>
	  </div>

	  <div class="progress-bar progress-bar-danger"style="width: 10%;">
	    <span class="fa fa-tags"></span> <span class=""> <?= (string) $percent ?>% </span>
	  </div>

	</div>
</div>

<div class="col-xs-2">
	<div class="progress ">
	  <div class="progress-bar " role="progressbar" aria-valuenow="{{ $value=Revised::whereUserId( Auth::user()->id )->count() }}" aria-valuemin="0" aria-valuemax="{{$total}}" style="width: {{ (string) $percent=round(($value/$total)*100,2)}}%;">
	    <span class="fa fa-mail-forward"></span> <span class=""> <?= (string) $percent ?>% </span>
	  </div>
	</div>
</div>
