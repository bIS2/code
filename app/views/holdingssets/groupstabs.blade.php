<?php
	$total = $holdingssets -> getTotal();
	$init = $holdingssets -> getTo();
	$groupsids = '';
	$g = 0;
	foreach ($groups as $group) {
		$g++;
		$groupsids .= $groups[count($groups)-$g] -> id;
		if ($g == 5) {
			break;
		}
		else {
			$groupsids .= ';';
		}
	}
	define('DEFAULTS_GROUPS', $groupsids);

	$restarcookie = true;
	$cookiesids = explode(';', $_COOKIE[Auth::user()->username.'_groups_to_show']);
	foreach ($groups as $group) {
		if (in_array($group -> id, $cookiesids)) {
			$restarcookie = false;
			break;
		}
	}
	// var_dump($restarcookie);
	//if ($restarcookie) 
	Session::put(Auth::user()->username.'_groups_to_show', ';');
	setcookie(Auth::user()->username.'_groups_to_show', ';', time() + (86400 * 30));
	
	if (!isset($_COOKIE[Auth::user()->username.'_groups_to_show']) || (Session::get(Auth::user()->username.'_groups_to_show') == ';')) {
	  setcookie(Auth::user()->username.'_groups_to_show', DEFAULTS_GROUPS, time() + (86400 * 30));
	  Session::put(Auth::user()->username.'_groups_to_show', DEFAULTS_GROUPS);
	}

	if (Session::get(Auth::user()->username.'_groups_to_show') == null)
	  Session::put(Auth::user()->username.'_groups_to_show', $_COOKIE[Auth::user()->username.'_groups_to_show']);

	if (Session::get(Auth::user()->username.'_groups_to_show') != '') 
 		setcookie(Auth::user()->username.'_groups_to_show', Session::get(Auth::user()->username.'_groups_to_show'), time() + (86400 * 30));


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
 	// var_dump($groupsids);
?>
<ul id="groups-tabs" class="nav nav-tabs">
  <li <?php if (!($group_id > 0)) { echo 'class="active"'; } ?>>
  	<a <?php if (($group_id > 0)) { echo 'href="'.route('sets.index', Input::except(['group_id'])).'"'; } ?>>
  		<?= trans('holdingssets.all') ?> <?= trans('holdingssets.title') ?>
  	</a>
  </li>
  <li>
	  <a href="#form-create-group" data-toggle="modal" class='btn btn-default disabled link_bulk_action_groups'  style="padding: 6px 11px;">
	  	<i class="fa fa-folder-o" style="font-size: 26px; padding: 0px;"></i>
	  	<span class="fa fa-plus-circle" style="position: absolute; font-size: 12px; top: 14px; left: 18px;"></span>
	  </a>
  </li>
	<?php foreach ($groups as $group) {
		if (in_array($group -> id, $groupsids)) { 
	 ?>
		<li id="group{{ $group->id }}" <?php if ($group_id == $group -> id) { echo 'class="active"'; } else { echo 'class="accepthos"'; } ?>>
			<a <?php if ($group_id != $group -> id) { echo 'href="'.route('sets.index',Input::except(['group_id']) + ['group_id' => $group->id ]).'"'; } ?> class="pull-left"><?= $group->name  ?> <span class="badge">{{ $group->holdingssets -> count() }} </span></a></a>
			<?php if ($group_id != $group -> id) { ?>
			<!-- <a href="{{ action('HoldingssetsController@putDelTabgroup',[$group->id]) }}" class="btn btn-ok btn-xs" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><button aria-hidden="true" data-dismiss="modal" class="close pull-left" type="button">×</button></a> -->
			<a href="{{ action('HoldingssetsController@putDelTabgroup',[$group->id]) }}" class="close" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-eye-slash"></i></a>
			<?php } ?>
		</li>
	<?php }
	} ?>
</ul>
<?php if (count($holdingssets) > 0) { ?>
<form method="post" action="{{ route('sets.index', Input::except(['noexists'])) }}">
<div id="hos_actions_and_filters" class="clearfix">
	<a id="open-all-hos" class="btn btn-md btn-danger pull-left" data-toggle="tooltip" title="<?= trans('holdingssets.open_all_hos'); ?>" data-container="body" ><i class="fa fa-folder-open-o"></i></a>

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
				<button type="submit" value="{{ trans('general.sort') }}" class="btn btn-xs btn-primary"> {{ trans('general.sort') }} </button>
		</div>
	</div>
	<div class="pull-right">
	  <a href="#table_fields" id="filter-btn" class="accordion-toggle btn btn-xs btn-default dropdown-toggle pull-right collapsed text-warning" data-toggle="collapse">
  		<span class="fa fa-check"></span> {{{ trans('general.show_hide_fields') }}}
		</a>
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
</div>
	<div class="col-xs-12">
		<div class="accordion" id="FieldsShow">
		   <div id="table_fields" class="accordion-body text-right collapse">
					<input type="hidden" name="urltoredirect" value="<?= route('sets.index', Input::except(['noexists'])); ?>">
					<?php									
						$allfields 	= explode(';', ALL_FIELDS);
						$tmpfields 	= Session::get(Auth::user()->username.'_fields_to_show_ok_hos');					
						$fields 		= '';
						if (isset($tmpfields)) {
							$fields 		= explode(';', $tmpfields);
						}
						?>
						<ul class="btn-group" data-toggle="buttons">
						<?php
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
									}

								$checked 			= '';
								$checkactive 		= '';
								if (($field != 'ocrr_ptrn')) {
										$checked 		= "checked = checked";
										$checkactive 	= " active"; ?>
										<li class="btn btn-xs btn-default{{ $checkactive }} {{ $popover }}" {{ $field_large }}>
											<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
										</li>
								<?php }
							}	?>
						<?php
							foreach ($allfields as $field) {
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
									}

								$checked 				= '';
								$checkactive 		= '';
								if (($field != 'ocrr_ptrn') && ($field != 'sys2')) {
									if (!(in_array($field, $fields))) { ?>
										<li class="btn btn-xs btn-default{{ $checkactive }} {{ $popover }}" {{ $field_large }}>
											<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
										</li>
										<?php
									}
									?>
								<?php }
							}	?>
						</ul>
						<input type="hidden" name="fieldstoshow[]" value="ocrr_ptrn">
						<button type="submit" value="{{ trans('general.update') }}" class="btn btn-xs btn-primary"> {{ trans('general.update') }} </button>
			</div>
		</div>
	</div>
	</form>
	
<?php } ?>

</section>