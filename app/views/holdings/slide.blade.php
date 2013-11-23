@extends('layouts.default')

@section('toolbar')
	@include('holdings.toolbar')
@stop

{{-- Content --}}
@section('content')

		<div class="row">

				<div class="col-xs-12 h" >
					<div id="slider" class="carousel slide" data-ride="carousel" data-interval="false">
						<?php $i=0  ?>
						<div class="carousel-inner">
							@foreach ($holdings as $holding)
							<div class="row item <?= ($i==0) ? 'active' : '' ?>">
								<?php $i=1 ?>
								<div id="<?= $holding->id ?>" class="col-xs-5 col-md-offset-1 {{ ($holding->is_correct) ? 'success' : '' }} {{ ($holding->is_annotated) ? 'danger' : '' }}" >
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
										<form action="{{ route('notes.store') }}" method="post" data-remote="true" id='create-note'>
									@foreach ( Tag::all() as $tag)

										<?php $note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note ?>


										{{ Form::hidden('holding_id',$holding->id) }}

										<div class="form-group">
									    <div class="input-group" data-toggle="buttons">
									      <label class="input-group-addon btn btn-primary btn-sm {{ ($note->tag_id) ? 'active' : '' }}">
									      	<span class="glyphicon glyphicon-ok-sign"></span>
									        <input type="checkbox" name="notes[{{ $tag->id }}][tag_id]" value="{{ $tag->id }}">{{ $tag->name }}
									      </label>
									      <input type="text"  name="notes[{{ $tag->id }}][content]" value="{{ $note->content }}" class="form-control input-sm" placeholder="{{ trans('placeholders.notes_'.$tag->name) }}">
									    </div><!-- /input-group -->
									  </div><!-- /input-group -->

									@endforeach
									<div class="">
									  <a href="{{ route('oks.store') }}" class="btn btn-success btn-ok" data-method="post" data-remote="true" data-params="holding_id={{$holding->id}}" data-disable-with="{{trans('general.sending')}}">
									  	<span class="fa fa-thumbs-up"></span> {{trans('general.confirm')}}
									  </a>
									  <button href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" type="submit" class="btn btn-danger btn-tag" data-disable-with="{{trans('general.sending')}}">
									  	<span class="fa fa-tags"></span> {{trans('general.annotated')}}
									  </button>
									</div>

								</div> <!-- /.col-xs-4 -->

						</form>	
							</div> <!-- /.row.item -->
						@endforeach
					</div> <!-- /.carousel-inner -->
							<div class="row">
								<div class="col-xs-12 text-center">
								  <a class="btn btn-default btn-lg" href="#slider" data-slide="prev">
								    <span class="glyphicon glyphicon-chevron-left"></span>
								  </a>
								  <a class="btn btn-default btn-lg" href="#slider" data-slide="next">
								    <span class="glyphicon glyphicon-chevron-right"></span>
								  </a>					
								</div>
							</div>
				  <!-- Controls -->
				</div>

			</div>

			
	</div>
</div>

@stop

