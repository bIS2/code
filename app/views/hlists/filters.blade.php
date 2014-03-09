  <div role="navigation" class="" id="navbar-example">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="{{ route('lists.index') }}" class="navbar-brand">{{ trans('lists.all-lists') }}</a>
      </div>
      <div>
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a data-toggle="dropdown" href="{{ route('lists.index', ['type'=>'control']) }}"><span class="fa fa-truck fa-flip-horizontal"></span> {{ trans('lists.type-control') }} <b class="caret"></b></a>
            <ul aria-labelledby="drop1" role="menu" class="dropdown-menu">
              <li role="presentation">
                <a href="{{ route('lists.index', ['type'=>'control']) }}" ><span class="fa fa-truck fa-flip-horizontal" ></span> {{ trans('general.all') }}</a>
              </li>
              <li role="presentation">
                <a href="{{ route('lists.index', ['type'=>'control', 'state' => 'pending']) }}" ><span class="fa fa-truck fa-flip-horizontal" ></span> {{ trans('holdings.pending') }}</a>
              </li>
              <li role="presentation">
                <a href="{{ route('lists.index', ['type'=>'control', 'state' => 'state']) }}"><span class="fa fa-truck fa-flip-horizontal" ></span> {{ trans('holdings.revised') }}</a>
              </li>
            </ul>
          </li>
          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" role="button" href="#" id="drop1"><span class="fa  fa-fa fa-tachometer" ></span> {{trans('lists.type-delivery')}} <b class="caret"></b></a>
            <ul aria-labelledby="drop1" role="menu" class="dropdown-menu">
              <li role="presentation">
                <a href="{{ route('lists.index', ['type'=>'delivery']) }}" ><span class="fa fa-tachometer"></span> {{ trans('general.all') }}</a>
              </li>
              <li role="presentation">
                <a href="{{ route('lists.index', ['type'=>'delivery', 'state' => 'pending']) }}" ><span class="fa fa-tachometer"></span> {{ trans('holdings.pending') }}</a>
              </li>
              <li role="presentation">
                <a href="{{ route('lists.index', ['type'=>'delivery', 'state' => 'state']) }}"><span class="fa fa-tachometer"></span> {{ trans('holdings.revised') }}</a>
              </li>
            </ul>
          </li>

          <li class="dropdown">
            <a href="{{ route('lists.index', ['type'=>'unsolve']) }}"><span class="fa fa-fire" ></span> {{ trans('lists.type-unsolve') }}</a>
          </li>

          <li class="dropdown">
            <a  href="{{ route('lists.index', ['type'=>'elimination']) }}"><span class="fa fa-trash-o" ></span> {{ trans('lists.type-elimination') }}</a>
          </li>
        </ul>
      </div><!-- /.nav-collapse -->
    </div><!-- /.container-fluid -->
  </div> <!-- /navbar-example -->
  <hr>