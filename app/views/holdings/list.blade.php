@extends('layouts.default')

@section('toolbar')
	@include('holdings.toolbar')
@stop

{{-- Content --}}
@section('content')


<div class="row">
	<div class="col-xs-12">
			
		<?php $i = 0 ?>
				
		@foreach ($holdings as $holding)

			<div class="row">
				<div id="<?= $holding->id  ?>" class="col-xs-12 h" >
					<div id="slider" class="carousel slide" data-ride="carousel">
				<!-- 	<div class="well well-sm <?= ($holding->is_correct) ? 'well-success' : '' ?> <?= ($holding->is_annotated) ? 'well-danger' : '' ?>"> -->
						<div class="pull-right actions" style="display:none">
						  <a href="{{ route('oks.store') }}" data-params="holding_id={{$holding->id}}" data-method="post" data-remote="true" >
						  	<span class="fa fa-thumbs-up"></span> {{ trans('general.correct') }}
						  </a>
						  <?php $is_tagged = ( ($count=$holding->notes->count())>0)  ?>
						  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-tag">
						  	<span class="fa fa-tags"></span> {{ trans('general.notes') }}
						  </a>
						</div>

						<div>
							<abbr class="text-muted">852b <i class="fa  fa-caret-right"></i></abbr>
					  	{{ link_to_route( 'holdings.show', $holding->f852b, [ $holding->f852b ] ) }}
						</div>
						<div>
						  <abbr class="text-muted">852h <i class="fa  fa-caret-right"></i></abbr >
						  {{ $holding->f852h }}
						</div>
						<div >
						  <abbr class="text-muted">Patrn <i class="fa  fa-caret-right"></i></abbr >
						  <span class="ocrr_ptrn">{{ $holding->patrn }}</span>
						</div>
						<div>
						  <abbr class="text-muted">245a <i class="fa  fa-caret-right"></i></abbr >
						  {{ $holding->f245a }}
						</div>
						<div>
						  <abbr class="text-muted">362a <i class="fa  fa-caret-right"></i></abbr >
						  {{ $holding->f362a }}
						</div>
						<div>
						  <abbr class="text-muted">866a <i class="fa  fa-caret-right"></i></abbr >
						  {{ $holding->f866a }}
						</div>
						<div>
						  <abbr class="text-muted">866z <i class="fa  fa-caret-right"></i></abbr >
						  {{ $holding->f866z }}
						</div>
					</div>
				</div>

			</div>

		@endforeach
			
	</div>
</div>
<div class="row text">
	<div class="col-xs-12 text-center">
		<?= $holdings->appends(Input::except('page'))->links()  ?>
	</div>
</div>



	</div>
</div>

<div class="remote">
 <div class="modal" id="form-create-notes"></div><!-- /.modal -->
</div>

	@include('hlists.create')
@stop

