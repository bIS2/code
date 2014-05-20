@extends('layouts.pages')

@section('main')

<div class="alert alert-success">
	{{ trans('titles.wellcome', [ 'name' => Auth::user()->username]) }}
</div>


@stop