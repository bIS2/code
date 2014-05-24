@extends('layouts.print')

{{-- Content --}}
@section('main')

		<div >

				<div class="col-xs-12 h" >
					<div class="" >
						<div class="row">
							<div class="col-xs-10 col-md-offset-1">
								<h3>
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
								</h3>
								<hr>
							</div>
						</div> <!-- /.carousel-inner -->

							@foreach ($holdings as $holding)
							<div class="row item">
								<div id="<?= $holding->id ?>" class="col-xs-10 col-md-offset-1" >
									<p class="">
										<span class="text-muted">
											{{ $holding->f852h }}
											@if ($holding->f852b)
												[{{ $holding->f852b }}]
											@endif
										</span>
										<span class="">
											<strong>
												{{ $holding->f245a }}
												{{ $holding->f245b }}
												{{ $holding->f245c }}
												{{ $holding->f245d }}
											</strong>
										</span>
										<span>
											<em>
												{{ $holding->f866a }}
												@if ($holding->f866c || $holding->f866z)
													( {{ implode('-',[$holding->f866c, $holding->f866z]) }} )
												@endif
											</em>
										</span>
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
												<li> 
													{{ trans('fields.size')}}: ____________
												</li>
											</ul>
											<ul>
												@foreach ( $holding->notes as $note)
													<li>{{ $note->content }}</li>
												@endforeach
											</ul>
									</p>
									<div style="border-bottom: 1px dotted;margin-bottom: 30px;margin-top: 20px;"></div>
									<div style="border-bottom: 1px dotted;margin-bottom: 30px;margin-top: 20px;"></div>
								</div> <!-- /.col-xs-8 -->

							</div> <!-- /.row -->

						@endforeach
				  <!-- Controls -->
				</div>

			</div>

			
	</div>
</div>

@stop

