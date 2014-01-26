
<div class="btn-group actions-menu" data-container="body">
  <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown">
    {{{ trans('general.action')}}} <span class="caret"></span>
  </button>
  <ul class="fa dropdown-menu" role="menu">

    <li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_view')}}">
		<a href="{{ route('holdings.show', $holding->id) }}" data-target="#modal-show" data-toggle="modal" >
			<span class="fa fa-eye" ></span>
		</a>
    </li>

    <li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_list_history')}}">
			<a href="{{ route('states.index', [ 'holding_id' => $holding->id]) }}" data-target="#modal-show" data-toggle="modal" >
				<span class="fa fa-folder" title="{{ trans('general.history') }}" ></span>
			</a>
    </li>

		<li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_show_external_link')}}">
			<a href="{{ $holding->library->externalurl }}" target="_blank" set="{{$holdingsset->id}}"  title="{{ trans('holdingssets.see_information_from_original_system') }}" >
				<span class="fa fa-external-link"></span>
			</a>
<!-- 			<a href="http://bis.trialog.ch/sets/from-library/<?= $holding->id; ?>" target="" set="{{$holdingsset->id}}" data-target="#modal-show" data-toggle="modal" title="{{ trans('holdingssets.see_information_from_original_system') }}" data-placement="top" data-toggle="popover" data-html="true" data-trigger="hover">
				<span class="fa fa-external-link"></span>
			</a>
 -->		</li>						

		@if ( Input::has('hlist_id') && (Auth::user()->id == $hlist->user->id ))
	    <li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_remove_from_list')}}">
				<a href="{{ action('HlistsController@postDetach', [ Input::get('hlist_id') ] ) }}" data-remote="true"  data-method="post" data-params="holding_id={{$holding->id}}" >
					<i class="fa fa-times" ></i>
				</a>
	    </li>
    @endif
			

		@if (Authority::can('touch', $holding))

			<li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_ok')}}">
			  <a href="{{ route('states.store') }}" class="btn-ok" data-method="post" data-remote="true" data-params="hlist_id={{$hlist->id}}&state=ok&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" >
			  	<span class="fa fa-thumbs-up"></span>
			  </a>
			</li>
			<li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_notes')}}">
			  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-tag">
			  	<span class="fa fa-tags"></span> 
			  </a>
			</li>
		@endif

		@if (Authority::can('trash', $holding))

			<li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_to_trash')}}">
			  <a href="{{ route('states.store') }}" class="btn-trash" data-params="state=trash&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-trash-o"></span> 
			  </a>
			</li>
		@endif

		@if (Authority::can('burn', $holding))

			<li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_unsolve')}}">
			  <a href="{{ route('states.store') }}" class="btn-burn" data-params="state=burn&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-fire"></span> 
			  </a>
			</li>
			
		@endif

		
		@if (Authority::can('receive',$holding))

			<li class="btn btn-sm" data-toggle="tooltip" title="{{trans('holdings.tooltip_receive')}}">
			  <a href="{{ route('states.store') }}" class="btn-receive" data-params="state=received&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-download"></span> 
			  </a>
			</li>
			
	  @endif
	  
		@if (Authority::can('comment',$holding))

			<li class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_comment')}}">
			  <a href="{{ route('comments.create', ['holding_id'=>$holding->id]) }}" title="{{ trans('general.comment') }}" class="btn-comment" data-toggle="modal" data-target="#form-create-comments">
			  	<span class="fa fa-comment"></span> 
			  </a>
			</li>

	  @endif

  </ul>
</div>
