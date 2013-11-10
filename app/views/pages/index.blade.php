@extends('layouts.default')

@section('content')
	
<div class="page-header">
	<div class="row">
		<div class="col-xs-12">
			<h2> 
				{{ trans('home.title') }} 
			</h2>
		</div> <!-- /.col-xs-12 -->
	</div> <!-- /.row -->
</div> <!-- /.page-header -->

	<ul>
		@foreach ($traces as $trace) 
			<li>
				{{ $trace->created_at->toDateString() }}
				{{ $trace->user->username }}
				{{ $trace->action }}
			</li>
		@endforeach
	</ul>

@stop