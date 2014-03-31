<?php
	error_reporting(E_ALL & ~E_NOTICE);
	$conn_string = "host=localhost port=5432 dbname=bis user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
	
	$conn = pg_connect($conn_string) or die('ERROR!!!');

	$truncate = pg_query($conn, "TRUNCATE holdingssets");
	$truncate = pg_query($conn, "TRUNCATE holdings");
	$resultbis = pg_query($conn, "SELECT * FROM hol_out WHERE sys1 <> '' and sys2 <> '' ORDER BY sys1 ASC, sys2 ASC");
	if (!$resultbis) {
	  die("Error connecting to database."); 
	}
	$iii = 0;
	$count 	= 0;
	$syskey = '';
	$jjj = 0;
	$kkk = 0;
	$blank = 'blank';
	$hdsid = -1;
	while ($bi = pg_fetch_assoc($resultbis)) {		
		$jjj++;
		// $kkk++;
		// var_dump($bi);
		if ($syskey != $bi['sys1']) {
			if ($syskey != '')  {
				$query = "UPDATE holdingssets SET holdings_number=".$count." WHERE sys1 = '".$syskey."'";
				$result = pg_query($conn, $query) or die(pg_last_error().'couting');
				// echo 'VOY A RECALCULAR EL: '.$hdsid.' - '.$count."\n"; 
				// $a = holdingsset_recall($hdsid); 
			}
			
			$iii++;
			// if ($kkk == 100) {
			// 	sprintf('HOS->'.$iii." \n ");
			// 	$kkk = 0;
			// }

			$syskey = $bi['sys1'];
			$cero = 0;
			// CREO UN NUEVO GRUPO E INSERTO
		
			$query = "INSERT INTO holdingssets (id, sys1, f245a, ptrn, f008x, holdings_number, groups_number, state, f852h_e) VALUES 
			(
				".$iii.",
				'".pg_escape_string(addslashes($bi['sys1']))."',
				'".pg_escape_string(addslashes($bi['f245a']))."',
				'".pg_escape_string(addslashes($bi['ptrn']))."',
				'".pg_escape_string(addslashes($bi['f008x']))."',
				".$cero.",
				".$cero.",
				'".$blank."',
				'".pg_escape_string(addslashes($bi['f852h_e']))."'
				)";
			$result = pg_query($conn, $query) or die(pg_last_error().$query);			
			$hdsid = $iii;
			// $holdingsset_id = pg_last_oid($result);
			$count = 0;
		}

		$count++;
		// INSERT ITEM IN HOL_BIS
		$false = false;
		$exists_online = $bi['exists_online'] == null ? f : $bi['exists_online'];
		$is_current = $bi['is_current'] == null ? f : $bi['is_current'];
		$has_incomplete_vols = $bi['has_incomplete_vols'] == null ? f : $bi['has_incomplete_vols'];		
		$is_aux = $bi['is_aux'] == null ? f : $bi['is_aux'];
		$pot_owner = $bi['pot_owner'] == null ? f : $bi['pot_owner'];
		$is_owner = $bi['is_owner'] == null ? f : $bi['is_owner'];
		$is_pref = $bi['is_pref'] == null ? f : $bi['is_pref'];
		$query = "INSERT INTO holdings
		(
			id,
			holdingsset_id,
			library_id,
			sys2,
			g,
			f022a,
			f245a,
			f245b,
			f245c,
			f260a,
			score,
			flag,
			f260b,
			f310a,
			f710a,
			f780t,
			f785t,
			f852b,
			hol_nrm,
			probability,
			f008x,
			f008y,
			f362a,
			f866a,
			f866z,
			f852h,
			i,
			is_owner,
			ptrn,
			ocrr_ptrn,
			aux_ptrn,
			j_ptrn,
			weight,
			ocrr_nr,
			is_aux,
			pot_owner,
			is_pref,
			hbib,
			f246a,
			f300a,
			f300b,
			f300c,
			f500a,
			f505a,
			f770t,
			f772t,
			f852a,
			f852j,
			f866c,
			f866h,
			exists_online,
			is_current,
			has_incomplete_vols,
			size,
			force_owner,
			force_aux,
			f866aupdated,
			f866aupdatedby,
			f_tit,
			f260c,
			f710b,
			f245a_e,
			f245b_e,
			f245c_e,
			f_tit_e,
			f260a_e,
			f260b_e,
			f310a_e,
			f362a_e,
			f710a_e,
			f780t_e,
			f785t_e,
			state,
			force_blue,
			f245p,
			f245n,
			f852h_e,
			years,
			f072a
		) 
	VALUES 
		(
			".$jjj.",
			".$iii.",
			".$cero.",
			'".pg_escape_string(addslashes($bi['sys2']))."',
		   ".pg_escape_string($bi['g']).",
			'".pg_escape_string(addslashes($bi['f022a']))."',
			'".pg_escape_string(addslashes($bi['f245a']))."',
			'".pg_escape_string(addslashes($bi['f245b']))."',
			'".pg_escape_string(addslashes($bi['f245c']))."',
			'".pg_escape_string(addslashes($bi['f260a']))."',
			'".$bi['score']."',
			'".$bi['flag']."',
			'".pg_escape_string(addslashes($bi['f260b']))."',
			'".pg_escape_string(addslashes($bi['f310a']))."',
			'".pg_escape_string(addslashes($bi['f710a']))."',
			'".pg_escape_string(addslashes($bi['f780t']))."',
			'".pg_escape_string(addslashes($bi['f785t']))."',
			'".pg_escape_string(addslashes($bi['f852b']))."',
			'".$bi['hol_nrm']."',
			'".$bi['probability']."',
			'".pg_escape_string(addslashes($bi['f008x']))."',
			'".pg_escape_string(addslashes($bi['f008y']))."',
			'".pg_escape_string(addslashes($bi['f362a']))."',
			'".pg_escape_string(addslashes($bi['f866a']))."',
			'".pg_escape_string(addslashes($bi['f866z']))."',
			'".pg_escape_string(addslashes($bi['f852h']))."',
			'".$bi['i']."',
			'".$is_owner."',
			'".$bi['ptrn']."',
			'".$bi['ocrr_ptrn']."',
			'".$bi['aux_ptrn']."',
			'".$bi['j_ptrn']."',
			0,
			0,
			'".$is_aux."',			
			'".$pot_owner."',
			'".$is_pref."',			
			'".$bi['hbib']."',
			'".pg_escape_string(addslashes($bi['f246a']))."',
			'".pg_escape_string(addslashes($bi['f300a']))."',
			'".pg_escape_string(addslashes($bi['f300b']))."',
			'".pg_escape_string(addslashes($bi['f300c']))."',
			'".pg_escape_string(addslashes($bi['f500a']))."',
			'".pg_escape_string(addslashes($bi['f505a']))."',
			'".pg_escape_string(addslashes($bi['f770t']))."',
			'".pg_escape_string(addslashes($bi['f772t']))."',
			'".pg_escape_string(addslashes($bi['f852a']))."',
			'".pg_escape_string(addslashes($bi['f852j']))."',
			'".pg_escape_string(addslashes($bi['f866c']))."',
			'".pg_escape_string(addslashes($bi['f866h']))."',
			'".$exists_online."',
			'".$is_current."',
			'".$has_incomplete_vols."',		 	
			".$cero.",
			'f',
			'f',
			'".pg_escape_string(addslashes($bi['f866a']))."',
			".$cero.",
			'".pg_escape_string(addslashes($bi['f_tit']))."',
			'".pg_escape_string(addslashes($bi['f260c']))."',
			'".pg_escape_string(addslashes($bi['f710b']))."',
			'".pg_escape_string(addslashes($bi['f245a_e']))."',
			'".pg_escape_string(addslashes($bi['f245b_e']))."',
			'".pg_escape_string(addslashes($bi['f245c_e']))."',
			'".pg_escape_string(addslashes($bi['f_tit_e']))."',
			'".pg_escape_string(addslashes($bi['f260a_e']))."',
			'".pg_escape_string(addslashes($bi['f260b_e']))."',
			'".pg_escape_string(addslashes($bi['f310a_e']))."',
			'".pg_escape_string(addslashes($bi['f362a_e']))."',
			'".pg_escape_string(addslashes($bi['f710a_e']))."',
			'".pg_escape_string(addslashes($bi['f780t_e']))."',
			'".pg_escape_string(addslashes($bi['f785t_e']))."',			
			'".$blank."',
			'f',
			'".pg_escape_string(addslashes($bi['f245p']))."',
			'".pg_escape_string(addslashes($bi['f245n']))."',
			'".pg_escape_string(addslashes($bi['f852h_e']))."',
			0,
			'".pg_escape_string(addslashes($bi['f072a']))."'
			)";

		$result = pg_query($conn, $query);// or echo(pg_last_error().$query);
	}
	// $kkk = 0;
	// for ($yyy=1; $yyy <=  $iii; $yyy++) { 
	// 	if ($kkk == 50) {
	// 		sprintf('HOS->'.$iii." \n ");
	// 		$kkk = 0;
	// 	}
	// 	holdingsset_recall($iii);
	// }

/*
*
*	Controls workflow with Holdings Set (HOS)
*
*/
$hop_no           	= 0;         // number of parts
$hol_nrm          	= '';        // saved hol f866a result normalized
$fld_list         	= array();   // All names of Knowledge Groups
$know_gr          	= '';        // knowledge group
$know             	= array();   // contains all knowledgeable elements for recognizing HOP
$hol_info          	= array();   // collect info about holding string
$hop_info         	= array();   // collect info about holding part
$hol_info['proc']  	= '';        // collects info about processing hol
$starttime        	= sprintf("%s", date("Y-m-d H:i:s"));
$stat             	= array();   // statistical info
$con              	= '';   // statistical info
$do_show_pattern	= '';   // statistical info
$do_give_info		= '';   // statistical info
$ho_val_prev		= '';   // statistical info
$con				= '';   // statistical info
$do_control			= '';   // statistical info
$do_show_know		= '';   // statistical info
$fld				= '';   // statistical info
$repl				= '';   // statistical info
$upper				= '';   // statistical info
$write_val			= '';   // statistical info




/* ---------------------------------------------------------------------------------
	Recall a holdingsset.
	--------------------------------------
	Params:
		$id: HOS id
		$Notice: Parameters to used in recall
						- force_owner(int: Holding id): Fix a Holdings that has to be owner of the HOS
						- 866aupdated if 866aupdated != '';
						- lockeds holdings can't be used to the algoritm

						-----------------------------------------------------------------------------------*/
function holdingsset_recall($id) {
	$conn_string = "host=localhost port=5432 dbname=bis user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
	// $conn_string1 = "host=localhost port=5432 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
	$con = pg_connect($conn_string); //or ($con = pg_connect($conn_string1));

	$query = "SELECT * FROM holdings WHERE holdingsset_id = ".$id." ORDER BY sys2, score DESC LIMIT 100";
	$result = pg_query($con, $query) or die("Cannot execute \"$query\"\n".pg_last_error());

	$ta_arr = pg_fetch_all($result);

	// echo $ta_arr[0]['sys2']." \n";
	$ta_amnt = sizeOf($ta_arr);
	/***********************************************************************
	 * Se forman los grupos y se calculan los valores
	 ***********************************************************************/

	$index				= -1;
	$forceowner_index	= -1;
	$blockeds_hols		= array();
	$curr_ta			= '';
	$ta_hol_arr		= array();

	for ($i=0; $i<$ta_amnt; $i++) {
		$ta_res_arr   = array(); //<------------------------------------------ Collects res
		
		$ta = $ta_arr[$i]['sys1'];
		$hol = $ta_arr[$i]['sys2'];
		$g = $ta_arr[$i]['g'];

		if ($ta !== $curr_ta) {
			$index++;
			$curr_ta = $ta;
			$ta_hol_arr[$index]['hol']= array();
			$ta_hol_arr[$index]['ptrn']= array();
		}
		
		/******************************************************************
		 * Aqui se genera el patron y se le pega a cada < ta >  OK
		 * hay que generar un patron de incompletos (pa pintar después)
		 ******************************************************************/
		
		$hol_ptrn = $ta_arr[$i]['hol_nrm'];
		//si tiene algo se parte por el ;
		$ta_arr[$i]['ptrn_arr'] = (preg_match('/\w/',$hol_ptrn))?explode(';',$hol_ptrn):array();

		if ($ta_arr[$i]['ptrn_arr']){
			$ptrn_amnt	= sizeOf($ta_arr[$i]['ptrn_arr']);
			for ($l=0; $l<$ptrn_amnt; $l++){
				$ptrn_piece = $ta_arr[$i]['ptrn_arr'][$l]; //preservar el valor original
				//aqui se quita la j que no sirve pa comparar
				$ptrn_piece = preg_replace('/[j]/', ' ', $ptrn_piece);
				$ptrn_piece = preg_replace('/\s$/', '',$ptrn_piece);
				
				$ptrn_piece = preg_replace('/[n]/', '',$ptrn_piece); //<---------- parche!!!!!!!!!!!!!!!!!!!!!!!!!!!
				
				$ptrn_piece[16] = '-'; //esto es un parche pa poner el - que faltaba en el hol_nrm
				
				if (!preg_match('/\w/',$ptrn_piece)){
					//si el pedacito viene en blanco se borra
					unset($ta_arr[$i]['ptrn_arr'][$l]);
				}
				//si tiene sustancia...
				else {
					//se parte en pedacitos
					$ptrn_chunks = explode ('-',$ptrn_piece);
					$chunks_amnt	= sizeOf($ptrn_chunks);
					for ($p=0; $p<$chunks_amnt; $p++){
						if (!preg_match('/\w/',$ptrn_chunks[$p])){
							//se quitan los que quedan en blanco
							unset($ptrn_chunks[$p]);
						}
						//y se echan pal ptrn
						else array_push($ta_hol_arr[$index]['ptrn'],$ptrn_chunks[$p]);
					}				
				}
			}
		}
		
		//aqui se escribe el ptrn	
		$ta_hol_arr[$index]['ptrn']=array_unique($ta_hol_arr[$index]['ptrn']);
		//aqui se ordenan los pedacitos del patron----------------------------
		$tmparr = $ta_hol_arr[$index]['ptrn'];
		
		$tmparr = array_map(
			function($n){
				return explode('|',substr(chunk_split($n,4,'|'),0,-1));
			}, 
			$tmparr); 
		
		$volume = array();
		$year = array();
		foreach($tmparr as $key => $row){
			$volume[$key] = $row[0];
			$year[$key] = $row[2];
		}

		array_multisort($year,SORT_ASC, $volume,SORT_ASC, $tmparr);
		//$tmparr = array_map('make_onepiece',$tmparr);
		$tmparr = array_map(
			function($n){
				return implode('',$n); 
			}, 
			$tmparr); 
		
		$tmparr = array_values($tmparr);
		
		$ta_hol_arr[$index]['ptrn']  = $tmparr;
		//aqui se van juntando los hol del TA
		array_push($ta_hol_arr[$index]['hol'],$ta_arr[$i]);

		// if ((Holding::find($ta_arr[$i]['id'])->locked) || (Holding::find($ta_arr[$i]['id'])->force_blue == 't') || (Holding::find($ta_arr[$i]['id'])->force_blue == '1')) {
		// 	$blockeds_hols[]['index'] = $i;
		// 	$blockeds_hols[]['id'] = $ta_arr[$i]['id'];
		// }

		unset($ta_arr[$i]);
		unset($tmparr);
		// echo '.';
	}

	foreach ($blockeds_hols as $hol) {
		unset($ta_hol_arr[0]['hol'][$hol['index']]);
	}

	$ta_hol_arr[0]['hol'] = array_values($ta_hol_arr[0]['hol']);

	$hol_amnt = sizeOf($ta_hol_arr[0]['hol']);
	$mishols = $ta_hol_arr[0]['hol'];

	for ($k=0; $k<$hol_amnt; $k++){ //por cada hol
		if ($mishols[$k]['force_owner'] == 't') $forceowner_index = $k;
	}


	//echo EOL.EOL;
	$ta_hol_amnt = sizeOf($ta_hol_arr); //la cantidad de grupos TA

	/***********************************************************************
	 * Function/s :)
	 ***********************************************************************/

	/***********************************************************************
	 * For each group of holdings (TA)...
	 * 	weight pattern
	 * For each holding (hol)...
	 * 	fixes the 16th char (patch)...
	 * 	occurrences pattern
	 * 	completeness pattern
	 * 	weight
	 * 	number of occurrences
	 * 	potential owners by weight
	 * 	potential owners by occurrences
	 ***********************************************************************/
	// var_dump($blockeds_hols);

	for ($i=0; $i<$ta_hol_amnt; $i++){ //<---------------------------------- for each group of holdings (TA)...
		
		//Patron del HOS - como arreglo
		$ptrn = $ta_hol_arr[$i]['ptrn'];

		// Tamaño del arreglo del patrón
		$ptrn_amnt = sizeOf($ptrn);
		
		// Hols del HOS
		$hol_arr = $ta_hol_arr[$i]['hol'];

		// Cantidad de hols
		$hol_amnt = sizeOf($hol_arr);

		$weight_ptrn = array_map(
			function ($n){
				$chunks = explode('|',substr(chunk_split($n,4,'|'),0,-1));
				$d_vol = intval($chunks[1])-intval($chunks[0]);
				$d_year = intval($chunks[3])-intval($chunks[2]);
				return (($d_vol>0)?$d_vol:(($d_year>0)?$d_year:0))+1;
			},
			$ptrn);
		
		$ta_hol_arr[$i]['weight_ptrn'] = $weight_ptrn; //<-------------------- weight pattern

		$mx_ocrr_nr = 0;
		$mx_weight = 0;
		$posowners = array();	
		$posowners_oc = array();
		$owner_index = ''; 
		$ta_hol_arr[$i]['owner'] = '';
		
		for ($k=0; $k<$hol_amnt; $k++) { //<----------------------------------- for each holding (hol)...
			// echo $k.'->';
			$ta = $hol_arr[$k]['sys1'];
			$hol = $hol_arr[$k]['sys2'];
			$g = $hol_arr[$k]['g'];
			
			$weight = 0;
			$ocrr_nr = 0;
			
			$j_factor = .5;

			$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'] = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array();
			$ta_hol_arr[$i]['hol'][$k]['j_arr'] = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array();
			
			$ocrr = $ta_hol_arr[$i]['hol'][$k]['ptrn_arr'];
			// echo '+occr';
			if ($ocrr) {
				$ocrr_amnt = sizeOf($ocrr);
				
				for ($l=0; $l<$ocrr_amnt; $l++){ //por cada pedacito
					// echo 'a-';
					if (isset($ocrr[$l])){
						//hay pedacito y se puede partir
						$ocrr_piece = $ocrr[$l];
						
						$is_j = preg_match('/[j]/',$ocrr_piece);
						$ocrr_piece[16] = '-'; //<------------------------------------ fixes the 16th char (patch)...			
						$ocrr_piece = preg_replace('/[j]/', ' ', $ocrr_piece);
						$ocrr_piece = preg_replace('/\s$/', '',$ocrr_piece);
						
						$ocrr_piece = preg_replace('/[n]/', '',$ocrr_piece); //<------ parche
						
						$ocrr_xtr = explode('-',$ocrr_piece);
						
						$ocrr_bgn = get_ptrn_position($ocrr_xtr[0],$ptrn);
						$val_bgn = $ocrr_xtr[0];

						if (array_key_exists(1,$ocrr_xtr)){ //<----------------------- vvvvVVVVyyyyYYYY-vvvvVVVVyyyyYYYY
							if (preg_match('/\w/',$ocrr_xtr[1])){
								$ocrr_end = get_ptrn_position($ocrr_xtr[1],$ptrn);
								$val_end = $ocrr_xtr[1];
							}
								else { //<------------------------------------------------ vvvvVVVVyyyyYYYY-
									$ocrr_end = $ptrn_amnt-1;
									$val_end = (isset($ptrn[$ptrn_amnt-1]))?$ptrn[$ptrn_amnt-1]:'';
								}
							}
						else { //<---------------------------------------------------- vvvvVVVVyyyyYYYY
							
							//si el valor solo es un agno buscar hasta donde llega ????
							$tiny_chunks = explode('|',substr(chunk_split($ocrr_bgn,4,'|'),0,-1));
							//if (preg_match('/\w/',$tiny_chunks[2])) echo $tiny_chunks[2].EOL;
							$ocrr_end = $ocrr_bgn;
							$val_end = $val_bgn;
						}
						$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'][$ocrr_end] = 1;
						if ($is_j) $ta_hol_arr[$i]['hol'][$ocrr_end]['j_arr'][$h] = 1;
						$ocrr_bgn = ($ocrr_bgn == '?') ? 0 : $ocrr_bgn;
						$ocrr_end = ($ocrr_end == '?') ? 0 : $ocrr_end;
						// echo '>>>'.$ocrr_bgn.'---'.$ocrr_end.'<<<';
						// var_dump($ocrr_bgn);
						// var_dump($ocrr_end);
						for ($h=$ocrr_bgn; $h<$ocrr_end; $h++) {
							$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'][$h] = 1;
							if ($is_j) $ta_hol_arr[$i]['hol'][$k]['j_arr'][$h] = 1;
						}
					}
					else {
						//no se pudo determinar
					}
				}
			}
			// echo '-occr';
			$ocrr_ptrn = $ta_hol_arr[$i]['hol'][$k]['ocrr_arr']; //<------------ occurrences pattern
			$j_ptrn = $ta_hol_arr[$i]['hol'][$k]['j_arr']; //<------------------ completeness pattern
			// echo '+map';
			$hol_weight_ptrn = array_map( 
			function($w, $o, $j){
				$j_factor = .5;
				return $w*$o*(($j>0)?$j_factor:1); 
			}, $weight_ptrn, $ocrr_ptrn, $j_ptrn); 	
			// echo '-map';
			$weight = array_sum($hol_weight_ptrn);  //<------------------------- weight
			$ocrr_nr  = array_sum($ocrr_ptrn);  //<----------------------------- number of occurrences
		
			/******************************************************************
			 * Finding potential owners
			 ******************************************************************/

			if ($weight !== 0 ) {
				if ($weight > $mx_weight ) {
					$mx_weight = $weight;
					$posowners = array();	
					$posowners[0] = $k;
				}
				else if ($weight === $mx_weight ) {
						array_push($posowners,$k); //<---------------------------------- potential owners by weight
				}
			}
			
			if ($ocrr_nr !== 0 ) {
				if ($ocrr_nr > $mx_ocrr_nr ) {
					$mx_ocrr_nr = $ocrr_nr;
					$posowners_oc = array();	
					$posowners_oc[0] = $k;
				}
				else if ($ocrr_nr === $mx_ocrr_nr ) {
					array_push($posowners_oc,$k); //<------------------------------- potential owners by occurrences
				}
			}
			
			$ta_hol_arr[$i]['hol'][$k]['ocrr_nr'] = $ocrr_nr;
			$ta_hol_arr[$i]['hol'][$k]['weight'] = $weight;

			/******************************************************************
			 * UPDATE hol_out
			 * 	ptrn
			 * 	ocrr_nr
			 * 	weight
			 * 	ocrr_ptrn
			 * 	j_ptrn
			 ******************************************************************/

			$ta_res_arr[$ta.$hol.$g]['sys1'] 	  = $ta;
			$ta_res_arr[$ta.$hol.$g]['sys2']      = $hol;
			$ta_res_arr[$ta.$hol.$g]['g']         = $g;
			$ta_res_arr[$ta.$hol.$g]['ptrn']      = implode('|',$ptrn);
			$ta_res_arr[$ta.$hol.$g]['ocrr_nr']   = $ocrr_nr;
			$ta_res_arr[$ta.$hol.$g]['ocrr_ptrn'] = implode('',$ocrr_ptrn);
			$ta_res_arr[$ta.$hol.$g]['weight']    = $weight;
			$ta_res_arr[$ta.$hol.$g]['j_ptrn']    = implode('',$j_ptrn);

			$ta_res_arr[$ta.$hol.$g]['is_owner']  = 'f';
			$ta_res_arr[$ta.$hol.$g]['aux_ptrn']  = '';
			$ta_res_arr[$ta.$hol.$g]['is_aux']    = 'f';

				/*
				$query = "UPDATE hol_out 
									SET ptrn='".implode('|',$ptrn) ."' , ocrr_nr='". $ocrr_nr ."' , ocrr_ptrn='". implode('',$ocrr_ptrn) ."' , weight='". $weight ."' , j_ptrn='". implode('',$j_ptrn) ."'
									WHERE sys1 = '".$ta."' AND sys2 = '".$hol."' AND g = '".$g."'";
				$result = pg_query($conn, $query) or die("Cannot execute \"$query\"\n");
				*/
				// echo 'un holding';
		}
			// echo 'saliaaaa'." \n ";
			/******************************************************************
			 * Finding "the owner" according to the following criteria:
			 * 	preferred
			 * 	heaviest
			 * 	highest occurrences number
			 ******************************************************************/
			
			if ($posowners) {
				$owners_amnt = sizeOf ($posowners);
				if ($owners_amnt>1){
					for ($o_index=0; $o_index<$owners_amnt; $o_index++){
						$is_pref = $ta_hol_arr[$i]['hol'][$o_index]['is_pref'];
						$owner_index = $posowners[$o_index];
						if ($is_pref=='t') break;
						else if (in_array($posowners[$o_index],$posowners_oc)) break;
					}
				}
				else $owner_index =  $posowners[0];
			}

			$owner_index = ($forceowner_index != -1)  ? $forceowner_index : $owner_index;
			$ta_hol_arr[$i]['owner'] = ($forceowner_index != -1)  ? $forceowner_index : $owner_index;
			$mishols[$i]['owner'] = ($forceowner_index != -1)  ? $forceowner_index : $owner_index;

			
			/******************************************************************
			 * UPDATE hol_out
			 * 	is_owner
			 ******************************************************************/

			if ($owner_index !== '') {
				
				$ta = $mishols[$owner_index]['sys1'];
				$hol = $mishols[$owner_index]['sys2'];
				$g = $mishols[$owner_index]['g'];
				
				$ta_res_arr[$ta.$hol.$g]['is_owner'] = 't';
				/*
				$query = "UPDATE hol_out SET is_owner='". 1 ."' 
									WHERE sys1 = '".$ta."' AND sys2 = '".$hol."' AND g = '".$g."'";
				$result = pg_query($conn, $query) or die("Cannot execute \"$query\"\n");
				*/
			}

		}
	// die('toy aqui ahora');
	/***********************************************************************
	 * Aqui se encuentra la biblioteca de apoyo a partir del owner
	 * la aux se calcula completando con la que tiene mayor peso/ocurrencia
	 ***********************************************************************/
	
	for ($i=0; $i<$ta_hol_amnt; $i++){ //por cada grupo...
		$hol_arr = $ta_hol_arr[$i]['hol'];
		$hol_amnt = sizeOf($hol_arr); //la cantidad de hol
		$mx_weight = 0;
		$weight = 0;
		$ocrr_nr  = 0;
		
		if($mishols[$i]['owner']) {

			$owner_ocrr_arr = $hol_arr[$ta_hol_arr[$i]['owner']]['ocrr_arr'];
			$owner_ocrr_amnt = sizeOf($owner_ocrr_arr);
			$weight_ptrn = $ta_hol_arr[$i]['weight_ptrn'];
			$ptrn = $ta_hol_arr[$i]['ptrn'];
			$ptrn_amnt = sizeOf($ptrn);
			$potaux_array = array();
			$potaux_array = array_fill(0,$hol_amnt,0);
			
			$denied_owner = array_map(
				function ($n){
					return intval(!$n);
				},
				$owner_ocrr_arr);

			for ($k=0; $k<$hol_amnt; $k++){ //por cada hol
				
				if (isset($hol_arr[$k]['ocrr_arr'])){ //esto e un parche porque falta una fila

					$ocrr_ptrn = $hol_arr[$k]['ocrr_arr'];
					$j_ptrn = $hol_arr[$k]['j_arr'];

					$aux_ptrn = array_map(
						function ($n, $m){
							return $n*$m;
						},
						$denied_owner, $ocrr_ptrn);

					$aux_weight_ptrn = array_map( 
						function($w, $a, $j){
							$j_factor = .5;
							return $w*$a*(($j>0)?$j_factor:1); 
						}, 
						$weight_ptrn, $aux_ptrn, $j_ptrn); 

					$aux_weight = array_sum($aux_weight_ptrn);
					$ocrr_nr  = array_sum($aux_ptrn);

			//se juntan los aul de mayor peso
					if ($aux_weight !== 0 ) {
						if ($aux_weight > $mx_weight ) {
							$mx_weight = $aux_weight;
							$potaux_array = array_fill(0,$hol_amnt,0);
							$potaux_array[$k] = 1;
						}
						else if ($aux_weight === $mx_weight ) {
							$potaux_array[$k] = 1;
						}
					}

					$ta = $hol_arr[$k]['sys1'];
					$hol = $hol_arr[$k]['sys2'];
					$g = $hol_arr[$k]['g'];

					$ta_res_arr[$ta.$hol.$g]['aux_ptrn'] = implode($aux_ptrn);


			/*
			$query = "UPDATE hol_out 
								SET aux_ptrn='". implode($aux_ptrn) ."'
								WHERE sys1 = '".$ta."' AND sys2 = '".$hol."' AND g = '".$g."'";
			$result = pg_query($conn, $query) or die("Cannot execute \"$query\"\n");	
			*/		

			}//fin del parche porque falta una fila
			
		}
		$ta_hol_arr[$i]['potaux_array'] = $potaux_array;
	}
	}

		/******************************************************************
		* Aqui se escribe aux_ptrn en la tabla y si is_aux
		******************************************************************/

	for ($i=0; $i<$ta_hol_amnt; $i++){ //por cada grupo...
		
		$hol_arr = $ta_hol_arr[$i]['hol'];
		
		$hol_amnt = sizeOf($hol_arr); //la cantidad de hol
		
		if($ta_hol_arr[$i]['owner']) {
			
			$potaux_array = $ta_hol_arr[$i]['potaux_array'];

			for ($k=0; $k<$hol_amnt; $k++){ //por cada hol
				
				if (isset($hol_arr[$k]['ocrr_arr'])){ //esto e un parche porque falta una fila
					
					$ta = $hol_arr[$k]['sys1'];
					$hol = $hol_arr[$k]['sys2'];
					$g = $hol_arr[$k]['g'];

					$ta_res_arr[$ta.$hol.$g]['is_aux'] = $potaux_array[$k];

				/*
				$query = "UPDATE hol_out 
									SET is_aux='". $potaux_array[$k] ."'
									WHERE sys1 = '".$ta."' AND sys2 = '".$hol."' AND g = '".$g."'";
				$result = pg_query($conn, $query) or die("Cannot execute \"$query\"\n");
				*/

				}//fin del parche porque falta una fila
			}
		}
	}

	//printf("<br>Updating table hol_out: <br> ");

	// print_r($ta_res_arr);

	$ta_res_amnt = sizeof($ta_res_arr);
	$ta_nr = 0;
	foreach ($ta_res_arr as $key => $value) { // foreach sys1,sys2,g  write result in table hol_out_ptrn
		$value['is_owner'] = (($value['is_owner'] == '0') || ($value['is_owner'] == 'f')) ? 'f' : 't';
		$value['is_aux'] = (($value['is_aux'] == '0') || ($value['is_aux'] == 'f')) ? 'f' : 't';
	  // var_dump($value);

		// Holding::find($mishols[$ta_nr]['id'])->update('j_ptrn' => $value['j_ptrn'], 'is_owner' => $value['is_owner'], 'aux_ptrn' => $value['aux_ptrn'], 'is_aux' => $value['is_aux']]);
		
		$query = "UPDATE  holdings SET  
		ocrr_nr = '".$value['ocrr_nr']."', 
		ocrr_ptrn = '".$value['ocrr_ptrn']."', 
		weight = '".$value['weight']."', 
		j_ptrn = '".$value['j_ptrn']."',
		is_owner = '".$value['is_owner']."',
		aux_ptrn = '".$value['aux_ptrn']."',
		is_aux = '".$value['is_aux']."'";
		
		// echo " \n ".$query. " \n "	;
		$result = pg_query($con, $query) or die(pg_last_error());
		$finalptrn = $value['ptrn'];

		$ta_nr++;
		//if (($ta_nr % $trigger) == 0) echo $ta_nr.'|';
	}

	// die("\nThat's a better end of the story");
	// Holdingsset::find($id)->update(['ptrn' => $finalptrn]);
	return 'ok';
}

function get_ptrn_position ($ocrr,$ptrn){
	$ptrn_size = sizeOf($ptrn);
	for ($i=0; $i<$ptrn_size; $i++){
		if ($ocrr===$ptrn[$i]) {
			return $i;
		}
	}
	return '?';
}

?>
