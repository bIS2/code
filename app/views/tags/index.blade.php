@extends('layouts.default')

@section('content')

<div class="page-header">
	<div class="row">
		<div class="col-xs-12">
			<h2> 
				{{ trans('titles.tags') }} 
			</h2>
		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->
</div> <!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			@if ($tags->count())
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>{{ trans('general.name')}}</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($tags as $tag)
							<tr>
								<td>
								<a href="#" class="editable" data-type="text" data-name="name" data-pk="{{$tag->id}}" data-url="{{ route('admin.tags.update',[$tag->id]) }}" >{{ $tag->name }} </a>						
								</td>
			<!--           <td>
			          	{{ link_to_route('admin.tags.edit', trans('general.edit'), array($tag->id), array('class' => 'btn btn-info btn-sm')) }}
			          	{{ link_to_route('admin.tags.destroy', trans('general.'), array($tag->id), array('class' => 'btn btn-danger btn-sm', 'data-method'=>"DELETE", 'data-remote'=>'true' )) }}
			          </td>
			 -->				</tr>
						@endforeach
					</tbody>
				</table>
			@else
				There are no tags
			@endif

		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->
@stop
