@extends('layouts.default')

@section('content')

<div class="page-header">
	<h3>{{{ trans('feedbacks.title') }}} </h3>
</div>

@if ($feedbacks->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>User_id</th>
				<th>Client</th>
				<th>Content</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($feedbacks as $feedback)
				<tr>
					<td>{{{ $feedback->user_id }}}</td>
					<td>{{{ $feedback->client }}}</td>
					<td>{{{ $feedback->content }}}</td>
          <td>
          	{{ link_to_route('admin.feedbacks.edit', 'Edit', array($feedback->id), array('class' => 'btn btn-info')) }}
          	{{ link_to_route('admin.feedbacks.edit', 'Edit', array($feedback->id), array('class' => 'btn btn-info')) }}
          </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no feedbacks
@endif

@stop
