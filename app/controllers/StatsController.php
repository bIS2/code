<?php

/*
*
*	Controls workflow stats. 
*
*/


class StatsController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = [
			'stats' 					= Stat::first(),
			'stats_libraries' = Library::find( Auth::user()->library_id ),
		]
  	return View::make('stats.index',$data);
	}

	

}
