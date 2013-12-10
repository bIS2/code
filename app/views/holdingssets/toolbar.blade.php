<div class="page-header container clearfix">
	<div id="main-filters" class="row">
		<div class="col-xs-12">
			<ul class="list-inline">
				<li>
					<strong>
						{{ trans('holdingssets.title') }} 
						@if (isset($group))
							<small>&raquo; {{ $group->name }}</small>
						@endif				
					</strong>
				</li>
			  <li>
				  <div class="btn-group">
				  	<div class="btn-group">
					  	<a href="#" class="btn btn-sm dropdown-toggle {{ (Input::has('group_id')) ? 'btn-primary' : 'btn-default'}}" data-toggle="dropdown">
					  		<i class="fa fa-list-ul"> </i> 
					  		@if (Input::has('group_id'))
					  			{{ Group::find(Input::get('group_id'))->name }}
					  		@else
					  			{{{ trans('holdingssets.groups') }}} 
					  		@endif
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
										<li>
											<a href="{{ route('sets.index',['group_id'=>$group->id]) }}"> {{ $group->name }} <span class="badge"><span class="fa fa-file-text"></span> {{ $group->holdingssets -> count() }} </span></a>
										</li>
										@endforeach
									</ul>
								<?php endif; ?>
							@endif
					  </div>
			  		<a href="#form-create-group" data-toggle="modal" class='btn btn-sm btn-default link_bulk_action disabled' style="padding: 0 5px;">
			  			<i class="fa fa-folder-o" style="padding: 0px; line-height: 1em; font-size: 27px;"></i>
	  					<span class="fa fa-plus-circle" style="position: absolute; left: 11px; font-size: 13px; top: 9px;"></span>
			  		</a>
				  </div>
			  </li>
			  <li>
				  <div class="btn-group">
				  	<a href="{{ route('sets.index', Input::except(['owner','aux']) + ['owner' => true]) }}" class="btn <?= ((Input::get('owner') == true) && (Input::get('aux') != true)) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<i class="fa fa-stop text-danger"></i> {{{ trans('holdingssets.just_owner') }}}
				  	</a>
				  	<a id="filter_pending" href="{{ route('sets.index', Input::except(['owner','aux']) + ['aux' => true]) }}" class="btn <?= ((Input::get('owner') != true) && (Input::get('aux') == true)) ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<i class="fa fa-stop text-warning"></i> {{{ trans('holdingssets.just_aux') }}}
				  	</a>
				  	<a id="filter_pending" href="{{ route('sets.index', Input::except(['owner','aux']) + ['owner' => true, 'aux' => true]) }}" class="btn <?= ((Input::get('owner') == true) && (Input::get('aux') == true)) ? 'btn-primary' : 'btn-default'; ?> btn-sm">
				  		<i class="fa fa-stop text-danger"></i> <i class="fa fa-stop text-warning"></i> {{{ trans('holdingssets.only_owner_and_aux') }}}
				  	</a>
				  	<a href="{{ route('sets.index', Input::except(['owner','aux'])) }}" class="btn btn-default btn-sm" title="{{ trans('holdingssets.clear_owner_filter') }}">	
				  		<i class="fa fa-eraser"></i>			  		
				  	</a>
				  	<span class="btn btn-sm">|</span>
				  	<a id="filter_all" href="{{ route('sets.index', Input::except('state')) }}" class="btn <?= ((Input::get('state') != 'ok') && (Input::get('state') != 'pending') && (Input::get('state') != 'annotated')) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-list"></span> {{{ trans('holdingssets.all') }}}
				  	</a>
				  	<a id="filter_confirmed" href="{{ route('sets.index', Input::except('state') + ['state'=>'ok']) }}" class="btn <?= (Input::get('state')=='ok') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-thumbs-up"></span> {{{ trans('holdingssets.oked') }}}
				  	</a>
				  	<a id="filter_pending" href="{{ route('sets.index', Input::except('state') + ['state'=>'pending']) }}" class="btn <?= (Input::get('state') == 'pending') ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="fa fa-warning"></span> {{{ trans('holdingssets.pending') }}}
				  	</a>
				  	<a id="filter_annotated" href="{{ route('sets.index', Input::except('state') + ['state'=>'annotated']) }}" class="btn <?= (Input::get('state') == 'annotated') ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="fa fa-tags"></span> {{{ trans('general.annotated') }}}
				  	</a>
				  	<span class="btn btn-sm">|</span>
				  	<a href="#collapseOne" id="filter-btn" class="accordion-toggle <?= ($is_filter) ? 'btn-primary' : 'collapsed' ?> btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm dropdown-toggle" data-toggle="collapse" data-parent="#accordion2">
			        <span class="fa fa fa-filter"></span> {{{ trans('holdingssets.advanced_filter') }}} <span class="caret"></span>
			      </a>
<!-- 				  	<a href="{{ route('sets.index') }}" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="glyphicon glyphicon-print"></span> {{{ trans('holdingssets.printer') }}}
				  	</a> -->
				  </div>
			  </li>
			</ul>
		</div>
	</div> <!-- /.row -->
	<div class="row">
		<div class="col-xs-12">
			<div class="accordion" id="filterContainer">
			  <div class="text-right accordion-group">
			    <div id="collapseOne" class="accordion-body <?= ($is_filter) ? 'in' : 'collapse' ?> text-left">
						<div class="row">
							<div class="col-xs-12">
									<div class="col-xs-12 text-center">
										<h3 class="text-primary"><span class="fa fa-check"></span> {{ trans('general.select_fields_to_search') }}	</h3>		
										<div id="currentfiltersoption" class="btn-group" data-toggle="buttons">
											
											<?php
											$allsearchablefields = ALL_SEARCHEABLESFIELDS;
											$allsearchablefields = explode(';', $allsearchablefields);
											foreach ($allsearchablefields as $field) {
												$checked 				= '';
												$checkactive 		= '';
												$value = Input::get('f'.$field);
												if ($value != '') {
													$checked 			= "checked = checked";
													$checkactive 	= " active";
												}
												?>
												<label class="btn btn-primary{{ $checkactive }}" href="#ff<?= $field; ?>" >
													<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>"><?= $field; ?>
												</label>
											<?php	}	?>
										</div>		
									</div>		
								<form id="advanced-search-form" class="form-inline" role="form" method="get" class="text-center">
									<div id="currentfilters" class="row clearfix text-center">
										<?= ($group_id > 0) ? '<input type="hidden" name="group_id" value="'.$group_id.'">': '' ?>
										<?= (Input::has('state')) ? '<input type="hidden" name="state" value="'.Input::get('state').'">': '' ?>
										<?php foreach ($allsearchablefields as $field) { 
											$value = Input::get('f'.$field);
											if ($value != '') { ?>
												<div id="ff<?= $field; ?>" class="form-group col-xs-2">
													<div class="input-group inline input-group-sm">
													  <label class="input-group-addon"><?= $field; ?></label>
										     			<select id="f<?= $field; ?>Filter" name="f<?= $field; ?>format" class="form-control">
												     		<option value="%s LIKE '%%%s%%'" <?php if (Input::get('f'.$field.'format') == "%s LIKE '%%%s%%'") echo 'selected'; ?>>{{ trans('general.contains') }}</option>
												     		<option value="%s NOT LIKE '%%%s%%'" <?php if (Input::get('f'.$field.'format') == "%s NOT LIKE '%%%s%%'") echo 'selected'; ?>>{{ trans('general.no_contains') }}</option>
												     		<option value="%s LIKE '%s%%'" <?php if (Input::get('f'.$field.'format') == "%s LIKE '%s%%'") echo 'selected'; ?>>{{ trans('general.begin_with') }}</option>
												     		<option value="%s LIKE '%%%s'" <?php if (Input::get('f'.$field.'format') == "%s LIKE '%%%s'") echo 'selected'; ?>>{{ trans('general.end_with') }}</option>
												     	</select>
													  <input type="text" class="form-control" name="f<?= $field; ?>" value="<?= Input::get('f'.$field) ?>">
													</div>
												</div>
											<?php }
										} ?>
									</div>
									<div id="searchsubmit" class="col-xs-12 text-center clearfix">
										<button style="margin: 20px 0;" type="submit" class="btn btn-default btn-sm btn-success"><span class="glyphicon glyphicon-search"></span> {{ trans('general.search') }}</button>
									</div>
								</form>
								<div id="fieldstosearchhidden" style="display: none;">
									<?php foreach ($allsearchablefields as $field) { 
										$value = Input::get('f'.$field);
										if (($value == null) || ($value == '')) { ?>
											<div id="ff<?= $field; ?>" class="form-group col-xs-2">
												<div class="input-group inline input-group-sm">
												  <label class="input-group-addon"><?= $field; ?></label>
									     			<select id="f<?= $field; ?>Filter" name="f<?= $field; ?>format" class="form-control">
											     		<option value="%s LIKE '%%%s%%'" selected>{{ trans('general.contains') }}</option>
											     		<option value="%s NOT LIKE '%%%s%%'">{{ trans('general.no_contains') }}</option>
											     		<option value="%s LIKE '%s%%'">{{ trans('general.begin_with') }}</option>
											     		<option value="%s LIKE '%%%s'">{{ trans('general.end_with') }}</option>
											     	</select>
												  <input type="text" class="form-control" name="f<?= $field; ?>" value="<?= Input::get('f'.$field)  ?>">
												</div>
											</div>
										<?php }
									} ?>
								</div>
							</div> <!-- /.col -->	
						</div> <!-- /.row -->	
			    </div>
			  </div>
			</div>
		</div>
	</div>
</div> <!-- /.page-header -->	
