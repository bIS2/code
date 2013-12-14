<?php //var_dump($holdingssets); ?>
@foreach ($holdingssets as $holdingsset)
	<?php 
		$HOSconfirm 	= $holdingsset->confirm()->exists();
		$HOSannotated = $holdingsset->is_annotated;
		$btn 	= ($HOSannotated) ? 'btn-warning' : 'btn-default';
		$btn 	= ($HOSconfirm) ? 'btn-success disabled' : $btn;
		$btn 	= ($holdingsset->is_unconfirmable) ? 'btn-success' : $btn;
	?>
		<li id="{{ $holdingsset -> id; }}">
			  <div class="panel-heading row">
		  		<input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="{{ $holdingsset->id }}" class="pull-left hl sel">
		      <div href="#{{ $holdingsset -> sys1; }}{{$holdingsset -> id;}}" data-parent="#group-xx" title="{{ $holdingsset->f245a ;}}" data-toggle="collapse" class="accordion-toggle collapsed col-xs-10" opened="0">
		      	{{ $holdingsset->sys1.' :: '.htmlspecialchars(truncate($holdingsset->f245a, 100),ENT_QUOTES); }}
		      	@if ($holdingsset->has('holdings') && $count1 = $holdingsset -> holdings -> count()) 
		      		<span class="badge"><i class="fa fa-files-o"></i> {{ $count1 }} </span><p class="separator">-</p>
		      	@endif
		      	@if ($holdingsset->has('groups') && ($count=$holdingsset->groups->count()>0))
		      		<span class="badge ingroups" title = "{{ $holdingsset -> showlistgroup }}"
		      		><i class="fa fa-folder-o"></i> {{ $holdingsset->groups->count() }}</span>
		      	@endif
		      </div>
		      <div class="text-right action-ok col-xs-1">
		      	@if (Auth::user()->hasRole('resuser')) 
		      	@else
		      	<a id="holdingsset{{ $holdingsset -> sys1;}}" href="@if ($btn != 'btn-success disabled'){{route('confirms.store',['holdingsset_id' => $holdingsset->id])}}@endif" class="btn btn-ok btn-xs {{ $btn }}" data-remote="true" data-method="post" data-disable-with="..." title="@if ($btn != 'btn-success disabled'){{ trans('holdingssets.confirm_ok_HOS') }} @else {{ trans('holdingssets.confirmed_HOS') }}@endif">
		      			<span class="glyphicon glyphicon-thumbs-up"></span>
		      	</a>		
		      	@endif      	
		      </div>
		      	@if ((isset($group_id)) && ($group_id > 0))
	      			<span class="btn btn-primary btn-xs move" title="{{ trans('holdingssets.drag_and_drop_into_a_grouptab_to_move_this_HOS_to_another_HosGroup'); }}"><i class="glyphicon glyphicon-move"></i></span>
	      			<a class="trash btn btn-error btn-xs" title="{{ trans('holdingssets.remove_hos_from_this_group'); }}" href="{{ action('HoldingssetsController@putDeleteHosFromGroup',[$holdingsset->id]) }}" data-params="group_id={{ $group_id }}" data-remote="true" data-method="put" data-disable-with="..."><i class="glyphicon glyphicon-trash"></i></a>
      			@else
							<span class="move btn btn-primary btn-xs" title="{{ trans('holdingssets.drag_and_drop_into_a_grouptab_to_add_this_HOS_to_a_HosGroup'); }}"><i class="fa fa-copy"></i></span>
      			@endif
			  </div>	
	  		<div class="panel-collapse collapse container" id="{{$holdingsset -> sys1;}}{{$holdingsset -> id;}}">
			    <div class="panel-body">
						<?php 
							$fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show');
							$fieldstoshow = explode(';',$fieldstoshow);
						?>
						<table class="table table-hover flexme table-bordered draggable">
							<thead>
								<tr>
									<th class="table_order">No.</th>
									@if (!($HOSconfirm))
										<th class="actions">Actions</th>
									@endif
									<?php	$k = 0; ?>
									@foreach ($fieldstoshow as $field) 
										@if ($field != 'ocrr_ptrn') 
											<?php $k++; ?>											
											<th>{{ $field; }} <span class="fa fa-info-circle"></span></th> 
												@if ($k == 1)
												<th class="hocrr_ptrn">ocrr_ptrn
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
								@foreach ($holdingsset -> holdings as $holding)
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
									@if (!($HOSconfirm))

										<td class="actions">
			      					@if (Auth::user()->hasRole('resuser'))
			      						@if ($holding->locked()->exists())
								      		<a id="holding{{ $holding -> id; }}lock" set="{{$holdingsset->id}}" href="{{ route('lockeds.store',['holding_id' => $holding->id]) }}" class="pop-over {{ $btnlock }}" data-remote="true" data-method="post" data-params="holdingsset_id={{$holdingsset->id}}"  data-disable-with="..." data-content="<strong>Locked By: </strong>{{ $holding->locked->user->name }}<hr><strong>Comments:</strong> {{ $holding->locked->comments }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ><span class="glyphicon glyphicon-lock"></span></a>
												@else
													<a id="holding{{ $holding -> id; }}lock" set="{{$holdingsset->id}}" href="#" class="editable " data-type="text" data-pk="{{$holdingsset->id}}" data-url="{{ route('lockeds.update',[$holding->id]) }}" title="@if ($btn != 'btn-success disabled') {{ trans('holdinssets.lock_hol') }} @else {{ trans('holdingssets.unlock_hol') }}@endif"><span class="glyphicon glyphicon-lock"></span></a>
												@endif
											@else
												@if (!($holding->locked)) 
													
													<a href="{{ route('holdings.show', $holding->id) }}" data-target="#modal-show" data-toggle="modal"><span class="glyphicon glyphicon-eye-open" title="{{ trans('holdingssets.see_more_information') }}"></span></a>
													<a href="http://bis.trialog.ch/sets/from-library/{{ $holding->id; }}" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" title="{{ trans('holdingssets.see_information_from_original_system') }}"><span class="glyphicon glyphicon-list-alt"></span></a>
									      	<a id="holding{{$holding -> id;}}delete" set="{{$holdingsset->id}}"  href="{{ action('HoldingssetsController@putNewHOS',[$holding->id]) }}" data-remote="true" data-method="put" data-params="holdingsset_id={{$holdingsset->id}}"  data-disable-with="..." title="{{ trans('holdingssets.remove_from_HOS') }}"><span class="glyphicon glyphicon-trash"></span></a>
									      	
									      	@if ($ownertrclass == '')
														<a id="holding{{$holding -> id;}}forceowner" set="{{$holdingsset->id}}" href="{{ action('HoldingssetsController@putForceOwner',[$holding->id]) }}" data-remote="true" data-method="put" data-params="holdingsset_id={{$holdingsset->id}}" data-disable-with="..." title="{{ trans('holdingssets.force_owner') }}"><span class="fa fa-stop text-danger"></span></a>
													@endif

													@if ($auxtrclass == '')
														<a id="holding{{$holding -> id;}}forceaux" set="{{$holdingsset->id}}" href="{{ action('HoldingssetsController@putForceAux',[$holding->id]) }}" data-remote="true" data-method="put" data-params="holdingsset_id={{$holdingsset->id}}" data-disable-with="..." title="{{ trans('holdingssets.force_aux') }}"><span class="fa fa-stop text-warning"></span></a>
													@endif

													@if ($holding->is_annotated)
														<a href="{{ route('notes.create',['holding_id'=>$holding->id, 'consult' => '1']) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs btn-tag">
															<span class="fa fa-tags text-danger"></span>
														</a>
													@endif
												@else
													<a id="holding{{ $holding -> id; }}lock" class="pop-over {{ $btnlock }}" data-content="<strong>Locked By: </strong>{{ $holding->locked->user->name }}<hr><strong>Comments:</strong> {{ $holding->locked->comments }}" data-placement="top" data-toggle="popover" data-html="true" class="btn btn-default" type="button" data-trigger="hover" data-original-title="" title=""><span class="glyphicon glyphicon-lock"></span></a>
												@endif

							      	@endif 
										</td>
										@endif
										<?php $k = 0; ?>
											@foreach ($fieldstoshow as $field)
												@if ($field != 'ocrr_ptrn')  <?php $k++; ?> @endif
												@if ($field != 'sys2') <?php $field = 'f'.$field; ?>							
													<td>{{htmlspecialchars($holding->$field);}}</td>
													@if ($k == 1)
														<td class="ocrr_ptrn">
															{{ $holding -> patrn }}
															<i class="glyphicon glyphicon-question-sign pop-over" data-content="<strong>866a: </strong>{{ $holding -> f866a }}" data-placement="top" data-toggle="popover" data-html="true" class="btn btn-default" type="button" data-trigger="hover" data-original-title="" title=""></i>
														</td>
														<td>{{ $holding->library->code; }}</td>
													@endif
												@endif 
											@endforeach
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
		</li>
	@endforeach