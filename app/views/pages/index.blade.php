@extends('layouts.pages')

@section('main')

<div class="row">
	<div class="col-xs-12">
		<form class="form-inline" >

			<div class="form-group">
				<select name="library_id" class="form-control">
					@foreach ($libraries as $library) {
						<option value="{{ $library->id }}" <?= ($library->id==Input::get('library_id')) ? 'selected' : '' ?>>
							{{ $library->code.":".$library->name }}
						</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<select name="month" class="form-control">
					<option value="*" >{{trans('general.all')}}</option>
					@for ($i=1; $i <= 12 ; $i++) 
					  <option value="{{$i}}" <?= ($i==Input::get('month')) ? 'selected' : '' ?> >{{$i}}</option>
					@endfor
				</select>
			</div>
			<div class="form-group">
				<select name="year" class="form-control">
					<option value="*" >{{trans('general.all')}}</option>
					@for ($i=2014; $i <= Carbon::now()->year ; $i++) 
					  <option value="{{$i}}" <?= ($i==Input::get('year')) ? 'selected' : '' ?> >{{$i}}</option>
					@endfor
				</select>
			</div>
			<button type="submit" class="btn btn-primary"> <i class="fa fa-search"></i> {{trans('general.search')}}</button>
		</form>		
	</div>
</div>
<hr>
<div class="row">
	<div class="col-xs-12">
		
		<h3>
			{{ trans('stats.graph') }}
		</h3>
		<div id="graph" style="width:800px;height:400px" data-url="{{ action('Pages@getStats') }}" ></div>
		<h3>
			{{ trans('stats.graph-size') }}
		</h3>
		<div id="graph-by-size" style="width:800px;height:400px" data-url="{{ action('Pages@getStats') }}" ></div>
	</div>
	<div class="data"></div>
	<div id="lang" class="hide">
    <span class="confirm">{{ trans('states.confirmed') }}</span>
    <span class="delivery">{{ trans('states.delivery') }}</span>
    <span class="integrated">{{ trans('states.integrated') }}</span>
    <span class="revised">{{ trans('states.revised') }}</span>
    <span class="trashed">{{ trans('states.trash') }}</span>
    <span class="burned">{{ trans('states.burn') }}</span>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-xs-9 overfloaded-x">

		<h2>
			{{ trans('stats.global_log') }}
		</h2>

		<h3>
			{{ trans('stats.holdingsets_confirm') }}
			<small>{{ trans('stats.holdingsets_confirm_details') }}</small>
		</h3>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{{ trans('general.date')}}</th>
					<th>{{ trans('table.user')}}</th>
					<th>sys</th>
					<th>245a</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($holdingsset_confirm as $confirm) 
				<tr>
					<td class="date col-xs-2 text-muted">
						{{ $confirm->created_at->toDateString() }}
					</td>
					<td class="user col-xs-2">
						{{ $confirm->user->username }}
					</td>
					<td class="col-xs-2">
						{{ $confirm->holdingsset->sys1 }}
					</td>
					<td class="tag col-xs-4">
						{{ truncate(htmlspecialchars($confirm->holdingsset->f245a), 60, ' ...') }} 
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>		

		@if (count($holdings_ok) > 0)
			<h3>
				{{ trans('stats.holding_oks') }}
				<small>{{ trans('stats.holding_oks_details') }}</small>
			</h3>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>{{ trans('general.date') }}</th>
						<th>{{ trans('table.user') }}</th>
						<th>sys</th>
						<th>245a</th>
					</tr>
				</thead>
				<tbody>
				@foreach ($holdings_ok as $ok) 
					<tr>
						<td class="date col-xs-2 text-muted">
							{{ $ok->created_at->toDateString() }}
						</td>
						<td class="user col-xs-2">
							{{ $ok->user->username }}
						</td>
						<td class="col-xs-2">
							{{ $ok->holding->sys2 }}
						</td>
						<td class="tag col-xs-4">
							{{ truncate(htmlspecialchars($ok->holding->f245a), 60, ' ...') }}
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		@endif
		<h3>
			{{ trans('stats.holding_annotated') }}
			<small>{{ trans('stats.holding_annotated_datails') }}</small>
		</h3>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>{{ trans('general.date') }}</th>
					<th>{{ trans('table.user') }}</th>
					<th>sys</th>
					<th>Tag</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($holdings_annotated as $ok) 
				<tr>
					<td class="date col-xs-2">
						{{ $ok->created_at->toDateString() }}
					</td>
					<td class="user col-xs-2">
						{{ $ok->user->username }}
					</td>
					<td class="col-xs-2">
						{{ $ok->holding->sys2 }}
					</td>
					<td class="tag col-xs-4">
						{{ htmlspecialchars($ok->tag->name) }}
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>

	</div>

	<div class="col-xs-3">
		<div class="well">
			<div class="text-center text-muted lead">
				<strong >{{ Auth::user()->library->code }} {{ trans('stats.stats') }}</strong >
			</div>
			<dl class="dl-horizontal">
			  <dt>{{trans('stats.total_holding')}}</dt>
			  <dd>{{$total}}</dd>
			  <dt>{{trans('stats.total_ok')}}</dt>
			  <dd>{{$total_ok}}</dd>
			  <dt>{{trans('stats.total_anottated')}}</dt>
			  <dd>{{ $total_anottated}}</dd>
			</dl>
		</div>	
	</div>
</div>	

@stop