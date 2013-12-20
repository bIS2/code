@extends('layouts.default')

@section('content')

<div class="page-header">
	<h3>{{{ trans('titles.feedbacks') }}} </h3>
</div>

@if ($feedbacks->count())
	<table class="table table-condensed table-hover datatable">
		<thead>
			<tr>
				<th>{{ trans('table.date')}} </th>
				<th>{{ trans('table.email')}} </th>
				<th>{{ trans('table.user')}} </th>
				<th>{{ trans('table.browser')}} </th>
				<th>{{ trans('table.content')}} </th>
				<th>{{ trans('table.url')}} </th>
			</tr>
		</thead>

		<tbody>
			@foreach ($feedbacks as $feedback)
				<tr id="{{ $feedback->id }}">
					<td>{{{ $feedback->created_at->toFormattedDateString() }}}</td>
					<td>{{{ $feedback->user->email }}}</td>
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
