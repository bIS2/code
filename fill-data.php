<?php
	error_reporting(0);
	$conn_string = "host=localhost port=5433 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
	$conn = pg_connect($conn_string) or die('ERROR!!!');

	$result = pg_query($conn, "SELECT * FROM hol_out ORDER BY sys1 ASC, sys2 ASC");
	if (!$result) {
	  die("Error connecting to database.");
	}
	$i 	= 0;
	$count 	= 0;
	$syskey = '';

	while ($bi = pg_fetch_row($bisresult)) {
		echo $bi['sys1'].'->';
		if ($syskey != $bi['sys1']) {
			if ($syskey != '')  {
				echo 'Actualizo count in BD';
				$query = 'UPDATE holdingssets1 SET holdings_numbers='.$count.' WHERE id = '.$holdingsset_id;
				$result = pg_query($conn, $query)  or die('error');
			}
			$syskey = $bi['sys1'];
			echo 'NUEVO GRUPO...';
			// CREO UN NUEVO GRUPO E INSERTO
			$query = 'INSERT INTO holdingssets1 VALUES ("'.$bi['sys1'].'","'.$bi['f245a'].'","'.$bi['ptrn'].'",'.$bi['f008x'].',0,0)';
			$result = pg_query($conn, $query) or die('error');
			$holdingsset_id = pg_last_oid($result);
			$count = 0;
		}
		$count++;
		// INSERT ITEM IN HOL_BIS
		$query = 'INSERT INTO holdings1 VALUES 
		(
			'.$holdingsset_id.',
			0,
			"'.$bi['sys2'].'",
		   '.$bi['g'].',
			"'.$bi['f022a'].'",
			"'.$bi['f245a'].'",
			"'.$bi['f245b'].'",
			"'.$bi['f245c'].'",
			"'.$bi['f260a'].'",
			"'.$bi['score'].'",
			"'.$bi['flag'].'",
			"'.$bi['f260b'].'",
			"'.$bi['f310a'].'",
			"'.$bi['f710a'].'",
			"'.$bi['f780t'].'",
			"'.$bi['f785t'].'",
			"'.$bi['f852b'].'",
			"'.$bi['hol_nrm'].'",
			"'.$bi['probability'].'",
			"'.$bi['f008x'].'",
			"'.$bi['f008y'].'",
			"'.$bi['f362a'].'",
			"'.$bi['f866a'].'",
			"'.$bi['f866z'].'",
			"'.$bi['f852h'].'",
			"'.$bi['i'].'",
			"'.$bi['is_owner'].'",
			"'.$bi['ptrn'].'",
			"'.$bi['ocrr_ptrn'].'",
			"'.$bi['aux_ptrn'].'",
			"'.$bi['j_ptrn'].'",
			"'.$bi['weight'].'",
			"'.$bi['ocrr_nr'].'",
			"'.$bi['is_aux'].'",			
			"'.$bi['pot_owner'].'",
			"'.$bi['hbib'].'",
			"'.$bi['f246a'].'",
			"'.$bi['f300a'].'",
			"'.$bi['f300b'].'",
			"'.$bi['f300c'].'",
			"'.$bi['f500a'].'",
			"'.$bi['f505a'].'",
			"'.$bi['f770t'].'",
			"'.$bi['f772t'].'",
			"'.$bi['f852a'].'",
			"'.$bi['f852j'].'",
			"'.$bi['f866c'].'",
			"'.$bi['f866h'].'",
			"'.$bi['exists_online'].'",
			"'.$bi['is_current'].'",
			"'.$bi['has_incomplete_vols'].'",			
			0.0,
			false,
			false,
			"'.$bi['f866a'].'",
			0
			)';
		$result = pg_query($conn, $query) or die('error');
	}
?>
