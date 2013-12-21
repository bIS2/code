@extends('layouts.admin')

@section('content')

<h1>
	{{ trans('notes.title-index') }}
	<small>{{ trans('notes.subtitle-index') }}</small>
</h1>

@if ($notes->count())
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Name</th>
				<th>{{ trans('table.no-holdings') }}</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($notes as $note)
				<tr>
					<td>{{{ $note->title }}}</td>
          <td>
          	{{ link_to_route('notes.edit', trans('general.edit'), array($note->id), array('class' => 'btn btn-info btn-sm')) }}
          	{{ link_to_route('notes.destroy', trans('general.'), array($note->id), array('class' => 'btn btn-danger btn-sm', 'data-method'=>"DELETE", 'data-remote'=>'true' )) }}
          </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no notes
@endif

@include('notes.create')

@stop
