@extends('layouts.default')


{{-- Content --}}
@section('content')

<div class="page-header">
	<h2> 
		{{ trans('holdings.title') }} 
		@if ($hlist)
			<small>&raquo; {{ $hlist->name }}</small>
		@endif

	</h2>
</div>
<div class="row">
	<div class="col-lg-12">

		<table id="holdings-items" class="table table-hover table-condensed ">
		<thead>
			<tr> 
				<th><?= '245a'; ?></th>
				<th><?= '245b'; ?></th>
				<th><?= '245c'; ?></th>
				<th><?= '260b'; ?></th>
				<th><?= '362a'; ?></th>
				<th><?= '866a'; ?></th>
				<th><?= '852h'; ?></th>
				<td></td>
			</tr>
		</thead>
		<tbody>
		@foreach ($holdings as $holding)
			<tr id="<?= $holding->id ?>">
<!-- 				<td>
					<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
						<span class="glyphicon glyphicon-eye-open"></span>
					</a>
					<a href="<?= route('holdings.show', $holding->id) ?>" data-target="#modal-show" data-toggle="modal" data-remote="<?= route('holdings.show', $holding->id) ?>">
											<span class="glyphicon glyphicon-ok"></span>
					</a>
				</td>

 -->		
 				<td>
 					{{ link_to_route('holdings.show', $holding->holdingsset->f245a,[ $holding->id ]) }}
 				</td>
				<td><?= $holding->f245b; ?></td>
				<td><?= $holding->f245c; ?></td>
				<td><?= $holding->f260b; ?></td>
				<td><?= $holding->f362a; ?></td>
				<td><?= $holding->f866a; ?></td>
				<td><?= $holding->f852h; ?></td>
				<td id="{{ $holding->id }}" class="col-lg-1">
					<div class="btn-group">
					  <a href="{{ action('HoldingsController@putOK',[$holding->id]) }}" class="btn btn-default btn-xs btn-ok" data-method="put" data-remote="true" >
					  	<span class="glyphicon glyphicon-thumbs-up"></span>
					  </a>
					  <a href="#" data-toggle="modal" data-target="#form-create-tags"  class="btn btn-default btn-xs">
					  	<span class="glyphicon glyphicon-tags"></span>
					  </a>
					</div>
				</td>

			</tr>
		@endforeach

		</tbody>
	</table>

	<?= $holdings->links()  ?>


	</div>
</div>


	@include('tags.create')

	@include('hlists.create')
	@include('hlists.index')
@stop
