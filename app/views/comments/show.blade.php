@extends('layouts.scaffold')

@section('main')

<h1>Show Comment</h1>

<p>{{ link_to_route('comments.index', 'Return to all comments') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>User_id</th>
				<th>Holding_id</th>
				<th>Content</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $comment->user_id }}}</td>
					<td>{{{ $comment->holding_id }}}</td>
					<td>{{{ $comment->content }}}</td>
                    <td>{{ link_to_route('comments.edit', 'Edit', array($comment->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('comments.destroy', $comment->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
