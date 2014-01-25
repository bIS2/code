<?php //var_dump($holdingssets); ?>
@foreach ($holdingssets as $holdingsset)
	
<?php 
// var_dump('Toy aqui');
$hosbyone = Holdingsset::pendings()->whereHoldingsNumber(1)->select('id')->lists('id');
foreach ($hosbyone as $onehos) {
	
}
$need_refresh = 0;
$HOSconfirm = $holdingsset->confirm()->exists();
$HOSannotated = $holdingsset->is_annotated;
$HOSincorrect = $holdingsset->is_incorrect;
if (($holdingsset->holdings_number == 1) && (!$HOSconfirm) && (!$HOSincorrect)) {
	Confirm::create([ 'holdingsset_id' => $holdingsset -> id, 'user_id' => Auth::user()->id ]);
	$HOSconfirm = true;
	$need_refresh = 1;
}
$btn 	= 'btn-default';
$route = ($HOSincorrect) ? 'incorrects' : 'confirms';
$txt 	= ($HOSannotated) ? ' text-warning' : '';
$btn 	= ($HOSconfirm) ? 'btn-success disabled' : $btn;
$btn 	= ($holdingsset->is_unconfirmable) ? 'btn-success' : $btn;
$btn 	= ($HOSincorrect) ? 'btn-danger' : $btn;
?>
<?php if ($holdingsset->holdings->count()==0) {
	$holdingsset -> delete();
}
else { ?>
<li id="{{ $holdingsset -> id; }}" class="list-group-item">
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

			<a href="#{{ $holdingsset -> sys1; }}{{$holdingsset -> id;}}" id="{{ $holdingsset->id }}" data-parent="#group-xx" title="{{ $holdingsset->f245a ;}}" data-toggle="collapse" class="accordion-toggle collapsed " opened="0" anchored="0" ajaxsuccess="0"><div>{{ $holdingsset->sys1 }}</div><i class="fa fa-caret-up"></i></a>

			<span opened="0">
				<?php 
				$holdings_number = $holdingsset -> holdings -> count();
				if ($holdings_number != $holdingsset -> holdings_number ) { $holdingsset->update(['holdings_number' => $holdings_number]); $need_refresh = 1; }
				?>
				<span class="badge"><i class="fa fa-files-o"></i> {{ $holdingsset -> holdings -> count() }}</span>
				<?php if ($need_refresh == 1) { ?>
				<span class="badge pop-over" style="background: red !important" data-content="<strong>{{ trans('holdingssets.please_refresh_the_page'); }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-refresh"></i></span>
				<?php } ?>

				<?php 
				$str = htmlspecialchars($holdingsset->f245a);
				if (strlen($str) > 100) { ?>
				<span class="pop-over" data-content="<strong>{{ $str }}</strong>" data-placement="top" data-toggle="popover" data-html="true" class="btn btn-default" type="button" data-trigger="hover">{{ truncate($str, 100) }}</span>
				<?php }
				else {
					echo $str;
				}
				?>
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
					<div class="text-right action-ok pull-left">
						@if (($HOSannotated && !$HOSconfirm) || !$HOSconfirm && !$HOSincorrect) 
							<a id="holdingsset{{ $holdingsset -> id }}incorrect" set="{{$holdingsset->id}}" href="{{route('incorrects.store',['holdingsset_id' => $holdingsset->id])}}" class="btn btn-ok btn-xs incorrect btn-default" data-remote="true" data-method="post" data-disable-with="..." title="{{ trans('holdingssets.incorrect_HOS') }}">
								<span id="incorrect{{ $holdingsset -> id }}text" class="fa fa-thumbs-down"></span>
							</a>		
						@endif
						<?php $hideconfirm = ''; ?>
							@if ($HOSincorrect)
							<?php $hideconfirm = 'style="display: none;"'; $txt = ' text-warning'; ?> 
							<a id="holdingsset{{ $holdingsset -> id }}incorrect" set="{{$holdingsset->id}}" href="@if ($btn != 'btn-success disabled'){{route(incorrects.'.store',['holdingsset_id' => $holdingsset->id])}}@endif" class="btn btn-ok btn-xs incorrect {{ $btn }}" data-remote="true" data-method="post" data-disable-with="..." title="">
								<span class="fa fa-thumbs-down"></span>
							</a>	
							@endif      	
						<a id="holdingsset{{ $holdingsset -> id }}confirm" set="{{$holdingsset->id}}" href="@if ($btn != 'btn-success disabled'){{route(confirms.'.store',['holdingsset_id' => $holdingsset->id])}}@endif" class="btn btn-ok btn-xs {{ $btn }}" data-remote="true" data-method="post" data-disable-with="..." title="@if ($btn != 'btn-success disabled'){{ trans('holdingssets.confirm_ok_HOS') }} @else {{ trans('holdingssets.confirmed_HOS') }}@endif" {{$hideconfirm}}>
							<span class="fa fa-thumbs-up {{$txt}}"></span>
						</a>
					</div>
				</div>

				<a class="newhos btn btn-primary btn-xs pop-over" set="{{$holdingsset->id}}" href="{{ action('HoldingssetsController@putNewHOS',[1]) }}" data-remote="true" data-method="put" data-params="holdingsset_id={{$holdingsset->id}}" data-disable-with="..." data-content="{{ trans('holdingssets.new_hos_from_these_hol'); }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-file-text"></i></a>
			</div>	
			<div class="panel-collapse collapse container" id="{{$holdingsset -> sys1}}{{$holdingsset -> id}}">
				<div class="panel-body">
				</div>
			</div>
		</li>
		<?php } ?>
		@endforeach