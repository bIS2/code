<?php


	// $total = $holdingssets -> getTotal();
	// $init = $holdingssets -> getTo();
	// $hlistsids = '';
	// $g = 0;
	// foreach ($hlists as $hlist) {
	// 	$g++;
	// 	$hlistsids .= $hlists[count($hlists)-$g] -> id;
	// 	if ($g == 5) {
	// 		break;
	// 	}
	// 	else {
	// 		$hlistsids .= ';';
	// 	}
	// }
	// define('DEFAULTS_HLISTS', $hlistsids);
	// // var_dump(DEFAULTS_HLISTS);
	// if (Input::has('hlist_id')) $hlist_id = Input::get('hlist_id');

	// $restarcookie = true;

	// $cookiesids = explode(';', $_COOKIE[Auth::user()->username.'_hlists_to_show']);

	// foreach ($hlists as $hlist) {
	// 	if (in_array($hlist -> id, $cookiesids)) {
	// 		$restarcookie = false;
	// 		break;
	// 	}
	// }

	// var_dump($restarcookie);

	//if ($restarcookie) 
	// 	Session::put(Auth::user()->username.'_hlists_to_show', ';');
	// 	setcookie(Auth::user()->username.'_hlists_to_show', ';', time() + (86400 * 30));

	// if (!isset($_COOKIE[Auth::user()->username.'_hlists_to_show']) || (Session::get(Auth::user()->username.'_hlists_to_show') == ';')) {
	//   setcookie(Auth::user()->username.'_hlists_to_show', DEFAULTS_HLISTS, time() + (86400 * 30));
	//   Session::put(Auth::user()->username.'_hlists_to_show', DEFAULTS_HLISTS);
	// }

	// if (Session::get(Auth::user()->username.'_hlists_to_show') == null)
	//   Session::put(Auth::user()->username.'_hlists_to_show', $_COOKIE[Auth::user()->username.'_hlists_to_show']);

	// if (Session::get(Auth::user()->username.'_hlists_to_show') != '') 
 // 		setcookie(Auth::user()->username.'_hlists_to_show', Session::get(Auth::user()->username.'_hlists_to_show'), time() + (86400 * 30));


	$hlistsids = '';
	$hlistsids = Session::get(Auth::user()->username.'_hlists_to_show');
	// var_dump($hlistsids);
	$hlist_id = $_GET['hlist_id'];
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

<div class="row">
	<div class="col-sm-1">
		@if ( Authority::can('create','Hlist') ) 
			<div class="btn-group">
			  <a href="{{route('lists.create')}}" id="link_create_list" data-toggle="modal" class='btn btn-default link_bulk_action disabled' data-target="#form-create-list" >
			  	<i class="fa fa-plus-circle" ></i>
			  </a>
			  <a href="{{action('HlistsController@getAttach')}}" id="link_to_list" data-toggle="modal" class='btn btn-default link_bulk_action disabled' data-target="#form-to-list" >
			  	<i class="fa fa-indent" ></i>
			  </a>
			</div>
		@endif
	</div> <!-- /.col-sm-1 -->

	<div class="col-sm-11">

		<ul id="lists-tabs" class="nav nav-tabs">

			@if (Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('bibuser'))

		  <li <?php if (!($hlist_id > 0)) { echo 'class="active"'; } ?>>
		  	<a href="<?= route('holdings.index', Input::except(['hlist_id', 'page']));  ?>">
		  		<?= trans('holdings.all') ?> <?= trans('holdings.title') ?>
		  	</a>

		  </li>

		  @endif

			@foreach ($hlists as $hlist) 
				@if (in_array($hlist->id, $hlistsids)) 
				<!-- Input::except(['hlist_id', 'page']) + ['hlist_id' => $hlist->id ] -->
			 
					<li id="hlist{{ $hlist->id }}" data-id="{{$hlist->id}}" class="<?php echo ($hlist_id == $hlist->id) ? 'active' : 'accepthos' ?> droppable" data-attach-url="{{ action('HlistsController@postAttach') }}">
						<a <?php if ($hlist_id != $hlist->id) { echo 'href="'.route('holdings.index',Input::except(['hlist_id', 'page']) + ['hlist_id' => $hlist->id ]).'"'; } ?> class="">
							{{ $hlist->type_icon }}
							<?= $hlist->name  ?> 
							<span class="badge counter">{{ $hlist->holdings->count() }} </span>
						</a>

						@if ($hlist_id != $hlist->id)
							<a href="{{ action('HoldingsController@putDelTabhlist',[$hlist->id]) }}" class="close" data-params="ok=true" data-remote="true" data-method="put" data-disable-with="..."><i class="fa fa-eye-slash"></i></a>
						@endif
					</li>

				@endif
			@endforeach
		</ul>

	</div>
</div>


<?php if (count($holdings) > 0) { ?>
	<form method="post" action="{{ route('holdings.index', Input::except(['noexists'])) }}">
<div id="hos_actions_and_filters" class="row">

	<!-- Information about pagination-->
	<div class="col-xs-7">

		<span class="control-label">
			{{ trans('general.pagination_information',['from'=>$holdings->getFrom(), 'to'=>$holdings->getTo(), 'total'=>$holdings->getTotal()])}} 
		</span>
		{{ $holdings->appends(Input::except('page'))->links()  }}
	<div id="profiles" class="pull-right" style="position: relative">
		<label class="btn btn-xs pull-left">
			<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ trans('general.profiles') }}</strong>
		</label>
		<div data-toggle="buttons" class="btn-group">
		  <label class="btn btn-info btn-xs active" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Default') }}: {{ trans('general.Default fields + Balance between text and column size + Fields order and visibles + Fields sort') }}">
		    <input type="radio" id="default" value="default" name="profile" checked="checked"><i class="fa fa-list"></i>
		  </label>
		  <label name="profile" class="btn btn-info btn-xs" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Tight') }}: {{ trans('general.Default fields + Smaller size for all columns + Fields order and visibles + Fields sort') }}">
		    <input type="radio" id="tight" value="tight" name="profile"><i class="fa fa-th"></i>
		  </label>
		  <label name="profile" class="btn btn-info btn-xs" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Full Open') }}: {{ trans('general.Default fields + Full Open text for all columns + Fields order and visibles + Fields sort') }}">
		    <input type="radio" id="full_open" value="full_open" name="profile"><i class="fa fa-bars"></i>
		  </label>

		</div>
		<span class="btn btn-warning btn-xs dropdown-toggle" data-toggle="dropdown" data-container="body"><div data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.See Custom Profiles') }}"><i class="fa fa-pencil" ></i> <span class="caret"></span></div></span>
        <ul class="dropdown-menu pull-right" style="margin-right: 37px;">
          <li style="position: relative;"><a href="#" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Select this profile') }}">Profile - 1</a><span class="btn btn-danger btn-xs" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Delete profile') }}" style="position: absolute;right: 10px;top: 2px;"><i class="fa fa-times"></i></span></li>
          <li style="position: relative;"><a href="#" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Select this profile') }}">Profile - 2</a><span class="btn btn-danger btn-xs" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Delete profile') }}" style="position: absolute;right: 10px;top: 2px;"><i class="fa fa-times"></i></span></li>
          <li style="position: relative;"><a href="#" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Select this profile') }}">Profile - 3</a><span class="btn btn-danger btn-xs" data-container="body" data-toggle="tooltip" data-original-title=" {{ trans('general.Delete profile') }}" style="position: absolute;right: 10px;top: 2px;"><i class="fa fa-times"></i></span></li>
        </ul>
		<div class="input-group input-group-sm pull-right" style="width: 150px;margin-left: 10px;">
			<input type="text" name="new_profile" class="form-control" style="height: 24px;">
			<span class="input-group-btn">
				<div class="btn btn-primary btn-xs" style="height: 24px;padding: 3px 7px" data-container="body" data-toggle="tooltip" data-original-title="{{ trans('general.Save new profile') }}"> <i class="fa fa-plus"></i> </div>
			</span>
		</div>
	</div>
	</div>

	<!-- Actions -->
	<div class="col-xs-5">
		<div class="col-xs-7">
			@if (Input::has('hlist_id'))
			<?php $list = Hlist::find(Input::get('hlist_id')); ?>
			@endif
			@if ( Input::has('hlist_id'))
			<a href="{{ route('lists.update',$list->id) }}" class="btn btn-success btn-sm btn-revise {{ ( Authority::can('revise',$list) ) ? '' : 'hide' }}" data-remote="true" data-method="put" data-params="revised=1" data-disabled-with="...">
				<span class="fa fa-check" ></span> {{ trans('holdings.revised')}}
			</a>
			@endif

			@if (Input::has('hlist_id'))
			<span class="label label-primary state-list"> {{ trans('states.'.$list->state) }}</span>
			@endif
		</div>
		<div class="col-xs-5">
			<a href="#table_fields" id="filter-btn" class="accordion-toggle btn btn-sm btn-default dropdown-toggle collapsed text-warning pull-right" data-toggle="collapse">
				<span class="fa fa-check"></span> {{{ trans('general.show_hide_fields') }}}
			</a>
		</div>
	</div>
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

						$tmpfields 	= Session::get(Auth::user()->username.'_fields_to_show_ok_hols');
						
						$fields 		= '';
						if (isset($allfields)) {
							$fields 		= explode(';', $tmpfields);
						}
						?>
						<ul class="btn-group" data-toggle="buttons">
						<?php
							foreach ($fields as $field) {
								$popover = '';
								$field_large = '';
								$field_short = trans('fields.'.$field);
								switch ($field) {
									case 'exists_online':
										$field_short = trans('holdings.exists_online_short');
										$field_large = ' data-content="<strong>'.trans('holdings.exists_online_large').'</strong>" data-placement="top" data-toggle="tooltip" data-html="true" data-trigger="hover" ';
										$popover = " pop-over ";
									break;
									
									case 'is_current':
										$field_short = trans('holdings.is_current_short');
										$field_large = ' data-content="<strong>'.trans('holdings.is_current_large').'</strong>" data-placement="top" data-toggle="tooltip" data-html="true" data-trigger="hover" ';
										$popover = " pop-over ";
									break;
									
									case 'has_incomplete_vols':
										$field_short = trans('holdings.has_incomplete_vols_short');
										$field_large = ' data-content="<strong>'.trans('holdings.has_incomplete_vols_large').'</strong>" data-placement="top" data-toggle="tooltip" data-html="true" data-trigger="hover" ';
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
								if (($field != 'ocrr_ptrn')) {
										$checked 			= "checked = checked";
										$checkactive 	= " active"; ?>
										<li class="btn btn-xs btn-default{{ $checkactive }} pop-over" {{ $field_large }}>
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
										$field_large = ' data-content="<strong>'.trans('holdings.exists_online_large').'</strong>" data-placement="top" data-toggle="tooltip" data-html="true" data-trigger="hover" ';
										$popover = " pop-over ";
										break;
										
										case 'is_current':
										$field_short = trans('holdings.is_current_short');
										$field_large = ' data-content="<strong>'.trans('holdings.is_current_large').'</strong>" data-placement="top" data-toggle="tooltip" data-html="true" data-trigger="hover" ';
										$popover = " pop-over ";
										break;
										
										case 'has_incomplete_vols':
										$field_short = trans('holdings.has_incomplete_vols_short');
										$field_large = ' data-content="<strong>'.trans('holdings.has_incomplete_vols_large').'</strong>" data-placement="top" data-toggle="tooltip" data-html="true" data-trigger="hover" ';
										$popover = " pop-over ";
										break;										
									}
								$checked 				= '';
								$checkactive 		= '';
								if (($field != 'ocrr_ptrn')) {
									if (!(in_array($field, $fields))) { ?>
										<li class="btn btn-xs btn-default{{ $checkactive }}"{{ $field_large }}>
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