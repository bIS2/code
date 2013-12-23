<div class="container page-header">

	<div class="row">
		<div class="col-xs-12">

			<ul class="list-inline">
<!-- 				<li>
					<strong>
						{{ trans('holdings.title') }} 
					</strong>
				</li>
 -->			  <li>
				  <div class="btn-group">
				  	<div class="btn-group">
					  	<a href="#" class="btn btn-sm dropdown-toggle {{ (Input::has('hlist_id')) ? 'btn-primary' : 'btn-default'}}" data-toggle="dropdown">
					  		<i class="fa fa-list-ul"> </i> 
					  		@if (Input::has('hlist_id'))
					  			<?php $list = Hlist::find(Input::get('hlist_id')) ?>
					  			{{ $list->name}} 
					  		@else
					  			{{{ trans('holdings.lists') }}} 
					  		@endif
					  		<span class="caret"></span>
					  	</a>
					  	<!-- Show list if exists -->
							@if ($hlists)
								<ul class="dropdown-menu" role="menu">
									@foreach ($hlists as $list) 
									<li>
										<a href="{{ route('holdings.index',['hlist_id'=>$list->id]) }}"> {{ $list->name }} <span class="badge">{{ $list->holdings()->count() }} </span></a>
									</li>
									@endforeach
								</ul>
							@endif
					  </div>
			  		<a href="#" class="btn btn-default btn-sm disabled link_bulk_action" data-toggle="modal" data-target="#form-create-list" >
			  			<span class="fa fa-plus-circle"></span>
			  		</a>
				  </div>
			  <li>

				  <div class="btn-group">

				  	<a href="{{ route('holdings.index') }}" class="btn btn-default btn-sm {{ ($is_all) ? 'btn-primary' : '' }} " >
				  		<span class="fa fa-list"></span> {{{ trans('holdings.all') }}}
				  	</a>

				  	<a href="{{ route('holdings.index', Input::only('view') + ['corrects'=>'true'] ) }}" class="btn <?= ( Input::has('corrects') ) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-thumbs-up"></span> {{{ trans('holdings.ok2') }}}
				  	</a>

				  	<div class="btn-group">
					  	<a href="?tagged=true" class="btn <?= ( Input::has('tagged' )) ? 'btn-primary' : 'btn-default' ?> btn-sm" data-toggle="dropdown">
					  		<span class="fa fa-tags"></span> 
					  		<?= (!Input::has('tagged') || Input::get('tagged')=='%' ) ? trans('holdings.annotated') : Tag::find( Input::get('tagged') )->name ?> 
					  		<span class="caret"></span>
					  	</a>
					  	<ul class="dropdown-menu" role="menu">
					  		<li><a href="?tagged=%">{{ trans('general.all') }}</a></li>
					  		<li class="divider"></li>
					  		@foreach (Tag::all() as $tag)
					  			<li> <a href="?tagged={{ $tag->id }}">{{ $tag->name }}</a> </li>
					  		@endforeach
					  	</ul>
				  	</div>
				  	<a href="{{ route('holdings.index', Input::only('view') + ['corrects'=>'true'] ) }}" class="btn <?= ( Input::has('deliveries') ) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-mail-forward"></span> {{{ trans('holdings.deliveries') }}}
				  	</a>
				  </div>

				  	<div class="btn-group">
					  	<a href="?owner=true" class="btn <?= ( Input::has('owner')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
					  		<i class="fa fa-square text-danger"></i> {{{ trans('holdings.owner') }}}
					  	</a>

					  	<a href="?aux=true" class="btn <?= ( Input::has('aux')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
					  		<i class="fa fa-square text-warning"></i> {{{ trans('holdings.aux') }}}
					  	</a>

				  	</div>	

				  	<div class="btn-group">
					  	<a href="?pendings=true" class="btn <?= ( Input::has('pendings')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
					  		<span class="fa fa-warning"></span> {{{ trans('holdings.pending') }}}
					  	</a>

					  	<a href="?unlist=true" class="btn <?= ( Input::has('orphans')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
					  		<span class="fa fa-chain-broken"></span> {{{ trans('holdings.ungroup') }}}
					  	</a>
				  	</div>


				  	<a href="#collapseOne" data-toggle="collapse" id="" class="btn <?= ($is_filter) ? 'btn-primary' : 'btn-default' ?> btn-sm accordion-toggle">
				  		<span class="fa fa-filter"></span> {{{ trans('holdings.advanced_filter') }}} {{$is_filter}} 
				  	</a>



				  <div class="btn-group" >

				  	<a href="{{ route('holdings.index', Input::except('view') ) }}" class="btn btn-default <?= (!Input::has('view')) ? 'active' : '' ?> btn-sm" >
				  		<span class="fa fa-table"></span> 
				  	</a>

				  	<a href="{{ route('holdings.index', Input::except('view') + ['view'=>'slide'] ) }}" class="btn btn-default <?= (Input::get('view')=='slide') ? 'active' : '' ?> btn-sm" >
				  		<span class="fa fa-desktop"></span> 
				  	</a>

				  	<a href="{{ route('holdings.index', Input::except('view') + ['view'=>'print'] ) }}" target="_blank" class="btn btn-default <?= (Input::get('view')=='print') ? 'active' : '' ?> btn-sm" >
				  		<span class="fa fa-print"></span> 
				  	</a>
				  	
				  </div>
			  </li>
			</ul>

		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->
</div> <!-- /.container -->

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="accordion" id="filterContainer">
			  <div class="text-right accordion-group">
			    <div id="collapseOne" class="accordion-body <?= ($is_filter) ? 'in' : 'collapse' ?> text-left well">
							<div class="">
									<div class="text-center">
										<!-- <h3 class="text-primary"><span class="fa fa-check"></span> {{ trans('general.select_fields_to_search') }}	</h3>		 -->
										<div id="currentfiltersoption" class="btn-group btn-group-justified btn-group-sm" data-toggle="buttons">
											<!-- <label>{{ trans('general.select_fields_to_search') }}	</label> -->
											<?php foreach ($allsearchablefields as $field) { ?>
												<label class="btn btn-primary {{ (Input::get('f'.$field)) ? 'active' : '' }}" href="#ff<?= $field; ?>" >
													<input type="checkbox" <?= (Input::get('f'.$field)) ? 'checked="checked"' : '' ?> value="<?= $field; ?>"><?= $field; ?>
												</label>
											<?php	}	?>
										</div>		
									</div>		
								<form id="advanced-search-form" class="form-inline" role="form" method="get" class="text-center">
									<div id="currentfilters" class="row clearfix text-center">

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
												     		<option value="%s = %s" <?php if (Input::get('f'.$field.'format') == "%s = %s") echo 'selected'; ?>>{{ trans('general.equal') }}</option>
												     	</select>
													  <input type="text" class="form-control" name="f<?= $field; ?>" value="<?= Input::get('f'.$field) ?>">
												  <select id="OrAndFilter" class="form-control" name="OrAndFilter{{$field}}">
										     		<option value="AND"{{ ( Input::get( "OrAndFilter".$field )  == 'AND')? ' selected':''  }}>{{ trans('general.AND') }}</option>
										     		<option value="OR"{{ ( Input::get( "OrAndFilter".$field ) == 'OR')? ' selected':''  }}>{{ trans('general.OR') }}</option>
										     	</select>
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
											     		<option value="%s = %s">{{ trans('general.equal') }}</option>

											     	</select>
												  <input type="text" class="form-control" name="f<?= $field; ?>" value="<?= Input::get('f'.$field)  ?>">
												  <select id="OrAndFilter" class="form-control" name="OrAndFilter{{$field}}">
										     		<option value="AND"{{ ($AndOrs[$ff] == 'AND')? ' selected':''  }}>{{ trans('general.AND') }}</option>
										     		<option value="OR"{{ ($AndOrs[$ff] == 'OR')? ' selected':''  }}>{{ trans('general.OR') }}</option>
										     	</select>

												</div>
											</div>
										<?php }
									} ?>
								</div>
							</div> <!-- /.col -->	
						</div> <!-- /.row -->	
					</div> <!-- /.row -->	
				</div> <!-- /.row -->	
			</div> <!-- /.row -->	
		</div> <!-- /.row -->	
</div> <!-- /.page-header -->	
