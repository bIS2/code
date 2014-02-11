@if (count($errors->all()) > 0)
<div class="alert alert-danger alert-block page-header">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>{{ trans('general.error') }}</h4>
	{{ trans('general.plase_fix_errors_and_try_again') }}
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
</div>	
@endif

@if ($message = Session::get('success'))
<div class="alert alert-success alert-block page-header">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
    @else
        {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block page-header">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    @if(is_array($message))
    @foreach ($message as $m)
    {{ $m }}
    @endforeach
    @else
    {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block page-header">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    @if(is_array($message))
    @foreach ($message as $m)
    {{ $m }}
    @endforeach
    @else
    {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block page-header">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    @if(is_array($message))
    @foreach ($message as $m)
    {{ $m }}
    @endforeach
    @else
    {{ $message }}
    @endif
 </div>
@endif
