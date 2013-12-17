@extends('layouts.pages')

@section('main')

<div class="row">
	<div class="col-xs-9">

		<h2>
			{{ trans('stats.holdingsets_confirm') }}
			<small>{{ trans('stats.holdingsets_confirm_details') }}</small>
		</h2>
		<ul class="list-unstyled">
			@foreach ($holdingsset_confirm as $confirm) 
				<li>
					<span class="date text-muted" >
						{{ $confirm->created_at->toDayDateTimeString() }}
					</span>
					<span class="user">
						{{ $confirm->user->username }}
					</span>
					{{ $confirm->holdingsset->sys1 }}
					{{ $confirm->holdingsset->f245a }} 
				</li>
			@endforeach
		</ul>		

		<h2>
			{{ trans('stats.holding_oks') }}
			<small>{{ trans('stats.holding_oks_details') }}</small>
		</h2>
		<ul class="list-unstyled">
			@foreach ($holdings_ok as $ok) 
				<li>
					<span class="date text-muted" >
						{{ $ok->created_at->toDayDateTimeString() }}
					</span>
					<span class="user">
						{{ $ok->user->username }}
					</span>
					{{ $ok->holding->f245a }}
					{{ $ok->holding->f245b }} 
					{{ $ok->holding->f245c }}
				</li>
			@endforeach
		</ul>

		<h2>
			{{ trans('stats.holding_annotated') }}
			<small>{{ trans('stats.holding_annotated_datails') }}</small>
		</h2>
		<ul class="list-unstyled">
			@foreach ($holdings_annotated as $ok) 
				<li>
					<span class="date">
						{{ $ok->created_at->toDayDateTimeString() }}
					</span>
					<span class="user">
						{{ $ok->user->username }}
					</span>
					<span class="tag">
						{{ $ok->tag->name }}
					</span>
				</li>
			@endforeach
		</ul>

	</div>

	<div class="col-xs-3">

		<dl class="dl-horizontal">
		  <dt>{{trans('stats.total_holding')}}</dt>
		  <dd>{{$total}}</dd>
		  <dt>{{trans('stats.total_ok')}}</dt>
		  <dd>{{$total_ok}}</dd>
		  <dt>{{trans('stats.total_anottated')}}</dt>
		  <dd>{{ $total_anottated}}</dd>
		  <dt>{{trans('stats.total_delivery')}}</dt>
		  <dd>{{$total_delivery}}</dd>
		</dl>
	</div>
</div>	

@stop