<?php $total= Holding::confirms()->inLibrary()->count() ?>

<?php $annotated = Holding::inLibrary()->confirms()->annotated()->count() ?>
<?php $corrects = Holding::inLibrary()->confirms()->corrects()->count() ?>
<?php $to_post = Holding::inLibrary()->reviseds()->corrects()->count() ?>

<div class="col-xs-1">
	<span class="label label-default" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.annotated') }}">
		<i class="fa  fa-tags"></i> {{$annotated}} [{{ round(($annotated/$total)*100,2) }}%]
	</span>
</div>

<div class="col-xs-1">
	<span class="label label-default" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.corrects') }}">
		<i class="fa  fa-thumbs-up"></i> {{$corrects}} [{{ round(($corrects/$total)*100,2) }}%]
	</span>
</div>

<div class="col-xs-1">
	<span class="label label-default" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.to_post') }}">
		<i class="fa  fa-mail-forward"></i> {{$to_post}} [{{ round(($to_post/$total)*100,2) }}%]
	</span>
</div>

