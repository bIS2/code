@extends('layouts.pages')

@section('main')

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
						<?php 
							$fecha = new DateTime($confirm->created_at->toDayDateTimeString());
							echo $fecha->format('d-m-Y H:i:s');
						?>
					</td>
					<td class="user col-xs-2">
						{{ $confirm->user->username }}
					</td>
					<td class="col-xs-2">
						{{ $confirm->holdingsset->sys1 }}
					</td>
					<td class="tag col-xs-4">
						{{ htmlspecialchars($confirm->holdingsset->f245a) }} 
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
							<?php 
								$fecha = new DateTime($ok->created_at->toDayDateTimeString());
								echo $fecha->format('d-m-Y H:i:s');
							?>
						</td>
						<td class="user col-xs-2">
							{{ $ok->user->username }}
						</td>
						<td class="col-xs-2">
							{{ $ok->holding->sys2 }}
						</td>
						<td class="tag col-xs-4">
							{{ htmlspecialchars($ok->holding->f245a) }}
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
					<?php 
						$fecha = new DateTime($ok->created_at->toDayDateTimeString());
						echo $fecha->format('d-m-Y H:i:s');
					?>
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