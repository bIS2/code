<div class="page-header">
	<div class="row">
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
								<?php $groups1 = Auth::user()->groups() ?>
								<ul class="dropdown-menu" role="menu">
									@foreach (Auth::user()->groups as $group) 
									<li>
										<a href="{{ route('sets.index',['group_id'=>$group->id]) }}"> {{ $group->name }} <span class="badge">{{ $group->holdingssets -> count() }} </span></a>
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
				  	<a id="filter_all" href="{{ route('sets.index') }}" class="btn <?= (Input::has('state')) ? 'btn-default' : 'btn-primary' ?> btn-sm" >
				  		<span class="fa fa-list"></span> {{{ trans('holdingssets.all') }}}
				  	</a>
				  	@if ((isset($group_id)) && ($group_id > 0))
					  	<a id="filter_confirmed" href="{{ route('sets.index',['state'=>'ok', 'group_id' => $group_id]) }}" class="btn <?= (Input::get('state')=='ok') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
					  		<span class="glyphicon glyphicon-thumbs-up"></span> {{{ trans('holdingssets.oked') }}}
					  	</a>
					  	<a id="filter_pending" href="{{ route('sets.index', ['state'=>'pending', 'group_id' => $group_id]) }}" class="btn <?= (Input::get('state') == 'pending') ? 'btn-primary' : 'btn-default' ?> btn-sm">
					  		<span class="glyphicon glyphicon-warning-sign"></span> {{{ trans('holdingssets.pending') }}}
					  	</a>
				  	@else
					  	<a id="filter_confirmed" href="{{ route('sets.index',['state'=>'ok']) }}" class="btn <?= (Input::get('state')=='ok') ? 'btn-primary' : 'btn-default' ?> btn-sm" >
					  		<span class="glyphicon glyphicon-thumbs-up"></span> {{{ trans('holdingssets.oked') }}}
					  	</a>
					  	<a id="filter_pending" href="{{ route('sets.index', ['state'=>'pending']) }}" class="btn <?= (Input::get('state') == 'pending') ? 'btn-primary' : 'btn-default' ?> btn-sm">
					  		<span class="glyphicon glyphicon-warning-sign"></span> {{{ trans('holdingssets.pending') }}}
					  	</a>
				  	@endif
				  	<a href="#collapseOne" id="filter-btn" class="accordion-toggle collapsed btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm dropdown-toggle" data-toggle="collapse" data-parent="#accordion2">
			        <span class="fa fa-question-circle"></span> {{{ trans('holdingssets.advanced_filter') }}} <span class="caret"></span>
			      </a>
				  	<a href="{{ route('sets.index') }}" class="btn <?= (false) ? 'btn-primary' : 'btn-default' ?> btn-sm" >
				  		<span class="glyphicon glyphicon-print"></span> {{{ trans('holdingssets.printer') }}}
				  	</a>
				  </div>
			  </li>
			</ul>
		</div>
	</div> <!-- /.row -->

	<div class="row">
		<div class="col-xs-12">
	<div class="accordion" id="filterContainer">
	  <div class="text-right accordion-group">
	    <div id="collapseOne" class="accordion-body collapse text-left">
				<div class="row">
					<div class="col-xs-12">
						<form class="form-inline" role="form" method="get">
							<div class="form-group col-xs-2">
								<div class="input-group inline input-group-sm">
								  <label class="input-group-addon">852b</label>
					     			<select id="f245bFilter" name="f852bformat" class="form-control">
							     		<option value="%%%s%%" selected>{{ trans('general.contains') }}</option>
							     		<option value="">{{ trans('general.no_contains') }}</option>
							     		<option value="%s%%">{{ trans('general.begin_with') }}</option>
							     		<option value="%%%s">{{ trans('general.end_with') }}</option>
							     	</select>
									  <input type="text" name="f852b" value="<?= Input::get('f852b')  ?>" class="form-control">
								</div>
							</div>
							<div class="form-group col-xs-2">
								<div class="input-group inline input-group-sm">
								  <label class="input-group-addon">852h</label>
					     			<select id="f245bFilter" name="f852hformat" class="form-control">
							     		<option value="%%%s%%" selected>{{ trans('general.contains') }}</option>
							     		<option value="">{{ trans('general.no_contains') }}</option>
							     		<option value="%s%%">{{ trans('general.begin_with') }}</option>
							     		<option value="%%%s">{{ trans('general.end_with') }}</option>
							     	</select>
								  <input type="text" class="form-control" name="f852h" value="<?= Input::get('f852h')  ?>">
								</div>
							</div>
							<div class="form-group col-xs-2">
								<div class="input-group inline input-group-sm">
								  <label class="input-group-addon">245b</label>
					     			<select id="f245bFilter" name="f245bformat" class="form-control">
							     		<option value="%%%s%%" selected>{{ trans('general.contains') }}</option>
							     		<option value="">{{ trans('general.no_contains') }}</option>
							     		<option value="%s%%">{{ trans('general.begin_with') }}</option>
							     		<option value="%%%s">{{ trans('general.end_with') }}</option>
							     	</select>
								  <input type="text" class="form-control" name="f245b" value="<?= Input::get('f245b')  ?>">
								</div>
							</div>
							<div class="form-group col-xs-2">
								<div class="input-group inline input-group-sm">
								  <label class="input-group-addon">362a</label>
					     			<select id="f245bFilter" name="f362aformat" class="form-control">
							     		<option value="%%%s%%" selected>{{ trans('general.contains') }}</option>
							     		<option value="">{{ trans('general.no_contains') }}</option>
							     		<option value="%s%%">{{ trans('general.begin_with') }}</option>
							     		<option value="%%%s">{{ trans('general.end_with') }}</option>
							     	</select>
								  <input type="text" class="form-control" name="f362a"  value="<?= Input::get('f362a')  ?>">
								</div>
							</div>
							<div class="form-group col-xs-2">
								<div class="input-group inline input-group-sm">
								  <label class="input-group-addon">866a</label>
					     			<select id="f245bFilter" name="f866aformat" class="form-control">
							     		<option value="%%%s%%" selected>{{ trans('general.contains') }}</option>
							     		<option value="">{{ trans('general.no_contains') }}</option>
							     		<option value="%s%%">{{ trans('general.begin_with') }}</option>
							     		<option value="%%%s">{{ trans('general.end_with') }}</option>
							     	</select>
								  <input type="text" class="form-control" name="f866a" value="<?= Input::get('f866a')  ?>">
								</div>
							</div>
							<div class="form-group col-xs-2">
								<div class="input-group inline input-group-sm">
								  <label class="input-group-addon">866z</label>
					     			<select id="f245bFilter" name="f866zformat" class="form-control">
							     		<option value="%%%s%%" selected>{{ trans('general.contains') }}</option>
							     		<option value="">{{ trans('general.no_contains') }}</option>
							     		<option value="%s%%">{{ trans('general.begin_with') }}</option>
							     		<option value="%%%s">{{ trans('general.end_with') }}</option>
							     	</select>
								  <input type="text" class="form-control" name="f866z" value="<?= Input::get('f866z')  ?>">
								</div>
							</div>
						  <button type="submit" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-search"></span>{{ trans('general.search') }}</button>
						</form>
					</div> <!-- /.col -->	
				</div> <!-- /.row -->	
	    </div>
	  </div>
	</div>
		</div>
	</div>
</div> <!-- /.page-header -->	
