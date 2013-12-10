@extends('layouts.pages')

@section('main')
	
<h2> 
	{{ trans('home.title') }} 
</h2>

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

	
	<h2>{{ trans('stats.holding_oks') }}</h2>
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
				{{ $ok->holding->library->id }}
			</li>
		@endforeach
	</ul>

	<h2>{{ trans('stats.holding_annotated') }}</h2>
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

@stop