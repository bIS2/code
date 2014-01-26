<section class="container navbar navbar-default navbar-fixed-top">
	<div class="page-header clearfix">

		<div id="main-filters" class="row">
			<div class="col-xs-12">
						<div class="btn-group">
							<a href="#" class="btn btn-sm dropdown-toggle btn-default {{ (Auth::user()->groups()->count() > 0) ? '' : ' disabled '}}" data-toggle="dropdown">
								<i class="fa fa-list-ul"> </i>					 
								{{{ trans('holdingssets.groups') }}}					  	
								<span class="caret"></span>
							</a>
							<!-- Show list if exists -->
							@if (Auth::user()->has('groups')) 
							<?php $groups = Auth::user()->groups()->count(); $i = 0; ?>
							@foreach (Auth::user()->groups as $group) 
							<?php $i++; ?>
							@endforeach 
							<?php if ($i > 0) : ?>
								<ul class="dropdown-menu" role="menu">
									@foreach (Auth::user()->groups as $group) 
									<li <?= ($group->id == Input::get('group_id')) ? 'class="active"' : '' ; ?>>
										<a href="{{ route('sets.index',Input::except(['group_id']) + ['group_id' => $group->id ]) }}"> {{ $group->name }} <span class="badge"><span class="fa fa-file-text"></span> {{ $group->holdingssets -> count() }} </span></a>
									</li>
									@endforeach
								</ul>
							<?php endif; ?>
							@endif
						</div>
						<div class="btn-group">
							<a href="{{ route('sets.index', Input::except(['owner','aux'])) }}" class="btn <?= ((Input::get('owner') != true) && (Input::get('aux') != true)) ? 'btn-primary' : 'btn-default' ?> btn-sm" title="{{ trans('holdingssets.clear_owner_filter') }}">	
								<span class="fa fa-list"></span> {{{ trans('holdingssets.all') }}}			  		
							</a>
							<a href="{{ route('sets.index', Input::except(['owner','aux']) + ['owner' => true]) }}" class="btn <?= ((Input::get('owner') == true) && (Input::get('aux') != true)) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
								<i class="fa fa-stop text-danger"></i> {{{ trans('holdingssets.just_owner') }}}
							</a>
							<a id="filter_owner" href="{{ route('sets.index', Input::except(['owner','aux']) + ['aux' => true]) }}" class="btn <?= ((Input::get('owner') != true) && (Input::get('aux') == true)) ? 'btn-primary' : 'btn-default' ?> btn-sm">
								<i class="fa fa-stop text-warning"></i> {{{ trans('holdingssets.just_aux') }}}
							</a>
							<a id="filter_aux" href="{{ route('sets.index', Input::except(['owner','aux']) + ['owner' => true, 'aux' => true]) }}" class="btn <?= ((Input::get('owner') == true) && (Input::get('aux') == true)) ? 'btn-primary' : 'btn-default'; ?> btn-sm">
								<i class="fa fa-stop text-danger"></i> <i class="fa fa-stop text-warning"></i> {{{ trans('holdingssets.only_owner_and_aux') }}}
							</a>
						</div>
						<div class="btn-group">
							<a id="filter_all" href="{{ route('sets.index', Input::except('state')) }}" class="btn btn-primary <?= ((Input::get('state') != 'ok') && (Input::get('state') != 'pending') && (Input::get('state') == '')) ? 'active' : '' ?> btn-sm" >
								<span class="fa fa-list"></span> {{{ trans('holdingssets.all') }}}
							</a>
							<a id="filter_pending" href="{{ route('sets.index', Input::except('state') + ['state'=>'pending']) }}" class="btn btn-default <?= (Input::get('state') == 'pending') ? 'active' : '' ?> btn-sm">
								<span class="fa fa-thumbs-up"></span> {{{ trans('holdingssets.pending') }}}
							</a>
							<a id="filter_confirmed" href="{{ route('sets.index', Input::except('state') + ['state'=>'ok']) }}" class="btn btn-success <?= (Input::get('state')=='ok') ? 'active' : '' ?> btn-sm" >
								<span class="fa fa-thumbs-up"></span> {{{ trans('holdingssets.oked') }}}
							</a>
							<a id="filter_annotated" href="{{ route('sets.index', Input::except('state') + ['state'=>'annotated']) }}" class="btn btn-default <?= (Input::get('state') == 'annotated') ? 'active' : '' ?> btn-sm">
								<span class="fa fa-thumbs-up text-warning"></span> <span class="text-warning">{{{ trans('general.annotated') }}}</span>
							</a>
							<a id="filter_incorrect" href="{{ route('sets.index', Input::except('state') + ['state'=>'reserveds']) }}" class="btn btn-info  <?= (Input::get('state') == 'reserveds') ? 'active' : '' ?> btn-sm">
								<span class="fa fa-lock"></span> <span class="">{{{ trans('general.reserveds') }}}</span>
							</a>
							<a id="filter_incorrect" href="{{ route('sets.index', Input::except('state') + ['state'=>'incorrects']) }}" class="btn btn-danger <?= (Input::get('state') == 'incorrects') ? 'active' : '' ?> btn-sm">
								<span class="fa fa-thumbs-down"></span> {{{ trans('general.incorrects') }}}
							</a>
							@if (count(Holdingsset::receiveds()->lists('id')) > 0 )
							<a href="/sets?state=receiveds" class="btn btn-default <?= (Input::get('state') == 'receiveds') ? 'active' : '' ?> btn-sm">
								<span class="fa fa-download"></span> {{ trans('holdingssets.receiveds') }}
							</a>
							@endif
						</div>
						<div class="btn-group">
							<a id="filter_all" href="{{ route('sets.index', Input::only(['state', 'owner', 'aux', 'group_id'])) }}" class="btn <?= (Input::get('filtered') == '1') ? 'btn-default' : 'btn-primary'; ?> btn-sm" >
								<span class="fa fa-list"></span> {{{ trans('holdingssets.all') }}}
							</a>
							<a href="#collapseOne" id="filter-btn" class="accordion-toggle <?= ($is_filter) ? 'btn-primary' : 'btn-default' ?> btn btn-sm dropdown-toggle" data-toggle="collapse" data-parent="#accordion2">
								<span class="fa fa fa-filter"></span> {{{ trans('holdingssets.advanced_filter') }}} <span class="caret"></span>
							</a>
						</div>
			</div>
		</div> <!-- /.row -->
		<div class="row">
			<div class="col-xs-12">
				<div class="accordion" id="filterContainer">
					<div class="text-right accordion-group">
						<div id="collapseOne" class="accordion-body collapse text-left">
							<div class="row">
								<div class="col-xs-12">
									<div class="col-xs-12 text-center">
										<h4 class="text-primary"><span class="fa fa-check"></span> {{ trans('general.select_fields_to_search') }}	</h4>		
										<div id="currentfiltersoption" class="btn-group" data-toggle="buttons">
											<?php
											$allsearchablefields = ALL_SEARCHEABLESFIELDS;
											$allsearchablefields = explode(';', $allsearchablefields);
											foreach ($allsearchablefields as $field) {
												$checked 				= '';
												$checkactive 		= '';
												$value = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size') && ($field != 'sys1') && ($field != 'sys2')) ? Input::get('f'.$field) : Input::get($field);
												if ($value != '') {
													$checked 			= "checked = checked";
													$checkactive 	= " active";
												}
												$field_short = $field;
												$popover = '';
												switch ($field) {
													case 'exists_online':
													$field_short = trans('holdings.exists_online_short');
													$field_large = ' data-content="<strong>'.trans('holdings.exists_online_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
													$popover = " pop-over ";
													break;
													
													case 'is_current':
													$field_short = trans('holdings.is_current_short');
													$field_large = ' data-content="<strong>'.trans('holdings.is_current_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
													$popover = " pop-over ";
													break;
													
													case 'has_incomplete_vols':
													$field_short = trans('holdings.has_incomplete_vols_short');
													$field_large = ' data-content="<strong>'.trans('holdings.has_incomplete_vols_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
													$popover = " pop-over ";
													break;										
												}?>
												<label class="btn btn-primary btn-xs{{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>" {{ $field_large }}>
													<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
												</label>
												<?php	}	?>
											</div>		
										</div>		
										<form id="advanced-search-form" class="form-inline text-center" role="form" method="get">
											<div id="currentfilters" class="row clearfix text-center">
												<input type="hidden" name="filtered" value="1">
												<?= ($group_id > 0) ? '<input type="hidden" name="group_id" value="'.$group_id.'">': '' ?>
												<?= (Input::has('state')) ? '<input type="hidden" name="state" value="'.Input::get('state').'">': '' ?>
												<?= (Input::has('owner')) ? '<input type="hidden" name="owner" value="'.Input::get('owner').'">': '' ?>
												<?= (Input::has('aux')) ? '<input type="hidden" name="aux" value="'.Input::get('aux').'">': '' ?>
												<?php $ff = 0; $AndOrs = Input::get('OrAndFilter');
												foreach ($allsearchablefields as $field) { 
												$value = (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols') || ($field == 'size') || ($field == 'sys1') || ($field == 'sys2')) ? Input::get($field) : Input::get('f'.$field);
												if ($value != '') { 
													if (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols'))  { ?>
													<div id="ff<?= $field; ?>" class="form-group col-xs-2">
														<div class="input-group inline input-group-sm">
															<label class="input-group-addon">{{ trans('holdings.'.$field.'_short') }}</label>
															<span class="input-group-addon  search-check">
																<input type="hidden" name="<?= $field; ?>" value="0">
																<input type="hidden" name="<?= $field; ?>format" value="%s = %s">
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
														<div class="input-group inline input-group-sm">
															<label class="input-group-addon"><?= $field; ?></label>
															<?php if (($field == '008x') || ($field == 'size')) { 
																$field = ($field == 'size') ? $field : 'f'.$field;
																?>
															<select id="<?= $field; ?>Filter" name="<?= $field; ?>format" class="form-control">
																<option value="%s = %s" <?= (Input::get($field.'format') == "%s = %s") ? 'selected' : ''; ?>>{{ trans('general.equal') }}</option>
																<option value="%s < %s" <?= (Input::get($field.'format') == "%s < %s") ? 'selected' : ''; ?>>{{ trans('general.less_than') }}</option>
																<option value="%s > %s" <?= (Input::get($field.'format') == "%s > %s") ? 'selected' : ''; ?>>{{ trans('general.greater_than') }}</option>
																<option value="%s LIKE '%%%s%%'" <?= (Input::get($field.'format') == "%s LIKE '%%%s%%'") ? 'selected' : ''; ?> >{{ trans('general.contains') }}-Fix to 008x</option>
															</select>
															<?php } else { 
																$field = (($field == 'sys1') || ($field == 'sys2')) ? $field : 'f'.$field;
																?>
															<select id="<?= $field; ?>Filter" name="<?= $field; ?>format" class="form-control">
																<option value="%s LIKE '%%%s%%'" <?= (Input::get($field.'format') == "%s LIKE '%%%s%%'") ? 'selected' : ''; ?> >{{ trans('general.contains') }}</option>
																<option value="%s NOT LIKE '%%%s%%'" <?= (Input::get($field.'format') == "%s NOT LIKE '%%%s%%'") ? 'selected': ''; ?> >{{ trans('general.no_contains') }}</option>
																<option value="%s LIKE '%s%%'" <?= (Input::get($field.'format') == "%s LIKE '%s%%'") ? 'selected' : ''; ?> >{{ trans('general.begin_with') }}</option>
																<option value="%s LIKE '%%%s'" <?= (Input::get($field.'format') == "%s LIKE '%%%s'") ? 'selected' : ''; ?> >{{ trans('general.end_with') }}</option>
															</select>
															<?php } ?>

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
											<div id="searchsubmit" class="col-xs-12 text-center clearfix">
												<button style="margin: 0 0 5px 0;" type="submit" class="btn btn-default btn-sm btn-success"><span class="glyphicon glyphicon-search"></span> {{ trans('general.search') }}</button>
											</div>
										</form>
								<div id="fieldstosearchhidden" style="display: none;">
									<?php foreach ($allsearchablefields as $field) { 
										$value = (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols') || ($field == 'size') || ($field == 'sys1') || ($field == 'sys2')) ? Input::get($field) : Input::get('f'.$field);
										if (($value == null) || ($value == '')) {
											if (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols'))  { ?>
											<div id="ff<?= $field; ?>" class="form-group col-xs-2">
												<div class="input-group inline input-group-sm">
													<label class="input-group-addon">{{ trans('holdings.'.$field.'_short') }}</label>
													<span class="input-group-addon search-check">
														<input type="hidden" name="<?= $field; ?>" value="0">
														<input type="checkbox" class="form-control" name="<?= $field; ?>" value="1" checked="checked">
														<input type="hidden" name="<?= $field; ?>format" value="%s = %s">
													</span>
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
													<div class="input-group inline input-group-sm">
														<label class="input-group-addon"><?= $field; ?></label>
														<?php if (($field == '008x') || ($field == 'size')) { 
															$field = ($field == 'size') ? $field : 'f'.$field;
															?>
															<select id="<?= $field; ?>Filter" name="<?= $field; ?>format" class="form-control">
																<option value="%s = %s" selected>{{ trans('general.equal') }}</option>
																<option value="%s < %s">{{ trans('general.less_than') }}</option>
																<option value="%s > %s">{{ trans('general.greater_than') }}</option>
																<option value="%s LIKE '%%%s%%'" <?= (Input::get('f'.$field.'format') == "%s LIKE '%%%s%%'") ? 'selected' : ''; ?> >{{ trans('general.contains') }}-Fix to 008x</option>
															</select>
														<?php }  else { 
															$field = (($field == 'sys1') || ($field == 'sys2')) ? $field : 'f'.$field;
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
