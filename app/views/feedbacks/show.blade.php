@extends('layouts.scaffold')

@section('main')

<h1>Show Feedback</h1>

<p>{{ link_to_route('feedbacks.index', 'Return to all feedbacks') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>User_id</th>
				<th>Client</th>
				<th>Content</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $feedback->user_id }}}</td>
					<td>{{{ $feedback->client }}}</td>
					<td>{{{ $feedback->content }}}</td>
                    <td>{{ link_to_route('feedbacks.edit', 'Edit', array($feedback->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('feedbacks.destroy', $feedback->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
