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
			<?php 

				//echo var_dump($last_query)
			 ?>
		  <table id="new-table" class="table table-bordered table-condensed flexme">
		  </table>
			<table id="holdings-items" class="table table-bordered table-condensed flexme">
			<thead>
				<tr>
					@if ( Authority::can('create','Hlist') ) 
						<th></th>
						<th>
							<input id="select-all" class="select-all" name="select-all" type="checkbox" value="1" data-target="#holdings-targets">
						</th>
					@endif
					<th class="actions">{{ trans('general.actions') }}</th>
					<th>{{trans('general.state')}}</th>
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
						<td>
	    				<i class="fa fa-ellipsis-v"></i>
	    				<i class="fa fa-ellipsis-v"></i>
						</td>
						<td style="width:5px !important">
							<input type="checkbox" value="{{ $holding->id }}" name="holding_id[]" class="sel hl" />
						</td>
					@endif
						</td>
					<td id="{{ $holding->id }}" class="actions" >
						@include('holdings.actions')
					</td>
					<td>
						<span class="label label-default">
							{{ trans('states.'.$holding->state) }}
						</span>	
					</td>

					<?php $k = 0; ?>
					@foreach ($fieldstoshow as $field)

						@if ($field != 'ocrr_ptrn')  

						<?php $k++;
							$field = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size') && ($field != 'sys2')) ? $field = 'f'.$field : $field; 
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
									<i class="glyphicon glyphicon-question-sign pop-over" data-content="<strong>{{ $holding -> f866a }}</strong>" data-placement="top" data-toggle="popover" data-html="true" type="button" data-trigger="hover" data-original-title="" title=""></i>
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

