<?php
/*
*
*	Controls workflow with Pages.
*
*/
class Pages extends BaseController {

	public function getIndex(){
		$user = Auth::user();

		if ( ( $user->roles()->count()==1 ) ){

			if ( $user->hasRole('magvuser') || $user->hasRole('maguser') || $user->hasRole('magvuser') || $user->hasRole('speichuser') )
				return Redirect::to('holdings');

			if ( $user->hasRole('postuser') ) 	return Redirect::to('lists');
			if ( $user->hasRole('bibuser') ) 		return Redirect::to('sets');
			if ( $user->hasRole('superuser') ) 	return Redirect::to('admin/users');
			if ( $user->hasRole('sysadmin') ) 	return Redirect::to('admin/users');

		}
		return View::make('pages.index');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getStatistics(){

		if  (Input::has('lang')) return Redirect::to(Request::header('referer'));

		$data['libraries'] 				= Library::all();
		$library_id = ( Input::has('library_id') && (Input::get('library_id')!='*') ) ? Input::get('library_id') : null;

		$holdings 						= Holding::inLibrary($library_id);
		$holdings_ok 					= State::inLibrary($library_id)->whereState('ok');
		$holdings_annotated 	= State::inLibrary($library_id)->whereState('annotated');
		$holdingsset_confirm 	= Confirm::orderBy('created_at', 'DESC'); 

		if ( Input::has('month') && (Input::get('month')!='*') ) {
			$month = Input::get('month');
			$holdings_ok 					= $holdings_ok->where( DB::raw('extract(month from created_at)'),$month );
			$holdings_annotated 	= $holdings_annotated->where( DB::raw('extract(month from created_at)'),$month );
			$holdingsset_confirm	= $holdingsset_confirm->where( DB::raw('extract(month from created_at)'),$month );
		}

		if (Input::has('year') && (Input::get('year')!='*')) {
			$year = Input::get('year');
			$holdings_ok 					= $holdings_ok->where( DB::raw('extract(year from created_at)'),$year );
			$holdings_annotated 	= $holdings_annotated->where( DB::raw('extract(year from created_at)'),$year );
			$holdingsset_confirm 	= $holdingsset_confirm->where( DB::raw('extract(year from created_at)'),$year );
		}

		$data['holdingsset_confirm'] 	= $holdingsset_confirm->take(10)->get();
		$data['holdings_ok'] 					= $holdings_ok->take(10)->get();
		$data['holdings_annotated'] 	= $holdings_annotated->take(10)->get();

		$data['total'] 							= $holdings->count();
		$data['total_ok'] 					= $holdings_ok->count();
		$data['total_anottated'] 		= $holdings_annotated->count();

		// $data['total'] 					= 1;
		// $data['total_ok'] 				= 2;
		// $data['total_anottated'] 		= 3;


		return View::make('pages.stats', $data);
	}

	public function getHelp(){
		return View::make('pages.help');
	}

	public function getStats(){

		$month = (Input::has('month')) ? Input::get('month') : false;
		$year = (Input::has('year')) ? Input::get('year') : false;

		$stats_size = [

		$this->find_stat( 'ok', 'size'),
		$this->find_stat( 'revised', 'size'),
		$this->find_stat( 'delivery', 'size'),
		$this->find_stat( 'integrated', 'size'),
		$this->find_stat( 'trash', 'size'),
		$this->find_stat( 'burn', 'size'),

		];
		$stats_count = [

		$this->find_stat( 'confirmed', 'count'),
		$this->find_stat( 'ok', 'count'),
		$this->find_stat( 'revised', 'count'),
		$this->find_stat( 'integrated', 'count'),
		$this->find_stat( 'trash', 'count'),
		$this->find_stat( 'burn', 'count'),

		];

		return Response::json([
			'titles'	=> [ 
			'size' =>[
			trans('states.ok'),
			trans('states.revised'),
			trans('states.delivery'), 
			trans('states.integrated'),
			trans('states.trash'),
			trans('states.burn') 
			],
			'count' =>[
			trans('states.confirmed'), 
			trans('states.ok'),
			trans('states.revised'),
			trans('states.integrated'),
			trans('states.trash'),
			trans('states.burn') 
			]
			],
			'count' 	=> $stats_count,
			'size'		=> $stats_size	
				// 'query'		=> Holding::stats($month, $year)->get()->toArray()
			]
			);

	}

	// Build array by state in each library
	// $state = string state
	// $value = stat type: size or count
	// return = array [ [library number 1, value of stat],[library number 2, value of stat] ... ]
	
	private function find_stat( $state, $value ){

		$stats 	=  Holding::stats($month, $year)->get()->toArray();
		$libraries = [ 1=>'AGK', 2=>'HBZ', 3=>'UBB', 4=>'ZBZ', 5=>'ZHB' ];
		$arr = [];

		foreach ( $libraries as $key => $library ) {
			$a = [$key,0];
			foreach ($stats as $stat){
				if (($stat['library']==$library) && ($stat['state']==$state) )
					$a = [ $key, (float) $stat[$value] ];
			}
			$arr[] = $a;
		}

		return $arr;

	}



	/**
	 * Extract data to a csv file
	 *
	 * @return Response
	 */
	public function getExtractData()
	{
		$user = Auth::user();
		if (!$user->hasRole('superuser')) {return Redirect::to('/'); }
		else {

			$data = array();
			$data['allselectablefields'] = ['sys2','g','852b','866c','852h','022a','245a','245b','245c','245n','245p','260a','260b', '866a','866aupdated','x866a','state' , 'size', 'size_dispatchable', 'is_owner', 'is_aux', 'holdingsset_id'];
			$data['allsearchablefields'] = ['852b','866c','852h','holtype','state'];
			extract(Input::all());

			if ($filtered == 1) {
				if (($fromajax != 1) && ($query != '')) {

					$file = $_SERVER['DOCUMENT_ROOT'].'/'.Auth::user()->username.'-extract-data.csv';
					$filezip = $_SERVER['DOCUMENT_ROOT'].'/'.Auth::user()->username.'-extract-data.zip';
					unlink($file);
					unlink($filezip);

					$db_config = Config::get('database');
					$database = $db_config['connections']['pgsql']['database'];
					$username = $db_config['connections']['pgsql']['username'];
					$password = $db_config['connections']['pgsql']['password'];
					$conn_string = "host=localhost port=5432 dbname=".$database." user=".$username." password=".$password." options='--client_encoding=UTF8'";
					$con = pg_connect($conn_string);
					pg_set_client_encoding($con, "UNICODE");

					$ilegalstatement = 0;
					$ilegalstatement += (strpos($query, 'DELETE') === false) ? 0 : 1;
					$ilegalstatement += (strpos($query, 'UPDATE') === false) ? 0 : 1;
					$ilegalstatement += (strpos($query, 'DROP') === false) ? 0 : 1;
					$ilegalstatement += (strpos($query, 'SET') === false) ? 0 : 1;


					if (($checkforerror == 1) && ($ilegalstatement > 0)) {
						die(trans('errors.ilegal_statement_in_query'));
					}
					$hide_io = false;
					if (strpos($query, ',is_owner') === false) {
						$queryparts = explode('FROM', $query);
						$qi = trim($queryparts[0]).',is_owner FROM';
						$query = $qi.$queryparts[1];
						$hide_io = true;
					}
					$hide_ia = false;
					if (strpos($query, ',is_aux') === false) {
						$queryparts = explode('FROM', $query);
						$qi = trim($queryparts[0]).',is_aux FROM';
						$query = $qi.$queryparts[1];
						$hide_ia = true;
					}
					
					$result = pg_query($con, $query) or ($queryerror = "Cannot execute \"$query\"\n".pg_last_error());



					if ($checkforerror == 1) {
						if ($queryerror != '') {
							die($queryerror);
						}
						else {		
							die('OK');
						}
					}

					$file = Auth::user()->username.'-extract-data.csv';
					header( "Content-Type: text/csv;charset=utf-8" );
					header( "Content-Disposition: attachment;filename=\"$file\"" );
					header("Pragma: no-cache");
					header("Expires: 0");
					$fp = fopen($file, 'w');
					$temp = $fieldstoshow;
					$cutpos = strpos($query, 'FROM') ;
					$subfields = substr($query, 7, $cutpos-8);
					$temp = explode(',', $subfields);
					$temp[] = 'Holtype';
					$tempOK = array();
					foreach ($temp as $tempt) {
						if (($tempt != 'ocrr_ptrn') && ($tempt != 'aux_ptrn')) {							
							if ((($tempt == 'is_owner') && ($hide_io)) || (($tempt == 'is_aux') && ($hide_ia))) {
								// Silent is gold
							} 
							else {
								$tempOK[] = $tempt;
							}
						}
					}
					fputcsv($fp, $tempOK, "\t");

					$results = pg_fetch_all($result);
					$currenthos = '';
					foreach ($results as $hol) :
						$temp = $hol;					
					if ($hol['holdingsset_id'] != $currenthos) {
						if ($currenthos != '')
								// fputcsv($fp, $blanks); 
							$currenthos = $hol['holdingsset_id'];
					}
					$htype = '';
					if (($temp['is_owner'] == 't') || ($temp['is_owner'] == '1')) $htype = 'AB';
					if ((($temp['is_aux'] == 't') || ($temp['is_aux'] == '1')) && ($temp['ocrr_ptrn'] == $temp['aux_ptrn'])) $htype = 'EB';
					if ((($temp['is_aux'] == 't') || ($temp['is_aux'] == '1')) && ($temp['ocrr_ptrn'] != $temp['aux_ptrn'])) $htype = 'EB/KB';
					if (strpos($temp['state'], 'reserv') !== false) $htype = 'GB';
					if ($htype == '') $htype = 'KB';
					$temp[] = $htype;
					unset($temp['ocrr_ptrn']);
					unset($temp['aux_ptrn']);
					if ($hide_io) unset($temp['is_owner']);
					if ($hide_ia) unset($temp['is_aux']);
					fputcsv($fp, $temp, "\t");
					endforeach;
					fclose($fp);

					$zip = new ZipArchive();

					if ($zip->open($filezip, ZipArchive::CREATE)!==TRUE) {
						exit("cannot open <$filezip>\n");
					}
					$zip->addFile($file,'extract-data-'.date('Y-m-d').'.csv');
					$zip->close();
					unlink($file);
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename='.Auth::user()->username.'-extract-data.zip');
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($filezip));
					ob_clean();
					flush();
					readfile($filezip);
					
				}
				else {
					$fields = array();	
					foreach ($fieldstoshow as $field) {
						$fields[] = (($field != 'sys2') && ($field != 'g') && ($field != 'state') && ($field != 'size') && ($field != 'size_dispatchable') && ($field != 'is_owner') && ($field != 'is_aux') && ($field != 'holdingsset_id')) ? 'f'.$field : $field ;
					}
					$query = 'SELECT '.implode(',', $fields).',ocrr_ptrn,aux_ptrn FROM holdings';
					$i = -1;
					$where = ' WHERE ';

					$fieldstoquery = array_unique($fieldstoquery);
					foreach ($fieldstoquery as $field) {
						switch ($field) {

							case '852b':
							
							if ($f852b) {
								$i++;
								$part = '';
								$t = 0;
								foreach ($f852b as $lib) {
									if (($t == 0)  && (count($f852b) > 1))
										$part .= '(';

											$part .= "f852b = '".$lib."'";

											if ($t < count($f852b) - 1)
												$part .= ' OR ';

											if (($t == count($f852b) - 1) && (count($f852b) > 1))
												$part .= ')';

$t++;
}
if ($part != '') {
	$query .= $where.$OrAndFilter[$i-1].' '.$NotOperator[$i].$part;
	$where = ' ';
}
}

break;

case 'holtypegb':

if (isset($holtypegb)) {
	$i++;
	$part = '';
	$t = 0;
	if ($holtypegb != '') {
		$part .= "state LIKE '%reserve%'";
	}

	if ($part != '') {
		$query .= $where.$OrAndFilter[$i-1].' '.$NotOperator[$i].$part;
		$where = ' ';
	}
}

break;

case 'holtype':

if (isset($holtype) && (count($holtype) != 4)) {
	$i++;
	$part = '';
	$t = 0;										
	foreach ($holtype as $lib) {
		if (($t == 0) && (count($holtype) > 1))
			$part .= '(';
				switch ($lib) {
											// case 'GB':
											// 	$part .= "state LIKE '%reserve%'";
											// 	break;
					case 'AB':
					$part .= "is_owner = 't'";
					break;
					case 'EB':
					$part .= "(is_aux = 't' AND ocrr_ptrn = aux_ptrn)";
					break;
					case 'EB/KB':
					$part .= "(is_aux = 't' AND ocrr_ptrn != aux_ptrn)";
					break;
					case 'KB':
					$part .= "(is_aux != 't' AND is_owner != 't')";
					break;
				}

				if ($t < count($holtype) - 1)
					$part .= ' OR ';

				if (($t == count($holtype) - 1) && (count($holtype) > 1))
					$part .= ')';

$t++;
}
if ($part != '') {
	$query .= $where.$OrAndFilter[$i-1].' '.$NotOperator[$i].$part;
	$where = ' ';
}
}

break;

case 'state':
if (isset($state)) {
	$i++;
	$part = '';
	$t = 0;
	foreach ($state as $st) {
		if (($t == 0)  && (count($state) > 1))
			$part .= '(';

				$part .= "state = '".$st."'";

				if ($t < count($state) - 1)
					$part .= ' OR ';

				if (($t == count($state) - 1) && (count($state) > 1))
					$part .= ')';

$t++;
}
if ($part != '') {
	$query .= $where.$OrAndFilter[$i-1].' '.$NotOperator[$i].$part;
	$where = ' ';
}
}
break;

case '866c':
if (isset($f866c)) {
	$i++;
	$part = '';
	$t = 0;
	if ($f866c != '') {
		$part = sprintf( $f866cformat, 'LOWER(f866c)', pg_escape_string(addslashes(strtolower( $f866c ) ) ) );
	}

	if ($part != '') {
		$query .= $where.$OrAndFilter[$i-1].' '.$NotOperator[$i].$part;
		$where = ' ';
	}
}
break;

case '852h':
if (isset($f852h)) {
	$i++;
	$part = '';
	$t = 0;
	if ($f852h != '') {
		$part = sprintf( $f852hformat, 'LOWER(f852h)', pg_escape_string(addslashes(strtolower( $f852h ) ) ) );
	}
	if ($part != '') {
		$query .= $where.$OrAndFilter[$i-1].' '.$NotOperator[$i].$part;
		$where = ' ';
	}
}
break;
}
}
$query .= ' ORDER BY holdingsset_id DESC, is_owner DESC, is_aux DESC';

if ($fromajax == 1) {
	echo $query;
	die();
}
}
}
else {				
	return View::make('pages/extractdata', $data);
}



}
}	

	/**
	 * Extract data to a csv file
	 *
	 * @return Response
	 */
	public function postExtractData()
	{
		var_dump(Input::all());
		die();
	}	



	/**
	 * Clear all cookies
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getClearCookies($id)
	{
		// unset cookies
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		return Redirect::to('/');
	}


}
