@extends('layouts.print')

{{-- Content --}}
@section('main')

		<div class="row">

				<div class="col-xs-12 h" >
					<div class="" >
						<div >
							<h1>bIS :: {{ trans('titles.holdings') }}</h1>
							<hr>
							@foreach ($holdings as $holding)
							<div class="row item">
								<div id="<?= $holding->id ?>" class="col-xs-5 col-md-offset-1" >
									<div class="well">
										<div class="row">
											<label class="col-xs-1 text-right" >852b</label>
											<div class="col-xs-11">
									  		{{ link_to_route( 'holdings.show', $holding->f852b, [ $holding->f852b ] ) }}
											</div>
										</div>
										<div class="row">
										  <label class="col-xs-1 text-right">852h</label >
										  <div class="col-xs-11">{{ $holding->f852h }}</div>
										</div>
										<div class="row">
										  <label class="col-xs-1 text-right">Patrn</label >
										  <div class="ocrr_ptrn col-xs-11">{{ $holding->patrn }}</div>
										</div>
										<div class="row">
										  <label class="col-xs-1 text-right">245a</label >
										  {{ $holding->f245a }}
										</div>
										<div class="row">
										  <label class="col-xs-1 text-right">362a</label >
										  {{ $holding->f362a }}
										</div>
										<div class="row">
										  <label class="col-xs-1 text-right">866a</label >
										  {{ $holding->f866a }}
										</div>
										<div class="row">
										  <label class="col-xs-1 text-right">866z</label >
										  {{ $holding->f866z }}
										</div>
									</div>


								</div> <!-- /.col-xs-8 -->
								<div class="col-xs-5">
									<div>
										<ul class="list-inline">
											<li><span class="fa fa-square-o"></span> {{trans('general.ok')}}</li>
											@foreach ( $tags = Tag::all() as $tag)
												<?php $note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note ?>
												<li>
													<?= ($note->tag_id) ? '<span class="fa fa-check-square-o"></span>' : '<span class="fa fa-square-o"></span>' ?> {{ $tag->name }}
												</li>
											@endforeach
										</ul>
										<ul>
											@foreach ( $holding->notes as $note)
												<li>{{ $note->content }}</li>
											@endforeach
										</ul>

									</div>
									<div></div>

								</div> <!-- /.col-xs-4 -->

							</div> <!-- /.row.item -->
						@endforeach
					</div> <!-- /.carousel-inner -->
				  <!-- Controls -->
				</div>

			</div>

			
	</div>
</div>

@stop

