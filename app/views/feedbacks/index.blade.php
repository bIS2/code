@extends('layouts.default')

@section('content')

<div class="page-header">
	<h3>{{{ trans('feedbacks.title') }}} </h3>
</div>

@if ($feedbacks->count())
	<table class="table table-condensed table-hover">
		<thead>
			<tr>
				<th>{{ trans('table.email')}} </th>
				<th>{{ trans('table.browser')}} </th>
				<th>{{ trans('table.user')}} </th>
				<th>{{ trans('table.content')}} </th>
				<th>{{ trans('table.url')}} </th>
			</tr>
		</thead>

		<tbody>
			@foreach ($feedbacks as $feedback)
				<tr id="{{ $feedback->id }}">
					<td>{{{ $feedback->user->email }}}</td>
					<td>{{{ $feedback->user->username }}}</td>
					<td>{{{ $feedback->client }}}</td>
					<td>{{{ $feedback->content }}}</td>
					<td>{{{ $feedback->url }}}</td>
          <td>
          	{{ link_to_route('admin.feedbacks.edit', 'Edit', array($feedback->id), array('class' => 'btn btn-info btn-xs')) }}
          	<a href="{{ route('admin.feedbacks.destroy',$feedback->id) }}" data-remote="true" data-method="delete" class="btn btn-danger btn-xs">
          		<span class="fa fa-times"></span> {{ trans('general.delete') }}
          	</a>
          </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no feedbacks
@endif

@stop
