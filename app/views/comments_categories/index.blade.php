@extends('layouts.scaffold')

@section('main')

<h1>All Comments_categories</h1>

<p>{{ link_to_route('comments_categories.create', 'Add new comments_category') }}</p>

@if ($comments_categories->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Name</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($comments_categories as $comments_category)
				<tr>
					<td>{{{ $comments_category->name }}}</td>
                    <td>{{ link_to_route('comments_categories.edit', 'Edit', array($comments_category->id), array('class' => 'btn btn-info')) }}</td>
                    <td>
                        {{ Form::open(array('method' => 'DELETE', 'route' => array('comments_categories.destroy', $comments_category->id))) }}
                            {{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
                        {{ Form::close() }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no comments_categories
@endif

@stop
