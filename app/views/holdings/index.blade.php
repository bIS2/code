@extends('layouts.default')
<?php 
$fieldstoshow = Session::get(Auth::user()->username.'_fields_to_show_ok_hols');
$fieldstoshow = explode(';',$fieldstoshow);

$sizeofields = $_COOKIE[Auth::user()->username.'_size_of_fields'];
$sizeofields = explode(';',$sizeofields);

$defaultsize = 100;

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
<?php $k = -1; ?>
@foreach ($fieldstoshow as $field) 

	<?php $k++; $sizeofield = ($sizeofields[$k] > 0) ? $sizeofields[$k] : $defaultsize ; ?>
	@if ($field == 'actions')
	<th>
		<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
			{{ trans('fields.'.$field) }}
		</div>
	</th>		
	@endif
	@if ($field == 'state')
	<th>
		<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
			{{ trans('fields.'.$field) }} <span class="fa fa-info-circle"></span>
		</div>
	</th>		
	@endif
	@if ($field == '866a')
	<th>
		<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
			{{ trans('holdings.f866atitle') }} <span class="fa fa-info-circle"></span>
		</div>
	</th> 
	@else
	<th>
		<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
			{{ trans('fields.'.$field) }} <span class="fa fa-info-circle"></span>
		</div>
	</th> 
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

		<?php $k = -1; ?>
		@foreach ($fieldstoshow as $field)

			<?php $k++; $sizeofield = ($sizeofields[$k] > 0) ? $sizeofields[$k] : $defaultsize ; ?>
			@if ($field == 'actions')
			<td id="{{ $holding->id }}" class="actions" >
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					@include('holdings.actions')
				</div>
			</td>	
			@elseif ($field == 'state')
			<td class="state">
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					<span class="label label-primary">
						{{ trans('states.'.$holding->state) }}
					</span>	
				</div>
			</td>	
			@elseif ($field == 'ocrr_ptrn')
			<td class="ocrr_ptrn">
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ $holding->patrn_no_btn }}

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
				</div>
			</td>
			@else

			<?php 
			$field = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size')  && ($field != 'size_dispatchable') && ($field != 'sys2')) ? $field = 'f'.$field : $field; 
			?>						
			<td>
				<div class="field_<?php echo $field; ?> dinamic" <?php echo 'style="width:'.$sizeofield.'px"'; ?>>
					{{ $holding->show( $field ) }}
				</div>
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
	<div class="modal" id="form-to-list"></div><!-- /.modal -->
	<div class="modal" id="modal-show"></div><!-- /.modal -->
</div>
<div id="field_size_in_blank" class="hide">{{ trans('errors.field_size_in_blank') }}</div>
<div id="field_size_dispatchable_in_blank" class="hide">{{ trans('errors.field_size_dispatchable_in_blank') }}</div>


@stop
