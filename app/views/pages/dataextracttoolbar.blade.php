<?php $user = Auth::user() ?>
<section class="container navbar navbar-default navbar-fixed-top">
	<div class="page-header clearfix">
		<div id="main-filters" class="row">
			<div class="col-xs-12">
				<div id="filterContainer" class="extract">
					<div class="text-right accordion-group">
						<div class="accordion-body text-left">
							<div class="row">
								<div class="col-xs-12">
									<div class="col-xs-12 text-center">
										<h4 class="text-primary"><span class="fa fa-check"></span> {{ trans('general.select_fields_to_search') }}	</h4>		
										<div id="currentfiltersoption" class="btn-group btn-group-centered btn-group-sm" data-toggle="buttons">
											<?php 											
											foreach ($allsearchablefields as $field) {
												$checked 				= '';
												$checkactive 		= '';
												$value = (($field != 'holtype') && ($field != 'status')) ? Input::get('f'.$field) : Input::get($field);
												if ($value != '') {
													$checked 		= "checked = checked";
													$checkactive 	= " active";
												}
												?>
												@if($field == 'holtype')
												<label class="btn btn-primary btn-xs{{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>ab" {{ $field_large }}>
													<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>">{{ trans('fields.'.$field) }}/AB
												</label>
												<label class="btn btn-primary btn-xs{{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>eb" {{ $field_large }}>
													<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>">{{ trans('fields.'.$field) }}/EB
												</label>
												<label class="btn btn-primary btn-xs{{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>ebkb" {{ $field_large }}>
													<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>">{{ trans('fields.'.$field) }}/EB/KB
												</label>
												<label class="btn btn-primary btn-xs{{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>kb" {{ $field_large }}>
													<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>">{{ trans('fields.'.$field) }}/KB
												</label>
												<label class="btn btn-primary btn-xs{{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>gb" {{ $field_large }}>
													<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>">{{ trans('fields.'.$field) }}/GB
												</label>
												@else
												<label class="btn btn-primary btn-xs{{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>" {{ $field_large }}>
													<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>">{{ trans('fields.'.$field) }}
												</label>
												@endif
												<?php	}	?>
											</div>				
											<form id="advanced-search-form" class="form-inline" role="form" method="get" class="text-center">
												<input type="hidden" name="filtered" value="1">
												<div id="currentfilters" class="row clearfix text-center">
													<?php foreach ($allsearchablefields as $field) {
														$value = (($field != 'holtype') && ($field != 'status')) ? Input::get('f'.$field) : Input::get($field);
														if ($value != '') {
															if ($field == '852b')  {
																$sublibraries = explode(';', Auth::user()->library->sublibraries);
																?>
																<div id="ff<?= $field; ?>" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon">{{ trans('fields.'.$field) }}</label>
																		<ul class="btn-group" data-toggle="buttons">
																			<?php 
																			foreach ($sublibraries as $library) { ?>
																			<li class="btn btn-xs btn-primary">
																				<input type="checkbox" id="f<?= $field; ?>" name="f<?= $field; ?>" <?= $checked; ?> value="<?= $library; ?>">
																			</li>				
																			<?php } ?>
																		</ul>
																		<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																			<option value="AND" selected>{{ trans('general.AND') }}</option>
																			<option value="OR">{{ trans('general.OR') }}</option>
																		</select>
																	</div>
																</div>
																<?php }
																elseif ($field == 'holtype')  { ?>
																<div id="ff<?= $field; ?>ab" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon">{{ trans('fields.'.$field) }}/AB</label>
																		<span class="input-group-addon  search-check">
																			<input type="hidden" name="<?= $field; ?>" value="0">
																			<input type="hidden" name="<?= $field; ?>format" value="%s = '%s'">
																			<input type="checkbox" class="form-control" name="<?= $field; ?>" value="1" checked="checked">
																		</span>
																		<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																			<option value="AND" selected>{{ trans('general.AND') }}</option>
																			<option value="OR">{{ trans('general.OR') }}</option>
																		</select>
																	</div>
																</div>
																<div id="ff<?= $field; ?>eb" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon">{{ trans('fields.'.$field) }}/EB</label>
																		<span class="input-group-addon  search-check">
																			<input type="hidden" name="<?= $field; ?>" value="0">
																			<input type="hidden" name="<?= $field; ?>format" value="%s = '%s'">
																			<input type="checkbox" class="form-control" name="<?= $field; ?>" value="1" checked="checked">
																		</span>
																		<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																			<option value="AND" selected>{{ trans('general.AND') }}</option>
																			<option value="OR">{{ trans('general.OR') }}</option>
																		</select>
																	</div>
																</div>
																<div id="ff<?= $field; ?>ebkb" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon">{{ trans('fields.'.$field) }}/EB/KB</label>
																		<span class="input-group-addon  search-check">
																			<input type="hidden" name="<?= $field; ?>" value="0">
																			<input type="hidden" name="<?= $field; ?>format" value="%s = '%s'">
																			<input type="checkbox" class="form-control" name="<?= $field; ?>" value="1" checked="checked">
																		</span>
																		<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																			<option value="AND" selected>{{ trans('general.AND') }}</option>
																			<option value="OR">{{ trans('general.OR') }}</option>
																		</select>
																	</div>
																</div>
																<div id="ff<?= $field; ?>kb" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon">{{ trans('fields.'.$field) }}/KB</label>
																		<span class="input-group-addon  search-check">
																			<input type="hidden" name="<?= $field; ?>" value="0">
																			<input type="hidden" name="<?= $field; ?>format" value="%s = '%s'">
																			<input type="checkbox" class="form-control" name="<?= $field; ?>" value="1" checked="checked">
																		</span>
																		<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																			<option value="AND" selected>{{ trans('general.AND') }}</option>
																			<option value="OR">{{ trans('general.OR') }}</option>
																		</select>
																	</div>
																</div>
																<div id="ff<?= $field; ?>gb" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon">{{ trans('fields.'.$field) }}/GB</label>
																		<span class="input-group-addon  search-check">
																			<input type="hidden" name="<?= $field; ?>" value="0">
																			<input type="hidden" name="<?= $field; ?>format" value="%s = '%s'">
																			<input type="checkbox" class="form-control" name="<?= $field; ?>" value="1" checked="checked">
																		</span>
																		<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																			<option value="AND" selected>{{ trans('general.AND') }}</option>
																			<option value="OR">{{ trans('general.OR') }}</option>
																		</select>
																	</div>
																</div>
																<?php }
																elseif($field == 'status')  { ?>
																<div id="ff<?= $field; ?>" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon">{{ trans('fields.'.$field) }}</label>
																		<span class="input-group-addon  search-check">
																			<input type="hidden" name="<?= $field; ?>" value="0">
																			<input type="hidden" name="<?= $field; ?>format" value="%s = '%s'">
																			<input type="checkbox" class="form-control" name="<?= $field; ?>" value="1" checked="checked">
																		</span>
																		<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																			<option value="AND" selected>{{ trans('general.AND') }}</option>
																			<option value="OR">{{ trans('general.OR') }}</option>
																		</select>
																	</div>
																</div>
																<?php }
																else { ?>
																<div id="ff<?= $field; ?>" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon"><?= trans('fields.'.$field); ?></label>
																		<select id="<?= $field; ?>Filter" name="<?= $field; ?>format" class="form-control">
																			<option value="%s LIKE '%%%s%%'" <?= (Input::get($field.'format') == "%s LIKE '%%%s%%'") ? 'selected' : ''; ?> >{{ trans('general.contains') }}</option>
																			<option value="%s NOT LIKE '%%%s%%'" <?= (Input::get($field.'format') == "%s NOT LIKE '%%%s%%'") ? 'selected': ''; ?> >{{ trans('general.no_contains') }}</option>
																			<option value="%s LIKE '%s%%'" <?= (Input::get($field.'format') == "%s LIKE '%s%%'") ? 'selected' : ''; ?> >{{ trans('general.begin_with') }}</option>
																			<option value="%s LIKE '%%%s'" <?= (Input::get($field.'format') == "%s LIKE '%%%s'") ? 'selected' : ''; ?> >{{ trans('general.end_with') }}</option>
																		</select>
																		<?php  ?>
																		<input type="text" class="form-control" name="<?= $field; ?>" value="<?= Input::get($field) ?>">
																		<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																			<option value="AND"{{ ($AndOrs[$ff] == 'AND')? ' selected':''  }}>{{ trans('general.AND') }}</option>
																			<option value="OR"{{ ($AndOrs[$ff] == 'OR')? ' selected':''  }}>{{ trans('general.OR') }}</option>
																		</select>
																	</div>
																</div>
																<?php 
															}
															$ff++; 
														}
													} ?>

												</div>
												<div class="col-xs-12" style="margin-top: 20px;">
													<div class="accordion" id="FieldsShow">
														<label class="label label-info text-center" style="margin: 10px auto;display:block;width: 220px;">{{ trans('general.show_hide_fields') }}</label>
														<div id="table_fields" class="accordion-body text-center">
															<input type="hidden" name="urltoredirect" value="<?= route('holdings.index', Input::except(['noexists'])); ?>">
															<?php									

															?>
															<ul class="btn-group" data-toggle="buttons">
																<?php
																$fields = $allselectablefields;
																foreach ($fields as $field) {
																	$popover = '';
																	$field_large = '';
																	$field_short = trans('fields.'.$field);
																	switch ($field) {
																		case 'size':
																		$field_short = trans('fields.size');
																		$field_large = ' data-content="<strong>'.trans('fields.size_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
																		$popover = " pop-over ";
																		break;
																	}
																	?>
																	<li class="btn btn-xs btn-default active pop-over" {{ $field_large }}>
																		<input type="checkbox" id="<?= $field; ?>" name="fieldstoshow[]" checked="checked" value="<?= $field; ?>"><?= $field_short; ?>
																	</li>
																	<?php 
																} ?>
															</ul>
														</div>
													</div>
												</div>
												<div id="searchsubmit" class="col-xs-12 text-center clearfix">
													<button style="margin: 20px 0;" type="submit" class="btn btn-default btn-sm btn-success" id="buttonsearch"><span class="fa fa-search"></span> {{ trans('general.search') }}</button>
												</div>
												<div id="queryerror" class="text-danger" style="display:none;">
													
												</div>
												<textarea id="query" name="query" class="form-control col-xs-12"></textarea>
												<!-- <pre id="querypainted" class="form-control col-xs-12"></pre> -->
											</form>
											<div id="fieldstosearchhidden" style="display: none;">
												<?php foreach ($allsearchablefields as $field) { 
													$value = (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols') || ($field == 'size') || ($field == 'years') || ($field == 'sys2')) ? Input::get($field) : Input::get('f'.$field);
													if (($value == null) || ($value == '')) {
														if ($field == '852b')  {
															$sublibraries = explode(',', Auth::user()->library->sublibraries);
															?>
															<div id="ff<?= $field; ?>" class="form-group col-xs-2">
																<div class="input-group inline input-group-sm text-left">
																	<label class="input-group-addon">{{ trans('fields.'.$field) }}</label>
																	<input type="hidden" name="fieldstoquery[]" value="<?php echo $field; ?>">
																	<select id="NotOperator" class="form-control" name="NotOperator[]">
																		<option value="" selected>-</option>
																		<option value="NOT ">NOT</option>
																	</select>
																	<ul class="btn-group pull-left" data-toggle="buttons" style="padding: 0;margin: 0">
																		<?php 
																		foreach ($sublibraries as $library) { ?>
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="f852b[]" value="<?= $library; ?>"><?= $library; ?>
																		</li>				
																		<?php } ?>
																	</ul>
																	<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																		<option value="AND" selected>{{ trans('general.AND') }}</option>
																		<option value="OR">{{ trans('general.OR') }}</option>
																	</select>
																</div>
															</div>
															<?php }
															elseif ($field == 'holtype')  { ?>
															<div id="ff<?= $field; ?>ab" class="form-group col-xs-2">
																<div class="input-group inline input-group-sm text-left">
																	<label class="input-group-addon">{{ trans('fields.'.$field) }}/AB</label>
																	<input type="hidden" name="fieldstoquery[]" value="<?php echo $field; ?>">
																	<select id="NotOperator" class="form-control" name="NotOperator[]">
																		<option value="" selected>-</option>
																		<option value="NOT ">NOT</option>
																	</select>
																	<ul class="btn-group pull-left" data-toggle="buttons" style="padding: 0;margin: 0">
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="holtype[]" value="AB">AB
																		</li>				
																	</ul>
																	<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">	
																		<option value="AND" selected>{{ trans('general.AND') }}</option>
																		<option value="OR">{{ trans('general.OR') }}</option>
																	</select>
																</div>
															</div>
															<div id="ff<?= $field; ?>eb" class="form-group col-xs-2">
																<div class="input-group inline input-group-sm text-left">
																	<label class="input-group-addon">{{ trans('fields.'.$field) }}/EB</label>
																	<input type="hidden" name="fieldstoquery[]" value="<?php echo $field; ?>">
																	<select id="NotOperator" class="form-control" name="NotOperator[]">
																		<option value="" selected>-</option>
																		<option value="NOT ">NOT</option>
																	</select>
																	<ul class="btn-group pull-left" data-toggle="buttons" style="padding: 0;margin: 0">			
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="holtype[]" value="EB">EB
																		</li>							
																	</ul>
																	<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">	
																		<option value="AND" selected>{{ trans('general.AND') }}</option>
																		<option value="OR">{{ trans('general.OR') }}</option>
																	</select>
																</div>
															</div>
															<div id="ff<?= $field; ?>ebkb" class="form-group col-xs-2">
																<div class="input-group inline input-group-sm text-left">
																	<label class="input-group-addon">{{ trans('fields.'.$field) }}EB/KB</label>
																	<input type="hidden" name="fieldstoquery[]" value="<?php echo $field; ?>">
																	<select id="NotOperator" class="form-control" name="NotOperator[]">
																		<option value="" selected>-</option>
																		<option value="NOT ">NOT</option>
																	</select>
																	<ul class="btn-group pull-left" data-toggle="buttons" style="padding: 0;margin: 0">
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="holtype[]" value="EB/KB">EB/KB
																		</li>						
																	</ul>
																	<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">	
																		<option value="AND" selected>{{ trans('general.AND') }}</option>
																		<option value="OR">{{ trans('general.OR') }}</option>
																	</select>
																</div>
															</div>
															<div id="ff<?= $field; ?>kb" class="form-group col-xs-2">
																<div class="input-group inline input-group-sm text-left">
																	<label class="input-group-addon">{{ trans('fields.'.$field) }}/KB</label>
																	<input type="hidden" name="fieldstoquery[]" value="<?php echo $field; ?>">
																	<select id="NotOperator" class="form-control" name="NotOperator[]">
																		<option value="" selected>-</option>
																		<option value="NOT ">NOT</option>
																	</select>
																	<ul class="btn-group pull-left" data-toggle="buttons" style="padding: 0;margin: 0">	
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="holtype[]" value="KB">KB
																		</li>						
																	</ul>
																	<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">	
																		<option value="AND" selected>{{ trans('general.AND') }}</option>
																		<option value="OR">{{ trans('general.OR') }}</option>
																	</select>
																</div>
															</div>
															<div id="ff<?= $field; ?>gb" class="form-group col-xs-2">
																<div class="input-group inline input-group-sm text-left">
																	<label class="input-group-addon">{{ trans('fields.'.$field) }}/GB</label>
																	<input type="hidden" name="fieldstoquery[]" value="<?php echo $field; ?>">
																	<select id="NotOperator" class="form-control" name="NotOperator[]">
																		<option value="" selected>-</option>
																		<option value="NOT ">NOT</option>
																	</select>
																	<ul class="btn-group pull-left" data-toggle="buttons" style="padding: 0;margin: 0">
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="holtype[]" value="GB">GB
																		</li>				
																	</ul>
																	<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">	
																		<option value="AND" selected>{{ trans('general.AND') }}</option>
																		<option value="OR">{{ trans('general.OR') }}</option>
																	</select>
																</div>
															</div>
															<?php } 
															elseif ($field == 'state')  { ?>
															<div id="ff<?= $field; ?>" class="form-group col-xs-2 col-xs-12" style="margin-bottom: 20px">
																<div class="input-group inline input-group-sm text-left">
																	<label class="input-group-addon">{{ trans('fields.'.$field) }}</label>
																	<input type="hidden" name="fieldstoquery[]" value="<?php echo $field; ?>">
																	<select id="NotOperator" class="form-control" name="NotOperator[]">
																		<option value="" selected>-</option>
																		<option value="NOT ">NOT</option>
																	</select>
																	<ul class="btn-group pull-left" data-toggle="buttons" style="padding: 0;margin: 0; height: 56px;">		
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="blank"> {{ trans('states.blank') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="ok"> {{ trans('states.ok') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="annotated"> {{ trans('states.annotated') }}
																		</li>						
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="confirmed"> {{ trans('states.confirmed') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="commented"> {{ trans('states.commented') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="delivery"> {{ trans('states.delivery') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="integrated"> {{ trans('states.integrated') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="received"> {{ trans('states.received') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="revised"> {{ trans('states.revised') }}
																		</li>		
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="revised_annotated"> {{ trans('states.revised_annotated') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="revised_ok"> {{ trans('states.revised_ok') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="spare"> {{ trans('states.spare') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="trash"> {{ trans('states.trash') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="pending"> {{ trans('states.pending') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="deleted"> {{ trans('states.deleted') }}
																		</li>				
																		<li class="btn btn-primary" style="height: 28px;">
																			<input type="checkbox" id="<?= $field; ?>" name="state[]" value="burn"> {{ trans('states.burn') }}
																		</li>				
																	</ul>
																	<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">	
																		<option value="AND" selected>{{ trans('general.AND') }}</option>
																		<option value="OR">{{ trans('general.OR') }}</option>
																	</select>
																</div>
															</div>
															<?php } 
															else {
																?>
																<div id="ff<?= $field; ?>" class="form-group col-xs-2">
																	<div class="input-group inline input-group-sm text-left">
																		<label class="input-group-addon"><?= trans('fields.'.$field) ?></label>
																		<input type="hidden" name="fieldstoquery[]" value="<?php echo $field; ?>">
																		<select id="NotOperator" class="form-control" name="NotOperator[]">
																			<option value="" selected>-</option>
																			<option value="NOT ">NOT</option>
																		</select>
																		<?php if (($field == 'size') || ($field == 'years')) { 
																			$field = (($field == 'size') || ($field == 'years')) ? $field : 'f'.$field;	?>
																			<select id="<?= $field; ?>Filter" name="<?= $field; ?>format" class="form-control">
																				<option value="%s = %s" selected>{{ trans('general.equal') }}</option>
																				<option value="%s < %s">{{ trans('general.less_than') }}</option>
																				<option value="%s > %s">{{ trans('general.greater_than') }}</option>
																			</select>
																			<?php }  else { 
																				$field = ($field == 'sys2') ? $field : 'f'.$field;
																				?>
																				<select id="<?= $field; ?>Filter" name="<?= $field; ?>format" class="form-control">
																					<option value="%s LIKE '%%%s%%'" selected>{{ trans('general.contains') }}</option>
																					<option value="%s NOT LIKE '%%%s%%'">{{ trans('general.no_contains') }}</option>
																					<option value="%s LIKE '%s%%'">{{ trans('general.begin_with') }}</option>
																					<option value="%s LIKE '%%%s'">{{ trans('general.end_with') }}</option>
																				</select>
																				<?php } ?>
																				<input type="text" class="form-control" name="<?= $field; ?>" value="<?= Input::get($field)  ?>">
																				<select id="OrAndFilter" class="form-control" name="OrAndFilter[]">
																					<option value="AND" selected>{{ trans('general.AND') }}</option>
																					<option value="OR">{{ trans('general.OR') }}</option>
																				</select>
																			</div>
																		</div>
																		<?php }
																	}
																} ?>
															</div>
														</div> <!-- /.row -->	
													</div>
												</div>
											</div>
										</div>
									</div>
								</div> <!-- /.page-header -->

<style type="text/css">
	#NotOperator {
		width: auto;
		display: block;
	}
	#querypainted {
		height: auto;
	}
	#queryerror {
		margin-bottom: 30px;
		font-style: italic;
	}
</style>

							</div> <!-- /.container -->
