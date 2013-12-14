@extends('layouts.default')

@section('toolbar')

	@include('holdings.toolbar')

@stop

{{-- Content --}}
@section('content')


<div class="row">
	<div class="col-lg-12 ">

		<div>{{ trans('general.pagination_information',['from'=>$holdings->getFrom(), 'to'=>$holdings->getTo(), 'total'=>$holdings->getTotal()])}} </div>
		<table id="holdings-items" class="table table-bordered table-condensed flexme datatable">
		<thead>
			<tr> 
				<th><input id="select-all" name="select-all" type="checkbox" value="1"></th>
				<th>{{ trans('general.actions') }}</th>
				<th>{{ trans('general.size') }}</th>
				<th>852b <span class="fa fa-info-circle"></span></th>
				<th>852h <span class="fa fa-info-circle"></span></th>
				<th>866a <span class="fa fa-info-circle"></span></th>
				<th>ocrr_ptrn</th>
				<th>245a <span class="fa fa-info-circle"></span></th>
				<th>362a <span class="fa fa-info-circle"></span></th>
				<th>866z <span class="fa fa-info-circle"></span></th>
			</tr>
		</thead>
		<tbody class="selectable">
		@foreach ($holdings as $holding)
			<tr id="<?= $holding->id ?>" class="{{ $holding->css }}" data-holdingsset="{{$holding->holdingsset_id}}">
				<td><input type="checkbox" value="{{ $holding->id }}" name="holding_id[]" class="sel hl"/></td>
				<td id="{{ $holding->id }}" class="actions">

					@if (Authority::can('revise', $holding))
					
						<a href="http://bis.trialog.ch/sets/from-library/<?= $holding->id; ?>" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" title="{{ trans('holdingssets.see_information_from_original_system') }}">
							<span class="glyphicon glyphicon-list-alt"></span>
						</a>

					  <a href="{{ route('oks.store') }}" class="btn-link btn-xs btn-ok" data-method="post" data-remote="true" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" >
					  	<span class="fa fa-thumbs-up"></span>
					  </a>

					  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-link btn-xs btn-tag">
					  	<span class="fa fa-tags"></span> 
					  </a>

					  <a href="{{ route('reviseds.store') }}" class="btn-link btn-xs btn-send" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
					  	<span class="fa fa-mail-forward"></span> 
					  </a>



					@endif

					@if (Authority::can('delivery', $holding))

					  <a href="{{ route('reviseds.store') }}" class="btn-link btn-xs btn-send" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
					  	<span class="fa fa-truck fa-flip-horizontal"></span> 
					  </a>

					@endif

				</td>
				<td>

					@if (Authority::can('set_size', $holding))

						<a href="#" class="editable" data-type="text" data-pk="{{$holding->id}}" data-url="{{ route('holdings.update',[$holding->id]) }}" >{{ $holding->size }} </a>

					@else

						{{ $holding->size }}

					@endif
					
				</td>
				<td>{{ $holding->f852b }} </td>
				<td><?= $holding->f852h; ?></td>
				<td><?= $holding->f866a; ?></td>
				<td class="ocrr_ptrn"><?= $holding->patrn ?></td>
 				<td>{{ $holding->holdingsset->f245a }}</td>
				<td><?= $holding->f362a; ?></td>
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

<div class="remote">
 <div class="modal" id="form-create-notes"></div><!-- /.modal -->
 <div class="modal" id="modal-show"></div><!-- /.modal -->
</div>

	@include('hlists.create')
@stop

