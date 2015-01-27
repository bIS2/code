<?php

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

$defaultsize = 100;
$sizeofields = $_COOKIE[Auth::user()->username.'_size_of_fields'];
$sizeofields = explode($cprofile);
$sizeofields = explode(';',$sizeofields);

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
				<span class="label label-primary state-list"<?php if ((Auth::user()->hasRole('resuser')) || (Auth::user()->hasRole('bibuser')) || ($list->type=="elimination")) echo ' style="display: none;"'?>> {{ trans('states.'.$list->state) }}</span>
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
				$k = -1;
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

						case '866a':
						$field_short = trans('holdings.f866atitle');
						break;	

						case 'x866a':
						$field_short = trans('holdings.fx866atitle');
						break;										

						case 'has_incomplete_vols':
						$field_short = trans('holdings.has_incomplete_vols_short');
						$field_large = ' data-content="<strong>'.trans('holdings.has_incomplete_vols_large').'</strong>" data-placement="top" data-toggle="tooltip" data-html="true" data-trigger="hover" ';
						$popover = " pop-over ";
						break;

						case 'size_dispatchable':
						$field_short = trans('holdings.size_dispatchabletitle');
						$field_large = ' data-content="<strong>'.trans('fields.sizedistachable_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
						$popover = " pop-over ";
						break;	

						case 'size':
						$field_short = trans('holdings.sizetitle');
						$field_large = ' data-content="<strong>'.trans('fields.size_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
						$popover = " pop-over ";
						break;	
						
						case 'years':
						$field_short = trans('fields.years');
						$field_large = ' data-content="<strong>'.trans('fields.years_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
						$popover = " pop-over ";
						break;											
					}
					$checked 		= "checked = checked";
					$checkactive 	= " active"; ?>
					<li class="btn btn-xs btn-default{{ $checkactive }} pop-over" {{ $field_large }}>
						<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
						<div class="change-size-box">					
							<i class="fa fa-exchange"></i>
							<div class="change-size-controls" target="field_<?php echo $field; ?>">							
								<input type="hidden" id="field_<?php echo $field; ?>_size" name="sizes[]" value="<?php echo $sizeofield; ?>">
								<i class="fa expand change-size fa-arrow-circle-o-right"></i><i class="fa compress change-size fa-arrow-circle-o-left"></i>  
							</div>  
						</div>
					</li>
					<?php }




					$k = -1;
					foreach ($allfields as $field) {
						$popover = '';
						$field_short = trans('fields.'.$field);
						$field_large = '';
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

							case '866a':
							$field_short = trans('holdings.f866atitle');
							break;

							case 'x866a':
							$field_short = trans('holdings.fx866atitle');
							break;		
							
							case 'size_dispatchable':
							$field_short = trans('holdings.size_dispatchabletitle');
							$field_large = ' data-content="<strong>'.trans('fields.sizedistachable_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
							$popover = " pop-over ";
							break;	

							case 'size':
							$field_short = trans('holdings.sizetitle');
							$field_large = ' data-content="<strong>'.trans('fields.size_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
							$popover = " pop-over ";
							break;	

							case 'has_incomplete_vols':
							$field_short = trans('holdings.has_incomplete_vols_short');
							$field_large = ' data-content="<strong>'.trans('holdings.has_incomplete_vols_large').'</strong>" data-placement="top" data-toggle="tooltip" data-html="true" data-trigger="hover" ';
							$popover = " pop-over ";
							break;										
						}
						$k++; 
						$sizeofield = ($sizeofields[$k] > 0) ? $sizeofields[$k] : $defaultsize ; 
						$checked 				= '';
						$checkactive 		= '';
						if (!(in_array($field, $fields))) { ?>
						<li class="btn btn-xs btn-default{{ $checkactive }}"{{ $field_large }}>
							<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
						</li>
						<?php } ?>
						<?php }	?>
					</ul>
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
					<input type="hidden" name="fieldstoshow[]" value="ocrr_ptrn">
					<button type="submit" value="{{ trans('general.update') }}" class="btn btn-xs btn-primary"> {{ trans('general.update') }} </button>
				</div>
			</div>
		</div>
	</form>
	<?php } ?>
</section>