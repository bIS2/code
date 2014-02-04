<?php
/*
*
*	Controls workflow with Pages.
*
*/
class Pages extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex(){
		if  (Input::has('lang')) return Redirect::to(Request::header('referer'));
		$data['holdingsset_confirm'] 	= Confirm::take(10)->get();
		$ids = Holding::inLibrary()->lists('holdings.id');
		$ids[] = -1;
		$data['holdings_ok'] 			= State::whereState('ok')->whereIn('holding_id', $ids);
		$data['holdings_annotated'] 	= Note::take(10)->get();
		$data['holdings_revised'] 		= Holding::inLibrary()->reviseds()->get();

		$data['total'] 					= Holding::inLibrary()->count();
		$data['total_ok'] 				= Holding::inLibrary()->corrects()->count();
		$data['total_anottated'] 		= Holding::inLibrary()->annotated()->count();

		return View::make('pages.index', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}


}
