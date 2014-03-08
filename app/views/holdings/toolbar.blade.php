<?php $user = Auth::user() ?>
<section class="container navbar navbar-default navbar-fixed-top">
	<div class="page-header clearfix">
		<div id="main-filters" class="row">
			<div class="col-xs-12">

				<div class="btn-group">

					<a href="#" class="btn btn-sm dropdown-toggle {{ (Input::has('hlist_id')) ? 'btn-default' : 'btn-default'}} {{ ($hlists->count() > 0) ? '' : ' disabled '}}" data-toggle="dropdown">
						<i class="fa fa-list-ul"> </i> 
						{{{ trans('holdings.lists') }}} 
						<span class="caret"></span>
					</a>

					<!-- Show list if exists -->
					@if ($hlists)

					<ul class="dropdown-menu" role="menu">

						@foreach ($hlists as $list) 

						<li <?= ($list->id == Input::get('hlist_id')) ? 'class="active"' : '' ; ?>>
							<a href="{{ route('holdings.index',Input::except(['hlist_id']) + ['hlist_id' => $list->id ]) }}"> {{ $list->name }} <span class="badge">{{ $list->holdings()->count() }} </span></a>
						</li>

						@endforeach

					</ul>

					@endif

				</div>

				  <div class="btn-group">


				  	<a href="{{ route('holdings.index', Input::except('state')) }}" class="btn btn-default btn-sm {{ ($is_all) ? 'btn-primary' : '' }} " >
				  		<span class="fa fa-list"></span> {{{ trans('holdings.all') }}}
				  	</a>

				  	<!-- CORRECT AND ANNOTATED -->
				  	@if ( $user->hasRole('magvuser') || $user->hasRole('maguser') )

					  	<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'ok'] ) }}" class="btn btn-default <?= ( Input::get('state')=='ok' ) ? 'active' : '' ?> btn-sm" >
					  		<div class="text-success"><span class="fa fa-thumbs-up"></span> {{{ trans('holdings.ok2') }}}</div>
					  	</a>

					  	<div class="btn-group">
						  	<a href="?tagged=true" class="btn btn-default <?= ( Input::has('tagged' )) ? 'active' : '' ?> btn-sm" data-toggle="dropdown">
						  		<div class="text-danger"><span class="fa fa-tags"></span>
						  		<?= (!Input::has('tagged') || Input::get('tagged')=='%' ) ? trans('holdings.annotated') : Tag::find( Input::get('tagged') )->name ?>  
						  		<span class="caret"></span></div>
						  	</a>
						  	<ul class="dropdown-menu" role="menu">
						  		<li><a href="?tagged=%">{{ trans('general.all') }}</a></li>
						  		<li class="divider"></li>
						  		@foreach (Tag::all() as $tag)
						  			<li> <a href="?tagged={{ $tag->id }}">{{ $tag->name }}</a> </li>
						  		@endforeach
						  	</ul>
					  	</div>

					  @endif
					  
					  <!-- REVISED -->
				  	@if ( $user->hasRole('magvuser') )

					  	<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'revised'] ) }}" class="btn btn-default text-primary <?= ( Input::get('state')=='revised' ) ? 'active' : '' ?> btn-sm" >
					  		<div class="text-primary"><span class="fa fa-mail-forward"></span> {{{ trans('holdings.reviseds') }}}</div>
					  	</a>

				  	@endif

				  	<!-- DELIVERIES -->
				  	@if ( $user->hasRole('postuser') )

					  	<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'delivery'] ) }}" class="btn btn-default <?= ( Input::get('state')=='delivery' ) ? 'active' : '' ?> btn-sm" >
					  		<span class="fa fa-truck fa-flip-horizontal"></span> {{{ trans('holdings.deliveries') }}}
					  	</a>

				  	@endif

				  	<!-- RECEIVED -->
				  	@if ( $user->hasRole('speichuser') )

					  	<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'receive'] ) }}" class="btn btn-default <?= ( Input::get('state')=='receive' ) ? 'active' : '' ?> btn-sm" >
					  		<span class="fa fa-download"></span> {{{ trans('holdings.receiveds') }}}
					  	</a>

				  	@endif

				  	<!-- COMMENTED WITH PROBLEMS RECEIVE -->
				  	@if ( $user->hasRole('postuser') || $user->hasRole('speichuser') || $user->hasRole('magvuser') )

					  	<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'commented'] ) }}" class="btn btn-default <?= ( Input::get('state')=='commented' ) ? 'active' : '' ?> btn-sm" >
					  		<span class="fa fa-comments"></span> {{{ trans('holdings.commenteds') }}}
					  	</a>

				  	@endif

				  	@if ( $user->hasRole('bibuser') || $user->hasRole('magvuser') )

					  	<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'integrated'] ) }}" class="btn btn-default <?= ( Input::get('state')=='integrated' ) ? 'active' : '' ?> btn-sm" >
					  		<span class="fa fa-flag-checkered"></span> {{{ trans('holdings.integrated') }}}
					  	</a>

					  @endif

				  	<!-- HOLDING SPARE -->
				  	@if ( $user->hasRole('bibuser') || $user->hasRole('magvuser') )

					  	<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'spare'] ) }}" class="btn btn-default <?= ( Input::get('state')=='spare' ) ? 'active' : '' ?> btn-sm" >
					  		<span class="fa fa-flag"></span> {{{ trans('holdings.spare') }}}
					  	</a>
					  	
				  	@endif
				  	
				  	<!-- (TRASH) MARK IT TO ELIMINATE -->
				  	@if ( $user->hasRole('bibuser') )

					  	<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'trash'] ) }}" class="btn btn-default <?= ( Input::get('state')=='trash' ) ? 'active' : '' ?> btn-sm" >
					  		<span class="fa fa-trash-o"></span> {{{ trans('holdings.trasheds') }}}
					  	</a>
					  	
				  	@endif


					<!-- DELETE -->
					@if ( $user->hasRole('magvuser') )

					<a href="{{ route('holdings.index', Input::only('view') + ['state'=>'burn'] ) }}" class="btn btn-default <?= ( Input::get('state')=='burn' ) ? 'active' : '' ?> btn-sm" >
						<span class="fa fa-fire"></span> {{{ trans('holdings.burneds') }}}
					</a>

					@endif

				</div>

				@if ( !Auth::user()->hasRole('bibuser') )

					<div class="btn-group">

						<a href="{{ route('holdings.index', Input::except(['owner', 'aux'])) }}" class="btn <?= ( !Input::has('owner') && !Input::has('aux')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
							<i class="fa fa-list"></i> {{{ trans('holdings.all') }}}
						</a>

						<a href="?owner=true" class="btn <?= ( Input::has('owner')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
							<i class="fa fa-square text-danger"></i> {{{ trans('holdings.owner') }}}
						</a>

						<a href="?aux=true" class="btn <?= ( Input::has('aux')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
							<i class="fa fa-square text-warning"></i> {{{ trans('holdings.aux') }}}
						</a>

					</div>

				@endif

				@if (!Auth::user()->hasRole('postuser'))

					<div class="btn-group">

						<a href="{{ route('holdings.index', Input::except(['pendings', 'unlist'])) }}" class="btn btn-default btn-sm{{ (Input::has('pendings') || Input::has('unlist')) ? '' : ' btn-primary ' }}" >
							<span class="fa fa-list"></span> {{{ trans('holdings.all') }}}
						</a>				  	

						<a href="?pendings=true" class="btn <?= ( Input::has('pendings')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
							<span class="fa fa-warning"></span> {{{ trans('holdings.pending') }}}
						</a>

						<a href="?unlist=true" class="btn <?= ( Input::has('unlist')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
							<span class="fa fa-chain-broken"></span> {{{ trans('holdings.ungroup') }}}
						</a>
					</div>
					<div class="btn-group">
						<a id="filter_all" href="{{ route('holdings.index', Input::only(['state', 'owner', 'aux', 'pending', 'unlist', 'hlist_id'])) }}" class="btn <?= (Input::get('filtered') == '1') ? 'btn-default' : 'btn-primary'; ?> btn-sm" >
							<span class="fa fa-list"></span> {{{ trans('holdingssets.all') }}}
						</a>
						<a href="#collapseOne" data-toggle="collapse" id="filter-btn" class="btn <?= ($is_filter) ? 'btn-primary' : 'btn-default' ?> btn-sm accordion-toggle">
							<span class="fa fa-filter"></span> {{{ trans('holdings.advanced_filter') }}} 
						</a>
					</div>

				@endif

				<div class="btn-group" >

					<a href="{{ route('holdings.index', Input::except('view') ) }}" class="btn btn-default <?= (!Input::has('view')) ? 'btn-primary' : '' ?> btn-sm" >
						<span class="fa fa-table"></span> 
					</a>

					@if ( Input::has('hlist_id') )
						<a href="{{ route('holdings.index', Input::except('view') + ['view'=>'slide'] ) }}" class="btn btn-default <?= (Input::get('view')=='slide') ? 'btn-primary' : '' ?> btn-sm" >
							<span class="fa fa-desktop"></span> 
						</a>

						<a href="{{ route('holdings.index', Input::except('view') + ['view'=>'print'] ) }}" target="_blank" class="btn btn-default <?= (Input::get('view')=='print') ? ' btn-primary' : '' ?> btn-sm" >
							<span class="fa fa-print"></span> 
						</a>
					@endif
					
				</div>
			</div>
		</div> <!-- /.row -->
			<div class="col-xs-12">
				<div class="accordion" id="filterContainer">
					<div class="text-right accordion-group">
						<div id="collapseOne" class="accordion-body collapse text-left">
							<div class="row">
								<div class="col-xs-12">
									<div class="col-xs-12 text-center">
										<h4 class="text-primary"><span class="fa fa-check"></span> {{ trans('general.select_fields_to_search') }}	</h4>		
										<div id="currentfiltersoption" class="btn-group btn-group-centered btn-group-sm" data-toggle="buttons">
											<?php 											
											foreach ($allsearchablefields as $field) {
												$checked 				= '';
												$checkactive 		= '';
												$value = (($field != 'exists_online') && ($field != 'is_current') && ($field != 'has_incomplete_vols') && ($field != 'size') && ($field != 'sys2')) ? Input::get('f'.$field) : Input::get($field);
												if ($value != '') {
													$checked 			= "checked = checked";
													$checkactive 	= " active";
												}
												$field_short = $field;
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

													case 'size':
													$field_short = trans('fields.size_short');
													$field_large = ' data-content="<strong>'.trans('fields.size_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
													$popover = " pop-over ";
													break;	

													case 'weight':
													$field_short = trans('fields.weight');
													$field_large = ' data-content="<strong>'.trans('fields.weight_large').'</strong>" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover" ';
													$popover = " pop-over ";
													break;
													default:
														$field_short = trans('fields.'.$field);
														$field_large = '';
														$popover = '';
													break;									
													?>
													<label class="btn btn-primary btn-xs{{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>" {{ $field_large }}>
														<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
													</label>
													<?php	}	?>
													<label class="btn btn-primary btn-xs {{ $checkactive }}{{ $popover }}" href="#ff<?= $field; ?>" {{ $field_large }}>
														<input type="checkbox" <?= $checked; ?> value="<?= $field; ?>"><?= $field_short; ?>
													</label>
											<?php	}	?>
										</div>				
										<form id="advanced-search-form" class="form-inline" role="form" method="get" class="text-center">
											<input type="hidden" name="filtered" value="1">
											<div id="currentfilters" class="row clearfix text-center">
												<?= (Input::has('state')) ? '<input type="hidden" name="state" value="'.Input::get('state').'">': '' ?>
												<?php foreach ($allsearchablefields as $field) { 
													$value = (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols') || ($field == 'size') || ($field == 'sys2')) ? Input::get($field) : Input::get('f'.$field);
													if ($value != '') { 
														if (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols'))  { ?>
														<div id="ff<?= $field; ?>" class="form-group col-xs-2">
															<div class="input-group inline input-group-sm">
																<label class="input-group-addon">{{ trans('holdings.'.$field) }}</label>
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
																<label class="input-group-addon"><?= trans('fields.'.$field); ?></label>
																<?php if (($field == 'size')) { 
																	$field = ($field == 'size') ? $field : 'f'.$field;
																	?>
																	<select id="<?= $field; ?>Filter" name="<?= $field; ?>format" class="form-control">
																		<option value="%s = %s" <?= (Input::get($field.'format') == "%s = %s") ? 'selected' : ''; ?>>{{ trans('general.equal') }}</option>
																		<option value="%s < %s" <?= (Input::get($field.'format') == "%s < %s") ? 'selected' : ''; ?>>{{ trans('general.less_than') }}</option>
																		<option value="%s > %s" <?= (Input::get($field.'format') == "%s > %s") ? 'selected' : ''; ?>>{{ trans('general.greater_than') }}</option>
																	</select>
																	<?php } else { 
																		$field = ($field == 'sys2') ? $field : 'f'.$field;
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
												<button style="margin: 20px 0;" type="submit" class="btn btn-default btn-sm btn-success"><span class="glyphicon glyphicon-search"></span> {{ trans('general.search') }}</button>
											</div>
										</form>
										<div id="fieldstosearchhidden" style="display: none;">
						<?php foreach ($allsearchablefields as $field) { 
							$value = (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols') || ($field == 'size') || ($field == 'sys2')) ? Input::get($field) : Input::get('f'.$field);
							if (($value == null) || ($value == '')) {
								if (($field == 'exists_online') || ($field == 'is_current') || ($field == 'has_incomplete_vols'))  { ?>
								<div id="ff<?= $field; ?>" class="form-group col-xs-2">
									<div class="input-group inline input-group-sm">
										<label class="input-group-addon">{{ trans('fields.'.$field) }}</label>
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
											<label class="input-group-addon"><?= trans('fields.'.$field) ?></label>
											<?php if (($field == 'size')) { 
												$field = ($field == 'size') ? $field : 'f'.$field;												?>
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
</div> <!-- /.container -->
