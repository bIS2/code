<?php $stats = Stat::first() ?>

<?php $total= $stats->sets_count ?>

<?php $grouped = Holdingsset::has('groups')->count() ?>
<?php $ungrouped = Holdingsset::whereNotIn('id',function($query){ $query->select('holdingsset_id')->from('group_holdingsset'); })->count() ?>

<?php $confirmed = $stats->sets_confirmed ?>
<?php $confirmed_owners = $stats->sets_confirmed_owner //Holdingsset::owners()->confirmed()->count() ?>
<?php $confirmed_aux = $stats->sets_confirmed_auxiliar //Holdingsset::auxiliars()->confirmed()->count() ?>

<?php $annotated = $stats->sets_annotated //Holdingsset::annotated()->count() ?>

<div class="col-xs-1">
	<span class="label label-success" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.confirmed') }}">
		<i class="fa  fa-thumbs-up"></i> {{$confirmed}}/{{$total}} [{{ round(($confirmed_aux/$confirmed)*100,2) }}%]
	</span>
</div>

<div class="col-xs-1">
	<span class="label label-success" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.confirmed_aux') }}">
		<i class="fa  fa-thumbs-up text-warning"></i> {{$confirmed_aux}}/{{$confirmed}} [{{ round(($confirmed_aux/$confirmed)*100,2) }}%]
	</span>
</div>

<div class="col-xs-1">
	<span class="label label-success" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.confirmed_owner') }}">
		<i class="fa  fa-thumbs-up text-danger"></i> {{$confirmed_owners}}/{{$confirmed}} [{{ round(($confirmed_owners/$confirmed)*100,2) }}%]
	</span>
</div>

<div class="col-xs-1" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.annotated') }}">
	<span class="label label-warning"><i class="fa  fa-tags"></i> {{$annotated}} [{{ round(($annotated/$total)*100,2)  }}%]</span>
</div>

<div class="col-xs-1">
	<span class="label label-primary" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.grouped') }}">
		<i class="fa  fa-th"></i> {{$grouped}} [{{ round(($grouped/$total)*100,2)  }}%]
	</span>
</div>

<div class="col-xs-1" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.ungrouped') }}">
	<span class="label label-default"><i class="fa  fa-unlink"></i> {{$ungrouped}} [{{ round(($ungrouped/$total)*100,2)  }}%]</span>
</div>

 