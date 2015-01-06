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
						<span>
							@if ($holding->f852h)
							<strong>{{ $holding->f852h }} @if (($holding->f866c) || ($holding->f852b)) - @endif  </strong>							
							@endif
							@if ($holding->f852b)
							{{ $holding->f852b }} @if ($holding->f866c) - @endif
							@endif
							@if ($holding->f866c)
							{{ $holding->f866c }}
							@endif
						</span>
						<br>
						<span>
							<?php
								$title = '';
								if($holding->f245a != '') $title .= $holding->f245a.' ';
								if($holding->f245b != '') $title .= ': '.$holding->f245b.'. ';
								if($holding->f245p != '') $title .= $holding->f245p.'. ';
								if($holding->f245n != '') $title .= $holding->f245n.'. ';
								if($holding->f245c != '') $title .= '/ '.$holding->f245c;
								$title = str_replace('<', '', $title);
								$title = str_replace('>', '', $title);
							?>
							{{ $title }}
						</span>
						<br>
						<span>
							{{ trans('holdings.f866atitle') }}: 
							{{ ($holding->f866aupdated != '') ? $holding->f866aupdated : $holding->f866a }}
						</span>
						<br>
						<span>
							<strong>{{ trans('holdings.Abgabe') }}: 
							{{ $holding->fx866a }}</strong>
						</span>
						<ul class="list-inline">
							<li> 
								{{ trans('holdings.sizeslide') }}: ____________
							</li>						
							<li> 
								<strong>{{ trans('holdings.size_dispatchableslide') }}: ____________</strong> 
							</li>
							<li>
								<h5>
									<span class="fa fa-square-o"></span> {{trans('general.ok')}}
								</h5>
							</li>
							@foreach ( $tags = Tag::all() as $tag)
							<?php $note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note ?>
							<li>
								<?= ($note->tag_id) ? '<span class="fa fa-check-square-o"></span>' : '<span class="fa fa-square-o"></span>' ?> {{ trans('tags.'.$tag->name) }}
							</li>
							@endforeach
						</ul>
						@if ($holding->notes()->exists())
						{{ trans('holdings.notes').': '.implode( ',', $holding->notes->lists('content')) }}
						@endif
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

