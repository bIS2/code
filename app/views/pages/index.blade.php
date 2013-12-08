@extends('layouts.pages')

@section('main')
	
<div class="page-header">
	<div class="row">
		<div class="col-xs-12">
			<h2> 
				{{ trans('home.title') }} 
			</h2>
		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->
</div> <!-- /.page-header -->
	
	<h2>{{ trans('stats.holding_oks') }}</h2>
	<ul>
		@foreach ($holdings_ok as $ok) 
			<li>
				<span class="date text-muted" >
					{{ $ok->created_at->toDayDateTimeString() }}
				</span>
				<span class="user">
					{{ $ok->user->username }}
				</span>
				{{ $ok->holding->f245a }}
			</li>
		@endforeach
	</ul>

	<h2>{{ trans('stats.holding_annotated') }}</h2>
	<ul>
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