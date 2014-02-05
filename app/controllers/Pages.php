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
		

		$list = array (
			array('State',	'pending', 'confirmed',	'sent',	'integrated',	'revised',	'trashed',	'eliminated'),
			array('Total del bIS',	'410504', '552339',	'259034',	'450818',	'1231572',	'1215966',	'641667'),
			array('ABKB',	'310504', '552339',	'259034',	'450818',	'1231572',	'1215966',	'641667'),
			array('LUZB',	'52083', '85640',	'42153',	'74257',	'198724',	'183159',	'50277'),
			array('BSUB',	'515910', '828669',	'362642',	'601943',	'1804762',	'1523681',	'862573'),
			array('ZHUB',	'202070', '343207'	,'157204',	'264160',	'754420',	'727124',	'407205'),
			array('ZHZB',	'2704659', '4499890',	'2159981',	'3853788',	'10604510',	'8819342',	'4114496'),
			);

		$fp = fopen('BIS.csv', 'w');

		foreach ($list as $fields) {
			fputcsv($fp, $fields);
		}

		fclose($fp);


		return View::make('pages.index', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}


}
