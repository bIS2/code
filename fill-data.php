<?php
	error_reporting(0);
	$conn_string = "host=localhost port=5432 dbname=bis user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
	
	$conn = pg_connect($conn_string) or die('ERROR!!!');

	$truncate = pg_query($conn, "TRUNCATE holdingssets");
	$truncate = pg_query($conn, "TRUNCATE holdings");
	$resultbis = pg_query($conn, "SELECT * FROM hol_out WHERE sys1 <> '' and sys2 <> '' ORDER BY sys1 ASC, sys2 ASC");
	if (!$resultbis) {
	  die("Error connecting to database."); 
	}
	$i = 0;
	$count 	= 0;
	$syskey = '';
	$j = 0;
	$blank = 'blank';
	$holdingsset_id = -1;
	while ($bi = pg_fetch_assoc($resultbis)) {
		$j++;
		// var_dump($bi);
		// echo $bi['sys1'].'->';
		if ($syskey != $bi['sys1']) {
			if ($syskey != '')  {
				// echo 'Actualizo count in BD';
				$query = "UPDATE holdingssets SET holdings_number=".$count." WHERE sys1 = '".$syskey."'";
				// echo '<br><br>'.$query.'<br><br>';
				$result = pg_query($conn, $query) or die(pg_last_error().'couting');
			}
			$i++;
			$syskey = $bi['sys1'];
			$cero = 0;
			// CREO UN NUEVO GRUPO E INSERTO
			$query = "INSERT INTO holdingssets (id, sys1, f245a, ptrn, f008x, holdings_number, groups_number, state, f852h_e) VALUES 
			(
				".$i.",
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
			if ($holdingsset_id != -1) { holdingsset_recall($holdingsset_id); }
			$holdingsset_id = $i;
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
			".$j.",
			".$i.",
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

		$result = pg_query($conn, $query) or die(pg_last_error().$query);
	}
	holdingsset_recall($i);
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
		
		for ($k=0; $k<$hol_amnt; $k++){ //<----------------------------------- for each holding (hol)...
			
			$ta = $hol_arr[$k]['sys1'];
			$hol = $hol_arr[$k]['sys2'];
			$g = $hol_arr[$k]['g'];
			
			$weight = 0;
			$ocrr_nr = 0;
			
			$j_factor = .5;

			$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'] = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array();
			$ta_hol_arr[$i]['hol'][$k]['j_arr'] = ($ptrn_amnt>0)?array_fill(0,$ptrn_amnt,0):array();
			
			$ocrr = $ta_hol_arr[$i]['hol'][$k]['ptrn_arr'];
			
			if ($ocrr) {
				$ocrr_amnt = sizeOf($ocrr);
				
				for ($l=0; $l<$ocrr_amnt; $l++){ //por cada pedacito
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
							if (preg_match('/\w/',$tiny_chunks[2])) echo $tiny_chunks[2].EOL;
							$ocrr_end = $ocrr_bgn;
							$val_end = $val_bgn;
						}
						$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'][$ocrr_end] = 1;
						if ($is_j) $ta_hol_arr[$i]['hol'][$ocrr_end]['j_arr'][$h] = 1;
						for ($h=$ocrr_bgn; $h<$ocrr_end; $h++){
							$ta_hol_arr[$i]['hol'][$k]['ocrr_arr'][$h] = 1;
							if ($is_j) $ta_hol_arr[$i]['hol'][$k]['j_arr'][$h] = 1;
						}
					}
					else {
						//no se pudo determinar
					}
				}
			}
			
			$ocrr_ptrn = $ta_hol_arr[$i]['hol'][$k]['ocrr_arr']; //<------------ occurrences pattern
			$j_ptrn = $ta_hol_arr[$i]['hol'][$k]['j_arr']; //<------------------ completeness pattern

			$hol_weight_ptrn = array_map( 
				function($w, $o, $j){
					$j_factor = .5;
					return $w*$o*(($j>0)?$j_factor:1); 
				}, 
				$weight_ptrn, $ocrr_ptrn, $j_ptrn); 
			
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
			
		}

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
					if ($is_pref=='t')break;
					else if (in_array($posowners[$o_index],$posowners_oc))break;
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
	foreach ($ta_res_arr as $key => $value){ // foreach sys1,sys2,g  write result in table hol_out_ptrn
		$value['is_owner'] = (($value['is_owner'] == '0') || ($value['is_owner'] == 'f')) ? 'f' : 't';
		$value['is_aux'] = (($value['is_aux'] == '0') || ($value['is_aux'] == 'f')) ? 'f' : 't';
	  // var_dump($value);
		Holding::find($mishols[$ta_nr]['id'])->update(['ocrr_nr' => $value['ocrr_nr'], 'ocrr_ptrn' => $value['ocrr_ptrn'], 'weight' => $value['weight'], 'j_ptrn' => $value['j_ptrn'], 'is_owner' => $value['is_owner'], 'aux_ptrn' => $value['aux_ptrn'], 'is_aux' => $value['is_aux']]);
		$finalptrn = $value['ptrn'];

		$ta_nr++;
		//if (($ta_nr % $trigger) == 0) echo $ta_nr.'|';
	}
	// die("\nThat's a better end of the story");
	Holdingsset::find($id)->update(['ptrn' => $finalptrn]);
}



// ***********************************************
function last_similar_ta_in_set( $ta_res_sim) {
// ***********************************************
// get number of last good ta candidate

	$ta_sim_last_good = 0;
	for ($rno = 0; $rno < sizeof($ta_res_sim); $rno++) {
		if ($ta_res_sim[$rno]['flag'] == '*' || $ta_res_sim[$rno]['flag'] == '=') { // included in the result set
			$ta_sim_last_good = $rno;  // remember last selected record
		} else { break; }
	}
	return $ta_sim_last_good;
}



// ***********************************************
function compare_field ($fld, $valO, $valC, $ta_res_sim, $rno) {
// ***********************************************
  // adapt weight for specific fields
  $similar = 0; // in case that both fields are empty
  if ($valO == '' and $valC == '') return 0;
  switch ($fld) {
  	case 'f008x' :
      if ($valC == 'uuuu' || $valO == 'uuuu') return 0; // don't compare incomplete information
      if ($valC == $valO) return 1; return 0;
      break;
      case 'f008y' :
      if ($valC == 'uuuu' || $valO == 'uuuu') return 0; // don't compare incomplete information
      if ($valC == $valO) return 1; return 0;
      break;
      case 'f022a' :
        // we have to compare from 1 to 19 occurrences. Every single match wins all
      $valO_arr = preg_split("/ *¬ */", $valO, null, PREG_SPLIT_NO_EMPTY);
      $valC_arr = preg_split("/ *¬ */", $valC, null, PREG_SPLIT_NO_EMPTY);
        if (count(array_intersect($valC_arr, $valO_arr)) > 0) { // check if at least one of the ISSN coincides
        	return 1;
        }
        break;
        case 'f260a' :
        // we have to compare 
        $valO_arr = preg_split("/ *¬ */", $valO, null, PREG_SPLIT_NO_EMPTY);
        $valC_arr = preg_split("/ *¬ */", $valC, null, PREG_SPLIT_NO_EMPTY);
        if (count(array_intersect($valC_arr, $valO_arr)) > 0) // check if at least one of the ISSN coincides
        return 1;
        else return $ta_res_sim[$rno]['s_'.$fld];
        break;
        case 'f780t' :
        // we have to compare 
        $valO_arr = preg_split("/ *¬ */", $valO, null, PREG_SPLIT_NO_EMPTY);
        $valC_arr = preg_split("/ *¬ */", $valC, null, PREG_SPLIT_NO_EMPTY);
        if (count(array_intersect($valC_arr, $valO_arr)) > 0) // check if at least one of the ISSN coincides
        return 1;
        else return $ta_res_sim[$rno]['s_'.$fld];
        break;
        case 'f785t' :
        // we have to compare 
        $valO_arr = preg_split("/ *¬ */", $valO, null, PREG_SPLIT_NO_EMPTY);
        $valC_arr = preg_split("/ *¬ */", $valC, null, PREG_SPLIT_NO_EMPTY);
        if (count(array_intersect($valC_arr, $valO_arr)) > 0) // check if at least one of the ISSN coincides
        return 1;
        else return $ta_res_sim[$rno]["s_".$fld];
        break;
        default :
        break;
    }
    return $similar;
}

// ***********************************************
function weight_every_fld($fld, $weight_model,$ta_res_sim, $rno, $fld_weight_model,$ta_res_sim, $rno, $fld_weight_model) {
// ***********************************************
	// calculate score value for $fld

	$fld_weight = $fld_weight_model[$weight_model];
	$score_delta = 0;
	$s_fld = 's_'.$fld; // form field name for similarity values
  if ($ta_res_sim[$rno][$s_fld] == 1) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['equ'];          // full match
  else if ($ta_res_sim[$rno][$s_fld] >= 0.8 ) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['sim'];  // presumable match
    else if ($ta_res_sim[$rno][$s_fld] < 0.8 ) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['dif']; // insecure or false match
    return $score_delta;
}




// ***********************************************
function show_time($date_from) {
// ***********************************************
  // show current time
	$date_now = new DateTime('now', new DateTimeZone('America/New_York'));
	$date_interval = $date_from->diff($date_now);
	return $date_interval->format('%H:%I:%S');
}

// ***********************************************
function cmp_score($a, $b) {
// ***********************************************
  // comparison operation
	if ($a['score'] == $b['score']) return 0;
	return ($a['score'] > $b['score']) ? -1 : 1;
}

// ***********************************************
function cmp_flag_score($a, $b) {
// ***********************************************
  // comparison operation
	if ($a['flag'] == '*') $a['flag'] = '<'; // replace for correct sorting
	if ($b['flag'] == '*') $b['flag'] = '<'; // replace for correct sorting
  $a_srt = sprintf("%1s%02d", $a['flag'], 500-$a['score']);  // sort with descending score
  $b_srt = sprintf("%1s%02d", $b['flag'], 500-$b['score']);  // sort with descending score
  if ($a_srt == $b_srt) return 0;
  return ($a_srt > $b_srt) ? -1 : 1;
}

function create_table($tab_name) {

	$conn_string = "host=localhost port=5432 dbname=bis user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
	// $conn_string1 = "host=localhost port=5432 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
	$con = pg_connect($conn_string);// or ($con = pg_connect($conn_string1));

	$query  = "DROP TABLE IF EXISTS $tab_name; ";
	$query .= "CREATE TABLE $tab_name (sys1 char(10), sys2 char(10), score integer, flag char(1), upd timestamp)";
	$result = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n"; exit; }
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

function normalize866a($new866a, $sys2) {
/* 
Project: SP2 - bIS
Function:
  get a single f886a
	recognizes the content using knowledge from TABLE hol_values
	writes elements recognized into hop_info
	normalizes every hop (voB1voB2yeB1yeB2voE1voE2yeE1yeE2IO) to in the form: VVVVvvvvYYYYyyyyVVVVvvvvYYYYyyyyIO
    - Explanation: vo = volume ; ye = year;  B = begin; E = end; 1= start period ; 2 = en period; I = incomplete; O = online

DevNotes:
  2012-10-01 08:35 pgt Start
	...
	2013-12-10 Adapted to bIS
	*/

/* ************************************************
 * Global variables
 ************************************************ */

date_default_timezone_set('America/Los_Angeles');  // correct
global $hop_no;         // number of parts
global $hol_nrm;        // saved hol f866a result normalized
global $fld_list;   // All names of Knowledge Groups
global $know_gr;        // knowledge group
global $know;   // contains all knowledgeable elements for recognizing HOP
global $hol_info;   // collect info about holding string
global $hop_info;   // collect info about holding part
global $starttime;
global $stat;   // statistical info
global $con;   // statistical info
global $do_show_pattern;
global $do_give_info;
global $ho_val_prev;
global $con;
global $do_control;
global $do_show_know;
global $fld;
global $repl;
global $upper;
global $write_val;

$hop_no           = 0;         // number of parts
$hol_nrm          = '';        // saved hol f866a result normalized
$fld_list         = array();   // All names of Knowledge Groups
$know_gr          = '';        // knowledge group
$know             = array();   // contains all knowledgeable elements for recognizing HOP
$hol_info          = array();   // collect info about holding string
$hop_info         = array();   // collect info about holding part
$hol_info['proc']  = '';        // collects info about processing hol
$starttime        = sprintf("%s", date("Y-m-d H:i:s"));
$stat             = array();   // statistical info

$conn_string = "host=localhost port=5432 dbname=bis user=bispgadmin password=%^$-*/-bIS-2014*-% options='--client_encoding=UTF8'";
$con = pg_connect($conn_string) or die('ERROR!!!');

// collect knowledge
$know['hG'] = acquire_knowledge('h', 'G', '');  // clearly recognizable strings at HOL level
$know['pF'] = acquire_knowledge('p', 'F', '');  // clearly recognizable string that could disturb in further processing
$know['pG'] = acquire_knowledge('p', 'G', '');  // clearly recognizable strings at HOP level
$know['pK'] = acquire_knowledge('p', 'K', '');  // strings at HOP level that can affect if used at start
$know['pL'] = acquire_knowledge('p', 'L', '');  // // strings at HOP level to eliminate not at last
$know['pN'] = acquire_knowledge('p', 'N', '');// strings at HOP level to eliminate at last

// if ($proc_flag['debug']) show_process_info($res_list);   // show information about current process
	
/* ================================================================== *
 * HOL 
 * ================================================================== */
// extract $ta de $res_list
$recno = 0;
$stat['A Record retrieved'] = 1; // !! we are looking ony at 1 HOL
$hol_str      = $new866a;       // get holding string. This string we will changed
$hol_sys      = $sys2;

// <--------------------------------- JUMP HEAR
RESTART_WITH_COMMA_REPLACED: // after , has been changed to ;

$hol_info['proc'] = '';            // init

// if ($proc_flag['show'])  printf("\n\n=== %s             :|%s|", $hol_sys, $hol_str);

/* ================================================================== *
* change things at HOL string level *
* ================================================================== */
// for each knowledge element
$know_gr = 'hG'; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hol_str = val_replace($hol_str);
// var_dump($hol_str);
// die('siguiendo holstr');

/* ======================================================================== *
* modify entire hol_str
* ======================================================================== */
// modify [L= ...; ...] to {L~ ...} so it will be kept together and not be split by later regex operations
$hol_str_prev = $hol_str;
$fld = 'LN='; $hol_str = save_LN($fld, $hol_str);  // save [L=...; ...; N=...] so it will be not split
// if ($hol_str_prev <> $hol_str) do_control('vLN', '', $hol_str_prev, '=>', $hol_str);

/* --------------------------------------------- *
 * Deal with the normal case                     *
 * --------------------------------------------- */
$ho_part = array(); // init
if (preg_match("/ *; */", $hol_str, $elem)) {  // check if we have something to do
	$hol_info['proc'] = 'Split by ;|';
	$ho_part = preg_split("/ *; */", $hol_str);
	$hop_no = 0; // init parts counter
} else $ho_part[0] = $hol_str;

/* ---------------------------------------------
* Loop through every HOP
* --------------------------------------------- */
$stat['A_Parts of holdings'] = 0;
for ($hop_no = 0; $hop_no < count($ho_part); $hop_no++) {
	$hop = $ho_part[$hop_no];           // we work on this string
	$hop_info[$hop_no] = array();     	// collect info about holding
	$hop_info[$hop_no]['NSER'] = 0;     // serial number starts at 0 (N.F. etc)
	$hop_info[$hop_no]['proc'] = '';    // init information collector of processing the hop
	$hop_orig = $hop;                   // save original string for later comparison
	$hop = trim($hop);                  // trim hop
	$stat['A_Parts of holdings']++;

		
	/* ***********************************************
	 * extract information elements
	 *********************************************** */
	// put {L=...} etc. in a separate variable
	// EX: 178(1994)-262(2006) {N~1-3| 8| 11| 32| 49}
	$hop_before = $hop;
	$know["L=N="] = "/^(.*) *(\{[NL]~[^\}]+\})$/";  // give regex pattern here
	if (preg_match($know["L=N="], $hop, $elem)) {  // do we have something to do?
		$hop_info["L=N="] = $elem[2];
		$hop  = $elem[1];
		$hop_info[$hop_no]["proc"] .= "cut {L=N=}|";
		$hop_info[$hop_no]['ICPL'] = 'j';
	}

	// Recognize and delete well recognizable but disturbing information  (F)
		$know_gr = "pF"; for ($c=0; $c < sizeof($know[$know_gr]['uses']); $c++) $hop = val_replace($hop);
			
	// put = ... in a separate variable
		 // but: What to do with : 42=1(1975)-45=4(1978)
		if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;  // shorten recognition path
		if (stripos($hop, '=') !== false) $hop = cut_holding_equivalent($hop);

	// Recognize and delete well recognizable information  (G)
	$know_gr = "pG"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;
		
	//$know_gr = "pH"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	//$know_gr = "pI"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	//$know_gr = "pJ"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	//if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;
		
	// Recognize well recognized element at BOL or EOL
	$know_gr = "pK"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;

	// Recognize and delete less recognizable information 
	$know_gr = "pL"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	$know_gr = "pN"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	if ($hop == '==RECOGNIZED==' or $hop == '_VOID_' ) goto SKIP_TO_LAST_THINGS;
		
	// Clean string once more
	$know_gr = "pG"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);

	// Third cleaning
	$know_gr = "pG"; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hop = val_replace($hop);
	
	// JUMP HERE <------------
	SKIP_TO_LAST_THINGS:   // jump here if work is already done
	

	// **********************************
	// check and adapt values for output
	// **********************************
	// avoid non numeric entries
	if (isset($hop_info[$hop_no]['yeB1']) && (!preg_match('/[0-9]+/', $hop_info[$hop_no]["yeB1"]) > 0)) $hop_info[$hop_no]["yeB1"] = '-';
	if (isset($hop_info[$hop_no]['yeB2']) && (!preg_match('/[0-9]+/', $hop_info[$hop_no]["yeB2"]) > 0)) $hop_info[$hop_no]["yeB2"] = '-';
	if (isset($hop_info[$hop_no]['yeE1']) && (!preg_match('/[0-9]+/', $hop_info[$hop_no]["yeE1"]) > 0)) $hop_info[$hop_no]["yeE1"] = '-';
	if (isset($hop_info[$hop_no]['yeE2']) && (!preg_match('/[0-9]+/', $hop_info[$hop_no]["yeE2"]) > 0)) $hop_info[$hop_no]["yeE2"] = '-';

	// reformat year if shorter than 4 digits
	if (isset($hop_info[$hop_no]['yeB2']) && (strlen($hop_info[$hop_no]['yeB2']) < 4)) $hop_info[$hop_no]['yeB2'] = reformat_val2($hop_info[$hop_no]['yeB1'], $hop_info[$hop_no]['yeB2']); 
	if (isset($hop_info[$hop_no]['yeE2']) && (strlen($hop_info[$hop_no]['yeE2']) < 4)) $hop_info[$hop_no]['yeE2'] = reformat_val2($hop_info[$hop_no]['yeE1'], $hop_info[$hop_no]['yeE2']); 
		
	// put number of month instead of the name
	if (isset($hop_info[$hop_no]['moB1'])) $hop_info[$hop_no]['moB1'] = &convert_month($hop_info[$hop_no]['moB1']);
	if (isset($hop_info[$hop_no]['moB2'])) $hop_info[$hop_no]['moB2'] = &convert_month($hop_info[$hop_no]['moB2']);
	if (isset($hop_info[$hop_no]['moE1'])) $hop_info[$hop_no]['moE1'] = &convert_month($hop_info[$hop_no]['moE1']);
	if (isset($hop_info[$hop_no]['moE2'])) $hop_info[$hop_no]['moE2'] = &convert_month($hop_info[$hop_no]['moE2']);


	// if type is empty, we recognized nothing
	if (!isset($hop_info[$hop_no]['type'])) {
		$hop_info[$hop_no]['type'] = '==UNKNOWN==';
		// do_control('vR!', '', $hop, '', '### '.$hop_info[$hop_no]['type']);
		// !!!! If "," in a too long hop encountered, assume ";" and RESTART
		if (preg_match('/\(?[0-9 \(\)-]{4,14}\)? *, *\(?[0-9]{1,4}\)?/', $hop, $elem)) { // Special cases: check if we should replace "," by ";"
			$hol_str = preg_replace('/ *, */', '; ', $hol_str);  // ### test this thoroughly. Until now it's a cheap patch!!!
			// do_control('vR2', 'RESTART', $hop, '', '>>>'.$hop);
			// unset ($hop_info[$hop_no]);
			goto RESTART_WITH_COMMA_REPLACED;
		}
	}
	// adapt recognition information
	//do_control('vRu', '', $hop, '))', $hop_info[$hop_no]['type']);
	if (!((strcmp($hop,'==RECOGNIZED==') == 0) or (strcmp($hop,'_VOID_') == 0))) {  // if $hop has not been recognized ...
		isset($stat['Z_UNKNOWN']) ? $stat['Z_UNKNOWN']++ : $stat['Z_UNKNOWN']=1;
		//do_control('vR!', '', $hop, '', '>> '.$hop_info[$hop_no]['type']);
	} else {
			if (!substr($hop_info[$hop_no]['type'],0,4) == 'MDL ')	$hop_info[$hop_no]['type'] = '==RECOGNIZED=+';
	}
	if ((strcmp($hop,'==RECOGNIZED==') == 0) and (!strcmp(substr($hop_info[$hop_no]['type'],0,4),'MDL ') == 0)) // if $hop has not been recognized ...
		$hop_info[$hop_no]['type'] = '==RECOGNIZED=+';

} // <- end of hop loop

$hol_nrm = normalize_result($hop_info);

// The End
return $hol_nrm;
}


/* ======================================================================== *
 *                                Functions                                 *
 * ======================================================================== */

// ------------------------------------------------------------------------
// function do_control($marker1, $model, $str_before, $marker2, $str_after) {
// // ------------------------------------------------------------------------
// // Purpose: prints manipulation a a string to the screen
//   global $do_control, $proc_flag;
//   if ($proc_flag['control']) printf("\n%-3s %-25s : %-70s %2s %s", $marker1, $model, $str_before, $marker2, $str_after);
// }

// ------------------------------------------------------------------------
function val_replace($ho_val) {
// ------------------------------------------------------------------------
	global $know, $know_gr, $proc_flag, $do_show_pattern, $do_give_info, $hop_info, $hop_no, $stat;
	if ($ho_val == '==RECOGNIZED==') return $ho_val;  // already recognized, so go back

	for ($c=0; $c < count($know[$know_gr]['srch']); $c++) {  // for each regular expression in the group ...
		$regex = '/'.$know[$know_gr]['srch'][$c].'/'.$know[$know_gr]['uppe'][$c];  // build regex string. Add i for search case insensitive (uppe)
		$ho_val_prev = $ho_val;
    // do_control('vR~', '', $regex, '', '');
		if (preg_match($regex, $ho_val, $elem)) {  // check if we have something to do
			$ho_val = preg_replace($regex, $know[$know_gr]['repl'][$c], $ho_val);
			if ($ho_val_prev <> $ho_val) {
				// do_control('vR~', '', $regex, '', '');
				// do_control('vR^', $know[$know_gr]['mode'][$c], $ho_val_prev, '', $ho_val);
				// do_control('vRv', '', $know[$know_gr]['writ'][$c], '', '****');
			}
			if ($know[$know_gr]['writ'][$c] > '') {  // use the variables given with the regex string
			  $vars = explode(';', $know[$know_gr]['writ'][$c]);
			  for ($c1=0; $c1<count($vars); $c1++) {
			    list($var, $val) = explode("=", $vars[$c1]);
					switch($var) { // store the information recognized
						case 'AUFBEWAHRUNG':
							switch ($val) {
							case '$1': // increment by 1
								$hop_info[$hop_no][$var]=$elem[1];
								// do_control('vRn', $var, $hop_info[$hop_no][$var], '', '$1');
								break;
							default:
								$hop_info[$hop_no][$var]=$val;
							}	  
						  break;
						case 'NSER':  // NF = nSer
							switch ($val) {
							case 'NF++': // increment by 1
								$hop_info[$hop_no][$var]++;
								// do_control('vRn', $var, $hop_info[$hop_no][$var], '', '++');
								break;
							case '$1': // increment by 1
								$hop_info[$hop_no][$var]=$elem[1];
								// do_control('vRn', $var, $hop_info[$hop_no][$var], '', '$1');
								break;
							default:
								$hop_info[$hop_no][$var]=1;
						}
						// do_control('vRn', $var, $hop_info[$hop_no][$var], '', '??');
						break; // end NF++
						case 'UNIT':
							if (isset($hop_info[$hop_no][$var])) $hop_info[$hop_no][$var].= '; '.$val; else $hop_info[$hop_no][$var] = $val;
							break;
						default: $hop_info[$hop_no][$var] = $val;
							break;
					}
					// do_control('vRV', $var, $hop_info[$hop_no][$var], '', '**');
			  }
			}
			if (substr($know[$know_gr]['mode'][$c],0,3) == 'MDL') {  // recognize data in MDL
			  $mdl = substr($know[$know_gr]['mode'][$c],4);  // ex: MDL V(JJJJ)
			  $mdl = str_replace('-','(HY)', $mdl); // for better handling of "-"
			  $pom = preg_split("/[^A-Z0-9]+/", $mdl.' X');  // split model into it's parts
			  if ($pom[0] == '') array_shift($pom);
			  array_pop($pom); // remove last element X
			  if (count($elem) > 1) array_shift($elem);  // remove hop entry at [0] (is whole string)
			  // prepare output  volB1 volB2 yearE1 yearE2 etc.
				$count['B'] = array ('vo' => 1, 'ye' => 1, 'he' => 1, 'mo' => 1, 'xx' => 1 );  // init B counter for every element
				$count['E'] = array ('vo' => 1, 'ye' => 1, 'he' => 1, 'mo' => 1, 'xx' => 1 );  // init E counter for every element
			  $phase = 'B'; // Format receiving field variables. Set B for begin
			  for($c2=0; $c2<count($pom); $c2++) {
			    switch ($pom[$c2]) {
			      case 'V'   : $pom[$c2] = sprintf("%s%s%d", 'vo', $phase, $count[$phase]['vo']++); break;
			      case 'VE1' : $pom[$c2] = sprintf("%s%s%d", 'vo', 'E',    '1'                   ); break;
			      case 'N'   : $pom[$c2] = sprintf("%s%s%d", 'he', $phase, $count[$phase]['he']++); break;
			      case 'JJJJ': $pom[$c2] = sprintf("%s%s%d", 'ye', $phase, $count[$phase]['ye']++);	break;
			      case 'JJ'  : $pom[$c2] = sprintf("%s%s%d", 'ye', $phase, $count[$phase]['ye']++);	break;
			      case 'TT'  : $pom[$c2] = sprintf("%s%s%d", 'da', $phase, $count[$phase]['ta']++);	break; // ### Field not exists. Ok?
			      case 'MM'  : $pom[$c2] = sprintf("%s%s%d", 'mo', $phase, $count[$phase]['mo']++);	break;
			      case 'm'   : $pom[$c2] = sprintf("%s%s%d", 'mo', $phase, $count[$phase]['mo']++);	break;
			      case 'HY'  : 
						  $elem_hy = array('-'); array_splice($elem, $c2, 0, $elem_hy); // insert - at HY position
							$phase = 'E';  // Set E for End after reaching HY
							break;
						default    : $pom[$c2] = sprintf("%s%s%d", $pom[$c2], '_', '0'); break;
					}
					if ($pom[$c2] > '') $hop_info[$hop_no][$pom[$c2]] =	$elem[$c2];
			  }
				// do_control('MDL', $mdl, implode('|', $pom), '', implode('|', $elem));
			}
			if ($ho_val == '') {
			  $ho_val = '==RECOGNIZED==';
				isset($stat['Z_RECOGNIZED']) ? $stat['Z_RECOGNIZED']++ : $stat['Z_RECOGNIZED']=1;
			}
			collect_proc_info($hop_info, $know[$know_gr]['mode'][$c], $hop_no, $ho_val, $elem[0]);
			// do_control('vR+', $know[$know_gr]['mode'][$c], $ho_val_prev, '', '|'.$ho_val.'|   {'.$know[$know_gr]['writ'][$c].')');
		} else {
			// do_control('vR-', $know[$know_gr]['mode'][$c], $ho_val_prev, '', $ho_val);
    }
	}
	//echo "@:"; print_r($hop_info[$hop_no]); echo ":@"; 
  return $ho_val;
}

// ------------------------------------------------------------------------
function cut_holding_equivalent($hop) {
// ------------------------------------------------------------------------
	global $hop_info;
	if ($equ_list = preg_split("/ *= */", $hop)) {
		$hop_prev = $hop;
		$hop = array_shift($equ_list);
		// do_control('EQU', '', $hop_prev, '', $hop.'  {'.implode('|', $equ_list).'}');
	}
	return $hop;
}

// ------------------------------------------------------------------------
function collect_proc_info($hop_info, $model, $hop_no, $hop, $trigger) {
// ------------------------------------------------------------------------
  global $stat, $hop_info;
  // $trigger: what remains for recognition ????
  $hop_info[$hop_no]['type'] = $model;
	if (substr($model,0,3) == 'MDL') $model_s = 'T_'.$model; else $model_s = 'S_'.$model;
  isset($stat[$model_s]) ? $stat[$model_s]++ : $stat[$model_s] = 1;
  if (strcmp($hop,'_VOID_') == 0) // if $hop has been recognized as _VOID_
		isset($stat['Z_RECOGNIZED']) ? $stat['Z_RECOGNIZED']++ : $stat['Z_RECOGNIZED'] = 1;  // _VOID_ is ==RECOGNIZED==
  isset($hop_info[$hop_no]['proc']) ? $hop_info[$hop_no]['proc'] .= $model.": '".$trigger."' {".$hop."}| " : $hop_info[$hop_no]['proc'] = $model.": '".$trigger."' {".$hop."}| ";
  // do_control('STA', $model, $stat[$model_s], '', '');
}

// ------------------------------------------------------------------------
function reformat_val2($val1, $val2) {
// ------------------------------------------------------------------------
  if (strlen($val2) > 0) {
    $lng = strlen($val1) - strlen($val2);
    $valPrefix = substr($val1,0,$lng);
    if (substr($val1,$lng,strlen($val2)) > $val2) {
      $valPrefix++;
    }      
    if ($lng > 0) $val2 = substr($valPrefix,0,$lng).$val2;
  }
	// 1899-00
  return $val2;
}

// ------------------------------------------------------------------------
function convert_month($month) {
// ------------------------------------------------------------------------
// convert month string to something useful
  if (isset($month)) {
  switch ($month) {
    // season
    case (preg_match("/^(Frühling)$/", $month, $elem) ? true : false) :                     $month = 'fr';      break;
    case (preg_match("/^(Sommer)$/", $month, $elem) ? true : false) :                                 $month = 'so';      break;
    case (preg_match("/^(Herbst)$/", $month, $elem) ? true : false) :                                 $month = 'he';      break;
    case (preg_match("/^(Winter)$/", $month, $elem) ? true : false) :                                 $month = 'wi';      break;
    // semester
    case (preg_match("/^(Sommersemester|Sommerhalbjahr|S\.-S\.|S\.S\.|SS|SH)$/", $month, $elem) ? true : false) : $month = 'SS';  break;  
    case (preg_match("/^(Wintersemester|Winterhalbjahr|W\.-S\.|W\.-S\.|WS|WH)$/", $month, $elem) ? true : false): $month = 'WS';  break;
    // month
    case (preg_match("/^(January|Januar|gennaio|Jan\.?)$/", $month, $elem) ? true : false):                   $month = '01';      break;
    case (preg_match("/^(February|Februar|février|Feb\.?)$/", $month, $elem) ? true : false):                 $month = '02';      break;
    case (preg_match("/^(March|März|Mrz\.?)$/", $month, $elem) ? true : false):  $month = '03';      break;
    case (preg_match("/^(April|Apr\.?)$/", $month, $elem) ? true : false) :                           $month = '04';      break;
    case (preg_match("/^(May|Mai)$/", $month, $elem) ? true : false) :                                $month = '05';      break;
    case (preg_match("/^(June|Juni|Jun\.?)$/", $month, $elem) ? true : false) :                       $month = '06';      break;
    case (preg_match("/^(July|Juli|juillet|Jul\.?)$/", $month, $elem) ? true : false) :               $month = '07';      break;
    case (preg_match("/^(August|Aug\.?)$/", $month, $elem) ? true : false) :            $month = '08';      break;
    case (preg_match("/^(September|Sept\.?|Sep\.?)$/", $month, $elem) ? true : false):  $month = '09';      break;
    case (preg_match("/^(October|Oktober|Okt\.?|Oct\.?)$/", $month, $elem) ? true : false): $month = '10';  break;
    case (preg_match("/^(November|Nov\.?)$/", $month, $elem) ? true : false) :          $month = '11';      break;
    case (preg_match("/^(Dezember|December|Dec\.?|Dez\.?)$/", $month, $elem) ? true : false) :  $month = '12'; break;
    default:	                                                                          $month = "??";      break;
    }
    return $month;
  }
}

// ------------------------------------------------------------------------
function save_LN($fld, $ho_val) {
// ------------------------------------------------------------------------
  // change ; to , within [L=...] or [N=...]
  global $hol_info, $hop_info, $hop_no, $ho_val_prev;
  $know['L=N='] = "/^(.*) *(\[[NL]=[^\]]+\]) *(.*)$/";
  if (preg_match($know['L=N='], $ho_val, $elem)) {
    $elem[2] = preg_replace("/=/", '~', $elem[2]);  // replace 
    $elem[2] = preg_replace("/;/", '|', $elem[2]);  // replace
    $elem[2] = preg_replace("/\[/", '{', $elem[2]); // replace [] by {}
    $elem[2] = preg_replace("/\]/", '}', $elem[2]); // replace [] by {}
    $ho_val  = $elem[1].$elem[2].$elem[3];
    $hol_info['L=N='] = $elem[2];   // collect info about holdings
    collect_proc_info($hop_info, $fld, $hop_no, $ho_val, $elem[1]);
    // do_control('LN1', $fld, $ho_val_prev, '',$ho_val);
  }
  if (preg_match($know['L=N='], $ho_val, $elem)) {   // do it twice for second [.=...]
    $elem[2] = preg_replace("/=/", '~', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/;/", '|', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/\[/", '{', $elem[2]); // replace [] by {}
    $elem[2] = preg_replace("/\]/", '}', $elem[2]); // replace [] by {}
    $ho_val = $elem[1].$elem[2].$elem[3];
    $hol_info['L=N='] = $elem[2];   // collect info about holdings
    collect_proc_info($hop_info, $fld, $hop_no, $ho_val, $elem[1]);
    // do_control('LN2', $fld, $ho_val_prev, '',$ho_val);
  }
  // correct missing ]   14 rows
  if (preg_match("/\[[LN]=/", $ho_val, $elem)) $ho_val .= ']';
  if (preg_match($know['L=N='], $ho_val, $elem)) {   // do it twice for second [.=...]
    $elem[2] = preg_replace("/=/", '~', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/;/", '|', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/\[/", '{', $elem[2]); // replace [] by {}
    $elem[2] = preg_replace("/\]/", '}', $elem[2]); // replace [] by {}
    $ho_val = $elem[1].$elem[2].$elem[3];
    $hol_info['L=N='] = $elem[2];   // collect info about holdings
    collect_proc_info($hop_info, $fld, $hop_no, $ho_val, $elem[1]);
    // do_control('LN3', $fld, $ho_val_prev, '',$ho_val);
  }
  return $ho_val;
}

// ------------------------------------------------------------------------
function show_statistics() {
// ------------------------------------------------------------------------
  global $stat;
  printf("\n\n************ %s: *********************\n", "Statistics");
  $group_prev = '';
  ksort($stat);
  foreach ($stat as $key => $val) {
    $group = substr($key,0,1);
    if ($group <> $group_prev)
      switch ($group) {
        case 'A' : printf("General Information:\n"); break;
        case 'S' : printf("Executed recognition steps:\n"); break;
        case 'T' : printf("Recognized Patterns:\n"); break;
        case 'Z' : printf("Recognition state:\n"); break;
      }
	$percentage = 100/$stat['A_Parts of holdings'] * $val;
    printf("  %-35s : %6d  %3d%%\n", substr($key,2), $val, $percentage);
    $group_prev = $group;
  }
}

// ------------------------------------------------------------------------
function write_to_screen() {
// ------------------------------------------------------------------------
  // show normalized string on screen
  global $hol_nrm;
	printf("NRM: %s\n", $hol_nrm);
}	

// ------------------------------------------------------------------------
function acquire_knowledge($use, $priority, $model) {
// ------------------------------------------------------------------------
  //acquire knowledge from database
  global $con, $do_show_know;
  $know = array();
	if ($use == '')
    $query = "SELECT use, prio, model, srch_a, srch, repl, upper, write_val FROM hol_values WHERE model ~* '$model' ORDER BY prio, model";
	else
    $query = "SELECT use, prio, model, srch_a, srch, repl, upper, write_val FROM hol_values WHERE model ~* '$model' AND use='$use' AND prio = '$priority' ORDER BY prio, model";
  pg_query($con, $query) or die("Cannot execute \"$query\"\n");
  $result = pg_query($con, $query); if (!$result) { echo 'Error executing '.$query."\n"; exit; }
  $know['uses'] = pg_fetch_all_columns($result, 0);
  $know['prio'] = pg_fetch_all_columns($result, 1);
  $know['mode'] = pg_fetch_all_columns($result, 2);
  $know['srca'] = pg_fetch_all_columns($result, 3);
  $know['srch'] = pg_fetch_all_columns($result, 4);
  $know['repl'] = pg_fetch_all_columns($result, 5);
  $know['uppe'] = pg_fetch_all_columns($result, 6);
  $know['writ'] = pg_fetch_all_columns($result, 7);
  if ($do_show_know) {
    printf("\n*** %s - Criteria: %s|%s|%s|\n", 'Show Knowledge', $use, $priority, $model);
    for ($c=0; $c < sizeof($know['uses']); $c++) {
      printf("%1s %1s %1s %-30s : %-80s %30s   (%s)\n", $know['uses'][$c], $know['prio'][$c], $know['uppe'][$c], $know['mode'][$c], $know['srch'][$c], $know['repl'][$c], $know['writ'][$c]);
		}
  }
  return $know;
}

// ------------------------------------------------------------------------
function do_know_control($marker) {
// ------------------------------------------------------------------------
  global $do_control, $do_show_know, $fld, $know, $repl, $upper, $write_val;
  if ($do_show_know) {
    printf("%-3s %-25s\n", $marker, $fld);
    for ($c=0; $c < sizeof($know[$fld]); $c++) {
      // printf("    %02d %s\n", $c, $know[$fld][$c]);
      printf("    %02d P:%-30s R:%-30s U:%1s W:%s\n", $c, 
	  $know[$fld][$c], 
	  $repl[$fld][$c], 
	  $upper[$fld][$c], 
	  $write_val[$fld][$c]);
    }
  }
}


// ------------------------------------------------------------------------
function normalize_result($hop_info) {
// ------------------------------------------------------------------------
  // normalize every hop. Pattern: VVVVvvvvYYYYyyyyVVVVvvvvYYYYyyyyIO
  $hol_nrm = array();
  $size = sizeof($hop_info);
  for ($i=0; $i < $size; $i++) {
		// write normalized string
		$hol_nrm[$i] = sprintf("%4s%4s%4s%4s%1s%4s%4s%4s%4s%1s%1s",
			substr('    '.(isset($hop_info[$i]['voB1'])?$hop_info[$i]['voB1']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['voB2'])?$hop_info[$i]['voB2']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['yeB1'])?$hop_info[$i]['yeB1']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['yeB2'])?$hop_info[$i]['yeB2']:'    '),-4,4),
			substr(   ' '.(isset($hop_info[$i]['hy']  )?$hop_info[$i]['hy']  :' '   ),-1,1),
			substr('    '.(isset($hop_info[$i]['voE1'])?$hop_info[$i]['voE1']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['voE2'])?$hop_info[$i]['voE2']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['yeE1'])?$hop_info[$i]['yeE1']:'    '),-4,4),
			substr('    '.(isset($hop_info[$i]['yeE2'])?$hop_info[$i]['yeE2']:'    '),-4,4),
			substr(   ' '.(isset($hop_info[$i]['ICPL'])?$hop_info[$i]['ICPL']:'    '),-1,1),
			substr(   ' '.(isset($hop_info[$i]['ONLINE'])?$hop_info[$i]['ONLINE']:'  '),-1,1));
	}
	// var_dump(substr('    '.(isset($hop_info[0]['voB1'])?$hop_info[0]['voB1']:'    '),-4,4));
	// var_dump($hop_info);
	// var_dump($hol_nrm);
	return implode(';',$hol_nrm);
}

// ------------------------------------------------------------------------
function dt_diff($date1, $date2) {
// ------------------------------------------------------------------------
  // show time elapsed
	return $diff = abs(strtotime($date2) - strtotime($date1));
	return sprintf("%d years, %d months, %d days\n", $years, $months, $days, $hours, $mins, $secs);
}


?>
