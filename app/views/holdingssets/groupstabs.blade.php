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
  <li <?php if (!isset($group_id)) { echo 'class="active"'; } ?>>
  	<a href="<?= route('sets.index')  ?>">
  		<?= trans('holdingssets.all') ?> <?= trans('holdingssets.title') ?>
  	</a>
  </li>
	<?php foreach ($groups as $group) {
		if (in_array($group -> id, $groupsids)) { 
	 ?>
		<li id="group{{ $group->id }}" <?php if ($group_id == $group -> id) { echo 'class="active"'; } else { echo 'class="accepthos"'; } ?>>
			<a href="<?= route('sets.index',['group_id' => $group->id ])  ?>" class="pull-left"><?= $group->name  ?>
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