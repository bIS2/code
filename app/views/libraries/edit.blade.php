@extends('layouts.default')

@section('content')
<div class="row">
	<div class="col-sm-8">

		<h1>{{trans('titles.edit_libraries')}}</h1>
		{{ Form::model($library, array('method' => 'PUT', 'route' => array('admin.libraries.update', $library->id), 'class'=>'form-horizontal')) }}

			<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}} ">
			    {{ Form::label('code', trans('table.code').':',["class"=>"col-sm-2 control-label"]) }}
			    <div class="col-sm-1">
			    	{{ Form::text('code',null,[ "class" => "form-control", 'disabled'=>'disabled' ]) }}
			        {{{ $errors->first('username', '<span class="help-inline">:message</span>') }}}
			    </div>
			</div>

			<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}} ">
			    {{ Form::label('name',trans('table.name').':',["class"=>"col-sm-2 control-label"]) }}
			    <div class="col-sm-8">
			    	{{ Form::text('name',null,[ "class" => "form-control" ]) }}
			        {{{ $errors->first('username', '<span class="help-inline">:message</span>') }}}
			    </div>
			</div>

			<div class="form-group {{{ $errors->has('username') ? 'error' : '' }}} ">
			    {{ Form::label( 'externalurl', trans('table.externalurl').':' ,["class"=>"col-sm-2 control-label"]) }}
			    <div class="col-sm-8">
			    	{{ Form::text('externalurl',null,[ "class" => "form-control" ]) }}
			        {{{ $errors->first('username', '<span class="help-inline">:message</span>') }}}
			    </div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn-success">
						<i class="fa fa-check"></i>
						{{trans('general.ok')}}
					</button>
					<a href="{{ route('admin.libraries.index') }}" class="btn btn-default"><i class="fa fa-angle-double-left"></i> {{trans('general.back')}}</a>
				</div>
			</div>

		{{ Form::close() }}

		@if ($errors->any())
			<ul>
				{{ implode('', $errors->all('<li class="error">:message</li>')) }}
			</ul>
		@endif

	</div>
</div>

@stop
