@extends('layouts.default')

@section('toolbar')
	@include('holdings.toolbar')
@stop

{{-- Content --}}
@section('content')


<div class="row">
	<div class="col-lg-12 ">
		<div class="container">
		<table id="holdings-items" class="table table-bordered table-condensed flexme">
		<thead>
			<tr> 
				<th><input id="select-all" name="select-all" type="checkbox" value="1"></th>
				<th>{{ trans('general.actions') }}</th>
				<th>852b</th>
				<th>852h</th>
				<th>ocrr_ptrn</th>
				<th>245a</th>
				<th>362a</th>
				<th>866a</th>
				<th>866z</th>
			</tr>
		</thead>
		<tbody class="selectable">
		@foreach ($holdings as $holding)
			<tr id="<?= $holding->id ?>" class="{{ ($holding->is_correct) ? 'success' : '' }} {{ ($holding->is_annotated) ? 'danger' : '' }}">
				<td><input type="checkbox" value="{{ $holding->id }}" name="holding_id[]" class="sel hl"/></td>
				<td id="{{ $holding->id }}" class="col-lg-1">
				  <a href="{{ route('oks.store') }}" class="btn-link btn-xs btn-ok" data-method="post" data-remote="true" data-params="holding_id={{$holding->id}}">
				  	<span class="fa fa-thumbs-up"></span>
				  </a>
				  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs btn-tag">
				  	<span class="fa fa-tags"></span> 
				  </a>
				  @if (Authority::can('delivery',$holding))

					  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs">
					  	<span class="fa fa-rocket"></span> 
					  </a>

					@endif

				</td>
				<td>{{ $holding->f852b }} </td>
				<td><?= $holding->f852h; ?></td>
				<td class="ocrr_ptrn"><?= $holding->patrn ?></td>
 				<td>{{ $holding->holdingsset->f245a }}</td>
				<td><?= $holding->f362a; ?></td>
				<td><?= $holding->f866a; ?></td>
				<td><?= $holding->f866z; ?></td>

			</tr>
		@endforeach

		</tbody>
	</table>
	</div>
	<p>
		<?= $holdings->appends(Input::except('page'))->links()  ?>
	</p>

	</div>
</div>


<div class="remote">
 <div class="modal" id="form-create-notes"></div><!-- /.modal -->
</div>

	@include('hlists.create')
@stop

