@foreach ($holdingssets as $holdingsset)
<?php 
	// var_dump($holdingsset);
	$HOSconfirm 	= $holdingsset->confirm()->exists();
	$HOSannotated = $holdingsset->is_annotated;
	$HOSincorrect = $holdingsset->is_incorrect;
	$btn 	= 'btn-default';
	$route = ($HOSincorrect) ? 'incorrects' : 'confirms';
	$txt 	= ($HOSannotated) ? ' text-warning' : '';
	$btn 	= ($HOSconfirm) ? 'btn-success disabled' : $btn;
	$btn 	= ($holdingsset->is_unconfirmable) ? 'btn-success' : $btn;
	$btn 	= ($HOSincorrect) ? 'btn-danger' : $btn;
	$fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show_ok');
	$fieldstoshow = explode(';',$fieldstoshow);
?>
<table class="table table-hover flexme table-bordered draggable">
	<thead>
		<tr>
			<th class="table_order">No.</th>
			@if (!($HOSconfirm) || $HOSannotated)
				<th class="actions">Actions</th>
			@endif
			<?php	$k = 0; ?>
			@foreach ($fieldstoshow as $field) 
				@if ($field != 'ocrr_ptrn') <?php $k++; ?>										
					<th>{{ $field; }} <span class="fa fa-info-circle"></span></th> 
						@if ($k == 1)
						<th class="hocrr_ptrn">{{ trans('holdingssets.ocurrence_patron') }}
							<a href="{{ route('sets.show', $holdingsset->id) }}" data-target="#set-show" data-toggle="modal">
								<span class="glyphicon glyphicon-question-sign" title="{{ trans('holdingssets.see_more_information') }}"></span>
							</a>
						</th>
						<th>hbib <span class="fa fa-info-circle"></span></th>
					@endif
				@endif
			@endforeach						
		</tr>
	</thead>
	<tbody>
		<?php $hol_order = 0; ?>
		@foreach ($holdingsset -> holdings->take(100) as $holding)
			<?php 
				$hol_order++;
				$btnlock 	= ($holding->locked()->exists()) ? 'btn-warning ' : '';	
				$trclass 	= ($holding->locked()->exists()) ? 'locked' : '';
				$ownertrclass = ($holding->is_owner == 't') ? ' is_owner' : '';
				$auxtrclass 	= ($holding->is_aux == 't') ? ' is_aux' : ''; 
				if (isset($aux_ptrn[$i]))  $classaux = ($aux_ptrn[$i] == '1') ? ' aux' : ''; 
				$preftrclass 	= ($holding->is_pref == 't') ? ' is_pref' : '';
				$librarianclass = ' '.substr($holding->sys2, 0, 4); 
			?>	
			<tr id="holding{{ $holding -> id; }}" class="{{ $trclass }}{{ $ownertrclass }}{{ $auxtrclass }}{{ $preftrclass }}{{ $librarianclass }}{{ ($holding->is_annotated) ? ' text-warning' : '' }}">
			<td class="table_order">{{ $hol_order }}</td>
			@if (!($HOSconfirm) || $HOSannotated)
				<td class="actions" holding="{{ $holding -> id }}">
					@if (!($HOSconfirm) && !($HOSincorrect))
  					@if (Auth::user()->hasRole('resuser'))
	  					@if ($holding->locked()->exists())
				      	<a id="holding{{ $holding -> id; }}lock" set="{{$holdingsset->id}}" href="{{ route('lockeds.store',['holding_id' => $holding->id]) }}" class="pop-over {{ $btnlock }} pull-right" data-remote="true" data-method="post" data-params="holdingsset_id={{$holdingsset->id}}"  data-disable-with="..." data-content="<strong>{{ trans('holdingssets.reserved_by') }} </strong>{{ $holding->locked->user->name }}<br><strong>{{ trans('holdingssets.on_behalf_of') }}</strong> {{ $holding->locked->comments }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="glyphicon glyphicon-lock"></span></a>
							@else
								<a id="holding{{ $holding -> id; }}lock" set="{{$holdingsset->id}}" href="#" class="editable  pull-right" data-type="text" data-pk="{{$holdingsset->id}}" data-url="{{ route('lockeds.update',[$holding->id]) }}" title="@if ($btn != 'btn-success disabled') {{ trans('holdinssets.lock_hol') }} @else {{ trans('holdingssets.unlock_hol') }}@endif"><span class="glyphicon glyphicon-lock"></span></a>
							@endif
						@endif
						@if (!($holding->locked)) 
							<input id="holding_id" name="holding_id[]" type="checkbox" value="{{ $holding->id }}" class="pull-left hld selhld">&nbsp;
							<a href="{{ route('holdings.show', $holding->id) }}" data-target="#modal-show" data-toggle="modal" class="pop-over" data-content="<strong>{{ trans('holdingssets.see_more_information') }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="glyphicon glyphicon-eye-open"></span></a>
							<a href="http://bis.trialog.ch/sets/from-library/{{ $holding->id; }}" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" class="pop-over" data-content="<strong>{{ trans('holdingssets.see_information_from_original_system') }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="glyphicon glyphicon-list-alt"></span></a>
				      	&nbsp;|&nbsp;
				      <a id="holding{{$holding -> id;}}delete" set="{{$holdingsset->id}}"  href="{{ action('HoldingssetsController@putNewHOS',[$holding->id]) }}" data-remote="true" data-method="put" data-params="holdingsset_id={{$holdingsset->id}}" data-disable-with="..." class="pop-over" data-content="<strong>{{ trans('holdingssets.remove_from_HOS') }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="glyphicon glyphicon-trash"></span></a>
				      <a href="http://bis.trialog.ch/sets/recall-holdings/{{ $holding->id; }}" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" class="pop-over" data-content="<strong>{{ trans('holdingssets.recall_hos_from_this_holding') }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-crosshairs"></span></a>
				      	<!-- <a href="http://bis.trialog.ch/sets/similarity-search/{{ $holding->id; }}" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" class="pop-over" data-content="<strong>{{ trans('holdingssets.similarity_search_from_this_holding') }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-search"></span></a> -->
				      	&nbsp;|&nbsp;
				      @if ($ownertrclass == '')
								<a id="holding{{$holding -> id;}}forceowner" set="{{$holdingsset->id}}" href="{{ action('HoldingssetsController@putForceOwner',[$holding->id]) }}" data-remote="true" data-method="put" data-params="holdingsset_id={{$holdingsset->id}}" data-disable-with="..." data-disable-with="..." class="pop-over" data-content="<strong>{{ trans('holdingssets.force_owner') }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-stop text-danger"></span></a>
							@endif
								<a id="holding{{$holding -> id;}}forceaux" set="{{$holdingsset->id}}" href="{{ action('HoldingssetsController@putForceAux',[$holding->id]) }}" data-remote="true" data-method="put" data-params="holdingsset_id={{$holdingsset->id}}" data-disable-with="..." data-disable-with="..." class="forceaux pop-over" data-content="<strong>{{ trans('holdingssets.force_aux') }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover"><span class="fa fa-stop text-warning"></span></a>
							@endif
		      	@endif
    			@if ($holding->is_annotated)
						<a href="{{ route('notes.create',['holding_id'=>$holding->id, 'consult' => '1']) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs btn-tag pop-over" data-content="<strong>{{ trans('holdingssets.see_storeman_annotations') }}</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover">
							<span class="fa fa-tags text-danger"></span>
						</a>
					@endif
				</td>
			@endif
				<?php $k = 0; ?>
					@foreach ($fieldstoshow as $field)
						@if ($field != 'ocrr_ptrn')  <?php $k++;$field = 'f'.$field; ?>						
							<td>{{htmlspecialchars($holding->$field);}}</td>
							@if ($k == 1)
								<td class="ocrr_ptrn">
									{{ $holding -> patrn }}
									<i class="glyphicon glyphicon-question-sign pop-over" data-content="<strong>{{ $holding -> f866a }}</strong>" data-placement="top" data-toggle="popover" data-html="true" class="btn btn-default" type="button" data-trigger="hover" data-original-title="" title=""></i>
								</td>
								<td>{{ $holding->library->code; }}</td>
							@endif
						@endif
					@endforeach
			</tr>
		@endforeach
	</tbody>
</table>
@endforeach