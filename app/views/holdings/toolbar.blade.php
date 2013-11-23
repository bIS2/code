<div class="container page-header">

	<div class="row">
		<div class="col-xs-12">

			<ul class="list-inline">
				<li>
					<strong>
						{{ trans('holdings.title') }} 
						@if ($hlist)
							<small>&raquo; {{ $hlist->name }}</small>
						@endif				
					</strong>
				</li>
			  <li>
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
							@if (Auth::user()->has('hlists')) 
								<?php $lists = Auth::user()->hlists() ?>
								<ul class="dropdown-menu" role="menu">
									@foreach (Auth::user()->hlists as $list) 
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
				  	<a href="?corrects=true" class="btn <?= ( Input::has('corrects') ) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-thumbs-up"></span> {{{ trans('holdings.ok2') }}}
				  	</a>
				  	<div class="btn-group">
					  	<a href="?tagged=true" class="btn <?= ( Input::has('tagged' )) ? 'btn-primary' : 'btn-default' ?> btn-sm" data-toggle="dropdown">
					  		<span class="fa fa-tags"></span> 
					  		<?= (!Input::has('tagged') || Input::get('tagged')=='%' ) ? trans('holdings.annotated') : Tag::find( Input::get('tagged') )->name ?>
					  	</a>
						  <button type="button" class="btn btn-default dropdown-toggle btn-sm" data-toggle="dropdown">
						    <span class="caret"></span>
						    <span class="sr-only">Toggle Dropdown</span>
						  </button>					  	
					  	<ul class="dropdown-menu" role="menu">
					  		<li><a href="?tagged=%">{{ trans('general.all') }}</a></li>
					  		<li class="divider"></li>
					  		@foreach (Tag::all() as $tag)
					  			<li> <a href="?tagged={{ $tag->id }}">{{ $tag->name }}</a> </li>
					  		@endforeach
					  	</ul>
				  	</div>
				  	<a href="?owner=true" class="btn <?= ( Input::has('owner')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<i class="fa fa-stop text-danger"></i> {{{ trans('holdings.owner') }}}
				  	</a>
				  	<a href="?aux=true" class="btn <?= ( Input::has('aux')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<i class="fa fa-stop text-warning"></i> {{{ trans('holdings.aux') }}}
				  	</a>
				  	<a href="?pendings=true" class="btn <?= ( Input::has('pendings')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="fa fa-warning"></span> {{{ trans('holdings.pending') }}}
				  	</a>
				  	<a href="?unlist=true" class="btn <?= ( Input::has('orphans')) ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="fa fa-question-circle"></span> {{{ trans('holdings.ungroup') }}}
				  	</a>
				  	<a href="#" id="filter-btn" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm">
				  		<span class="fa fa-filter"></span> {{{ trans('holdings.advanced_filter') }}} 
				  	</a>
				  </div>
				  <div class="btn-group" >
				  	<a href="{{ route('holdings.index', Input::except('view') ) }}" class="btn <?= (!Input::has('view')) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-table"></span> 
				  	</a>
<!-- 				  	
				  	<a href="{{ route('holdings.index', Input::except('view') + ['view'=>'list'] ) }}" class="btn <?= (Input::get('view')=='list') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-align-justify"></span> 
				  	</a>
				  	<a href="{{ route('holdings.index', Input::except('view') + ['view'=>'grid'] ) }}" class="btn <?= (Input::get('view')=='grid') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-th-large"></span> 
				  	</a>
	 -->			  	<a href="{{ route('holdings.index', Input::except('view') + ['view'=>'slide'] ) }}" class="btn <?= (Input::get('view')=='slide') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-desktop"></span> 
				  	</a>
				  	<a href="{{ route('holdings.index', Input::except('view') + ['view'=>'print'] ) }}" target="_blank" class="btn <?= (Input::get('view')=='print') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="fa fa-print"></span> 
				  	</a>
				  </div>
			  </li>
			</ul>

		</div>
	</div> <!-- /.row -->

	<div class="row">

		<div class="col-xs-12">
			<div class="well well-sm row" id="filter-well"  {{ ($is_filter) ? '' : 'style="display:none"' }} >

				<form class="form-inline" role="form" method="get">

					<div class="form-group col-xs-2">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">852b</label>
			     			<select id="f245bFilter" name="f852bformat" class="form-control">
					     		<option value="%s LIKE '%%%s%%'" >{{ trans('general.contains') }}</option>
					     		<option value="%s NOT LIKE '%%%s%%'">{{ trans('general.no_contains') }}</option>
					     		<option value="%s LIKE '%s%%'">{{ trans('general.begin_with') }}</option>
					     		<option value="%s LIKE '%%%s'">{{ trans('general.end_with') }}</option>
					     	</select>
							  <input type="text" name="f852b" value="<?= Input::get('f852b')  ?>" class="form-control">
						</div>
					</div>
					<div class="form-group col-xs-2">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">852h</label>
			     			<select id="f245bFilter" name="f852hformat" class="form-control">
					     		<option value="%s LIKE '%%%s%%'" selected>{{ trans('general.contains') }}</option>
					     		<option value="%s NOT LIKE '%%%s%%'">{{ trans('general.no_contains') }}</option>
					     		<option value="%s LIKE '%s%%'">{{ trans('general.begin_with') }}</option>
					     		<option value="%s LIKE '%%%s'">{{ trans('general.end_with') }}</option>
					     	</select>
						  <input type="text" class="form-control" name="f852h" value="<?= Input::get('f852h')  ?>">
						</div>
					</div>
					<div class="form-group col-xs-2">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">245a</label>
			     			<select id="f245bFilter" name="f245aformat" class="form-control">
					     		<option value="%s LIKE '%%%s%%'" selected>{{ trans('general.contains') }}</option>
					     		<option value="%s NOT LIKE '%%%s%%'">{{ trans('general.no_contains') }}</option>
					     		<option value="%s LIKE '%s%%'">{{ trans('general.begin_with') }}</option>
					     		<option value="%s LIKE '%%%s'">{{ trans('general.end_with') }}</option>
					     	</select>
						  <input type="text" class="form-control" name="f245a" value="<?= Input::get('f245a')  ?>">
						</div>
					</div>
					<div class="form-group col-xs-2">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">362a</label>
			     			<select id="f245bFilter" name="f362aformat" class="form-control">
					     		<option value="%s LIKE '%%%s%%'" selected>{{ trans('general.contains') }}</option>
					     		<option value="%s NOT LIKE '%%%s%%'">{{ trans('general.no_contains') }}</option>
					     		<option value="%s LIKE '%s%%'">{{ trans('general.begin_with') }}</option>
					     		<option value="%s LIKE '%%%s'">{{ trans('general.end_with') }}</option>
					     	</select>
						  <input type="text" class="form-control" name="f362a"  value="<?= Input::get('f362a')  ?>">
						</div>
					</div>
					<div class="form-group col-xs-2">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">866a</label>
			     			<select id="f245bFilter" name="f866aformat" class="form-control">
					     		<option value="%s LIKE '%%%s%%'" selected>{{ trans('general.contains') }}</option>
					     		<option value="%s NOT LIKE '%%%s%%'">{{ trans('general.no_contains') }}</option>
					     		<option value="%s LIKE '%s%%'">{{ trans('general.begin_with') }}</option>
					     		<option value="%s LIKE '%%%s'">{{ trans('general.end_with') }}</option>
					     	</select>
						  <input type="text" class="form-control" name="f866a" value="<?= Input::get('f866a')  ?>">
						</div>
					</div>
					<div class="form-group col-xs-2">
						<div class="input-group inline input-group-sm">
						  <label class="input-group-addon">866z</label>
			     			<select id="f245bFilter" name="f866zformat" class="form-control">
					     		<option value="%s LIKE '%%%s%%'" selected>{{ trans('general.contains') }}</option>
					     		<option value="%s NOT LIKE '%%%s%%'">{{ trans('general.no_contains') }}</option>
					     		<option value="%s LIKE '%s%%'">{{ trans('general.begin_with') }}</option>
					     		<option value="%s LIKE '%%%s'">{{ trans('general.end_with') }}</option>
					     	</select>
						  <input type="text" class="form-control" name="f866z" value="<?= Input::get('f866z')  ?>">
						</div>
					</div>

				  <button type="submit" class="btn btn-default btn-sm"><span class="fa fa-search"></span>{{ trans('general.search') }}</button>
				</form>

			</div> <!-- /.well -->	
		</div> <!-- /.col -->	
	</div> <!-- /.row -->	

</div> <!-- /.page-header -->	
