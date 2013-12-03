@extends('layouts.default')

@section('toolbar')
	@include('holdingssets.toolbar')
@stop

{{-- Content --}}
@section('content')

@include('holdingssets.groupstabs')

	<div>
		<div class="checkbox col-xs-3">
		  <label>
		    <input id="select-all" name="select-all" type="checkbox" value="1">
		    <p class="text-primary"><strong>{{ trans('holdingssets.select_all_hos') }}</strong></p>
		  </label>
		</div>
		<div class="col-xs-12 clearfix">
		  <a href="#collapseTwo" id="filter-btn" class="accordion-toggle btn btn-sm dropdown-toggle pull-right collapsed text-warning" data-toggle="collapse">
    		<span class="fa fa-check"></span> {{{ trans('general.show_hide_fields') }}}
  		</a>
		</div>
		<div class="col-xs-12">
			<div class="accordion" id="FieldsShow">
			   <div id="collapseTwo" class="accordion-body text-right collapse">
					<form method="post" action="{{ route('sets.index', Input::except(['noexists'])) }}">
						<input type="hidden" name="urltoredirect" value="<?= route('sets.index', Input::except(['noexists']));  ?>">
						<?php									
							$allfields 	= explode(';', ALL_FIELDS);
							$tmpfields 	= Session::get(Auth::user()->username.'_fields_to_show');
							
							$fields 		= '';
							if (isset($tmpfields)) {
								$fields 		= explode(';', $tmpfields);
							}
							?>
							<div class="btn-group" data-toggle="buttons">
							<?php
								foreach ($allfields as $field) {
									$checked 				= '';
									$checkactive 		= '';
									if (($field != 'ocrr_ptrn') && ($field != 'sys2')) {
										if (in_array($field, $fields)) {
											$checked 			= "checked = checked";
											$checkactive 	= " active";
										}
										?>
										<label class="btn btn-default{{ $checkactive }}">
											<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" <?= $checked; ?> value="<?= $field; ?>"><?= $field; ?>
										</label>
									<?php }
								}	?>
							</div>
							<input type="hidden" name="fieldstoshow[]" value="ocrr_ptrn">
							<input type="hidden" name="fieldstoshow[]" value="sys2">
							<button type="submit" value="{{ trans('general.save') }}" class="btn btn-success"><span class="glyphicon glyphicon-save"></span> {{ trans('general.save') }} </button>
					</form>
				</div>
			</div>
		</div>
	</div>
<section id="hosg" group_id = "<?php echo $group_id;  ?>">
	<ul class="hol-sets table">
		@include('holdingssets.hos')
	</ul>
</section>	

@include('groups.create')
	<div class="remote">
		<div id="modal-show" class="modal face"><div class="modal-body"></div></div>
	</div>
	<div class="remote">
		<div id="modal-show-external" class="modal face"><div class="modal-body"></div></div>
	</div>
@stop