<?php

class Pages extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex(){
		$data['traces'] = Trace::lastest()->get();
		return View::make('pages.index', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}


}
