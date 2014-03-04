@extends('layouts.print')

{{-- Content --}}
@section('main')

		<div >

				<div class="col-xs-12 h" >
					<div class="" >
						<div class="row">
							<div class="col-xs-10 col-md-offset-1">
								<h1>bIS :: {{ trans('titles.holdings') }}</h1>
								<h2>
									{{ $hlist->name}}
									<small>
										<ul class="pull-right list-inline">
											<li>
												<i class="fa fa-calendar"></i>
												{{$hlist->created_at->toDateString()}}
											</li>
											<li>
												<i class="fa fa-user"></i>
												{{ $hlist->worker->username }}
											</li>
											<li>
												<i class="fa fa-th"></i>
												{{ $hlist->holdings->count() }}
											</li>
										</ul>
									</small>
								</h2>
								<hr>
							</div>
							@foreach ($holdings as $holding)
							<div class="row item">
								<div id="<?= $holding->id ?>" class="col-xs-5 col-md-offset-1" >
									<div class="well well-sm">
										<div>
												<label  >852b: </label>
									  		{{ $holding->f852b }}
										</div>
										<div >
										  <label >852h: </label >
										  {{ $holding->f852h }}
										</div>
										<div class="ocrr_ptrn " style="white-space: inherit !important;">
										  <label >Patrn: </label >
									  	{{ $holding->patrn }}
										</div>
										<div >
										  <label >245a: </label >
										  {{ $holding->f245a }}
										</div>
										<div >
										  <label >362a: </label >
										  {{ $holding->f362a }}
										</div>
										<div >
										  <label >866a: </label >
										  {{ $holding->f866a }}
										</div>
										<div >
										  <label >866z: </label >
										  {{ $holding->f866z }}
										</div>
									</div>


								</div> <!-- /.col-xs-8 -->
								<div class="col-xs-5">
									<div>
										<ul class="list-inline">
											<li>
												<h5>
													<span class="fa fa-square-o"></span> {{trans('general.ok')}}
												</h5>
											</li>
											@foreach ( $tags = Tag::all() as $tag)
												<?php $note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note ?>
												<li>
													<h5>
													<?= ($note->tag_id) ? '<span class="fa fa-check-square-o"></span>' : '<span class="fa fa-square-o"></span>' ?> {{ trans('tags.'.$tag->name) }}
													</h5>
												</li>
											@endforeach
										</ul>
										{{ trans('fields.size')}} <input type="text">
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

