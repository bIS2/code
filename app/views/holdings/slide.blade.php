@extends('layouts.default')
{{-- Show holding index in slideshow way--}}

@section('toolbar')
@include('holdings.toolbar')
@stop

{{-- Content --}}
@section('content')

<div class="row">

	<div class="col-xs-12 h" >
		<div id="slider" class="carousel slide" data-ride="carousel" data-interval="false" data-wrap="false">
			<?php $i=0  ?>
			<div class="row" style="margin-bottom:20px">
				<div class="col-xs-12 text-center">
					<a class="btn btn-default " href="#slider" data-slide="prev">
						<span class="fa fa-chevron-left"></span>
					</a>
					<span id="num" class="btn btn-default"></span>
					<a class="btn btn-default " href="#slider" data-slide="next">
						<span class="fa fa-chevron-right"></span>
					</a>					
				</div>
			</div>
			<!-- Controls -->						
			<div class="carousel-inner">
				@foreach ($holdings as $holding)

				<div class="row item <?= ($i==0) ? 'active' : '' ?>">
					<form action="{{ route('notes.store') }}" method="post" data-remote="true" id='create-note-{{$holding->id}}' class="create-note">

						<div class="row" style="margin-bottom:20px">

							<?php $disabled = (Authority::can('touch', $holding)) ? '' : 'disabled'  ?>

							<div class="col-xs-5 col-md-offset-1">
								<a href="{{ route('states.store') }}" action="click-and-slide" class="btn btn-success btn-ok col-sm-12" data-method="post" data-remote="true" data-params="state=ok&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-disable-with="{{trans('general.sending')}}" {{ $disabled }}>
									<span class="fa fa-thumbs-up"></span> {{trans('general.confirm')}}
								</a>
							</div>

							<div class="col-xs-5">
								<button id="submit-create-notes" href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" type="submit" class="btn btn-danger btn-tag col-sm-12" data-disable-with="{{trans('general.sending')}}" {{ $disabled }}>
									<span class="fa fa-tags"></span> {{trans('holdings.annotate')}}
								</button>
							</div>
						</div>

						<div class="row ">

							<?php $i=1 ?>
							<div id="<?= $holding->id ?>" class="col-xs-5 col-md-offset-1 {{ $holding->css }} {{ ($holding->is_correct) ? 'success' : 'not_ok' }} {{ ($holding->is_annotated) ? 'danger' : '' }}" >
								<div class="well" id="holding-slide" style="height: 350px; overflow-y: auto;">
									<dl class="dl-horizontal ">
	<!-- 									<dt >{{ trans('general.state') }}</dt>
										<dd class="state">
											<span class="label label-primary">{{ $holding->title_state }}</span>
										</dd> -->
										<dt >{{ trans('general.library') }}: </dt>
										<dd>{{ $holding->f852b }}</dd>

										<dt>{{ trans('holdings.Standort') }}: </dt>
										<dd>{{ $holding->f866c }}</dd>

										<dt>{{ trans('holdings.signature') }}: </dt>
										<dd>{{ $holding->f852h }}</dd>

										<dt>{{ trans('holdings.Titel') }}: </dt>								
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
										<dd>{{ $title }}</dd>

										<dt>{{ trans('holdings.f866atitle') }}: </dt>
										<!-- <dd>{{ ($holding->f866aupdated != '') ? $holding->f866aupdated : $holding->f866a }}</dd> -->
										<dd>{{ $holding->f866a }}</dd>

										<dt>{{ trans('holdings.sizeslide') }}: </dt>
										<dd>
											@if ( Authority::can('set_size', $holding) )
											<input type="text" value="{{ $holding->size }}" name="size" class="" id="size" size="7" >
											@else
											{{ $holding->size }}
											@endif
										</dd>

										<dt>{{ trans('holdings.Abgabe') }}: </dt>
										<dd><strong>{{ $holding->fx866a }}</strong></dd>

										<dt>{{ trans('holdings.size_dispatchableslide') }}: </dt>
										<dd>
											@if ( Authority::can('set_size', $holding) )
											<input type="text" value="{{ $holding->size_dispatchable }}" name="size_dispatchable" class="" id="size_dispatchable" size="7" >
											@else
											{{ $holding->size_dispatchable }}
											@endif
										</dd>
									</dl>
								</div>
							</div> <!-- /.col-xs-8 -->
							<div class="col-xs-5">

								{{ Form::hidden('holding_id',$holding->id) }}
								@foreach ( Tag::all() as $tag)

								<?php $note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note ?>



								<div class="form-group">
									<div class="input-group" data-toggle="buttons">
										<label class="input-group-addon btn btn-primary btn-sm {{ ($note->tag_id) ? 'active' : '' }}">
											<input type="checkbox" name="notes[{{ $tag->id }}][tag_id]" value="{{ $tag->id }}" data-tagid="{{ $tag->id }}">{{ trans('tags.'.$tag->name) }}
										</label>
										<input type="text"  name="notes[{{ $tag->id }}][content]" value="{{ $note->content }}" class="form-control input-sm content" placeholder="{{ trans('placeholders.notes_'.$tag->name) }}">
									</div><!-- /input-group -->
									<div  class="text-danger error"></div>
								</div><!-- /input-group -->

								@endforeach


							</div> <!-- /.col-xs-4 -->
						</div> <!-- /.row -->



					</div> <!-- /.row.item -->

				</form>	

				@endforeach
			</div> <!-- /.carousel-inner -->



		</div>

	</div>


</div>
</div>
<div class="hide">
	<div id="field_size_in_blank">{{ trans('errors.field_size_in_blank') }}</div>
	<div id="field_size_dispatchable_in_blank" class="hide">{{ trans('errors.field_size_dispatchable_in_blank') }}</div>
	<div id="field_note_in_blank">{{ trans('errors.field_note_in_blank') }} </div>
	<div id="select_notes_is_0">{{ trans('errors.select_notes_is_0') }} </div>
</div>
@stop

