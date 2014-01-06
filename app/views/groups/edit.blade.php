@extends('layouts.default')

@section('content')

<div class="panel panel-info">
	<div class="panel-heading">
		<h1 class="panel-title">{{ trans('groups.edit_group') }}</h1>
	</div>
	<div class="panel-body">
		{{ Form::model($group, array('method' => 'PATCH', 'route' => array('groups.update', $group->id), 'role' => 'form')) }}
			<div class="input-group text-center col-xs-12">
					<div class="form-group">
		        <div class="input-group">
		            {{ Form::label('name', trans('groups.name'), array('class' => "input-group-addon")) }}
		            {{ Form::text('name', $group->name, array('class' => "form-control"))}}
		            {{ Form::hidden('user_id', Auth::user()->id)}}
		        </div>
		      </div>
				<div class="form-group">
					{{ Form::submit(trans('general.update'), array('class' => 'btn btn-info')) }}
					<a href="http://bis.trialog.ch/groups"><input type="button" value="{{ trans('general.cancel') }}" class="btn btn-danger"></a>
				</div>
			</div>
		{{ Form::close() }}
	</div>
</div>
@stop
