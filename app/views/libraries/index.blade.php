@extends('layouts.admin')

@section('content')

<h1>All Libraries</h1>

<p>{{ link_to('#myModal', 'Add new library',['data-toggle'=>"modal" ] ) }}</p>

@if ($libraries->count())
	<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th>Name</th>
				<th>Code</th>
			</tr>
		</thead>

		<tbody>
			@foreach ($libraries as $library)
				<tr>
					<td>{{{ $library->name }}}</td>
					<td>{{{ $library->code }}}</td>
                    <td>
                    {{ link_to_route('admin.libraries.edit', 'Edit', array($library->id), array('class' => 'btn btn-info btn-sm')) }}
                    {{ link_to_route('admin.libraries.edit', 'Edit', [$library->id], 
                    	[
	                    	'class' => 'btn btn-danger btn-sm',
	                    	'data-method' => 'DELETE',
	                    	'date-remote' => 'true'
                    	] ) }}
                    </td>
				</tr>
			@endforeach
		</tbody>
	</table>
@else
	There are no libraries
@endif

@include('libraries.create')
@stop
