@extends('layouts.admin')

@section('content')

<h1>All Traces</h1>

@if ($traces->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>User_id</th>
				<th>Action</th>
				<th>trans</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($traces as $trace)
				<tr>
					<td>{{{ $trace->action }}}</td>
					<td>{{{ $trace->created_at }}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no traces
@endif

@include('traces.create')

@stop
