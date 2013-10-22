@extends('layouts.default')

{{-- Content --}}
@section('content')

<div class="page-header">
	<h3>Hol Groups</h3>
</div>

<table class="table table-striped table-hover dataTable">
	<thead>
		<tr>
			<th>Id</th>
			<th>Sys1</th>
			<th>F245a</th>
			<th>Patern</th>
		</tr>
	</thead>
	@foreach ($holgroups as $hol)
		<tr>
			<td>{{ $hol->id }}</td>
			<td>{{ $hol->sys1 }}</td>
			<td>{{ $hol->f245a }}</td>
			<td>{{ $hol->ptrn }}</td>
		</tr>
	@endforeach		
</table>

{{ $holgroups->links() }}

@stop