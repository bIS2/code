<?php
// CURRENT PROFILE
$cprofile = $_COOKIE[Auth::user()->username.'_current_profile'];
$cprofile = (($cprofile == '') || ($cprofile == null) || ($cprofile)) ? Session::get(Auth::user()->username.'_current_profile') : $cprofile ;

// ALL FIELDS
$allfields = $_COOKIE[Auth::user()->username.'_'.$cprofile.'_fields'];	
$allfields = (($allfields == '') || ($allfields == null) || ($allfields)) ? explode(';', Session::get(Auth::user()->username.'_'.$cprofile.'_fields')) : explode(';', $cpaf);

$defaultsize = 100;

$sizeofields = $_COOKIE[Auth::user()->username.'_'.$cprofile.'_size_of_fields'];
$sizeofields = explode(';',$sizeofields);
?>

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
					<input type="hidden" name="urltoredirect" value="<?= route('sets.index', Input::except(['noexists'])); ?>">
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
										<input type="hidden" id="field_<?php echo $field; ?>_size" name="sizes[]" value="<?php echo $defaultsize; ?>">
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
									<input type="hidden" id="field_<?php echo $field; ?>_size" name="sizes[]" value="<?php echo $defaultsize; ?>">
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