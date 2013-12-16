
<?php $total= Holdingsset::count() ?>

<?php $grouped = Holdingsset::has('groups')->count() ?>
<?php $ungrouped = Holdingsset::whereNotIn('id',function($query){ $query->select('holdingsset_id')->from('group_holdingsset'); })->count() ?>

<?php $confirmed = Holdingsset::confirmed()->count() ?>
<?php $confirmed_owners = Holdingsset::owners()->confirmed()->count() ?>
<?php $confirmed_aux = Holdingsset::auxiliars()->confirmed()->count() ?>

<?php $annotated = Holdingsset::annotated()->count() ?>

<div class="col-xs-1">
	<span class="label label-warning" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.confirmed_aux') }}">
		<i class="fa  fa-thumbs-up"></i> {{$confirmed_aux}} [{{ round(($confirmed_aux/$confirmed)*100,2) }}%]
	</span>
</div>

<div class="col-xs-1">
	<span class="label label-danger" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.confirmed_owner') }}">
		<i class="fa  fa-thumbs-up"></i> {{$confirmed_owners}} [{{ round(($confirmed_owners/$confirmed)*100,2) }}%]
	</span>
</div>

<div class="col-xs-1">
	<span class="label label-default" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.grouped') }}">
		<i class="fa  fa-th"></i> {{$grouped}} [{{ round(($grouped/$total)*100,2)  }}%]
	</span>
</div>

<div class="col-xs-1" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.ungrouped') }}">
	<span class="label label-default"><i class="fa  fa-unlink"></i> {{$ungrouped}} [{{ round(($ungrouped/$total)*100,2)  }}%]</span>
</div>

<div class="col-xs-1" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.annotated') }}">
	<span class="label label-default"><i class="fa  fa-tags"></i> {{$annotated}} [{{ round(($annotated/$total)*100,2)  }}%]</span>
</div>
 