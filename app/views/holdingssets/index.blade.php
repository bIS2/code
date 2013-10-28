@extends('layouts.holdingssets')

{{-- Content --}}
@section('content')

<div class="page-header">
	<h3><?= trans('holdingssets.title') ?></h3>
</div>

<table class="table table-striped table-hover dataTable table-condensed">
	<thead>
		<tr>
			<th><input id="select-all" name="select-all" type="checkbox" value="1" /></th>
			<th>Sys1</th>
			<th>F245a</th>
			<th>Patern</th>
		</tr>
	</thead>
	@foreach ($holdingssets as $holdingsset)
		<tr id="<?= $holdingsset->id ?>">
			<td><input id="holdingsset_id" name="holdingsset_id[]" type="checkbox" value="<?= $holdingsset->id ?>" /></td>
			<td>{{ link_to( route('holdingssets.show',[$holdingsset->id]), $holdingsset->sys1) }}</td>
			<td>{{ $holdingsset->f245a }}</td>
			<td>{{ $holdingsset->ptrn }}</td>
		</tr>
	@endforeach		
</table>

{{ $holdingssets->links() }}
@include('groups.create')

@stop