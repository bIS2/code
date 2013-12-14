
<?php $total= Holdingsset::count() ?>

<?php $grouped = Holdingsset::whereIn('id',function($query){ $query->select('holdingsset_id')->from('group_holdingsset'); })->count() ?>
<?php $ungrouped = Holdingsset::whereNotIn('id',function($query){ $query->select('holdingsset_id')->from('group_holdingsset'); })->count() ?>
<?php $confirmed = Holdingsset::whereIn('id',function($query){ $query->select('holdingsset_id')->from('confirms'); })->count() ?>





<div class="col-xs-2">
	<div class="progress" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.group') }} <?= (string) $percent=round(($holdingsset_in_group/$total)*100,2) ?>%">
	  <div class="progress-bar" role="progressbar" aria-valuenow="{{ $value=Revised::whereUserId( Auth::user()->id )->count() }}" aria-valuemin="0" aria-valuemax="{{$total}}" style="width: {{ (string) $percent }}%;"></div>
	</div>
</div>


<div class="col-xs-2">
	<div class="progress" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.ungroup') }} <?= (string) $percent=round(($holdingsset_not_in_group/$total)*100,2) ?>%">
	  <div class="progress-bar " role="progressbar" aria-valuenow="{{ $value=Revised::whereUserId( Auth::user()->id )->count() }}" aria-valuemin="0" aria-valuemax="{{$total}}" style="width: {{ (string) $percent }}%;"></div>
	</div>
</div>

<div class="col-xs-2">
	<div class="progress" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.confirmed') }} <?= (string) $percent=round(($confirmed/$total)*100,2) ?>%">
	  <div class="progress-bar " role="progressbar" aria-valuenow="{{ $value=Revised::whereUserId( Auth::user()->id )->count() }}" aria-valuemin="0" aria-valuemax="{{$total}}" style="width: {{ (string) $percent }}%;"></div>
	</div>
</div>

<div class="col-xs-4">
	<span class="label label-info" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.confirmed') }}">
		<i class="fa  fa-thumbs-up"></i> {{$confirmed}} [{{ round(($confirmed/$total)*100,2) }}%]
	</span>

	<span class="label label-info" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('stats.grouped') }}">
		<i class="fa  fa-th"></i> {{$grouped}} [{{ round(($grouped/$total)*100,2)  }}%]
	</span>

	<span class="label label-info"><i class="fa  fa-unlink"></i> {{$ungrouped}} [{{ round(($ungrouped/$total)*100,2)  }}%]</span>
</div>
 