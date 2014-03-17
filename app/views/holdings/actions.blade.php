

    <span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_view')}}">
		<a href="{{ route('holdings.show', $holding->id) }}" data-target="#modal-show" data-toggle="modal" >
			<span class="fa fa-eye" ></span>
		</a>
    </span>


    <span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_list_history')}}">
			<a href="{{ route('states.index', [ 'holding_id' => $holding->id]) }}" data-target="#modal-show" data-toggle="modal" >
				<span class="fa fa-folder" title="{{ trans('general.history') }}" ></span>
			</a>
    </span>


		<span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_show_external_link')}}">
			<a href="{{ $holding->library->externalurl.substr($holding->sys2, 4, 9) }}" target="_blank" set="{{$holdingsset->id}}"  title="{{ trans('holdingssets.see_information_from_original_system') }}" >
				<span class="fa fa-external-link"></span>
			</a>

	</span>
						

		@if ( Input::has('hlist_id') && (Auth::user()->id == $hlist->user->id ))
		
	    <span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_remove_from_list')}}">
				<a href="{{ action('HlistsController@postDetach', [ Input::get('hlist_id') ] ) }}" data-remote="true"  data-method="post" data-params="holding_id={{$holding->id}}" >
					<i class="fa fa-times" ></i>
				</a>
	    </span>

    @endif
			

		@if (Authority::can('touch', $holding))

			<span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_ok')}}">
			  <a href="{{ route('states.store') }}" class="btn-ok" data-method="post" data-remote="true" data-params="hlist_id={{$hlist->id}}&state=ok&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" >
			  	<span class="fa fa-thumbs-up"></span>
			  </a>
			</span>

			<span class="btn btn-xs btn-notes" data-toggle="tooltip" title="{{trans('holdings.tooltip_notes')}}">
			  <a href="{{ route('notes.create',['holding_id'=>$holding->id]) }}" data-toggle="modal" data-target="#form-create-notes" class="btn-tag">
			  	<span class="fa fa-tags"></span> 
			  </a>
			</span>

		@endif

		@if (Authority::can('delete', $holding))

			<span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_to_trash')}}">
			  <a href="{{ route('states.store') }}" class="btn-trash" data-params="state={{ $holding->delete }}&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-times"></span> 
			  </a>
			</span>

		@endif

		@if (Authority::can('burn', $holding))

			<span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_unsolve')}}">
			  <a href="{{ route('states.store') }}" class="btn-burn" data-params="state=burn&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-fire"></span> 
			  </a>
			</span>
			
		@endif

		@if (Authority::can('receive',$holding))

			<span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_receive')}}">
			  <a href="{{ route('states.store') }}" class="btn-receive" data-params="state=received&holding_id={{$holding->id}}&user_id={{Auth::user()->id}}" data-method="post" data-remote="true">
			  	<span class="fa fa-download"></span> 
			  </a>
			</span>

			
	  @endif
	  
		@if (Authority::can('view_comment',$holding))

			<span data-content="{{$holding->comment->content}}" data-placement="top" data-toggle="popover" data-container="body" class="btn btn-xs" data-original-title="" data-trigger="hover">
		  	<i class="fa fa-comment"></i> 
      </span>

		@endif
		
		@if (Authority::can('comment',$holding))

			<span class="btn btn-xs" data-toggle="tooltip" title="{{trans('holdings.tooltip_comment')}}">
			  <a href="{{ route('comments.create', ['holding_id'=>$holding->id]) }}" title="{{ trans('general.comment') }}" class="btn-comment" data-toggle="modal" data-target="#form-create-comments">
			  	<span class="fa fa-comment"></span> 
			  </a>
			</span>

	  @endif



