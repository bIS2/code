@extends('layouts.default')

@section('content')

<h1>All Libraries</h1>

<p>{{ link_to('#myModal', 'Add new library',['data-toggle'=>"modal" ] ) }}</p>

@if ($libraries->count())
	<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th>{{trans('table.name')}}</th>
				<th>{{trans('table.code')}}</th>
				<th>{{trans('table.sublibraries')}}</th>
				<th>{{trans('table.externalurl')}}</th>
				<th>{{trans('table.no-users') }}</th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			@foreach ($libraries as $library)
				<tr>
					<td>{{{ $library->code }}}</td>
					<td>{{{ $library->name }}}</td>
					<td>{{{ $library->sublibraries }}}</td>
					<td>{{{ $library->externalurl }}}</td>
					<td>{{{ $library->users()->count() }}}</td>
                    <td>
                    	<a href="{{route('admin.libraries.edit',$library->id)}}" class = 'btn btn-info btn-sm'>
                    		<i class="fa fa-edit"></i>
                    		{{trans('general.edit')}}
                    	</a>
<!--                     {{ link_to_route('admin.libraries.edit', 'Edit', [$library->id], 
                    	[
	                    	'class' => 'btn btn-danger btn-sm',
	                    	'data-method' => 'DELETE',
	                    	'date-remote' => 'true'
                    	] ) }} -->
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
