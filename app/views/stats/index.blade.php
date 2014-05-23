@if (!Auth::guest())

	@if (Auth::user()->hasRole('bibuser') || Auth::user()->hasRole('resuser'))
		@include('stats.bibuser')
	@endif

	@if (Auth::user()->hasRole('magvuser') || Auth::user()->hasRole('maguser'))
		@include('stats.magvuser')
	@endif

	@if (Auth::user()->hasRole('postuser'))
		@include('stats.postuser')
	@endif

@endif

