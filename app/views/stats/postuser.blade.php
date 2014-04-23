<?php $total= Holding::reviseds()->corrects()->inLibrary()->count() ?>

<?php $on_list = Holding::reviseds()->corrects()->inLibrary()->count() ?>
<?php $unlist = Holding::inLibrary()->confirms()->corrects()->count() ?>

<div class="col-xs-1">
	<span class="label label-default" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.on_list') }}">
		<i class="fa  fa-tags"></i> {{$on_list}} [{{ round(($on_list/$total)*100,2) }}%]
	</span>
</div>

<div class="col-xs-1">
	<span class="label label-default" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.unlist') }}">
		<i class="fa  fa-thumbs-up"></i> {{$unlist}} [{{ round(($unlist/$total)*100,2) }}%]
	</span>
</div>
