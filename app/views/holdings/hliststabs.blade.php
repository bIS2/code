<?php
	// $total = $holdingssets -> getTotal();
	// $init = $holdingssets -> getTo();
	$hlistsids = '';
	$g = 0;
	foreach ($hlists as $hlist) {
		$g++;
		$hlistsids .= $hlists[count($hlists)-$g] -> id;
		if ($g == 5) {
			break;
		}
		else {
			$hlistsids .= ';';
		}
	}
	define('DEFAULTS_HLISTS', $hlistsids);
	// var_dump(DEFAULTS_HLISTS);
	if (Input::has('hlist_id')) $hlist_id = Input::get('hlist_id');

	$restarcookie = true;

	$cookiesids = explode(';', $_COOKIE[Auth::user()->username.'_hlists_to_show']);

	foreach ($hlists as $hlist) {
		if (in_array($hlist -> id, $cookiesids)) {
			$restarcookie = false;
			break;
		}
	}

	// var_dump($restarcookie);

	if ($restarcookie) Session::put(Auth::user()->username.'_hlists_to_show', ';');

	if (!isset($_COOKIE[Auth::user()->username.'_hlists_to_show']) || (Session::get(Auth::user()->username.'_hlists_to_show') == ';')) {
	  setcookie(Auth::user()->username.'_hlists_to_show', DEFAULTS_HLISTS, time() + (86400 * 30));
	  Session::put(Auth::user()->username.'_hlists_to_show', DEFAULTS_HLISTS);
	}

	if (Session::get(Auth::user()->username.'_hlists_to_show') == null)
	  Session::put(Auth::user()->username.'_hlists_to_show', $_COOKIE[Auth::user()->username.'_hlists_to_show']);

	if (Session::get(Auth::user()->username.'_hlists_to_show') != '') 
 		setcookie(Auth::user()->username.'_hlists_to_show', Session::get(Auth::user()->username.'_hlists_to_show'), time() + (86400 * 30));


	$hlistsids = '';
	$hlistsids = Session::get(Auth::user()->username.'_hlists_to_show');
	// var_dump($hlistsids);

 	if (isset($hlist_id))  {
 		$tempids = [];
 		$tempids = explode(';', $hlistsids);
 		if (!in_array($hlist_id, $tempids)) {
 			$hlistsids = $hlist_id.';'.$hlistsids;
	 		setcookie(Auth::user()->username.'_hlists_to_show', $hlistsids, time() + (86400 * 30));
	 		Session::put(Auth::user()->username.'_hlists_to_show', $hlistsids);
	 	}
 	}
	$hlistsids = explode(';', $hlistsids);
 	// var_dump($hlistsids);
?>
<ul id="groups-tabs" class="nav nav-tabs">
  <li <?php if (!($hlist_id > 0)) { echo 'class="active"'; } ?>>
  	<a href="<?= route('holdings.index', Input::except(['hlist_id', 'page']));  ?>">
  		<?= trans('holdings.all') ?> <?= trans('holdings.title') ?>
  	</a>
  </li>

  @if ( Authority::can('create','Hlist') ) 
	  <li>
		  <a data-toggle="modal" class='btn btn-default link_bulk_action disabled' data-target="#form-create-list" style="padding: 6px 11px;">
		  	<i class="fa fa-plus-circle" style="font-size: 26px; padding: 0px;"></i>
		  </a>
	  </li>
  @endif
  
	<?php foreach ($hlists as $hlist) {
		if (in_array($hlist -> id, $hlistsids)) { 
	 ?>
		<li id="hlist{{ $hlist->id }}" <?php if ($hlist_id == $hlist -> id) { echo 'class="active"'; } else { echo 'class="accepthos"'; } ?>>
			<a href="<?= route('holdings.index',Input::except(['hlist_id', 'page']) + ['hlist_id' => $hlist->id ])  ?>" class="pull-left"><?= $hlist->name  ?> <span class="badge">{{ $hlist->holdings -> count() }} </span></a></a>

			<?php if ($hlist_id != $hlist -> id) { ?>
			<a href="{{ action('HoldingsController@putDelTabhlist',[$hlist->id]) }}" class="close" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-eye-slash"></i></a>
			<?php } ?>
		</li>
	<?php }
	} ?>
</ul>
<?php if (count($holdings) > 0) { ?>
<form method="post" action="{{ route('holdings.index', Input::except(['noexists'])) }}">
<div id="hos_actions_and_filters" class="clearfix">
	<div class="pull-right">
	  <a href="#table_fields" id="filter-btn" class="accordion-toggle btn btn-xs btn-default dropdown-toggle pull-right collapsed text-warning" data-toggle="collapse">
  		<span class="fa fa-check"></span> {{{ trans('general.show_hide_fields') }}}
		</a>
	</div>
	<div class="pull-left">{{ trans('general.pagination_information',['from'=>$holdings->getFrom(), 'to'=>$holdings->getTo(), 'total'=>$holdings->getTotal()])}} </div>
	<div class="pull-left">{{ $holdings->appends(Input::except('page'))->links()  }}</div>
<!-- 	<div id="hos-pagination" class="pull-right text-center text-success">
		<p>{{ trans('holdingssets.showing') }} </p>
		<div id="current_quantity" class="active">
      <div style="width: 100%">{{ $init }}</div>
    </div> 
		<p>{{ trans('holdingssets.of') }}</p>
		<div id="total_quantity">{{ $total }}</div>
	</div> -->
</div>
	<div class="col-xs-12">
		<div class="accordion" id="FieldsShow">
		   <div id="table_fields" class="accordion-body text-right collapse">
					<input type="hidden" name="urltoredirect" value="<?= route('holdings.index', Input::except(['noexists'])); ?>">
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
						<button type="submit" value="{{ trans('general.update') }}" class="btn btn-xs btn-primary"> {{ trans('general.update') }} </button>
			</div>
		</div>
	</div>
	</form>
<?php } ?>
</section>