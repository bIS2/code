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
			$holdingsset_id = pg_last_oid($result);
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
			'""',
			'".pg_escape_string(addslashes($bi['f072a']))."'
			)";

		$result = pg_query($conn, $query) or die(pg_last_error().$query);
	}
?>
