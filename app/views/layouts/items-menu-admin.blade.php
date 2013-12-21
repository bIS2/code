<li {{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}">
	<span class="fa fa-users"></span> {{{ trans('titles.users') }}}</a>
</li>

<li {{ (Request::is('admin/tags*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/tags') }}}">
	<span class="fa fa-tags"></span> {{{ trans('titles.tags') }}}</a>
</li>

@if ( Authority::can('manage', 'Feedback') )
	<li {{ (Request::is('admin/feedbacks*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/feedbacks') }}}">
		<span class="fa fa-bug"></span> {{{ trans('titles.feedbacks') }}}</a>
	</li>
@endif
