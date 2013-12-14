<form action="{{route('admin.feedbacks.store')}}" method="post" data-remote="true" >

    {{ Form::hidden( 'user_id',Auth::user()->id) }}
    {{ Form::hidden('client') }}
    {{ Form::hidden('url') }}
    <div class="form-group">
    	{{ Form::textarea('content',null,['class'=>'form-control', 'rows'=>4]) }}
    </div>

		<div class="form-group">
			{{ Form::submit('Submit', array('class' => 'btn btn-info btn-block')) }}
		</div>

</form>




