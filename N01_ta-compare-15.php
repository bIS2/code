<?php
/*
Project:   SP2
Function:
  Compare a TA to all other TA by similarity
	Check if a TA has as frequent title (pe. Bulletin;  Jahresbericht)
	Adapt rough similarity values to it's conditions an weight it
	Classify similiar TA by: = equal (!!) ; * = similar ; - = uncertain or probably not similar

DevNotes:
  2012-08-28            Start
  ...
	2013-12-05 Adaptaded to bIS
*/

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1');
//ini_set('max_execution_time', 24000); // 24000 seconds = 40 Min
date_default_timezone_set('America/New_York');
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
$date_start = $date = new DateTime('now', new DateTimeZone('America/New_York'));

$offset           = 0;
$limit            = 10000000;

// command line arguments : 0 = <program>; 1 = <sys>; 2 = weight model (0,1) ; 3 = options
/* $argv = array ( 
	 0 => 'ta-compare-nn.php',
	 1 => 'offset+limit',
	 2 => '<weight_model>'
	 3 => 'debug+show+time+table+drop'
); */

if (! isset($argv[1])) { printf("Usage: %s <sys>[+<modelnumber>] <options>", $argv[0]); exit; }
else {
  $argv_program = $argv[0];
  // receive sys (= sys)
//  if (isset($argv[1])) $sys = $argv[1];  // use this later. DOS window allows only 2 argv parameters !!!
  if (isset($argv[1])) list($offset, $limit) = explode('+', $argv[1]);
	// put options into $proc_flag
  if (isset($argv[2])) $argv_option = explode('+', $argv[2]); else $argv_option = array();
  $proc_flag = array('debug' => false, 'show' => false, 'test' => false, 'time' => false, 'table' => false, 'create' => false); foreach ($argv_option as $option) $proc_flag[$option] = true;
}

if ($proc_flag['debug']) {
  printf("RUN %s OPTION: %s\n", $argv_program, implode(';', $argv_option));
  printf("    Parameters:\n");
  printf("    %-6s: %-6s\n",'offset',$offset);
  printf("    %-6s: %-6s\n",'limit', $limit);
  printf("    %-6s: %-6s\n",'show' , $proc_flag['show']);
  printf("    %-6s: %-6s\n",'time' , $proc_flag['time']);
  printf("    %-6s: %-6s\n",'debug', $proc_flag['debug']);
  printf("    %-6s: %-6s\n",'create', $proc_flag['create']);
}

// measure execution time
if ($proc_flag['time']) {
  $date_start = $date = new DateTime('now', new DateTimeZone('America/New_York'));
  printf("Starting: %s\n", $date_start->format('Y-m-d_His'));
}

// connect to postgres server
require_once 'db-connection.php';

// initialize variables
$ta_sim_name      = 'ta_sim';    // result table
$select_fld       = 'sys,f022a,f245a,f245a_e,f245b_e,f245c_e,f_tit_e,f260a_e,f260b_e,f310a_e,f362a_e,f710a_e,f780t_e,f785t_e,f008x,f008y';  // fields 
$fld_ta           = array();         // field list of table ta
$ta_sim_fields    = '';              // fields for ta_sim_test
$fld_sim          = array();         // field list of table_cmp
$freq_tit         = get_freq_tit();  // fill $tit_freq with all frequent titles
$weight_model     = 0;               // general weight model
$fld_weight_model = array();         // model list of weights for every field
$fld_weight       = array();         // currently used list of weights for every field
$max_score        = 0;               // remember top score
$treshold_score   = 45;              // discriminating similar and different   !!! recheck this value 
$is_freq_tit      = false;           // remember if a title is a frequent title defined in tit_freq
//$mult_f022a     = ' ';             // mark ISSN if there are several
$rno              = 0;               // records number
$sys_reference    = '';              // 
$procedure        = $argv[0];
//$sys_compared     = array();         // collect all sys1 o sys2 that already have been put into sets

// prepare list of fields to be used for comparison
read_fieldlist();                    // create $fld_ta

// create resulting table
//create_table($ta_sim_name);


if ($proc_flag['create']) {
  printf("Table $ta_sim_name truncated.\n");
  create_table($ta_sim_name);
}


// get records to compare
$query = "SELECT sys FROM ta ORDER by sys OFFSET $offset LIMIT $limit";
printf("SEL: %s\n", $query);
$result = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n"; exit; }
$ta_res = pg_fetch_all($result);
printf("Records found: %s\n", sizeof($ta_res));

$date_start_cycle = new DateTime('now', new DateTimeZone('America/New_York'));

// ------------------------------- for every record found
for ($recno = 0; $recno < sizeof($ta_res); $recno++) {
   if ($recno % 100 == 0) echo $recno.'/';

   $bib_sys_ref = $ta_res[$recno]["sys"];

  // compare - if not already compared: compare ta with all in table ta
  $query = "SELECT sys2 FROM $ta_sim_name WHERE sys2 = '$bib_sys_ref'";
  $result_cmp = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n"; exit; }
  $ta_sys_compared = pg_fetch_array($result_cmp);
  if (sizeof($ta_sys_compared['sys2']) == 0) {
  	compare($bib_sys_ref);
    $ta_sim = array();
	for ($rno = 0; $rno < sizeof($ta_res_sim); $rno++) {  // for all found
		// write to table
        $ta_sim  = &$ta_res_sim[$rno];
	    if ($proc_flag['table']) {
	      if ($ta_sim['flag'] <> '-') {
		    $bib_sys_cur = $ta_sim["sys"];
		    $query  = "INSERT INTO $ta_sim_name (sys1, sys2, score, flag, upd) VALUES";
		    $query .= "('".$bib_sys_ref."'".",'".$bib_sys_cur."',".$ta_sim["score"].",'".$ta_sim["flag"]."', current_timestamp);";
	        //printf("QUERY: %s\n", $query);
		    $result = pg_query($con, $query); if (!$result) echo "Error executing".$query."\n";
		      // remember that
	        $sys_compared[$bib_sys_cur] = true;
	     }
	   }
    	// print results
	    if ($proc_flag['show'])
	      printf("%2d%s%s-%s %-12s %-37s %-20s %-35s\n",
				substr($ta_sim['score'],0,2), 
				$ta_res_sim[$rno]['flag'], 
				$sys_reference, 
				$ta_sim['sys'], 
				substr($ta_sim['f022a'],0,12),
				substr($ta_sim['f245a'],0,37),
				substr($ta_sim['f362a_e'],0,20),
				substr($ta_sim['f710a'],0,35)
	      );
	    }
	}
}

if ($proc_flag['time']) {
  $proc_info['time_used'] = show_time($date_start_cycle);
  //echo " TIME comp: ".show_time($date_start_cycle);
  echo "\nTOTAL TIME: ".show_time($date_start);
}

// ***********************************************
function compare($sys) {
// ***********************************************
    global $con, $select_fld, $ta, $fld_ta, $ta_res_sim, $proc_flag, $rno, $freq_tit, $fld_weight_model, $ta_sim_fields, $fld_sim, $freq_tit,
    $fld_weight_model, $fld_weight, $max_score, $treshold_score, $is_freq_tit, $proc_flag, $sys_reference;

	// get reference record
	$query = "SELECT ".$select_fld." FROM ta WHERE sys = '$sys'";
	$result = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n"; exit; }
	$tas = pg_fetch_all($result);
	$ta = $tas[0];


	// ************************************************
	// COMPARE WITH OTHERS
	// ************************************************
	$is_freq_tit = false;  // later set to true of title occurs many times
	// initialize collect process information
	$proc_info = array('equ' => 0, 'sim' => 0, 'try' => 0, 'dif' => 0, 'AGKB' => 0, 'BSUB' => 0, 'LUZB' => 0, 'SGHG' => 0, 'ZHUZ' => 0, 'ZHZB' => 0);

	// get current time for process measurement
	if ($proc_flag['time']) $date_start_cycle = new DateTime('now', new DateTimeZone('America/New_York'));

	$sys_reference = $ta['sys']; // ex. bib_sys
		
	// **** check if is_tit_freq  
	// break f245a the same way as the titles in tit_freq
	$query = "SELECT regexp_split_to_array(lower('".pg_escape_string($ta['f245a'])."'), E'[\- \.,:;\(\){}\"\']+') f245a_s";
	$result = pg_query($con, $query); if (!$result) echo "Error executing".$query."\n";
	$tit = pg_fetch_all($result);
	$tit = substr($tit[0]['f245a_s'], 1, strlen($tit[0]['f245a_s'])-2);  // cut ()
	$tit = implode(' ', explode(',',$tit));
	$tit = str_replace(" \"\"", "", $tit);
	$tit = preg_replace("/[\[\]]/", "", $tit);
	if (in_array($tit, $freq_tit)) { // check if normalized title is a frequent title
		$is_freq_tit = true;
		if ($proc_flag['debug']) echo " !! FREQ(".$ta['f245a'].")\n";
	}

	// create comparison query. If value is '' the result will be 0, so we do not compare this field
	$query  = "SELECT sys,";
	$query .= "\n f022a,         "; ($ta['f022a']   > '') ? $query .= " similarity(f022a,  '".pg_escape_string($ta['f022a'])."'  ) s_f022a," : $query .= " 0::integer s_f022a,";
	$query .= "\n f245a, f245a_e,"; ($ta['f245a_e'] > '') ? $query .= " similarity(f245a_e,'".pg_escape_string($ta['f245a_e'])."') s_f245a," : $query .= " 0::integer s_f245a,";
	$query .= "\n f245b, f245b_e,"; ($ta['f245b_e'] > '') ? $query .= " similarity(f245b_e,'".pg_escape_string($ta['f245b_e'])."') s_f245b," : $query .= " 0::integer s_f245b,";
	$query .= "\n f245c,         "; ($ta['f245c_e'] > '') ? $query .= " similarity(f245c_e,'".pg_escape_string($ta['f245c_e'])."') s_f245c," : $query .= " 0::integer s_f245c,";
	$query .= "\n f_tit,         "; ($ta['f_tit_e'] > '') ? $query .= " similarity(f_tit_e,'".pg_escape_string($ta['f_tit_e'])."') s_f_tit," : $query .= " 0::integer s_f_tit,";
	$query .= "\n f260a, f260a_e,"; ($ta['f260a_e'] > '') ? $query .= " similarity(f260a_e,'".pg_escape_string($ta['f260a_e'])."') s_f260a," : $query .= " 0::integer s_f260a,";
	$query .= "\n f260b,         "; ($ta['f260b_e'] > '') ? $query .= " similarity(f260b_e,'".pg_escape_string($ta['f260b_e'])."') s_f260b," : $query .= " 0::integer s_f260b,";
	$query .= "\n f310a,         "; ($ta['f310a_e'] > '') ? $query .= " similarity(f310a_e,'".pg_escape_string($ta['f310a_e'])."') s_f310a," : $query .= " 0::integer s_f310a,";
	$query .= "\n f362a, f362a_e, similarity(
		array_to_string(regexp_split_to_array(f362a_e, E'[^0-9]+'),';','*'),
		array_to_string(regexp_split_to_array('".pg_escape_string($ta['f362a_e'])."', E'[^0-9]+'),';','*')) s_f362a,";
	$query .= "\n f710a, f710a_e,"; ($ta['f710a_e'] > '') ? $query .= " similarity(f710a_e,'".pg_escape_string($ta['f710a_e'])."') s_f710a," : $query .= " 0::integer s_f710a,";
	$query .= "\n f780t, f780t_e,"; ($ta['f780t_e'] > '') ? $query .= " similarity(f780t_e,'".pg_escape_string($ta['f780t_e'])."') s_f780t," : $query .= " 0::integer s_f780t,";
	$query .= "\n f785t, f785t_e,"; ($ta['f785t_e'] > '') ? $query .= " similarity(f785t_e,'".pg_escape_string($ta['f785t_e'])."') s_f785t," : $query .= " 0::integer s_f785t,";
	$query .= "\n f008x,         "; ($ta['f008x']   > '') ? $query .= " similarity(f008x  ,'".pg_escape_string($ta['f008x'])  ."') s_f008x," : $query .= " 0::integer s_f008x,";
	$query .= "\n f008y,         "; ($ta['f008y']   > '') ? $query .= " similarity(f008y  ,'".pg_escape_string($ta['f008y'])  ."') s_f008y"  : $query .= " 0::integer s_f008y";
	$query .= "\n FROM ta";
	if ($is_freq_tit) { // for frequent titles include filters
		$query .= "\n  WHERE similarity(f245a_e,'".pg_escape_string($ta['f245a_e'])."') = 1";  // same title
		if (($ta['f710a_e'] > '') and ($ta['f245c_e'] > '')) {
 			$query .= " AND (similarity(f710a_e,'".pg_escape_string($ta['f710a_e'])."') > 0.9";  // similiar organisation
			$query .= "\n OR similarity(f245c_e,'".pg_escape_string($ta['f245c_e'])."') > 0.8)";
		} else {
			if (($ta['f710a_e'] >  '') AND ($ta['f245c_e'] == ''))
				$query .= " AND similarity(f710a_e,'".pg_escape_string($ta['f710a_e'])."') > 0.9";  // similiar organisation (710a)
			if (($ta['f710a_e'] == '') AND ($ta['f245c_e'] >  ''))
				$query .= " AND similarity(f245a_e,'".pg_escape_string($ta['f245a_e'])."') > 0.8";  // similar organisation (245c)
		}
	} else {
		$query .= "\n  WHERE similarity(f245a_e,'".pg_escape_string($ta['f245a_e'])."') > 0.6";
		$query .= "\n     OR similarity(f710a_e,'".pg_escape_string($ta['f710a_e'])."') > 0.8";
	}
	$query .= "\n  ORDER BY s_f245a DESC, f245a_e";
//printf("%s\n", $query);
	$result = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n"; exit; }
	$ta_res_sim = pg_fetch_all($result);
	$size_r = sizeof($ta_res_sim);
	if (!$ta_res_sim) $size_r = 0;

	$proc_info['found'] = $size_r;
	if ($proc_flag['debug']) printf(" FOUND: %s\n", $proc_info['found']);


	// -------------------- analyse and optimize result
	for ($rno = 0; $rno < $size_r; $rno++) {
	  // prepare score evaluation
	  $ta_res_sim[$rno]['score']      = 0;
	  $ta_res_sim[$rno]['flag']       = '_'; // initialize with '_'
		adjust_similarity_values();            // correct some 'strange'* comparison values
		analyse_special_fields();              // analyse in detail some fields: f022a, ...
		weight_ta();                           // **** weight similarity of TA
	  if ($ta_res_sim[$rno]['score'] >= $max_score) $max_score = $ta_res_sim[$rno]['score'];  // remember highest score
	}

	if ($proc_flag['debug']) printf("SCORE: %s %s\n", $treshold_score, $max_score); // show SCORE


	// now assign a similarity category for each TA
	for ($rno = 0; $rno < $size_r; $rno++) {
		if ($ta_res_sim[$rno]['score'] >= ($max_score - $treshold_score)) $ta_res_sim[$rno]['flag'] = '*'; else $ta_res_sim[$rno]['flag'] = '-'; // mark treshold
		// adjust categorization of TA if f245a or f710a are nearly equal
		if ($is_freq_tit) { // for frequent title use a stricter comparison
			if (($ta_res_sim[$rno]['s_f245a'] == 1) && ($ta_res_sim[$rno]['s_f710a'] == 1)) $ta_res_sim[$rno]['flag'] = '*'; // must correspond
		} else { // normal case
			if ($ta_res_sim[$rno]['s_f245a'] > 0.9) $ta_res_sim[$rno]['flag'] = '*'; // title must correspond a bit less
		}	
		if ($sys_reference == $ta_res_sim[$rno]['sys']) $ta_res_sim[$rno]['flag'] = '='; // mark original record with '='
	}

	// -------------------- sort results in similarity order
	usort($ta_res_sim, 'cmp_score'); // *** sort result by score type for output
	$last_ta_in_set = last_similar_ta_in_set(); // look for the last TA above the treshold

  usort($ta_res_sim, 'cmp_flag_score'); // *** sort result by flag, score for output
  // return $ta_res_sim;
}

// ***********************************************
function last_similar_ta_in_set() {
// ***********************************************
// get number of last good ta candidate
  global $ta_res_sim;
	$ta_sim_last_good = 0;
	for ($rno = 0; $rno < sizeof($ta_res_sim); $rno++) {
		if ($ta_res_sim[$rno]['flag'] == '*' || $ta_res_sim[$rno]['flag'] == '=') { // included in the result set
			$ta_sim_last_good = $rno;  // remember last selected record
		} else { break; }
	}
	return $ta_sim_last_good;
}

// ***********************************************
function analyse_special_fields() {
// ***********************************************
  // analyse fields with special content
  global $ta_res_sim, $rno, $ta;
  $ta_res_sim[$rno]['s_f008x'] = compare_field('f008x', $ta['f008x']  , $ta_res_sim[$rno]['f008x']);
  $ta_res_sim[$rno]['s_f008y'] = compare_field('f008y', $ta['f008y']  , $ta_res_sim[$rno]['f008y']);
  $ta_res_sim[$rno]['s_f022a'] = compare_field('f022a', $ta['f022a']  , $ta_res_sim[$rno]['f022a']); // check if ISSN contained
  $ta_res_sim[$rno]['s_f260a'] = compare_field('f260a', $ta['f260a_e'], $ta_res_sim[$rno]['f260a_e']);
  $ta_res_sim[$rno]['s_f780t'] = compare_field('f780t', $ta['f780t_e'], $ta_res_sim[$rno]['f780t_e']);
  $ta_res_sim[$rno]['s_f785t'] = compare_field('f785t', $ta['f785t_e'], $ta_res_sim[$rno]['f785t_e']);
}

// ***********************************************
function compare_field ($fld, $valO, $valC) {
// ***********************************************
  // adapt weight for specific fields
  global $ta_res_sim, $rno;
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
function weight_ta() {
// ***********************************************
  // add all weighted scores of each ta found
  global $ta_res_sim, $rno, $weight_model;
  $ta_res_sim[$rno]["score"] += weight_every_fld("f022a", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f245a", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f245c", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f_tit", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f260a", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f260b", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f310a", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f362a", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f710a", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f780t", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f785t", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f008x", $weight_model);
  $ta_res_sim[$rno]["score"] += weight_every_fld("f008y", $weight_model);
}

// ***********************************************
function weight_every_fld($fld, $weight_model) {
// ***********************************************
	// calculate score value for $fld
  global $ta_res_sim, $rno, $fld_weight_model;
	$fld_weight = $fld_weight_model[$weight_model];
	$score_delta = 0;
	$s_fld = 's_'.$fld; // form field name for similarity values
  if ($ta_res_sim[$rno][$s_fld] == 1) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['equ'];          // full match
  else if ($ta_res_sim[$rno][$s_fld] >= 0.8 ) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['sim'];  // presumable match
    else if ($ta_res_sim[$rno][$s_fld] < 0.8 ) $score_delta = $ta_res_sim[$rno][$s_fld] * $fld_weight[$fld]['dif']; // insecure or false match
	return $score_delta;
}


// ***********************************************
function adjust_similarity_values() {
// ***********************************************
  // adjust some fld with 'strange' similarity values
  global $ta_res_sim, $rno;
  if (!isset($ta_res_sim[$rno]['s_f022a']) or $ta_res_sim[$rno]['s_f022a'] == 'NaN') $ta_res_sim[$rno]['s_f022a']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f245a']) or $ta_res_sim[$rno]['s_f245a'] == 'NaN') $ta_res_sim[$rno]['s_f245a']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f245b']) or $ta_res_sim[$rno]['s_f245b'] == 'NaN') $ta_res_sim[$rno]['s_f245b']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f245c']) or $ta_res_sim[$rno]['s_f245c'] == 'NaN') $ta_res_sim[$rno]['s_f245c']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f_tit']) or $ta_res_sim[$rno]['s_f_tit'] == 'NaN') $ta_res_sim[$rno]['s_f_tit']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f260a']) or $ta_res_sim[$rno]['s_f260a'] == 'NaN') $ta_res_sim[$rno]['s_f260a']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f260b']) or $ta_res_sim[$rno]['s_f260b'] == 'NaN') $ta_res_sim[$rno]['s_f260b']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f310a']) or $ta_res_sim[$rno]['s_f310a'] == 'NaN') $ta_res_sim[$rno]['s_f310a']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f362a']) or $ta_res_sim[$rno]['s_f362a'] == 'NaN') $ta_res_sim[$rno]['s_f362a']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f710a']) or $ta_res_sim[$rno]['s_f710a'] == 'NaN') $ta_res_sim[$rno]['s_f710a']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f780t']) or $ta_res_sim[$rno]['s_f780t'] == 'NaN') $ta_res_sim[$rno]['s_f780t']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f785t']) or $ta_res_sim[$rno]['s_f785t'] == 'NaN') $ta_res_sim[$rno]['s_f785t']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f008x']) or $ta_res_sim[$rno]['s_f008x'] == 'NaN') $ta_res_sim[$rno]['s_f008x']  = 0;
  if (!isset($ta_res_sim[$rno]['s_f008y']) or $ta_res_sim[$rno]['s_f008y'] == 'NaN') $ta_res_sim[$rno]['s_f008y']  = 0;
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

// -----------------------------------------------
function get_freq_tit() {
// -----------------------------------------------
// lookup title in frequency list
global $con;
// fill $tit_freq
  $query = "SELECT f245a FROM tit_freq";
  $result = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n".pg_last_error(); exit; }
  $tmp_arr = pg_fetch_all($result);
  foreach ($tmp_arr as $tmp) $freq_tit[] = $tmp['f245a'];
  return $freq_tit;
}


// -----------------------------------------------
function read_fieldlist() {
// -----------------------------------------------
  global $fld_ta, $fld_weight_model, $fld_sim, $cmp_sim_fields;
  // fields of a ta
  $fld_ta = array (
    'sys',      'f008x',    'f008y',    'f008l',    'f008s',
    'f022a',    'f245a',    'f245b',    'f245c',    'f245d',    'f246i',
    'f260a',    'f260b',    'f260c',    'f300c',
    'f310a',    'f362a',    'f500a',
    'f710a',    'f710b',    'f730a',    'f770t',    'f780t',    'f780w',
    'f852a',    'f852h',    'f856u',    'f866a',
    'f949j',    'f949z'
  );
  // fields with the results of every field compared (values 0..1)
  $fld_sim = array (
    's_f008x',  's_f008y',
    's_f022a',  's_f245a',  's_f245b',  's_f245c',  's_f_tit',
    's_f260a',  's_f260b',
    's_f310a',  's_f362a',
    's_f710a',  's_f780a'
  );

  // output fields
  $cmp_sim_fields  = "sys1,sys2, score, flag, f022a,s_f022a, f245a,s_f245a, f245b,s_f245b, f245c,s_f245c, f_tit, s_f_tit"; 
  $cmp_sim_fields .= ",f260a,s_f260a, f260b,s_f260b";
  $cmp_sim_fields .= ",f310a,s_f310a, f362a,s_f362a, f710a,s_f710a, f780t,s_f780t, f785t,s_f785t, f008x, s_f008x, f008y, s_f008y, proc_cmp, run_cmp";
  $cmp_sim_fields .= ", off, no";


  // Weight model with values for match, similar, no-match. Values can be negative
	// balanced weighting
  $fld_weight_model[0] = array (
    'f008x' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
    'f008y' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
    'f022a' => array('equ' => 10, 'sim' =>  0, 'dif' => -6),
    'f245a' => array('equ' => 15, 'sim' =>  1, 'dif' => -3),
    'f245b' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f245c' => array('equ' => 15, 'sim' =>  0, 'dif' =>  0),
    'f_tit' => array('equ' =>  3, 'sim' =>  1, 'dif' =>  0),
    'f260a' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
    'f260b' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
    'f260c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f300c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f310a' => array('equ' =>  5, 'sim' =>  1, 'dif' => -3),
    'f362a' => array('equ' =>  7, 'sim' =>  2, 'dif' => -7),
    'f710a' => array('equ' => 10, 'sim' =>  3, 'dif' => -5),
    'f780t' => array('equ' => 10, 'sim' =>  3, 'dif' =>  0),
    'f785t' => array('equ' => 10, 'sim' =>  3, 'dif' =>  0),
    'f852a' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
  );
	// weights institution (7xx) even more
  $fld_weight_model[1] = array (
    'f008x' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
    'f008y' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
    'f022a' => array('equ' => 10, 'sim' =>  0, 'dif' => -6),
    'f245a' => array('equ' => 10, 'sim' =>  1, 'dif' => -3),
    'f245b' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f245c' => array('equ' => 10, 'sim' =>  0, 'dif' =>  0),
    'f_tit' => array('equ' =>  3, 'sim' =>  1, 'dif' =>  0),
    'f260a' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
    'f260b' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
    'f260c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f300c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f310a' => array('equ' =>  5, 'sim' =>  1, 'dif' => -3),
    'f362a' => array('equ' =>  7, 'sim' =>  2, 'dif' => -7),
    'f710a' => array('equ' => 20, 'sim' =>  3, 'dif' => -5),
    'f780t' => array('equ' => 20, 'sim' =>  3, 'dif' =>  0),
    'f785t' => array('equ' => 20, 'sim' =>  3, 'dif' =>  0),
    'f852a' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
  );  

	// weighting issn (022) more
  $fld_weight_model[2] = array (
    'f008x' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
    'f008y' => array('equ' =>  3, 'sim' =>  0, 'dif' => -3),
    'f022a' => array('equ' => 20, 'sim' =>  0, 'dif' => -6),
    'f245a' => array('equ' => 10, 'sim' =>  1, 'dif' => -3),
    'f245b' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f245c' => array('equ' => 10, 'sim' =>  0, 'dif' =>  0),
    'f_tit' => array('equ' =>  3, 'sim' =>  1, 'dif' =>  0),
    'f260a' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
    'f260b' => array('equ' =>  5, 'sim' =>  3, 'dif' => -2),
    'f260c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f300c' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
    'f310a' => array('equ' =>  5, 'sim' =>  1, 'dif' => -3),
    'f362a' => array('equ' =>  7, 'sim' =>  2, 'dif' => -7),
    'f710a' => array('equ' => 10, 'sim' =>  3, 'dif' => -5),
    'f780t' => array('equ' => 10, 'sim' =>  3, 'dif' =>  0),
    'f785t' => array('equ' => 10, 'sim' =>  3, 'dif' =>  0),
    'f852a' => array('equ' =>  1, 'sim' =>  1, 'dif' =>  0),
  );  
}

function create_table($tab_name) {
  global $con;
  // create table 
  $query  = "DROP TABLE IF EXISTS $tab_name; ";
  $query .= "CREATE TABLE $tab_name (sys1 char(10), sys2 char(10), score integer, flag char(1), upd timestamp)";
  $result = pg_query($con, $query); if (!$result) { echo "Error executing".$query."\n"; exit; }
}

?>