@extends('layouts.default')
<?php 
	$fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show_ok');
	$fieldstoshow = explode(';',$fieldstoshow);
?>
{{-- Content --}}
@section('content')

@section('toolbar')
	@include('holdings.toolbar')	
	@include('holdings.hliststabs')
@stop

	<div class="row">
		<div class="col-xs-12">
		  <table id="new-table" class="table table-bordered table-condensed flexme">
		  </table>
			<table id="holdings-items" class="table table-bordered table-condensed flexme">
			<thead>
				<tr>
					@if ( Authority::can('create','Hlist') ) 
						<th>
							<input id="select-all" class="select-all" name="select-all" type="checkbox" value="1" data-target="#holdings-targets">
						</th>
					@endif
					<th class="actions">{{ trans('general.actions') }}</th>
					<?php	$k = 0; ?>
					@foreach ($fieldstoshow as $field) 
						@if ($field != 'ocrr_ptrn') <?php $k++; ?>										
							<th>{{ $field; }} <span class="fa fa-info-circle"></span></th> 
								@if ($k == 2)
								<th class="hocrr_ptrn">{{ trans('holdingssets.ocurrence_patron') }}
								</th>
							@endif
						@endif
					@endforeach	
				</tr>
			</thead>
			<tbody id="holdings-targets" class="selectable">
			@foreach ($holdings as $holding)

				<tr id="<?= $holding->id ?>" class="{{ $holding->css }}" data-holdingsset="{{$holding->holdingsset_id}}" >
					@if (Authority::can('create','Hlist')) 
						<td style="width:5px !important">
								<input type="checkbox" value="{{ $holding->id }}" name="holding_id[]" class="sel hl" />
						</td>
					@endif
					<td id="{{ $holding->id }}" class="actions" >

						<div class="btn-group actions-menu" data-container="body">
						  <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
						    {{{ trans('general.action')}}} <span class="caret"></span>
						  </button>
						  <ul class="fa dropdown-menu" role="menu">

						    <li class="btn btn-xs">
									<a href="{{ route('holdings.show', $holding->id) }}" data-target="#modal-show" data-toggle="modal" >
										<span class="fa fa-eye" title="{{ trans('holdingssets.see_more_information') }}" data-placement="top" data-toggle="popover" data-trigger="hover"></span>
									</a>
						    </li>

								@if (Authority::can('touch', $holding))

									<li class="btn btn-xs" >
										<a href="http://bis.trialog.ch/sets/from-library/<?= $holding->id; ?>" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" title="{{ trans('holdingssets.see_information_from_original_system') }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover">
											<span class="fa fa-external-link"></span>
										</a>
									</li>						
									<li class="btn btn-xs">
									  <a href="{{ route('oks.store') }}" class="btn-ok" data-method="post" data-remote="true" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" >
									  	<span class="fa fa-thumbs-up"></span>
									  </a>
									</li>
									<li class="btn btn-xs">
									  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-tag">
									  	<span class="fa fa-tags"></span> 
									  </a>
									</li>
								@endif

								@if (Authority::can('revise', $holding))

									<li class="btn btn-xs">
									  <a href="{{ route('reviseds.store') }}" class="btn-send" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
									  	<span class="fa fa-mail-forward"></span> 
									  </a>
									</li>
									
								@endif

								
								@if (Authority::can('receive',$holding))
									<li class="btn btn-xs">
									  <a href="{{ route('receiveds.store') }}" class="" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
									  	<span class="fa fa-download"></span> 
									  </a>
									</li>
									<li class="btn btn-xs">
									  <a href="{{ route('comments.create',['holding_id'=>$holding->id]) }}" title="{{ trans('general.comment') }}" class="btn-comment" data-toggle="modal" data-target="#form-create-comments">
									  	<span class="fa fa-comment"></span> 
									  </a>
									</li>
							  @endif

						  </ul>
						</div>
					</td>

				<?php $k = 0; ?>
					@foreach ($fieldstoshow as $field)

						@if ($field != 'ocrr_ptrn')  

						<?php $k++;
							$field = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size')) ? $field = 'f'.$field : $field; 
						?>						
							<td>
								@if ($field == 'size') 

									@if (Authority::can('set_size', $holding))
										<a href="#" class="editable" data-type="text" data-pk="{{$holding->id}}" data-url="{{ route('holdings.update',[$holding->id]) }}" >{{ $holding->size }} </a>
									@else
										{{ $holding->size }}
									@endif

								@else 

									{{ $holding->show( $field ) }}

								@endif
							</td>

							@if ($k == 2)
								<td class="ocrr_ptrn">
									{{ $holding -> patrn_no_btn }}
									<i class="glyphicon glyphicon-question-sign pop-over" data-content="<strong>{{ $holding -> f866a }}</strong>" data-placement="top" data-toggle="popover" data-html="true" class="btn btn-default" type="button" data-trigger="hover" data-original-title="" title=""></i>
								</td>
							@endif

						@endif

					@endforeach
				</tr>
			@endforeach
			</tbody>
		</table>
		</div>
		</div>
		<div class="remote">
		 <div class="modal" id="form-create-notes"></div><!-- /.modal -->
		 <div class="modal" id="form-create-comments"></div><!-- /.modal -->
		 <div class="modal" id="modal-show"></div><!-- /.modal -->
		</div>

	@include('hlists.create')

@stop

