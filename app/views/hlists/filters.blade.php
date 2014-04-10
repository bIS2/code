  <div role="navigation" class="" id="navbar-example">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="{{ route('lists.index') }}" class="navbar-brand">{{ trans('lists.all-lists') }}</a>
      </div>
      <div>
        <ul class="nav navbar-nav nav-pills">
          <li class="dropdown <?php if (Input::get('type') == 'control') echo 'active';  ?>">
            <a data-toggle="dropdown" href="{{ route('lists.index', ['type'=>'control']) }}"><span class="fa fa-truck fa-flip-horizontal"></span> {{ trans('lists.type-control') }} <b class="caret"></b></a>
            <ul aria-labelledby="drop1" role="menu" class="dropdown-menu">
              <li class=" <?php if ((Input::get('type') == 'control') && (Input::get('state') == '')) echo 'active';  ?>">
                <a href="{{ route('lists.index', ['type'=>'control']) }}" ><span class="fa fa-truck fa-flip-horizontal" ></span> {{ trans('general.all') }}</a>
              </li>
              <li class=" <?php if ((Input::get('type') == 'control') && (Input::get('state') == 'pending')) echo 'active';  ?>">
                <a href="{{ route('lists.index', ['type'=>'control', 'state' => 'pending']) }}" ><span class="fa fa-truck fa-flip-horizontal" ></span> {{ trans('holdings.pending') }}</a>
              </li>
              <li class=" <?php if ((Input::get('type') == 'control') && (Input::get('state') == 'revised')) echo 'active';  ?>">
                <a href="{{ route('lists.index', ['type'=>'control', 'state' => 'revised']) }}"><span class="fa fa-truck fa-flip-horizontal" ></span> {{ trans('holdings.revised') }}</a>
              </li>
            </ul>
          </li>
          <li class="dropdown <?php if (Input::get('type') == 'delivery') echo 'active';  ?>">
            <a data-toggle="dropdown" class="dropdown-toggle" role="button" href="#" id="drop1"><span class="fa  fa-fa fa-tachometer" ></span> {{trans('lists.type-delivery')}} <b class="caret"></b></a>
            <ul aria-labelledby="drop1" role="menu" class="dropdown-menu">
              <li class="<?php if ((Input::get('type') == 'delivery') && (Input::get('state') == '')) echo 'active';  ?>">
                <a href="{{ route('lists.index', ['type'=>'delivery']) }}" ><span class="fa fa-tachometer"></span> {{ trans('general.all') }}</a>
              </li>
              <li class="<?php if ((Input::get('type') == 'delivery') && (Input::get('state') == 'pending')) echo 'active';  ?>">
                <a href="{{ route('lists.index', ['type'=>'delivery', 'state' => 'pending']) }}" ><span class="fa fa-tachometer"></span> {{ trans('holdings.pending') }}</a>
              </li>
              <li class="<?php if ((Input::get('type') == 'delivery') && (Input::get('state') == 'revised')) echo 'active';  ?>">
                <a href="{{ route('lists.index', ['type'=>'delivery', 'state' => 'revised']) }}"><span class="fa fa-tachometer"></span> {{ trans('holdings.revised') }}</a>
              </li>
            </ul>
          </li>

          <li class="dropdown <?php if (Input::get('type') == 'unsolve') echo 'active';  ?>">
            <a href="{{ route('lists.index', ['type'=>'unsolve']) }}"><span class="fa fa-fire" ></span> {{ trans('lists.type-unsolve') }}</a>
          </li>

          <li class="dropdown <?php if (Input::get('type') == 'elimination') echo 'active';  ?>">
            <a  href="{{ route('lists.index', ['type'=>'elimination']) }}"><span class="fa fa-trash-o" ></span> {{ trans('lists.type-elimination') }}</a>
          </li>
        </ul>
      </div><!-- /.nav-collapse -->
    </div><!-- /.container-fluid -->
  </div> <!-- /navbar-example -->
  <hr>