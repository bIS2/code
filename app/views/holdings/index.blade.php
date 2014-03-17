@extends('layouts.default')
<?php 
	$fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show_ok_hols');
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
					<th></th>
					<th>
						<input id="select-all" class="select-all" name="select-all" type="checkbox" value="1" data-target="#holdings-targets" {{ ( Authority::can('create','Hlist') ) ? '' : 'disabled' }}>
					</th>
					<th class="actions" style="width:10px !important">
						{{trans('general.actions')}}
					</th>
					<th>{{trans('general.state')}}</th>
					<?php	$k = 1; ?>

					@foreach ($fieldstoshow as $field) 
						<?php $k++; ?>
						@if ($k == 2) 
						<th>{{ trans('fields.ocrr_ptrn') }} <span class="fa fa-info-circle"></span></th> 
						@else										
						<th>{{ trans('fields.'.$field) }} <span class="fa fa-info-circle"></span></th> 
						@endif

					@endforeach	

				</tr>
			</thead>
			<tbody id="holdings-targets" class="selectable">
			@foreach ($holdings as $holding)

				<tr id="<?= $holding->id ?>" class="{{ $holding->css }} draggable" data-holdingsset="{{$holding->holdingsset_id}}" >
						<td>
							@if (Authority::can('create','Hlist')) 
								<span class="move">
				    				<i class="fa fa-ellipsis-v"></i>
				    				<i class="fa fa-ellipsis-v"></i>
								</span>
							@endif
						</td>
						<td style="width:5px !important">
							<input type="checkbox" value="{{ $holding->id }}" name="holding_id[]" class="sel hl" {{ ( Authority::can('create','Hlist') ) ? '' : 'disabled' }} />
						</td>
					<td id="{{ $holding->id }}" class="actions" >
						@include('holdings.actions')
					</td>
					<td class="state">
						<span class="label label-primary">
							{{ trans('states.'.$holding->state) }}
						</span>	
					</td>

					<?php $k = 1; ?>
					@foreach ($fieldstoshow as $field)
						<?php $k++ ?>
						@if ($k == 2)
							<td class="ocrr_ptrn">

								{{ $holding->patrn_no_btn }}
								{{ $this->ocrr_ptrn }}
								<i class="glyphicon glyphicon-question-sign pop-over" data-content="<strong>{{ $holding -> f866a }}</strong>" data-placement="top" data-toggle="popover" data-html="true" type="button" data-trigger="hover" data-original-title="" title=""></i>
							</td>
						@else 
						<?php 
							$k++;
							$field = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size') && ($field != 'sys2')) ? $field = 'f'.$field : $field; 
						?>						
						<td>

							{{ $holding->show( $field ) }}

						</td>
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
		 <div class="modal" id="form-create-list"></div><!-- /.modal -->
		 <div class="modal" id="modal-show"></div><!-- /.modal -->
		</div>
		<div id="field_size_in_blank" class="hide">{{ trans('errors.field_size_in_blank') }}</div>


@stop