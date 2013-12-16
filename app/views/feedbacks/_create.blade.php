<form action="{{route('admin.feedbacks.store')}}" method="post" data-remote="true" >

    {{ Form::hidden( 'user_id',Auth::user()->id) }}
    {{ Form::hidden('client') }}
    {{ Form::hidden('url',Request::url()) }}
    <div class="form-group">
    	{{ Form::textarea('content',null,['class'=>'form-control', 'rows'=>4]) }}
    </div>

		<div class="form-group">
			<div class="row">
				<div class="col-xs-6">
					<button type="submit" class='btn btn-info btn-block' data-disable-with="{{trans('general.sending')}}">{{trans('general.send')}}</button>
				</div>
				<div class="col-xs-6">
					<button class="btn btn-default btn-block close-popover">{{{ trans('general.cancel') }}}</button>
				</div>
			</div>
		</div>

</form>




