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
		<div class="">
		  <table id="new-table" class="table table-bordered table-condensed flexme">
		  </table>
			<table id="holdings-items" class="table table-bordered table-condensed flexme">
			<thead>
				<tr>
			
						<th></th>
			

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
					<td style="width:5px !important">
						@if (Authority::can('create','Hlist')) 
							<input type="checkbox" value="{{ $holding->id }}" name="holding_id[]" class="sel hl" />
						@endif
					</td>
					<td id="{{ $holding->id }}" class="actions" >
						<a href="{{ route('holdings.show', $holding->id) }}" data-target="#modal-show" data-toggle="modal"><span class="glyphicon glyphicon-eye-open" title="{{ trans('holdingssets.see_more_information') }}"></span></a>


						@if (Authority::can('touch', $holding))						
							<a href="http://bis.trialog.ch/sets/from-library/<?= $holding->id; ?>" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" title="{{ trans('holdingssets.see_information_from_original_system') }}">
								<span class="glyphicon glyphicon-list-alt"></span>
							</a>

						  <a href="{{ route('oks.store') }}" class="btn-link btn-xs btn-ok" data-method="post" data-remote="true" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" >
						  	<span class="fa fa-thumbs-up"></span>
						  </a>

						  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs btn-tag">
						  	<span class="fa fa-tags"></span> 
						  </a>

						  <a href="{{ route('reviseds.store') }}" class="btn-link btn-xs btn-send" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
						  	<span class="fa fa-mail-forward"></span> 
						  </a>
						@endif

						@if (Authority::can('receive',$holding))
						  <a href="{{ route('receiveds.store') }}" class="btn-link btn-xs btn-send" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
						  	<span class="fa fa-download"></span> 
						  </a>
					  @endif
					</td>

				<?php $k = 0; ?>
					@foreach ($fieldstoshow as $field)
						@if ($field != 'ocrr_ptrn')  
						<?php $k++;
							$field = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size')) ? $field = 'f'.$field : $field; 
						?>						
							<td>
								<?php if ($field == 'size') {  ?>
									@if (Authority::can('set_size', $holding))
										<a href="#" class="editable" data-type="text" data-pk="{{$holding->id}}" data-url="{{ route('holdings.update',[$holding->id]) }}" >{{ $holding->size }} </a>
									@else
										{{ $holding->size }}
									@endif
								<?php	}
								else {
									$str = htmlspecialchars($holding->$field);
									if (strlen($str) > 30) { ?>
										<span class="pop-over" data-content="<strong>{{ $str }}</strong>" data-placement="top" data-toggle="popover" data-html="true" class="btn btn-default" type="button" data-trigger="hover">{{ truncate($str, 30) }}</span>
									<?php }
									else {
										echo $str;
									}
								}
								?>
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
		 <div class="modal" id="modal-show"></div><!-- /.modal -->
		</div>

	@include('hlists.create')

@stop

