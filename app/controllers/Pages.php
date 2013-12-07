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
		return View::make('pages.index', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}


}
