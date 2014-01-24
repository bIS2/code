<li {{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}">
	<span class="fa fa-users"></span> {{{ trans('titles.users') }}}</a>
</li>

<li {{ (Request::is('admin/libraries*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/libraries') }}}">
	<span class="fa fa-book"></span> {{{ trans('titles.libraries') }}}</a>
</li>

<li {{ (Request::is('admin/feedbacks*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/feedbacks') }}}">
	<span class="fa fa-bug"></span> {{{ trans('titles.feedbacks') }}}</a>
</li>
