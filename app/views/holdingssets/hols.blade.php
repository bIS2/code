@foreach ($holdingssets as $holdingsset)
<?php 
$HOSconfirm   = $holdingsset->confirm()->exists();
$HOSannotated = $holdingsset->is_annotated;

// $fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show_ok_hos');
// $fieldstoshow = explode(';',$fieldstoshow);

$cprofile = $_COOKIE[Auth::user()->username.'_current_profile'];

$fieldstoshow = $_COOKIE[Auth::user()->username.'_'.$cprofile.'_fields_showed'];
$sizeofields = $_COOKIE[Auth::user()->username.'_'.$cprofile.'_size_of_fields'];

$fieldstoshow = explode(';',$fieldstoshow);
$sizeofields = explode(';',$sizeofields);
$profile = $_COOKIE[Auth::user()->username.'_active_profile'];

$defaultsize = 100;

?>
<table class="table table-hover header-only table-bordered" style="position:fixed">
	<thead>
		<tr>
			<th><div style="width:15px;">&nbsp;</div></th>
			<th class="table_order" style="border-left:4px solid #ffffff">No.</th>
			<?php $k = -1; ?>
			@foreach ($fieldstoshow as $field) 
			<?php $k++; $sizeofield = ($sizeofields[$k] > 0) ? $sizeofields[$k] : $defaultsize ; ?>	
			@if ($field != 'ocrr_ptrn')									
			<th>
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ trans('fields.'.str_replace('f', '', $field)); }} <span class="fa fa-info-circle"></span>
				</div>
			</th> 
			@else
			<th class="hocrr_ptrn">
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ trans('holdingssets.ocurrence_patron') }}
					<a href="{{ route('sets.show', $holdingsset->id) }}" data-target="#set-show" data-toggle="modal"><span class="glyphicon glyphicon-question-sign" title="{{ trans('holdingssets.see_more_information') }}"></span></a>
					@if (!$HOSconfirm)
					<a set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putRecallHoldingsset',[$holdingsset->id]); ?>" data-remote="true" data-method="put" data-disable-with="..." data-disable-with="..." class="forceblue pop-over" data-content="<?= trans('holdingssets.recall_HOS'); ?>" data-placement="right" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-refresh text-danger"></i></a>
					@endif
				</div>
			</th>
			<!-- <th>hbib <span class="fa fa-info-circle"></span></th> -->
			@endif
			@endforeach						
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<table class="table table-hover flexme full-hos table-bordered draggable <?= ($HOSconfirm) ? 'confirm' : ''; ?>">
	<thead>
		<tr>
			<th><div style="width:15px;">&nbsp;</div></th>
			<th class="table_order">No.</th>
			<?php $k = -1; ?>
			@foreach ($fieldstoshow as $field) 
			<?php $k++; $sizeofield = ($sizeofields[$k] > 0) ? $sizeofields[$k] : $defaultsize ; ?>	
			@if ($field != 'ocrr_ptrn')								
			<th>
				<div class="field_<?php echo $field; ?> dinamic <?php echo $sizeofields[$k]; ?>" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ trans('fields.'.str_replace('f', '', $field)); }} <span class="fa fa-info-circle"></span>
				</div>
			</th> 
			@else
			<th class="hocrr_ptrn">
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ trans('holdingssets.ocurrence_patron') }}
					<a href="{{ route('sets.show', $holdingsset->id) }}" data-target="#set-show" data-toggle="modal"><span class="glyphicon glyphicon-question-sign" title="{{ trans('holdingssets.see_more_information') }}"></span></a>
					@if (!$HOSconfirm)
					<a set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putRecallHoldingsset',[$holdingsset->id]); ?>" data-remote="true" data-method="put" data-disable-with="..." data-disable-with="..." class="forceblue pop-over" data-content="<?= trans('holdingssets.recall_HOS'); ?>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-refresh text-danger"></i></a>
					@endif
				</div>
			</th>
			<!-- <th>hbib <span class="fa fa-info-circle"></span></th> -->
			@endif
			@endforeach						
		</tr>
	</thead>

	<tbody>
		<?php $hol_order = 0;?>
		<?php $holdings = Holding::whereHoldingssetId($holdingsset->id)->orderBy('is_owner', 'DESC')->orderBy('is_aux', 'DESC')->orderBy('weight', 'DESC')->get();
		// $queries = DB::getQueryLog();
		// var_dump(end($queries));
		?>
		@foreach ($holdings as $holding)
		<?php
		$hol_order++;
		$btnlock 	= ($holding->locked()->exists()) ? 'btn-warning ' : '';	
		$trclass 	= ($holding->locked()->exists()) ? 'locked' : '';
		$ownertrclass = (($holding->is_owner == 't') || ($holding->is_owner == '1')) ? ' is_owner' : '';
		$auxtrclass   = (($holding->is_aux == 't') || ($holding->is_aux == '1')) ? ' is_aux' : ''; 
		if (isset($aux_ptrn[$i]))  $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
		$preftrclass 	= ($holding->is_pref == 't') ? ' is_pref' : '';
		$librarianclass = ' '.substr($holding->sys2, 0, 4); 
		?>	
		<tr id="holding{{ $holding -> id; }}" holding="{{ $holding -> id; }}" class="{{ $trclass }} {{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}{{ ($holding->is_annotated) ? ' text-warning' : '' }}">
			<td>
				<div style="width:15px;">
					@if (!($holding->locked)) <input id="holding_id" name="holding_id[]" type="checkbox" value="<?= $holding->id; ?>" class="pull-left hld selhld"> @endif
				</div>
			</td>
			<td class="table_order">{{ $hol_order }}</td>

			<?php $k = -1; ?>
			@foreach ($fieldstoshow as $field) 
			<?php $k++; $sizeofield = ($sizeofields[$k] > 0) ? $sizeofields[$k] : $defaultsize ; ?>
			@if (($field != 'ocrr_ptrn') && ($field != 'actions')) 				
			<td>
				<div class="field_<?php echo $field; ?> dinamic <?php echo $sizeofields[$k]; ?>" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ $holding->show( $field ) }}
				</div>
			</td>
			@elseif($field == 'ocrr_ptrn')
			<td class="ocrr_ptrn">
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ $holding -> patrn }}
					<i class="fa fa-question-circle pop-over" data-content="<strong>
						<?php 
						if ($holding->f866aupdated == '') { 
							echo $holding->clean($holding->f866a);
						}
						else {
							echo $holding->clean($holding->f866aupdated);
						}
						?>
					</strong>" data-placement="top" data-toggle="popover" data-html="true" type="button" data-trigger="hover" data-original-title="" title=""></i>
				</div>
			</td>
			<!-- <td>{{ $holding->library->code }}</td> -->
			@elseif($field == 'actions')
			
			<td class="actions" holding="{{ $holding -> id }}">
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ $holding -> bibuser_actions($holdingsset, $hol_order) }}
				</div>
			</td>
			@endif
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>
@endforeach
<script type="text/javascript">
	$(function() {
		if ($('li#' + <?php echo $holdingsset-> id ?> + ' table.full-hos tbody tr').length > 10) {
			ths = $('li#' + <?php echo $holdingsset-> id ?> + ' table.full-hos th');
			ths1 = $('li#' + <?php echo $holdingsset-> id ?> + ' table.header-only th');
			for (var i = 0; i < $(ths).length; i++) {
				// $(ths1[i]).css('min-width', $(ths[i]).css('width'))
				// $(ths1[i]).css('width', $(ths[i]).css('width'))
			}
			onlyheader<?php echo $holdingsset-> id ?> = $('li#' + <?php echo $holdingsset-> id ?> + ' table.header-only');
			header<?php echo $holdingsset-> id ?> = $('li#' + <?php echo $holdingsset-> id ?> + ' table.flexme thead tr');
			var a;
			var b;
			a = onlyheader<?php echo $holdingsset-> id ?>;
			b = header<?php echo $holdingsset-> id ?>;
			$(window).scroll(function() {
				jQuery(a).offset({ left: jQuery(b).offset().left-1 });

				if ( jQuery(b).offset().top >  jQuery('.page-header').offset().top +  parseInt(jQuery('.page-header').height()) ) {
					jQuery(a).offset({ top: jQuery(b).offset().top});		    		
					jQuery(a).fadeOut();
				}
				else {
					jQuery(a).offset({ top: jQuery('.page-header').offset().top +  parseInt(jQuery('.page-header').height()) + 10 });
					jQuery(a).fadeIn();
				}
			})
			$(window).mousemove(function() {
				jQuery(a).offset({ left: jQuery(b).offset().left-1 });
			})
		}
		else {
			$('li#' + <?php echo $holdingsset-> id ?> + ' table.header-only').remove();
		}
	})
</script>