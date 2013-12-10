<?php

class Pages extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex(){
		$data['holdings_ok'] 				= Ok::all();
		$data['holdings_annotated'] = Note::all();

		$data['total'] 						= Holding::inLibrary()->count();
		$data['total_ok'] 				= Holding::inLibrary()->corrects()->count();
		$data['total_anottated'] 	= Holding::inLibrary()->annotated()->count();
		$data['total_delivery'] 	= Holding::inLibrary()->deliveries()->count();

		return View::make('pages.index', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}


}
