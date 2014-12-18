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
			<table id="holdings-items" class="table table-bordered table-condensed flexme datatable">
			<thead >
				<tr>
					<th></th>
					<th>
<!-- 						<input id="select-all" class="select-all" name="select-all" type="checkbox" value="1" data-target="#holdings-targets" {{ ( Authority::can('create','Hlist') ) ? '' : 'disabled' }} />
 -->					<div class="btn-group">
						  <a class="dropdown-toggle btn-xs" data-toggle="dropdown" {{ ( Authority::can('create','Hlist') ) ? '' : 'disabled' }}>
						    <i class="fa fa-check-square" ></i> <span class="caret"></span>
						  </a>
						  <ul class="dropdown-menu" role="menu">
						    <li><a href="#" class="select" data-check="false" data-target="-">{{ trans('general.clear') }}</a></li>
						    <li role="presentation" class="divider"></li>
						    <li><a href="#" class="select" data-check="false" data-target="">{{ trans('general.all') }}</a></li>
						    <li><a href="#" class="select" data-check="false" data-target=".confirmed"> {{ trans('general.select').' '.trans('states.confirmed') }}</a></li>
						    <li><a href="#" class="select" data-check="false" data-target=".revised_ok"> {{ trans('general.select').' '. trans('states.revised_ok') }}</a></li>
						    <li><a href="#" class="select" data-check="false" data-target=".delivery"> {{ trans('general.select').' '. trans('states.delivery') }}</a></li>
						  </ul>
						</div>

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
						@endif
						@if ($field == '866a')
						<th>{{ trans('holdings.f866atitle') }} <span class="fa fa-info-circle"></span></th> 
						@else
						<th>{{ trans('fields.'.$field) }} <span class="fa fa-info-circle"></span></th> 
						@endif

					@endforeach	

				</tr>
			</thead>
			<tbody id="holdings-targets" class="selectable">
			@foreach ($holdings as $holding)

				<tr id="<?= $holding->id ?>" class="{{ $holding->css }} draggable ui-selected {{ $holding->state}}" data-holdingsset="{{$holding->holdingsset_id}}" >
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
						@endif

						<?php 
							$k++;
						$field = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size')  && ($field != 'size_dispatchable') && ($field != 'sys2')) ? $field = 'f'.$field : $field; 
						?>						

						<td>

							{{ $holding->show( $field ) }}

						</td>


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
		 <div class="modal" id="form-to-list"></div><!-- /.modal -->
		 <div class="modal" id="modal-show"></div><!-- /.modal -->
		</div>
		<div id="field_size_in_blank" class="hide">{{ trans('errors.field_size_in_blank') }}</div>
		<div id="field_size_dispatchable_in_blank" class="hide">{{ trans('errors.field_size_dispatchable_in_blank') }}</div>


@stop
