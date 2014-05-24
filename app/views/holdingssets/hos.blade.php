@foreach ($holdingssets as $holdingsset)
	
<?php 

$need_refresh = 0;
$HOSconfirm = $holdingsset->confirm()->exists();
$HOSannotated = $holdingsset->is_annotated;
$HOSincorrect = $holdingsset->is_incorrect;
// var_dump($no_force_lock);
if (($holdingsset->holdings_number == 1) && (!$HOSconfirm) && (!$HOSincorrect) && (!$HOSannotated) && ($no_force_lock != 1) && ($holdingsset->autoconfirm != 1)) {
	Confirm::create([ 'holdingsset_id' => $holdingsset -> id, 'user_id' => Auth::user()->id ]);
	Holdingsset::find($holdingsset -> id)->update(['state' => 'ok', 'autoconfirm' => 1]);
	holdingsset_recall($holdingsset -> id);
	$HOSconfirm = true;
	$need_refresh = 1;
}

$btn 	= 'btn-default';
$route = ($HOSincorrect) ? 'incorrects' : 'confirms';
$txt 	= ($HOSannotated) ? ' text-warning' : '';
$btn 	= ($HOSconfirm) ? 'btn-success' : $btn;
$btn 	= ($HOSincorrect) ? 'btn-danger' : $btn;
$btn 	.= ($holdingsset->is_unconfirmable) ? ' disabled' : '';
?>
<?php if ($holdingsset->holdings->count()==0) {
	$holdingsset -> delete();
}
else { ?>
<li id="{{ $holdingsset -> id; }}" class="list-group-item<?php if ((isset($group_id) && ($group_id > 0)) { echo ' nogroups'; } ?>">
	<div class="panel-heading row">
		<div class="col-sm-12">
			@if ((isset($group_id)) && ($group_id > 0))
			<span class="move text-muted pull-left" title="{{ trans('holdingssets.drag_and_drop_into_a_grouptab_to_move_this_HOS_to_another_HosGroup'); }}">
				<i class="fa fa-ellipsis-v"></i>
				<i class="fa fa-ellipsis-v"></i>
			</span>
			<a class="trash btn btn-error btn-xs" title="{{ trans('holdingssets.remove_hos_from_this_group'); }}" href="{{ action('HoldingssetsController@putDeleteHosFromGroup',[$holdingsset->id]) }}" data-params="group_id={{ $group_id }}" data-remote="true" data-method="put" data-disable-with="..."><i class="glyphicon glyphicon-trash"></i></a>
			@else
			<span class="move text-muted pull-left" title="{{ trans('holdingssets.drag_and_drop_into_a_grouptab_to_add_this_HOS_to_a_HosGroup'); }}">
				<i class="fa fa-ellipsis-v"></i>
				<i class="fa fa-ellipsis-v"></i>
			</span>
			@endif

			<input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="{{ $holdingsset->id }}" class="hl sel pull-left">

			<a href="#{{ $holdingsset -> sys1; }}{{$holdingsset -> id;}}" id="{{ $holdingsset->id }}" data-parent="#group-xx" title="{{ $holdingsset->clean($holdingsset->f245a) }}" data-toggle="collapse" class="accordion-toggle collapsed " opened="0" anchored="0" ajaxsuccess="0"><div>{{ $holdingsset->sys1 }}</div><i class="fa fa-caret-up"></i></a>

			<span opened="0">
				<?php 
				$holdings_number = $holdingsset -> holdings -> count();
				if ($holdings_number != $holdingsset -> holdings_number ) { $holdingsset->update(['holdings_number' => $holdings_number]); $need_refresh = 1; }
				?>
				<span class="badge"><i class="fa fa-files-o"></i> {{ $holdingsset -> holdings -> count() }}</span>
				{{  $holdingsset->show('f245a', 100) }}
				<?php 
				$groups_number = $holdingsset -> groups -> count();
				if ($groups_number  != $holdingsset -> groups_number ) { $holdingsset->update(['groups_number' => $groups_number]); $need_refresh = 1; }
				?>
				@if ($holdingsset->has('groups') && ($count=$holdingsset->groups->count()>0))
					<span class="badge ingroups" title = "{{ $holdingsset -> showlistgroup }}"
					><i class="fa fa-folder-o"></i> {{ $holdingsset->groups->count() }}</span>
				@endif
				<?php if ($need_refresh == 1) { ?>
					<span class="badge pop-over" style="background: red !important" data-content="<strong>{{ trans('holdingssets.please_refresh_the_page'); }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-refresh"></i></span>
				<?php } ?>
			</span>
			<div class="text-right action-ok pull-left">
				@if (($HOSannotated && !$HOSconfirm) || !$HOSconfirm && !$HOSincorrect) 
					<span class="btn-incorrect pop-over" data-content="<?= trans('holdingssets.check_hos_as_incorrect'); ?>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" data-container="#{{ $holdingsset -> id; }}">
						<a id="holdingsset{{ $holdingsset -> id }}incorrect" set="{{$holdingsset->id}}" href="{{route('incorrects.store',['holdingsset_id' => $holdingsset->id])}}" class="btn btn-ok btn-xs incorrect btn-default" data-remote="true" data-method="post" data-disable-with="...">
							<span id="incorrect{{ $holdingsset -> id }}text" class="fa fa-thumbs-down text-danger"></span>
						</a>		
					</span>
				@endif
				<?php $hideconfirm = ''; ?>
					@if ($HOSincorrect)
					<?php $hideconfirm = 'style="display: none;"'; $txt = ' text-warning'; ?> 
					<a id="holdingsset{{ $holdingsset -> id }}incorrect" set="{{$holdingsset->id}}" href="@if ($btn != ' disabled'){{route(incorrects.'.store',['holdingsset_id' => $holdingsset->id])}}@endif" class="btn btn-ok btn-xs incorrect {{ $btn }} pop-over" data-remote="true" data-method="post" data-disable-with="..." data-content="<?= trans('holdingssets.click_to_remove_incorrect_state'); ?>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" data-container="#{{ $holdingsset -> id; }}">
						<span class="fa fa-thumbs-down"></span>
					</a>	
					@endif      	
				<a id="holdingsset{{ $holdingsset -> id }}confirm" set="{{$holdingsset->id}}" href="@if ($btn != ' disabled'){{route(confirms.'.store',['holdingsset_id' => $holdingsset->id])}}@endif" class="btn btn-ok btn-xs {{ $btn }} pop-over" data-remote="true" data-method="post" data-disable-with="..." {{$hideconfirm}} data-content="@if ($btn == ' disabled'){{ trans('holdingssets.hos_blocked_by_proccess') }} @elseif($btn == 'btn-success') {{ trans('holdingssets.click_to_remove_correct_state') }} @else {{ trans('holdingssets.click_to_confirm_HOS') }} @endif" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" data-container="#{{ $holdingsset -> id; }}">
					<span class="fa fa-thumbs-up {{$txt}}"></span>
				</a>
			</div>
				<a class="newhos btn btn-primary btn-xs pop-over" set="{{$holdingsset->id}}" href="{{ action('HoldingssetsController@putNewHOS',[1]) }}" data-remote="true" data-method="put" data-params="holdingsset_id={{$holdingsset->id}}" data-disable-with="..." data-content="{{ trans('holdingssets.new_hos_from_these_hol'); }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" data-container="#{{ $holdingsset -> id; }}"><i class="fa fa-file-text"></i></a>
		</div>
	</div>
	<div class="panel-collapse collapse container" id="{{$holdingsset -> sys1}}{{$holdingsset -> id}}">
		<div class="panel-body">
		</div>
	</div>
</li>
		<?php } ?>
		@endforeach
