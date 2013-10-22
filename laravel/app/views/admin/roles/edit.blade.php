@extends('admin.layouts.modal')

{{-- Content --}}
@section('content')


	{{-- Edit Role Form --}}
	<form class="form-horizontal" method="post" action="" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

				<!-- Name -->
		<div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="name">Name</label>
			<div class="col-md-10">
				<input class="form-control" type="text" name="name" id="name" value="{{{ Input::old('name', $role->name) }}}" />
				{{{ $errors->first('name', '<span class="help-inline">:message</span>') }}}
			</div>
		</div>
		<!-- ./ name -->

		<div class="form-group">
			<label class="col-md-2 control-label" for="name">Privilegies</label>
			<div class="col-md-10">
				<ul class="form-group unstyled">
					@foreach ($permissions as $permission)
					<li>
						<label>
							<input type="hidden" id="permissions[{{{ $permission['id'] }}}]" name="permissions[{{{ $permission['id'] }}}]" value="0" />
							<input type="checkbox" id="permissions[{{{ $permission['id'] }}}]" name="permissions[{{{ $permission['id'] }}}]" value="1"{{{ (isset($permission['checked']) && $permission['checked'] == true ? ' checked="checked"' : '')}}} />
							{{{ $permission['display_name'] }}}
						</label>
					</li>
					@endforeach
				</ul>
			</div>

		</div>

		<!-- Form Actions -->
		<div class="form-group">
			<div class="col-md-offset-2 col-md-10">
				<element class="btn-cancel close_popup">Cancel</element>
				<button type="reset" class="btn btn-default">Reset</button>
				<button type="submit" class="btn btn-success">Update Role</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>
@stop
