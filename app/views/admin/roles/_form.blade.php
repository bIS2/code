
{{-- Content --}}

	{{-- Create Role Form --}}
	<form class="form-horizontal" method="post" action="" autocomplete="off">
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->

				<!-- description -->
		<div class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
			<label class="col-md-2 control-label" for="name">{{ trans('table.description') }} </label>
			<div class="col-md-10">
				<input class="form-control" type="text" name="description" id="description" value="{{{ Input::old('description', $role->description) }}}" />
				{{{ $errors->first('name', '<span class="help-inline">:message</span>') }}}
			</div>
		</div>
		<!-- ./ description -->

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
				<button type="submit" class="btn btn-success">Update Role</button>
				<element class="btn-cancel close_popup">Cancel</element>
				<button type="reset" class="btn btn-default">Reset</button>
			</div>
		</div>
		<!-- ./ form actions -->
	</form>

