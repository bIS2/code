@foreach ($holdingssets as $holdingsset)
<?php 
$HOSconfirm   = $holdingsset->confirm()->exists();
$HOSannotated = $holdingsset->is_annotated;

// $fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show_ok_hos');
// $fieldstoshow = explode(';',$fieldstoshow);

$cprofile = $_COOKIE[Auth::user()->username.'_current_profile'];

$fieldstoshow = $_COOKIE[Auth::user()->username.'_'.$cprofile.'_fields_showed'];
$fieldstoshow = explode(';',$fieldstoshow);

$profile = $_COOKIE[Auth::user()->username.'_active_profile'];

?>
<table class="table table-hover header-only table-bordered" style="position:fixed">
	<thead>
		<tr>
			<th></th>
			<th class="table_order" style="border-left:4px solid #ffffff">No.</th>
			@foreach ($fieldstoshow as $field) 
			@if ($field != 'ocrr_ptrn')									
			<th>
				<div class="field_<?php echo $field; ?> dinamic" <?php if ($_COOKIE[$profile.'_'.$field] != '') { echo ' style="width:'.$_COOKIE[$profile.'_'.$field].'"'; }?>>
					{{ trans('fields.'.str_replace('f', '', $field)); }} <span class="fa fa-info-circle"></span>
				</div>
			</th> 
			@else
			<th class="hocrr_ptrn">{{ trans('holdingssets.ocurrence_patron') }}
				<a href="{{ route('sets.show', $holdingsset->id) }}" data-target="#set-show" data-toggle="modal"><span class="glyphicon glyphicon-question-sign" title="{{ trans('holdingssets.see_more_information') }}"></span></a>
				@if (!$HOSconfirm)
				<a set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putRecallHoldingsset',[$holdingsset->id]); ?>" data-remote="true" data-method="put" data-disable-with="..." data-disable-with="..." class="forceblue pop-over" data-content="<?= trans('holdingssets.recall_HOS'); ?>" data-placement="right" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-refresh text-danger"></i></a>
				@endif
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
			<th></th>
			<th class="table_order">No.</th>
			<?php $k = 0; ?>
			@foreach ($fieldstoshow as $field) 
			@if ($field != 'ocrr_ptrn') <?php $k++; ?>										
			<th>
				<div class="field_<?php echo $field; ?> dinamic" <?php if ($_COOKIE[$profile.'_'.$field] != '') { echo ' style="width:'.$_COOKIE[$profile.'_'.$field].'"'; }?>>
					{{ trans('fields.'.str_replace('f', '', $field)); }} <span class="fa fa-info-circle"></span>
				</div>
			</th> 
			@else
			<th class="hocrr_ptrn">{{ trans('holdingssets.ocurrence_patron') }}
				<a href="{{ route('sets.show', $holdingsset->id) }}" data-target="#set-show" data-toggle="modal"><span class="glyphicon glyphicon-question-sign" title="{{ trans('holdingssets.see_more_information') }}"></span></a>
				@if (!$HOSconfirm)
				<a set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putRecallHoldingsset',[$holdingsset->id]); ?>" data-remote="true" data-method="put" data-disable-with="..." data-disable-with="..." class="forceblue pop-over" data-content="<?= trans('holdingssets.recall_HOS'); ?>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-refresh text-danger"></i></a>
				@endif
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
				// var_dump($holding->is_aux);
		if (isset($aux_ptrn[$i]))  $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
		$preftrclass 	= ($holding->is_pref == 't') ? ' is_pref' : '';
		$librarianclass = ' '.substr($holding->sys2, 0, 4); 
		?>	
		<tr id="holding{{ $holding -> id; }}" holding="{{ $holding -> id; }}" class="{{ $trclass }} {{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}{{ ($holding->is_annotated) ? ' text-warning' : '' }}">
			<td>@if (!($holding->locked)) <input id="holding_id" name="holding_id[]" type="checkbox" value="<?= $holding->id; ?>" class="pull-left hld selhld"> @endif
			</td>
			<td class="table_order">{{ $hol_order }}</td>
			@foreach ($fieldstoshow as $field)
			@if (($field != 'ocrr_ptrn') && ($field != 'actions')) 
			<?php $k++; ?>					
			<td>
				@if($hol_order == 1) 
				<div class="change-size-box">					
					<i class="fa fa-exchange"></i>
					<div class="change-size-controls" target="field_<?php echo $field; ?>">							
						<i class="fa expand change-size fa-arrow-circle-o-right"></i><i class="fa compress change-size fa-arrow-circle-o-left"></i>  
					</div>  
				</div>
				@endif
				<div class="field_<?php echo $field; ?> dinamic" <?php if ($_COOKIE[$profile.'_'.$field] != '') { echo ' style="width:'.$_COOKIE[$profile.'_'.$field].'"'; }?>>
					{{ $holding->show( $field ) }}
				</div>
			</td>
			@elseif($field == 'ocrr_ptrn')
			<td class="ocrr_ptrn">
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
			</td>
			<!-- <td>{{ $holding->library->code }}</td> -->
			@elseif($field == 'actions')
			<td class="actions" holding="{{ $holding -> id }}">{{ $holding -> bibuser_actions($holdingsset, $hol_order) }}</td>
			@endif
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>
@endforeach

<script type="text/javascript">

	var Changing = null;
	var ColumnEdited = null;

	$(function() {
		if ($('li#' + <?php echo $holdingsset-> id ?> + ' table.full-hos tbody tr').length > 10) {
			ths = $('li#' + <?php echo $holdingsset-> id ?> + ' table.full-hos th');
			ths1 = $('li#' + <?php echo $holdingsset-> id ?> + ' table.header-only th');
			for (var i = 0; i < $(ths).length; i++) {
				$(ths1[i]).css('min-width', $(ths[i]).css('width'))
				$(ths1[i]).css('width', $(ths[i]).css('width'))
			}
			onlyheader<?php echo $holdingsset-> id ?> = $('li#' + <?php echo $holdingsset-> id ?> + ' table.header-only');
			header<?php echo $holdingsset-> id ?> = $('li#' + <?php echo $holdingsset-> id ?> + ' table.flexme thead tr');
			var a;
			var b;
			a = onlyheader<?php echo $holdingsset-> id ?>;
			b = header<?php echo $holdingsset-> id ?>;
		    // console.log(a)
		    // console.log(b)
		    // var timer<?php echo $holdingsset-> id ?> = setInterval(gettogether(a, b), 100);

		    $(window).scroll(function() {
		    	// console.log(a);
		    	// console.log(b);
		    	// console.log(jQuery(b).offset().left);
		    	// console.log(jQuery(a).offset().left);
		    	jQuery(a).offset({ left: jQuery(b).offset().left-1 });

		    	if ( jQuery(b).offset().top >  jQuery('.page-header').offset().top +  parseInt(jQuery('.page-header').height()) ) {
		    		jQuery(a).offset({ top: jQuery(b).offset().top});		    		
		    		jQuery(a).fadeOut();
		    	}
		    	else {
		    		jQuery(a).offset({ top: jQuery('.page-header').offset().top +  parseInt(jQuery('.page-header').height()) + 10 });
		    		jQuery(a).fadeIn();
		    	}
		    	// gettogether(a, b);
		    })
		    $(window).mousemove(function() {
		    	// console.log(a);
		    	// console.log(b);
		    	// console.log(jQuery(b).offset().left);
		    	// console.log(jQuery(a).offset().left);
		    	jQuery(a).offset({ left: jQuery(b).offset().left-1 });

		    	// gettogether(a, b);
		    })

		    function gettogether(x, y) {
		    	Offset = jQuery(b).offset().left;
		    	jQuery(x).offset({ left: Offset });
		    }
		}
		else {
			$('li#' + <?php echo $holdingsset-> id ?> + ' table.header-only').remove();
		}


		/* Profile Code */


		$('.expand').on('mousedown', function() {
			ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
			window.clearInterval(Changing);
			$(ColumnEdited).removeAttr('touched');
			if (($(ColumnEdited).attr('touched') == undefined) || ($(ColumnEdited).attr('touched') == '')) {	
				$(ColumnEdited).attr('touched', 0); 
				Changing = window.setInterval(function() {
					if (($(ColumnEdited).width() > 1000) || ($(ColumnEdited).attr('touched') > 67)) {
						window.clearInterval(Changing);
						$(ColumnEdited).removeAttr('touched'); 
						return false
					}
					else {
						$(ColumnEdited).css('width', $(ColumnEdited).width() + 15);
						var temp = $(ColumnEdited).attr('touched');
						temp++;
						$(ColumnEdited).attr('touched', temp);
					}
				}, 50);
			}

		})

		$('.expand').on('mouseup', function() {
			window.clearInterval(Changing);
			ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
			$(ColumnEdited).removeAttr('touched'); 
		})
		// $('.expand').on('mouseout', function() {
		// 	window.clearInterval(Changing);
		// 	ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
		// 	$(ColumnEdited).removeAttr('touched'); 
		// })

$('.compress').on('mousedown', function() {
	window.clearInterval(Changing);
	$(ColumnEdited).removeAttr('touched'); 
	ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
	if (($(ColumnEdited).attr('touched') == undefined) || ($(ColumnEdited).attr('touched') == '')) {	
		$(ColumnEdited).attr('touched', 0); 
		Changing = window.setInterval(function() {
			if (($(ColumnEdited).width() <= 40) || ($(ColumnEdited).attr('touched') > 65)) {
				window.clearInterval(Changing);
				$(ColumnEdited).removeAttr('touched'); 
				return false
			}
			else {
				$(ColumnEdited).css('width', $(ColumnEdited).width() - 15);
				var temp = $(ColumnEdited).attr('touched');
				temp++;
				$(ColumnEdited).attr('touched', temp);
			}
		}, 50);
	}

})

$('.compress').on('mouseup', function() {
	window.clearInterval(Changing);
	ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
	$(ColumnEdited).removeAttr('touched'); 
})
		// $('.compress').on('mouseout', function() {
		// 	window.clearInterval(Changing);
		// 	ColumnEdited = $('div.' + $(this).parents('.change-size-controls').attr('target'));
		// 	$(ColumnEdited).removeAttr('touched'); 
		// })


})
</script>
<style type="text/css">
	table.flexme td .dinamic {
		display: inline-block;
		min-width: 40px;
		overflow: hidden;
		vertical-align: middle;
	}
	table.flexme td .change-size-box {
		display: inline-block;
		margin-left: -10px;
		position: relative;
		vertical-align: middle;
	}
	table.flexme td .change-size-box .fa-exchange {
		font-size: 10px;
	}
	table.flexme td .change-size-box .change-size-controls {
		background: none repeat scroll 0 0 hsl(0, 0%, 100%);
		border-radius: 5px;
		display: none;
		height: 46px;
		left: -5px;
		padding: 3px;
		position: absolute;
		top: -20px;
		width: 26px;
	}
	table.flexme td .change-size-box .change-size-controls .fa.change-size {
		color: hsl(240, 100%, 50%) !important;
		cursor: pointer !important;
		font-size: 20px;
		position: absolute;
	}
	table.flexme td .change-size-box .change-size-controls .fa.change-size.compress {
		bottom: 0;
		left: 3px;
	}
	table.flexme td .change-size-box .change-size-controls .fa.change-size.expand {
		left: 3px;
	}
	table.flexme td .change-size-box:hover .change-size-controls {
		display: block;
	}
	table.flexme td .change-size-box + dinamic {
		margin-left: -5px;
	}
</style>