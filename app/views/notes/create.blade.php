<?php $consultnotes = (Input::get('consult') == 1) ? ' disabled' : ''; ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">{{{ trans('notes.title-create') }}}</h4>
		</div>

		<form action="{{ route('notes.store') }}" method="post" data-remote="true" id='create-note' class="create-note">
			{{ Form::hidden('hlist_id',$hlist_id) }}
			{{ Form::hidden('holding_id',$holding->id) }}

			<div class="modal-body {{$holding->css}}">
				<div  class="alert alert-danger alert-error hide"></div>

				<div class="row">
					<div class="col-xs-5 ">
						<dl class="dl-horizontal">
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
								{{ $holding->size }}
								<!-- @if ( Authority::can('set_size', $holding) ) -->
								<!-- <input type="text" value="{{ $holding->size }}" name="size" class="" readonly id="size" size="7" > -->
								<!-- @else -->
								<!-- @endif -->
							</dd>

							<dt>{{ trans('holdings.Abgabe') }}: </dt>
							<dd><strong>{{ $holding->fx866a }}</strong></dd>

							<dt>{{ trans('holdings.size_dispatchableslide') }}: </dt>
							<dd>
								{{ $holding->size_dispatchable }}
								<!-- @if ( Authority::can('set_size', $holding) ) -->
								<!-- <input type="text" value="{{ $holding->size_dispatchable }}" name="size_dispatchable" readonly class="" id="size_dispatchable" size="7" > -->
								<!-- @else -->
								<!-- @endif -->
							</dd>
						</dl>
					</div>
					<div class="col-xs-7">

						@foreach ( Tag::all() as $tag)

						<?php
						$note = ( $note=Note::whereHoldingId($holding->id)->whereTagId($tag->id)->first() ) ? $note : new Note;
						if ($username == '') $username = $note->user->username;
						if ($uname == '') $uname = $note->user->name;
												// var_dump($username);
						?>

						<div class="form-group">
							<div class="input-group" data-toggle="buttons">
								<label class="input-group-addon btn btn-primary btn-sm {{ ($note->tag_id) ? 'active' : '' }}{{ $consultnotes }}" >
									<input type="checkbox" name="notes[{{ $tag->id }}][tag_id]" value="{{ $tag->id }}" {{ ($note->tag_id) ? 'checked="checked"' : '' }}  />
									{{ trans('tags.'.$tag->name) }}
								</label>
								<input type="text"  name="notes[{{ $tag->id }}][content]" value="{{ $note->content }}" class="form-control input-sm content"{{ $consultnotes }} placeholder="{{ trans('placeholders.notes_'.$tag->name) }}">
							</div><!-- /input-group -->
							<div  class="text-danger error"></div>
						</div><!-- /input-group -->

						@endforeach

						@if ($errors->any())
						<ul>
							{{ implode('', $errors->all('<li class="error">:message</li>')) }}
						</ul>
						@endif

					</div>

				</div>
				@if ($consultnotes)
				<div class="row text-primary">
					<div class="col-xs-5 text-right">
						{{ trans('holdingssets.notes_made_by') }}
					</div>
					<div class="col-xs-5 text-left">
						<strong><i class="fa fa-user"></i>{{ $uname }} ({{ $username }})</strong>
					</div>
				</div>
				@endif
			</div>

			<div class="modal-footer">
				<button type="reset" class="btn btn-default{{ $consultnotes }}" ><?= trans('general.reset') ?></button>
				<button type="submit" id="submit-create-notes" class="btn btn-success{{ $consultnotes }}" data-disabled-with="{{trans('general.disable_with')}}">
					<i class="fa fa-check"></i> <?= trans('general.save') ?>
				</button>
				<a href="#" class="btn btn-danger" data-dismiss="modal" ><i class="fa fa-times"></i> <?= trans('general.close') ?></a>
			</div>

		</form>

	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<div class="hide">
	<div id="field_note_in_blank">{{ trans('errors.field_note_in_blank') }} </div>
	<div id="select_notes_is_0">{{ trans('errors.select_notes_is_0') }} </div>
</div>