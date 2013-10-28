@extends('layouts.scaffold')

@section('main')

<h1>Show Comments_category</h1>

<p>{{ link_to_route('comments_categories.index', 'Return to all comments_categories') }}</p>

<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Name</th>
		</tr>
	</thead>

	<tbody>
		<tr>
			<td>{{{ $comments_category->name }}}</td>
                    <td>{{ link_to_route('comments_categories.edit', 'Edit', array($comments_category->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('comments_categories.destroy', $comments_category->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
		</tr>
	</tbody>
</table>

@stop
