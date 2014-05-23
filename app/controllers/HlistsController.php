<?php
/*
*
*	Controls workflow with Holdings List
*
*/

class HlistsController extends BaseController {

	/**
	 * Hlist Repository
	 *
	 * @var Hlist
	 */
	protected $hlist;

	public function __construct(Hlist $hlist)
	{
		$this->hlist = $hlist;
		$this->data = [];

		$this->data['types'] = $types = [ 
			'control'=>	'<i class="fa fa-tachometer"></i> '.trans('lists.type-control'), 
			'unsolve'=>	'<i class="fa fa-fire"></i> '.trans('lists.type-unsolve'), 
			'delivery'=>'<i class="fa fa-truck"></i> '.trans('lists.type-delivery'),
			'elimination'=>'<i class="fa fa-trash-o"></i> '.trans('lists.type-elimination') ,
		];
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (Input::has('q')) 
			$this->hlist = $this->hlist->where('name','like', '%'.Input::get('q').'%');		

		if (Input::has('type')) 
			$this->hlist = $this->hlist->whereType(Input::get('type'));

		if (Input::has('state')) 
			$this->hlist = $this->hlist->whereRevised(Input::get('state') == 'revised');

		$this->data['hlists'] = $this->hlist->orderBy('created_at', 'desc')->my()->paginate(50);

		$maguser = Role::whereName('maguser')
						->first()
						->users()
						->whereLibraryId( Auth::user()->library_id )
						->select('username','users.id')
						->lists('username','id'); 

		$postuser = Role::whereName('postuser')
						->first()
						->users()
						->whereLibraryId( Auth::user()->library_id )
						->select('username','users.id')
						->lists('username','id'); 

		$this->data['users'] = 	json_encode($postuser+$maguser);
		
		return View::make('hlists.index', $this->data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$users = User::all()->lists('username','id');
							
/*							->whereLibraryId( Auth::user()->library_id )
							->orderby('username')
*/							

		return View::make('hlists.create', ['users' => $users]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$holding_ids = Input::get('holding_id');
		$error = '';

		//echo var_dump($holding_ids);
		$hlist = new Hlist([ 'name' => Input::get('name'), 'user_id' => Auth::user()->id, 'worker_id' => Input::get('worker_id') ]);
		$name_list_exists = Hlist::where('name', '=', Input::get('name') )->exists();
		$empty_name = !Input::has('name');

/*		echo var_dump($name_list_exists);
		die();
*/
		if ( Input::has('worker_id') && !$name_list_exists && !$empty_name ) {

			$hlist->worker_id = Input::get('worker_id');

			if ( Input::has('type') ) $hlist->type = Input::get('type');
			$worker = User::whereId( Input::get('worker_id') )->first();

			// if worker is postuser then attad to list only revised_ok holdings
			if ( $worker->hasRole('postuser') ){
				$holding_ids[] = -1;
				$ids = Holding::whereIn('id',$holding_ids)->where( function($query){ 
					$query->whereState('revised_ok')->orWhere('state','=','commented'); 
				})->lists('id');

			 	$holding_ids =  ( count($ids)>0) ? $ids : []; 

			}

			if ( $worker->hasRole('maguser') ){

				if (  Input::get('type') =='control' ){
					$holding_ids[] = -1;
					$ids = Holding::whereIn('id',$holding_ids)->where( function($query){ 

						$query
							->whereState('ok')
							->orWhere('state','=','annotated')
							->orWhere('state','=','confirmed')
							->orWhere('state','=','commented');  
						})->lists('id');

				 	$holding_ids =  (count($ids)>0) ? $ids : []; 
				}

				if (  Input::get('type')=='unsolve' ){
					$holding_ids[] = -1;
					$ids = Holding::whereIn('id',$holding_ids)->where( function($query){ 
							$query->whereState('incorrected')->orWhere('state','=','commented');
					})->lists('id');

				 	$holding_ids =  ( count($ids)>0 ) ? $ids : []; 
				}

				if (  Input::get('type')=='elimination' ){
					$holding_ids[] = -1;
					$ids = Holding::whereIn('id',$holding_ids)->where( function($query){ 
						$query->whereState('trash')->orWhere('state','=','commented');
					})->lists('id');

				 	$holding_ids =  ( count($ids)>0 ) ? $ids : []; 
				}

			}

			//die( var_dump( User::find(Input::get('worker_id')) ) );
	
		}

		if ( count($holding_ids)==0 ) 	$error = trans('errors.list_in_blank');
		if ($name_list_exists) 			$error = trans('errors.list_name_is_duplicate');
		if ($empty_name) 				$error = trans('errors.list_name_is_blank');

		$validation = Validator::make( $hlist->toArray(), Hlist::$rules );

		if ($validation->passes()) {


			if ( $error == '' ) {

				$hlist->save();
				$hlist->holdings()->attach( $holding_ids );
				

				return Response::json(['location'=>route('holdings.index', ['hlist_id'=>$hlist->id])]);
				// return Response::json(['created_list'=>$hlist->id]);

			} else {

				return Response::json(['error' => $error ]);

			}


		}

		return Redirect::route('hlists.create')
			->withInput()
			->withErrors($validation)
			->with('message', 'There were validation errors.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		// $hlist = $this->hlist->findOrFail($id);
		// return View::make('hlists.show', compact('hlist'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$this->data['list'] = $this->hlist->find($id);

		$maguser = Role::whereName('maguser')
						->first()
						->users()
						->whereLibraryId( Auth::user()->library_id )
						->select('username','users.id')
						->lists('username','id'); 

		$postuser = Role::whereName('postuser')
						->first()
						->users()
						->whereLibraryId( Auth::user()->library_id )
						->select('username','users.id')
						->lists('username','id'); 

		$this->data['users'] = $maguser+$postuser;
		return View::make('hlists.edit', $this->data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$input = array_except(Input::all(), '_method');
		$validation = Validator::make($input, Hlist::$rules);

			$hlist = $this->hlist->find($id);
			$hlist->update($input);

			if (Request::ajax()){

				if ( $input['revised']==1 ){
					return Response::json([ 
						'list_revised' => $id, 
						'state' => trans( 'states.'.$hlist->state ) 
					]);
				} else {
					return Response::json([ 
						'list_received' => $id, 
						'state' => trans( 'states.'.$hlist->state ) 
					]);

				}


			} else {
				return Redirect::route('lists.index', $id);
			}

		}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->hlist->find($id)->delete();
		return Response::json( ['remove' => [$id]] );
	}


	public function getAttach($id) {
		
		return View::make('hlists.to',[ 'lists' => Hlist::my()->orderBy('name')->get() ]);
	}
 
	/**
	 * Attach exists Holdings to List.
	 *
	 * @param  int  $id
	 * @return Response JSON
	 */
	public function postAttach(){
		$id = Input::get('hlist_id');
		$list = $this->hlist->find($id);
		$holdings_in_list = $list->holdings()->select('holdings.id')->lists('holdings.id');
		$holdings = Holding::whereIn('id',Input::get('holding_id'))->whereNotIn('id',$holdings_in_list);

		$error = '';
		$inserted = 0;

		if ($holdings->exists()){

			if ( $list->type=='control' ) {
				$controls = $holdings->whereState('confirmed')->orwhere('state','ok')->orWhere('state','annotated')->lists('id');
				if ( count($controls)==0) {
					$error = 'attach_list_control';
				} else{
					$list->holdings()->sync( $controls );	
					$inserted = count($controls);
				}
			}

			if ( $list->type=='delivery' ) {
				$deliveries = $holdings->where( 'state','like','revised_%' )->lists('id');
				if ( count($deliveries)==0 ){
					$error = 'attach_list_delivery';
				} else {
					$list->holdings()->sync($deliveries);
					$inserted = count($deliveries);
				}
			}

			if (  $list->type=='elimination' ){
				$eliminations = $holdings->whereState('commented')->orwhere('state','trash')->lists('id');
				if ( count($eliminations)==0 ){
					$error = 'attach_list_elimination';
				}	else {
					$list->holdings()->sync($eliminations);
					$inserted = count($eliminations);
				}
			}

			if (  $list->type=='unsolve' ){
				$unsolves = $holdings->whereState('incorrected')->orwhere('state','commented')->lists('id');
				if ( count($unsolves)==0 ){
					$error = 'attach_list_elimination';
				}	else {
					$list->holdings()->sync( $unsolves );
					$inserted = count($unsolves);
				}
				
			}
		}

		if ( $error=='' && $inserted==0 ) $error = 'attach_holding_in_list';

		if ($error=='')
			return Response::json([ 'location'=> route('holdings.index', ['hlist_id'=>$id]) , 'attach' => $id,'counter' => $list->holdings()->count(), 'inserted'=>$inserted ] );
		else 
			return Response::json( [ 'error' => trans('errors.'.$error), 'type'=> $holdings->state ] );

	}

	/**
	 * Detach Holding from List.
	 *
	 * @param  int  $id
	 * @return Response JSON
	 */
	public function postDetach($id){
		$holding_id = Input::get('holding_id');
		$list = $this->hlist->find($id);
		$list->holdings()->detach($holding_id);		
		return Response::json( ['remove' => $holding_id, 'counter' => $list->holdings()->count() ] );
	}

}
