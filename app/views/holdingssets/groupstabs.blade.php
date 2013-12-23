<?php
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
	if ($restarcookie) Session::put(Auth::user()->username.'_groups_to_show', ';');
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
  	<a href="<?= route('sets.index', Input::except(['group_id']));  ?>">
  		<?= trans('holdingssets.all') ?> <?= trans('holdingssets.title') ?>
  	</a>
  </li>
	<?php foreach ($groups as $group) {
		if (in_array($group -> id, $groupsids)) { 
	 ?>
		<li id="group{{ $group->id }}" <?php if ($group_id == $group -> id) { echo 'class="active"'; } else { echo 'class="accepthos"'; } ?>>
			<a href="<?= route('sets.index',Input::except(['group_id']) + ['group_id' => $group->id ])  ?>" class="pull-left"><?= $group->name  ?>
			</a>
			<?php if ($group_id != $group -> id) { ?>
			<!-- <a href="{{ action('HoldingssetsController@putDelTabgroup',[$group->id]) }}" class="btn btn-ok btn-xs" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><button aria-hidden="true" data-dismiss="modal" class="close pull-left" type="button">×</button></a> -->
			<a href="{{ action('HoldingssetsController@putDelTabgroup',[$group->id]) }}" class="close" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-eye-slash"></i></a>
			<?php } ?>
		</li>
	<?php }
	} ?>
  <li>
	  <a href="#form-create-group" data-toggle="modal" class='btn btn-default link_bulk_action disabled'  style="padding: 6px 11px;">
	  	<i class="fa fa-folder-o" style="font-size: 26px; padding: 0px;"></i>
	  	<span class="fa fa-plus-circle" style="position: absolute; font-size: 12px; top: 14px; left: 18px;"></span>
	  </a>
  </li>
</ul>
<?php if (count($holdingssets) > 0) { ?>
<form method="post" action="{{ route('sets.index', Input::except(['noexists'])) }}">
<div id="hos_actions_and_filters" class="clearfix">
	<div class="pull-left select-all">
	  <label>
	    <input id="select-all" name="select-all" type="checkbox" value="1">
	    <p class="btn btn-xs btn-primary">{{ trans('holdingssets.select_all_hos') }}</p>
	  </label>
	</div>
	<div id="hos-sorting" class="pull-left text-center text-success">
		<div class="btn-group" data-toggle="buttons">
		  <label class="btn btn-success btn-xs pull-left disabled">
				{{ trans('holdingssets.order_hos_by') }} 
		  </label>
		  <label class="btn btn-default btn-xs{{ ((Session::get(Auth::user()->username.'_sortinghos_by') == null) || (Session::get(Auth::user()->username.'_sortinghos_by') == f245a)) ? ' active' : '' }}">
		    <input type="radio"{{ ((Session::get(Auth::user()->username.'_sortinghos_by') == null) || (Session::get(Auth::user()->username.'_sortinghos_by') == f245a)) ? ' checked = checked' : '' }} name="sortinghos_by" value="f245a" id="option1"> 245a
		  </label>
		  <label class="btn btn-default btn-xs{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'holdings_number') ? ' active' : '' }}" name="sortinghos_by">
		    <input type="radio"{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'holdings_number') ? ' checked = checked' : '' }} name="sortinghos_by" value="holdings_number" id="option2"> hols#
		  </label>
		  <label class="btn btn-default btn-xs{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'groups_number') ? ' active' : '' }}" name="sortinghos_by">
		    <input type="radio"{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'groups_number') ? ' checked = checked' : '' }} name="sortinghos_by" value="groups_number" id="option3"> HosG#
		  </label>
		  <label class="btn btn-default btn-xs{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'f008x') ? ' active' : '' }}" name="sortinghos_by">
		    <input type="radio"{{ (Session::get(Auth::user()->username.'_sortinghos_by') == 'f008x') ? ' checked = checked' : '' }} name="sortinghos_by" value="f008x" id="option4"> 008x
		  </label>
		</div>
		<div class="btn-group" data-toggle="buttons">
		  <label class="btn btn-default btn-xs{{ ((Session::get(Auth::user()->username.'_sortinghos') == null) || (Session::get(Auth::user()->username.'_sortinghos') == 'ASC')) ? ' active' : '' }}">
		    <input type="radio" {{ ((Session::get(Auth::user()->username.'_sortinghos') == null) || (Session::get(Auth::user()->username.'_sortinghos') == 'ASC')) ? ' checked = checked' : '' }} name="sortinghos" value="ASC" id="option1"><i class="fa fa-sort-amount-asc"></i> ASC
		  </label>
		  <label class="btn btn-default btn-xs{{ (Session::get(Auth::user()->username.'_sortinghos') == 'DESC') ? ' active' : '' }}">
		    <input type="radio" {{ (Session::get(Auth::user()->username.'_sortinghos') == 'DESC') ? ' checked = checked' : '' }} name="sortinghos" value="DESC" id="option2"><i class="fa fa-sort-amount-desc"></i> DESC
		  </label>
		</div>
		<div class="btn-group">
				<button type="submit" value="{{ trans('general.sort') }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-save"></span> {{ trans('general.sort') }} </button>
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
	</div>
</div>
	<div class="col-xs-12">
		<div class="accordion" id="FieldsShow">
		   <div id="table_fields" class="accordion-body text-right collapse">
					<input type="hidden" name="urltoredirect" value="<?= route('sets.index', Input::except(['noexists'])); ?>">
					<?php									
						$allfields 	= explode(';', ALL_FIELDS);
						$tmpfields 	= Session::get(Auth::user()->username.'_fields_to_show_ok');
						
						$fields 		= '';
						if (isset($tmpfields)) {
							$fields 		= explode(';', $tmpfields);
						}
						?>
						<ul class="btn-group" data-toggle="buttons">
						<?php
							foreach ($fields as $field) {
								$checked 				= '';
								$checkactive 		= '';
								if (($field != 'ocrr_ptrn') && ($field != 'sys2')) {
										$checked 			= "checked = checked";
										$checkactive 	= " active"; ?>
										<li class="btn btn-xs btn-default{{ $checkactive }}">
											<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field; ?>
										</li>
								<?php }
							}	?>
						<?php
							foreach ($allfields as $field) {
								$checked 				= '';
								$checkactive 		= '';
								if (($field != 'ocrr_ptrn') && ($field != 'sys2')) {
									if (!(in_array($field, $fields))) { ?>
										<li class="btn btn-xs btn-default{{ $checkactive }}">
											<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field; ?>
										</li>
										<?php
									}
									?>
								<?php }
							}	?>
						</ul>
						<input type="hidden" name="fieldstoshow[]" value="ocrr_ptrn">
						<button type="submit" value="{{ trans('general.update_fields') }}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-save"></span> {{ trans('general.update_fields') }} </button>
			</div>
		</div>
	</div>
	</form>
<?php } ?>
</section>