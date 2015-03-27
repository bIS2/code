<?php
$total = $holdingssets -> getTotal();
$init = $holdingssets -> getTo();

if ($restarcookie) {
	Session::put(Auth::user()->username.'_groups_to_show', ';');
	setcookie(Auth::user()->username.'_groups_to_show', ';', time() + (86400 * 30));
}

$groupsids = '';
$groupsids = Session::get(Auth::user()->username.'_groups_to_show');


if (isset($group_id))  {
	$tempids = [];
	$tempids = explode(';', $groupsids);
	if (!in_array($group_id, $tempids)) {
		$groupsids = $group_id.';'.$groupsids;
		setcookie(Auth::user()->username.'_groups_to_show', $groupsids, time() + (86400 * 30));
		Session::put(Auth::user()->username.'_groups_to_show', $groupsids);
	}
}
$groupsids = explode(';', $groupsids);

// CURRENT PROFILE
$cprofile = $_COOKIE[Auth::user()->username.'_current_profile'];
$cprofile = (($cprofile == '') || ($cprofile == null) || ($cprofile)) ? Session::get(Auth::user()->username.'_current_profile') : $cprofile ;

// ALL FIELDS
$allfields = $_COOKIE[Auth::user()->username.'_'.$cprofile.'_fields'];	
$allfields = (($allfields == '') || ($allfields == null) || ($allfields)) ? explode(';', Session::get(Auth::user()->username.'_'.$cprofile.'_fields')) : explode(';', $cpaf);

$defaultsize = 100;

$sizeofields = $_COOKIE[Auth::user()->username.'_'.$cprofile.'_size_of_fields'];

$sizeofields = explode($cprofile);

$sizeofields = explode(';',$sizeofields);


?>
<ul id="groups-tabs" class="nav nav-tabs">
	<li <?php if (!($group_id > 0)) { echo 'class="active"'; } ?>>
		<a <?php if (($group_id > 0)) { echo 'href="'.route('sets.index', Input::except(['group_id'])).'"'; } ?>>
			<?= trans('holdingssets.all') ?> <?= trans('holdingssets.title') ?>
		</a>
	</li>
	<li>
		<a href="#form-create-group" data-toggle="modal" class='btn btn-default disabled link_bulk_action'  style="padding: 6px 11px;">
			<i class="fa fa-folder-o" style="font-size: 26px; padding: 0px;"></i>
			<span class="fa fa-plus-circle" style="position: absolute; font-size: 12px; top: 14px; left: 18px;"></span>
		</a>
	</li>
	<?php foreach ($groups as $group) {
		if (in_array($group -> id, $groupsids)) { 
			?>
			<li id="group{{ $group->id }}" <?php if ($group_id == $group -> id) { echo 'class="active"'; } else { echo 'class="accepthos"'; } ?>>
				<a <?php if ($group_id != $group -> id) { echo 'href="'.route('sets.index',Input::except(['group_id']) + ['group_id' => $group->id ]).'"'; } ?> class="pull-left">{{ $group->name }}/{{ $group->user->username }}/<?php $date = explode(' ', $group->created_at); echo $date[0]; ?> <span class="badge">{{ $group->holdingssets -> count() }} </span></a>
				<?php if ($group_id != $group -> id) { ?>
				<!-- <a href="{{ action('HoldingssetsController@putDelTabgroup',[$group->id]) }}" class="btn btn-ok btn-xs" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><button aria-hidden="true" data-dismiss="modal" class="close pull-left" type="button">×</button></a> -->
				<a href="{{ action('HoldingssetsController@putDelTabgroup',[$group->id]) }}" class="close" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-eye-slash"></i></a>
				<?php } ?>
			</li>
			<?php }
		} ?>
	</ul>

	<?php if (count($holdingssets) > 0) { ?>
	<form method="post" action="{{ route('sets.index', Input::except(['noexists'])) }}" id="profiles-form" ajax-post="{{ route('sets.index') }}?onlyprofiles=1">
		<input type="hidden" value="0" name="reload" id="reload">
		<input type="hidden" id="urltoredirect" name="urltoredirect" value="<?= route('sets.index', Input::except(['noexists'])); ?>">
		<div id="hos_actions_and_filters" class="clearfix">
			<a id="open-all-hos" class="btn btn-md btn-danger pull-left" data-toggle="tooltip" title="<?= trans('holdingssets.open_all_hos'); ?>" data-container="body" ><i class="fa fa-folder-open-o"></i></a>
		<!-- 	<a id="join-the-hos" class="btn btn-md btn-warning pull-left disabled" href="{{ action('HoldingssetsController@putJoinHOS') }}" data-remote="true" data-method="put" data-params="" data-disable-with="..." data-content="{{ trans('holdingssets.join_HOS'); }}"><i class="fa fa-magnet"></i></a> -->
			<div class="pull-left select-all">
				<label>
					<input id="select-all" class="select-all" name="select-all" type="checkbox" value="1" data-target="#hos-targets">
					<p class="btn btn-xs btn-primary pop-over"data-content="{{ trans('holdingssets.select_all_hos') }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-check"></i></p>
				</label>
			</div>
			<div id="hos-sorting" class="pull-left text-center text-success">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-xs pull-left disabled">
						<strong>{{ trans('holdingssets.order_hos_by') }}</strong>
					</label>
					<label data-toggle="tooltip" title="<?= trans('holdingssets.order_by_field_245a'); ?>" data-container="body" class="btn btn-default btn-xs{{ ((Session::get(Auth::user()->username.'_sortinghos_by') == null) || (Session::get(Auth::user()->username.'_sortinghos_by') == f245a)) ? ' active' : '' }}">
						<input type="radio"{{ ((Session::get(Auth::user()->username.'_sortinghos_by') == null) || (Session::get(Auth::user()->username.'_sortinghos_by') == f245a)) ? ' checked = checked' : '' }} name="sortinghos_by" value="f245a" id="option1"> 245a
					</label>
					<label data-toggle="tooltip" title="<?= trans('holdingssets.order_by_number_of_hols_inside_hos'); ?>" data-container="body"  class="btn btn-default btn-xs{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'holdings_number') ? ' active' : '' }}" name="sortinghos_by">
						<input type="radio"{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'holdings_number') ? ' checked = checked' : '' }} name="sortinghos_by" value="holdings_number" id="option2"> hols#
					</label>
					<label data-toggle="tooltip" title="<?= trans('holdingssets.order_by_groups_numbers_that_belong_ecah_hos'); ?>" data-container="body" class="btn btn-default btn-xs{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'groups_number') ? ' active' : '' }}" name="sortinghos_by">
						<input type="radio"{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'groups_number') ? ' checked = checked' : '' }} name="sortinghos_by" value="groups_number" id="option3"> HosG#
					</label>
					<label data-toggle="tooltip" title="<?= trans('holdingssets.order_by_field_008x'); ?>" data-container="body"  class="btn btn-default btn-xs{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'f008x') ? ' active' : '' }}" name="sortinghos_by">
						<input type="radio"{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'f008x') ? ' checked = checked' : '' }} name="sortinghos_by" value="f008x" id="option4"> 008x
					</label>
				</div>
				<div class="btn-group" data-toggle="buttons">
					<label data-toggle="tooltip" title="<?= trans('holdingssets.order_asc_using_order_criteria'); ?>" data-container="body"  class="btn btn-default btn-xs{{ ((Session::get(Auth::user()->username.'_sortinghos') == null) || (Session::get(Auth::user()->username.'_sortinghos') == trans('general.asc'))) ? ' active' : '' }}">
						<input type="radio" {{ ((Session::get(Auth::user()->username.'_sortinghos') == null) || (Session::get(Auth::user()->username.'_sortinghos') == 'ASC')) ? ' checked = checked' : '' }} name="sortinghos" value="{{ trans('general.asc') }}" id="option1"><i class="fa fa-sort-amount-asc"></i> {{ trans('general.asc') }}
					</label>
					<label data-toggle="tooltip" title="<?= trans('holdingssets.order_desc_using_order_criteria'); ?>" data-container="body"  class="btn btn-default btn-xs{{ (Session::get(Auth::user()->username.'_sortinghos') == trans('general.desc')) ? ' active' : '' }}">
						<input type="radio" {{ (Session::get(Auth::user()->username.'_sortinghos') == trans('general.desc')) ? ' checked = checked' : '' }} name="sortinghos" value="{{ trans('general.desc') }}" id="option2"><i class="fa fa-sort-amount-desc"></i> {{ trans('general.desc') }}
					</label>
				</div>
				<div class="btn-group">
					<button type="submit" value="{{ trans('general.sort') }}" reload="1" class="btn btn-xs btn-primary"> {{ trans('general.sort') }} </button>
				</div>
			</div>
			<div id="hos-pagination" class="pull-right text-center text-success">
				<p>{{ trans('holdingssets.showing') }} </p>
				<div id="current_quantity" class="active">
					<div style="width: 100%">{{ $init }}</div>
				</div> 
				<p>{{ trans('holdingssets.of') }}</p>
				<div id="total_quantity">{{ $total }}</div>
				<a id="next-page" class="btn btn-xs btn-info"  @if ($init == $total) {{ 'style="visibility : hidden;"' }} @endif data-toggle="tooltip" title="<?= trans('holdingssets.more_holdingssets'); ?>" data-container="body" ><i class="fa fa-forward"></i></a>
			</div>

			<div id="profiles-container">
				<div id="profiles" class="pull-left" style="position: relative">
					<label class="btn btn-xs pull-left">
						<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('general.profiles') }}</strong>
					</label>
					<div data-toggle="buttons" class="btn-group" id="btn-profiles">
						<label class="btn btn-info btn-xs<?php if ($cprofile == 'general') {echo ' active';} ?>" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.General view') }}">
							<input type="radio" id="default" value="general" name="profile"<?php if ($cprofile == 'general') {echo ' checked="checked"';} ?>><i class="fa fa-list"></i>
						</label>
						<label name="profile" class="btn btn-info btn-xs<?php if ($cprofile == 'title') {echo ' active';} ?>" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Title control') }}">
							<input type="radio" id="tight" value="title" name="profile"<?php if ($cprofile == 'title') {echo ' checked="checked"';} ?>><i class="fa fa-th"></i>
						</label>
						<label name="profile" class="btn btn-info btn-xs<?php if ($cprofile == 'compare') {echo ' active';} ?>" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Compare HOL') }}">
							<input type="radio" id="full_open" value="compare" name="profile"<?php if ($cprofile == 'compare') {echo ' checked="checked"';} ?>><i class="fa fa-bars"></i>
						</label>
						<?php if ($_COOKIE[Auth::user()->username.'_noDefprofiles'] != '') { ?>
							<?php $noDefprofiles = explode(';', $_COOKIE[Auth::user()->username.'_noDefprofiles']); ?>
							<span class="pull-left">&nbsp;</span>
							<?php foreach ($noDefprofiles as $profile) { ?>
							<label class="btn btn-success btn-xs<?php if ($cprofile == $profile) {echo ' active';} ?>" data-container="body" data-toggle="tooltip" data-original-title="{{ trans('general.Custom profile:') }} {{ $profile }}">
								<input type="radio" id="default" value="{{ $profile }}" name="profile"<?php if ($cprofile == $profile) {echo ' checked="checked"';} ?>><i class="fa fa-list-ul"></i>
							</label>
							<?php } ?>
							<?php } ?>
						</div>
						<div data-toggle="buttons" class="btn-group">
							@if (($cprofile == 'general') || ($cprofile == 'title') || ($cprofile == 'compare'))
							<label class="btn btn-danger btn-xs" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Reset profile') }}">
								<input type="checkbox" id="restarprofile" value="1" name="restarprofile"><i class="fa fa-rotate-left"></i>
							</label>
							@else
							<label class="btn btn-danger btn-xs" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Delete custom profile') }}">
								<input type="checkbox" id="restarprofile" value="1" name="restarprofile"><i class="fa fa-eraser"></i>
							</label>
							@endif
						</div>
						&nbsp;&nbsp;
						<div class="input-group input-group-sm pull-right" style="width: 150px;margin-left: 10px;">
							<?php if ($_COOKIE[Auth::user()->username.'_noDefprofiles'] == '') { ?>
								<input type="text" name="new_profile" class="form-control" style="height: 24px;" placeholder="{{ trans('general.My profile') }}">
							<?php } ?>

							<span class="input-group-btn">
								<!-- 						<div class="btn btn-primary btn-xs" style="height: 24px;padding: 3px 7px" data-container="body" data-toggle="tooltip" data-original-title="{{ trans('general.Save new profile') }}"> <i class="fa fa-plus"></i> </div> -->
								&nbsp;
							</span>
							<button type="submit" value="{{ trans('general.update') }}" class="btn btn-xs btn-primary" style="height: 24px;padding: 3px 7px"> {{ trans('general.update') }} </button>
						</div>
					</div>
					<div class="pull-left" style="margin-left: 20px;">
						<a href="#table_fields" id="filter-btn" class="accordion-toggle btn btn-xs btn-default dropdown-toggle pull-right collapsed text-warning" data-toggle="collapse">
							<span class="fa fa-check"></span> {{{ trans('general.show_hide_fields') }}}
						</a>
					</div>
					<div class="col-xs-12" style="clear:both">
						<div class="accordion" id="FieldsShow">
							<div id="table_fields" class="accordion-body text-right collapse text-center">
								<?php	

						// ACTIVE FIELDS
								$activefields = $_COOKIE[Auth::user()->username.'_'.$cprofile.'_fields_showed'];

								if ((!$activefields) || ($activefields == null) || ($activefields == '')) {
									setcookie(Auth::user()->username.'_'.$cprofile.'_fields_showed', implode(';', $allfields), time()+60*60*24*3650);
									$activefields = $allfields;
								}
								else {
									$activefields = explode(';',$activefields);
								}

								$fields = $activefields;
						// var_dump($allfields);
						// var_dump($activefields);
						// die();

								?>
								<ul class="btn-group" data-toggle="buttons">
									<?php
									$k = -1;
									foreach ($fields as $field) {
										$popover = '';
										$field_short = trans('fields.'.$field);
										$field_large  = '';
										switch ($field) {
											case 'exists_online':
											$field_short = trans('holdings.exists_online_short');
											$field_large = ' data-content="<strong>'.trans('holdings.exists_online_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
											$popover = " pop-over ";
											break;

											case 'is_current':
											$field_short = trans('holdings.is_current_short');
											$field_large = ' data-content="<strong>'.trans('holdings.is_current_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
											$popover = " pop-over ";
											break;

											case 'has_incomplete_vols':
											$field_short = trans('holdings.has_incomplete_vols_short');
											$field_large = ' data-content="<strong>'.trans('holdings.has_incomplete_vols_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
											$popover = " pop-over ";
											break;	

											case 'size':
											$field_short = trans('fields.size');
											$field_large = ' data-content="<strong>'.trans('fields.size_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
											$popover = " pop-over ";
											break;	

											case 'years':
											$field_short = trans('fields.years');
											$field_large = ' data-content="<strong>'.trans('fields.years_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
											$popover = " pop-over ";
											break;	

											default:
											$popover = '';
											$field_short = trans('fields.'.$field);
											$field_large  = '';
											break;										
										}
										$checked 		= "checked = checked";
										$checkactive 	= " active"; 
										$k++; 
										$sizeofield = ($sizeofields[$k] > 0) ? $sizeofields[$k] : $defaultsize ; 
										?>
										<li class="btn btn-xs btn-default{{ $checkactive }} {{ $popover }}" {{ $field_large }}>
											<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
											<div class="change-size-box">					
												<i class="fa fa-exchange"></i>
												<div class="change-size-controls" target="field_<?php echo $field; ?>">							
													<input type="hidden" id="field_<?php echo $field; ?>_size" name="sizes[]" value="<?php echo $sizeofield; ?>">
													<i class="fa expand change-size fa-arrow-circle-o-right"></i><i class="fa compress change-size fa-arrow-circle-o-left"></i>  
												</div>  
											</div>
										</li>
										<?php 
									}	?>
									<style type="text/css">
										table .dinamic {
											display: inline-block;
											min-width: 40px;
											overflow: hidden;
											vertical-align: middle;
										}
										.change-size-box {
											display: inline-block;
											position: relative;
											vertical-align: middle;
											display: none;
											margin-top: -25px;
										}
										.btn.btn-xs.btn-default.active .change-size-box {
											display: inline-block;
										}
										.change-size-box .fa-exchange {
											font-size: 10px;
										}
										.change-size-box .change-size-controls {
											background: none repeat scroll 0 0 hsl(0, 0%, 100%);
											border-radius: 5px;
											display: none;
											left: 0;
											padding: 0;
											position: absolute;
											top: -13px;
											width: 40px;
											left: -14px;
										}
										.change-size-box .change-size-controls .fa.change-size {
											color: hsl(240, 100%, 50%) !important;
											cursor: pointer !important;
											font-size: 20px;
										}
										.change-size-box .change-size-controls .fa.change-size.compress {
											bottom: 0;
											left: 3px;
										}
										.change-size-box .change-size-controls .fa.change-size.expand {
											left: 3px;
											margin-right: 5px;
										}
										.change-size-box:hover .change-size-controls {
											display: block;
										}
										.change-size-box + .dinamic {
											margin-left: -5px;
										}
									</style>

									<?php
									$k = -1;
									foreach ($allfields as $field) {
										if (!(in_array($field, $fields))) {
											$popover = '';
											$field_short = trans('fields.'.$field);
											switch ($field) {
												case 'exists_online':
												$field_short = trans('holdings.exists_online_short');
												$field_large = ' data-content="<strong>'.trans('holdings.exists_online_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
												$popover = " pop-over ";
												break;

												case 'is_current':
												$field_short = trans('holdings.is_current_short');
												$field_large = ' data-content="<strong>'.trans('holdings.is_current_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
												$popover = " pop-over ";
												break;

												case 'has_incomplete_vols':
												$field_short = trans('holdings.has_incomplete_vols_short');
												$field_large = ' data-content="<strong>'.trans('holdings.has_incomplete_vols_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
												$popover = " pop-over ";
												break;

												case 'size':
												$field_short = trans('fields.size');
												$field_large = ' data-content="<strong>'.trans('fields.size_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
												$popover = " pop-over ";
												break;	

												case 'years':
												$field_short = trans('fields.years');
												$field_large = ' data-content="<strong>'.trans('fields.years_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
												$popover = " pop-over ";
												break;	
												default:
												$popover = '';
												$field_short = trans('fields.'.$field);
												$field_large = '';
												break;										
											}
											$k++; 
											$sizeofield = ($sizeofields[$k] > 0) ? $sizeofields[$k] : $defaultsize ; 
											$checked 			= '';
											$checkactive 		= ''; ?>
											<li class="btn btn-xs btn-default{{ $checkactive }} {{ $popover }}" {{ $field_large }}>
												<input type="hidden" id="field_<?php echo $field; ?>_size" name="sizes[]" value="<?php echo $sizeofield; ?>">
												<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
											</li>
											<?php }
										}	?>
									</ul>
									<button type="submit" value="{{ trans('general.update') }}" class="btn btn-xs btn-primary"> {{ trans('general.update') }} </button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>

			<?php } ?>

		</section>