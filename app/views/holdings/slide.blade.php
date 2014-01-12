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
											<form action="{{ route('notes.store') }}" method="post" data-remote="true" id='create-note'>
								<div class="row">

									<?php $i=1 ?>
									<div id="<?= $holding->id ?>" class="col-xs-5 col-md-offset-1 {{ ($holding->is_correct) ? 'success' : '' }} {{ ($holding->is_annotated) ? 'danger' : '' }}" >
										<div class="well">
											<div class="row">
											  <label class="col-xs-2 text-right">{{trans('holdings.size')}}</label >
											  <div class="col-xs-10">
	  											<a href="#" class="editable" data-type="text" data-pk="{{$holding->id}}" data-url="{{ route('holdings.update',[$holding->id]) }}" >{{ $holding->size }} </a>
											  </div>
											</div>
											<div class="row">
												<label class="col-xs-2 text-right" >852b</label>
												<div class="col-xs-10">
										  		{{ link_to_route( 'holdings.show', $holding->f852b, [ $holding->f852b ] ) }}
												</div>
											</div>
											<div class="row">
											  <label class="col-xs-2 text-right">852h</label >
											  <div class="col-xs-10">{{ $holding->f852h }}</div>
											</div>
											<div class="row">
											  <label class="col-xs-2 text-right">Patrn</label >
											  <div class="ocrr_ptrn col-xs-10">{{ $holding->patrn }}</div>
											</div>
											<div class="row">
											  <label class="col-xs-2 text-right">245a</label >
											  {{ $holding->f245a }}
											</div>
											<div class="row">
											  <label class="col-xs-2 text-right">362a</label >
											  {{ $holding->f362a }}
											</div>
											<div class="row">
											  <label class="col-xs-2 text-right">866a</label >
											  {{ $holding->f866a }}
											</div>
											<div class="row">
											  <label class="col-xs-2 text-right">866z</label >
											  {{ $holding->f866z }}
											</div>
										</div>
									</div> <!-- /.col-xs-8 -->
									<div class="col-xs-5">

										@foreach ( Tag::all() as $tag)

											<?php $note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note ?>


											{{ Form::hidden('holding_id',$holding->id) }}

											<div class="form-group">
										    <div class="input-group" data-toggle="buttons">
										      <label class="input-group-addon btn btn-primary btn-sm {{ ($note->tag_id) ? 'active' : '' }}">
										        <input type="checkbox" name="notes[{{ $tag->id }}][tag_id]" value="{{ $tag->id }}">{{ $tag->name }}
										      </label>
										      <input type="text"  name="notes[{{ $tag->id }}][content]" value="{{ $note->content }}" class="form-control input-sm" placeholder="{{ trans('placeholders.notes_'.$tag->name) }}">
										    </div><!-- /input-group -->
										  </div><!-- /input-group -->

										@endforeach


									</div> <!-- /.col-xs-4 -->
								</div> <!-- /.row -->

								<div class="row">
									<div class="col-xs-5 col-md-offset-1">
									  <a href="{{ route('oks.store') }}" class="btn btn-success btn-ok col-sm-12" data-method="post" data-remote="true" data-params="holding_id={{$holding->id}}" data-disable-with="{{trans('general.sending')}}">
									  	<span class="fa fa-thumbs-up"></span> {{trans('general.confirm')}}
									  </a>

									</div>
									<div class="col-xs-5">
									  <button href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" type="submit" class="btn btn-danger btn-tag col-sm-12" data-disable-with="{{trans('general.sending')}}">
									  	<span class="fa fa-tags"></span> {{trans('general.annotated')}}
									  </button>
									</div>
								</div>
							</div> <!-- /.row.item -->

							</form>	

						@endforeach
					</div> <!-- /.carousel-inner -->
							<div class="row" style="margin-top:20px">
								<div class="col-xs-12 text-center">
								  <a class="btn btn-default " href="#slider" data-slide="prev">
								    <span class="fa fa-chevron-left"></span>
								  </a>
								  <a class="btn btn-default " href="#slider" data-slide="next">
								    <span class="fa fa-chevron-right"></span>
								  </a>					
								</div>
							</div>
				  <!-- Controls -->
				</div>

			</div>

			
	</div>
</div>

@stop

