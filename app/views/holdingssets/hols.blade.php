@foreach ($holdingssets as $holdingsset)
<?php 
	$HOSconfirm   = $holdingsset->confirm()->exists();
	$HOSannotated = $holdingsset->is_annotated;
	$fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show_ok');
	$fieldstoshow = explode(';',$fieldstoshow);
?>
<table class="table table-hover flexme table-bordered draggable <?= ($HOSconfirm) ? 'confirm' : ''; ?>">
	<thead>
		<tr>
			<th></th>
			<th class="table_order">No.</th>
			<th class="actions">Actions</th>
			<?php	$k = 0; ?>
			@foreach ($fieldstoshow as $field) 
				@if ($field != 'ocrr_ptrn') <?php $k++; ?>										
					<th>{{ $field; }} <span class="fa fa-info-circle"></span></th> 
						@if ($k == 1)
						<th class="hocrr_ptrn">{{ trans('holdingssets.ocurrence_patron') }}
							<a href="{{ route('sets.show', $holdingsset->id) }}" data-target="#set-show" data-toggle="modal"><span class="glyphicon glyphicon-question-sign" title="{{ trans('holdingssets.see_more_information') }}"></span></a>
							@if (!$HOSconfirm)
								<a set="<?=$holdingsset->id; ?>" href="<?= action('HoldingssetsController@putRecallHoldingsset',[$holdingsset->id]); ?>" data-remote="true" data-method="put" data-disable-with="..." data-disable-with="..." class="forceblue pop-over" data-content="<?= trans('holdingssets.recall_HOS'); ?>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><i class="fa fa-refresh text-danger"></i></a>
							@endif
						</th>
						<th>hbib <span class="fa fa-info-circle"></span></th>
					@endif
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
				$auxtrclass 	= (($holding->is_aux == 't') || ($holding->is_aux == '1')) ? ' is_aux' : ''; 
				// var_dump($holding->is_aux);
				if (isset($aux_ptrn[$i]))  $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
				$preftrclass 	= ($holding->is_pref == 't') ? ' is_pref' : '';
				$librarianclass = ' '.substr($holding->sys2, 0, 4); 
			?>	
			<tr id="holding{{ $holding -> id; }}" holding="{{ $holding -> id; }}" class="{{ $trclass }} {{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}{{ ($holding->is_annotated) ? ' text-warning' : '' }}">
				<td>
					<?php if (!($holding->locked)) : ?>
	            		<input id="holding_id" name="holding_id[]" type="checkbox" value="<?= $holding->id; ?>" class="pull-left hld selhld">
	          		<?php endif ?>
				</td>

			<td class="table_order">{{ $hol_order }}</td>
				<td class="actions" holding="{{ $holding -> id }}">
					{{ $holding -> bibuser_actions($holdingsset, $hol_order) }}
				</td>
				<?php $k = 0; ?>
					@foreach ($fieldstoshow as $field)

						@if ($field != 'ocrr_ptrn')  

						<?php $k++; ?>					
							<td>
								{{ $holding->show( $field ) }}
							</td>
							@if ($k == 1)
								<td class="ocrr_ptrn">
									{{ $holding -> patrn }}
									<i class="glyphicon glyphicon-question-sign pop-over" data-content="<strong>
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
								<td>{{ $holding->library->code }}</td>
							@endif
						@endif
					@endforeach
			</tr>
		@endforeach
	</tbody>
</table>
@endforeach