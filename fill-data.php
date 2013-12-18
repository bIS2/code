<?php
	error_reporting(0);
	$conn_string = "host=localhost port=5433 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
	
	$conn = pg_connect($conn_string) or die('ERROR!!!');

	$resultbis = pg_query($conn, "SELECT * FROM hol_out WHERE sys1 <> '' ORDER BY sys1 ASC, sys2 ASC");
	if (!$resultbis) {
	  die("Error connecting to database.");
	}
	$i = 0;
	$count 	= 0;
	$syskey = '';
	$j = 0;
	while ($bi = pg_fetch_assoc($resultbis)) {
		$j++;
		// var_dump($bi);
		// echo $bi['sys1'].'->';
		if ($syskey != $bi['sys1']) {
			// if ($syskey != '')  {
			// 	echo 'Actualizo count in BD';
			// 	$query = "UPDATE holdingssets1 SET holdings_number=".$count." WHERE id = ".$i;
			// 	echo '<br><br>'.$query.'<br><br>';
			// 	$result = pg_query($conn, $query) or die(pg_last_error().'couting');
			// }
			$i++;
			$syskey = $bi['sys1'];
			$cero = 0;
			// CREO UN NUEVO GRUPO E INSERTO
			$query = "INSERT INTO holdingssets1 (id, sys1, f245a, ptrn, f008x, holdings_number, groups_number) VALUES 
			(
				".$i.",
				'".pg_escape_string(addslashes($bi['sys1']))."',
				'".pg_escape_string(addslashes($bi['f245a']))."',
				'".pg_escape_string(addslashes($bi['ptrn']))."',
				'".pg_escape_string(addslashes($bi['f008x']))."',
				".$cero.",
				".$cero."
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
		$query = "INSERT INTO holdings1
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
f866aupdatedby
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
			'".$bi['weight']."',
			'".$bi['ocrr_nr']."',
			'".$is_aux."',			
			'".$pot_owner."',
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
			".$cero."
			)";

		$result = pg_query($conn, $query) or die(pg_last_error().$query);
	}
?>
