@extends('layouts.admin')

@section('content')

<h1>
	{{ trans('tags.title-index') }}
	<small>{{ trans('tags.subtitle-index') }}</small>
</h1>

<p>
	<a href="#myModal" data-toggle="modal" >{{ trans('tags.add-new') }}</a>
	{{ link_to_route('admin.tags.create', 'Add new tag') }}
</p>

@if ($tags->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Name</th>
				<th>{{ trans('table.no-holdings') }}</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($tags as $tag)
				<tr>
					<td>{{{ $tag->name }}}</td>
					<td>{{{ $tag->holdings->count() }}}</td>
          <td>
          	{{ link_to_route('admin.tags.edit', trans('general.edit'), array($tag->id), array('class' => 'btn btn-info btn-sm')) }}
          	{{ link_to_route('admin.tags.destroy', trans('general.'), array($tag->id), array('class' => 'btn btn-danger btn-sm', 'data-method'=>"DELETE", 'data-remote'=>'true' )) }}
          </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no tags
@endif

@include('tags.create')

@stop
