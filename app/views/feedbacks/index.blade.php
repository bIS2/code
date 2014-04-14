@extends('layouts.default')

@section('content')

<div class="page-header">
	<h3>{{{ trans('titles.feedbacks') }}} </h3>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="row">
			{{ Form::open(['url' => route('admin.feedbacks.index'), 'method'=>'get']) }}

		    <div class="input-group col-xs-6">
	      	<input type="text" class="form-control" name="q">
		      <span class="input-group-btn">
		        <button class="btn btn-default " type="submit">
		        	<i class="fa fa-search"></i> {{ trans('general.search') }}
		        </button>
		      </span>
		    </div><!-- /input-group -->

			{{ Form::close() }}
		</div>
	</div>
</div>
<br>
<div class="row">
	<div class="col-xs-12">

		@if ($feedbacks->count())
			<table class="table table-condensed table-hover datatable">
				<thead>
					<tr>
						<th>{{ trans('table.date')}} </th>
						<th>{{ trans('table.user')}} </th>
						<th>{{ trans('table.browser')}} </th>
						<th>{{ trans('table.content')}} </th>
						<th>{{ trans('table.url')}} </th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					@foreach ($feedbacks as $feedback)
						<tr id="{{ $feedback->id }}">
							<td>{{{ $feedback->created_at->toFormattedDateString() }}}</td>
							<td>{{{ $feedback->user->username }}}</td>
							<td>{{{ $feedback->client }}}</td>
							<td>
								<a href="#" class="editable" data-type="textarea" data-rows="3" data-cols="20" data-pk="{{$feedback->id}}" data-url="{{ route('admin.feedbacks.update',[$feedback->id]) }}" >{{{ $feedback->content }}}</a>
							</td>
							<td>{{{ $feedback->url }}}</td>
		          <td>
		          	<a href="{{ route('admin.feedbacks.destroy',$feedback->id) }}" data-remote="true" data-method="delete" class="btn btn-danger btn-xs">
		          		<span class="fa fa-times"></span> {{ trans('general.delete') }}
		          	</a>
		          </td>
						</tr>
					@endforeach
				</tbody>
			</table>
			</div>
				<p>
					{{ $feedbacks->appends(Input::except('page'))->links()  }}
				</p>
			</div>	
		@else
			There are no feedbacks
		@endif

		@stop
	</div> <!-- .col -->
</div> <!-- .row -->


