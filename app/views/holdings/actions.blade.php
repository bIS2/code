
<div class="btn-group actions-menu" data-container="body">
  <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
    {{{ trans('general.action')}}} <span class="caret"></span>
  </button>
  <ul class="fa dropdown-menu" role="menu">

    <li class="btn btn-xs">
			<a href="{{ route('holdings.show', $holding->id) }}" data-target="#modal-show" data-toggle="modal" >
				<span class="fa fa-eye" title="{{ trans('holdingssets.see_more_information') }}" data-placement="top" data-toggle="popover" data-trigger="hover"></span>
			</a>
    </li>

		@if (Authority::can('touch', $holding))

			<li class="btn btn-xs" >
				<a href="http://bis.trialog.ch/sets/from-library/<?= $holding->id; ?>" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" title="{{ trans('holdingssets.see_information_from_original_system') }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover">
					<span class="fa fa-external-link"></span>
				</a>
			</li>						
			<li class="btn btn-xs">
			  <a href="{{ route('oks.store') }}" class="btn-ok" data-method="post" data-remote="true" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" >
			  	<span class="fa fa-thumbs-up"></span>
			  </a>
			</li>
			<li class="btn btn-xs">
			  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-tag">
			  	<span class="fa fa-tags"></span> 
			  </a>
			</li>
		@endif

		@if (Authority::can('revise', $holding))

			<li class="btn btn-xs">
			  <a href="{{ route('reviseds.store') }}" class="btn-send" data-params="holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-mail-forward"></span> 
			  </a>
			</li>
			
		@endif

		@if (Authority::can('trash', $holding))

			<li class="btn btn-xs">
			  <a href="{{ route('states.store') }}" class="btn-trash" data-params="state=trash&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-trash-o"></span> 
			  </a>
			</li>
		@endif

		@if (Authority::can('burn', $holding))

			<li class="btn btn-xs">
			  <a href="{{ route('states.store') }}" class="btn-burn" data-params="state=burn&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-fire"></span> 
			  </a>
			</li>
			
		@endif

		
		@if (Authority::can('receive',$holding))
			<li class="btn btn-xs">
			  <a href="{{ route('states.store') }}" class="" data-params="state=receive&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-download"></span> 
			  </a>
			</li>
			<li class="btn btn-xs">
			  <a href="{{ route('comments.create',['holding_id'=>$holding->id]) }}" title="{{ trans('general.comment') }}" class="btn-comment" data-toggle="modal" data-target="#form-create-comments">
			  	<span class="fa fa-comment"></span> 
			  </a>
			</li>
	  	@endif

  </ul>
</div>
