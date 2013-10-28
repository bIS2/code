@extends('layouts.scaffold')

@section('main')

<h1>All Comments</h1>

<p>{{ link_to_route('comments.create', 'Add new comment') }}</p>

@if ($comments->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Holding_id</th>
				<th>Category_id</th>
				<th>User_id</th>
				<th>Comments</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($comments as $comment)
				<tr>
					<td>{{{ $comment->holding_id }}}</td>
					<td>{{{ $comment->category_id }}}</td>
					<td>{{{ $comment->user_id }}}</td>
					<td>{{{ $comment->comments }}}</td>
                    <td>{{ link_to_route('comments.edit', 'Edit', array($comment->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('comments.destroy', $comment->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no comments
@endif

@stop
