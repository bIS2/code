@extends('layouts.pages')

@section('main')

<div class="row">
	<div class="col-xs-9">

		<h2>
			{{ trans('stats.global_log') }}
		</h2>

		<h3>
			{{ trans('stats.holdingsets_confirm') }}
			<small>{{ trans('stats.holdingsets_confirm_details') }}</small>
		</h3>
		<table class="table table-striped">
			@foreach ($holdingsset_confirm as $confirm) 
				<tr><td>
					<span class="date text-muted" >
						{{ $confirm->created_at->toDayDateTimeString() }}
					</span>
					<span class="user">
						{{ $confirm->user->username }}
					</span>
					{{ $confirm->holdingsset->sys1 }}
					{{ $confirm->holdingsset->f245a }} 
				</td></tr>
			@endforeach
		</table>		

		@if (count($holdings_ok) > 0)
			<h3>
				{{ trans('stats.holding_oks') }}
				<small>{{ trans('stats.holding_oks_details') }}</small>
			</h3>
			<table class="table table-striped">
				@foreach ($holdings_ok as $ok) 
					<tr><td>
						<span class="date text-muted" >
							{{ $ok->created_at->toDayDateTimeString() }}
						</span>
						<span class="user">
							{{ $ok->user->username }}
						</span>
						{{ $ok->holding->f245a }}
						{{ $ok->holding->f245b }} 
						{{ $ok->holding->f245c }}
					</td></tr>
				@endforeach
			</table>
		@endif
		<h3>
			{{ trans('stats.holding_annotated') }}
			<small>{{ trans('stats.holding_annotated_datails') }}</small>
		</h3>
		<table class="table table-striped">
			@foreach ($holdings_annotated as $ok) 
				<tr><td>
					<span class="date">
						{{ $ok->created_at->toDayDateTimeString() }}
					</span>
					<span class="user">
						{{ $ok->user->username }}
					</span>
					<span class="tag">
						{{ $ok->tag->name }}
					</span>
				</td></tr>
			@endforeach
		</table>

	</div>

	<div class="col-xs-3">
	<h3>{{ Auth::user()->library->code }} {{ trans('stats.stats') }}</h3>
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

@stop