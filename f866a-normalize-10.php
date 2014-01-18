<?php
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

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
ini_set('memory_limit', '-1'); 

$version = '131101 14:12 pgt';

// require_once 'db-connection.inc.php'; // connect to postgres server
$conn_string = "host=localhost port=5433 dbname=bis user=postgres password=postgres+bis options='--client_encoding=UTF8'";
$con = pg_connect($conn_string) or die('ERROR!!!');

/* ************************************************
 * Global variables
 ************************************************ */

date_default_timezone_set('America/Los_Angeles');  // correct
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

// command line arguments : 0 = <program>; 1 = <sys>; 2 = <options>
/* $argv = array ( 
	 0 => 'program name',
	 1 => '<sys>',
	 2 => 'debug+show+time+data'
); */

// get arguments
if (! isset($argv[1])) { 
  printf("Not enough parameters.\n
	Use: %s sys <show+time+data+statistics+debug+unknown+pattern+fields+knowledge>", $argv[0]);
  $hol_nrm = '**Parameter error**'; // return an empty string
	exit;
} else {
  $argv_program = $argv[0];
  // receive sys (= bib||sys)
  if (isset($argv[1])) $sys = $argv[1];
	// put options into $proc_flag
  if (isset($argv[2])) $argv_option = explode('+', $argv[2]); else $argv_option = array();
  $proc_flag = array(
	  'show'           => false, // show result
		'time'           => false, // show execution time
		'control'        => false, // show all matches cases
		'data'           => false, // output as tab delimited
		'statistics'     => false, // show statistics about function use
		'debug'          => false, // show in debug mode
		'unknown'        => false, // show not resolved patterns
		'pattern'        => false, // show used pattern
    'fields'         => false, // show resulting field values
		'knowledge'      => false  // show all knowledge collected
	); 
	foreach ($argv_option as $option) $proc_flag[$option] = true;
}

if ($proc_flag['debug']) {
  printf("RUN %s (sys %s) OPTION: %s\n", $argv_program, $sys, implode(';', $argv_option));
  printf("    Parameters:\n");
  printf("    %-6s: %-6s\n",'sys', $sys);
  printf("    %-6s: %-6s\n",'table', $res_table);
  printf("    %-6s: %-6s\n",'time', $proc_flag['time']);
  printf("    %-6s: %-6s\n",'show', $proc_flag['show']);
  printf("    %-6s: %-6s\n",'clear_table', $proc_flag['clear_table']);
  printf("    %-6s: %-6s\n",'unknown', $proc_flag['unknown']);
  printf("    %-6s: %-6s\n",'pattern', $proc_flag['pattern']);
  printf("    %-6s: %-6s\n",'data', $proc_flag['data']);
  printf("    %-6s: %-6s\n",'statistics', $proc_flag['statistics']);
  printf("    %-6s: %-6s\n",'debug', $proc_flag['debug']);
}

// measure execution time
if ($proc_flag['time']) {
  $date_start = $date = new DateTime('now', new DateTimeZone('America/New_York'));
  printf("Starting: %s\n", $date_start->format('Y-m-d_His'));
}

// collect knowledge
$know['hG'] = acquire_knowledge('h', 'G', '');  // clearly recognizable strings at HOL level
$know['pF'] = acquire_knowledge('p', 'F', '');  // clearly recognizable string that could disturb in further processing
$know['pG'] = acquire_knowledge('p', 'G', '');  // clearly recognizable strings at HOP level
$know['pK'] = acquire_knowledge('p', 'K', '');  // strings at HOP level that can affect if used at start
$know['pL'] = acquire_knowledge('p', 'L', '');  // // strings at HOP level to eliminate not at last
$know['pN'] = acquire_knowledge('p', 'N', '');// strings at HOP level to eliminate at last


// get f866a
$query = "SELECT sys1, f866a FROM hol WHERE sys1 = '$sys'";
$result = pg_query($con, $query) or die("Cannot execute \"$query\"\n");
$res_list = pg_fetch_all($result);
$size = sizeof($res_list);
if ($size < 1) {
  printf("Search result should contain at least 1 record. %d found. Exit.", $size);
  $hol_nrm = '**'.$sys.' Not found**'; // return
	exit;
}
if ($proc_flag['debug']) show_process_info($res_list);   // show information about current process
	
/* ================================================================== *
 * HOL 
 * ================================================================== */
// extract $ta de $res_list
$recno = 0;
$stat['A Record retrieved'] = 1; // !! we are looking ony at 1 HOL
$hol_str      = $res_list[$recno]['f866a'];       // get holding string. This string we will changed
$hol_sys      = $res_list[$recno]['sys1'];

// <--------------------------------- JUMP HEAR
RESTART_WITH_COMMA_REPLACED: // after , has been changed to ;

$hol_info['proc'] = '';            // init

if ($proc_flag['show'])  printf("\n\n=== %s             :|%s|", $hol_sys, $hol_str);

/* ================================================================== *
* change things at HOL string level *
* ================================================================== */
// for each knowledge element
$know_gr = 'hG'; $uses = sizeof($know[$know_gr]['uses']); for ($c=0; $c < $uses; $c++) $hol_str = val_replace($hol_str);

/* ======================================================================== *
* modify entire hol_str
* ======================================================================== */
// modify [L= ...; ...] to {L~ ...} so it will be kept together and not be split by later regex operations
$hol_str_prev = $hol_str;
$fld = 'LN='; $hol_str = save_LN($fld, $hol_str);  // save [L=...; ...; N=...] so it will be not split
if ($hol_str_prev <> $hol_str) do_control('vLN', '', $hol_str_prev, '=>', $hol_str);

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

	if ($proc_flag['show']) printf("\n--- HOP                       :|%s|\n", $hop);
		
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
	
	if ($proc_flag['debug']) foreach ($hop_info[$hop_no] as $f => $va) { printf("%3s %25s : %s\n", 'FLD_', $f, $va);} // visualize if needed

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

	if ($proc_flag['fields']) foreach ($hop_info[$hop_no] as $f => $va) { printf("%3s %25s : %s\n", 'FLD=', $f, $va);} // visualize if needed

	// if type is empty, we recognized nothing
	if (!isset($hop_info[$hop_no]['type'])) {
		$hop_info[$hop_no]['type'] = '==UNKNOWN==';
		do_control('vR!', '', $hop, '', '### '.$hop_info[$hop_no]['type']);
		// !!!! If "," in a too long hop encountered, assume ";" and RESTART
		if (preg_match('/\(?[0-9 \(\)-]{4,14}\)? *, *\(?[0-9]{1,4}\)?/', $hop, $elem)) { // Special cases: check if we should replace "," by ";"
			$hol_str = preg_replace('/ *, */', '; ', $hol_str);  // ### test this thoroughly. Until now it's a cheap patch!!!
			do_control('vR2', 'RESTART', $hop, '', '>>>'.$hop);
			// unset ($hop_info[$hop_no]);
			if ($proc_flag['debug']) printf("\n*** RESTART RECOGITION with ; ***\n");
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
if ($proc_flag['show']) write_to_screen(); // show 


if ($proc_flag['statistics']) show_statistics(); // make statistics
// Print process ending time
if ($proc_flag['time']) {
  printf("\n%23s: %s\n", 'Start time', $starttime);
  $endtime = date("Y-m-d H:i:s");
  printf("%23s: %s\n", 'End time', date("Y-m-d H:i:s"));
  printf("%23s: %s seconds\n", 'Duration', dt_diff($starttime, $endtime));
  printf("%23s: %s seconds\n", 'Per Record', dt_diff($starttime, $endtime)/$stat['A Record retrieved']);
}
// The End
return $hol_nrm;

/* ======================================================================== *
 *                                Functions                                 *
 * ======================================================================== */

// ------------------------------------------------------------------------
function do_control($marker1, $model, $str_before, $marker2, $str_after) {
// ------------------------------------------------------------------------
// Purpose: prints manipulation a a string to the screen
  global $do_control, $proc_flag;
  if ($proc_flag['control']) printf("\n%-3s %-25s : %-70s %2s %s", $marker1, $model, $str_before, $marker2, $str_after);
}

// ------------------------------------------------------------------------
function val_replace($ho_val) {
// ------------------------------------------------------------------------
	global $know, $know_gr, $proc_flag, $do_show_pattern, $do_give_info, $hop_info, $hop_no, $stat;
	if ($ho_val == '==RECOGNIZED==') return $ho_val;  // already recognized, so go back

	for ($c=0; $c < count($know[$know_gr]['srch']); $c++) {  // for each regular expression in the group ...
		$regex = '/'.$know[$know_gr]['srch'][$c].'/'.$know[$know_gr]['uppe'][$c];  // build regex string. Add i for search case insensitive (uppe)
		$ho_val_prev = $ho_val;
    do_control('vR~', '', $regex, '', '');
		if (preg_match($regex, $ho_val, $elem)) {  // check if we have something to do
			$ho_val = preg_replace($regex, $know[$know_gr]['repl'][$c], $ho_val);
			if ($ho_val_prev <> $ho_val) {
				do_control('vR~', '', $regex, '', '');
				do_control('vR^', $know[$know_gr]['mode'][$c], $ho_val_prev, '', $ho_val);
				do_control('vRv', '', $know[$know_gr]['writ'][$c], '', '****');
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
								do_control('vRn', $var, $hop_info[$hop_no][$var], '', '$1');
								break;
							default:
								$hop_info[$hop_no][$var]=$val;
							}	  
						  break;
						case 'NSER':  // NF = nSer
							switch ($val) {
							case 'NF++': // increment by 1
								$hop_info[$hop_no][$var]++;
								do_control('vRn', $var, $hop_info[$hop_no][$var], '', '++');
								break;
							case '$1': // increment by 1
								$hop_info[$hop_no][$var]=$elem[1];
								do_control('vRn', $var, $hop_info[$hop_no][$var], '', '$1');
								break;
							default:
								$hop_info[$hop_no][$var]=1;
						}
						do_control('vRn', $var, $hop_info[$hop_no][$var], '', '??');
						break; // end NF++
						case 'UNIT':
							if (isset($hop_info[$hop_no][$var])) $hop_info[$hop_no][$var].= '; '.$val; else $hop_info[$hop_no][$var] = $val;
							break;
						default: $hop_info[$hop_no][$var] = $val;
							break;
					}
					do_control('vRV', $var, $hop_info[$hop_no][$var], '', '**');
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
					if ($proc_flag['debug']) printf("   =: %-6s = %-20s\n", $pom[$c2], $elem[$c2]);
					if ($pom[$c2] > '') $hop_info[$hop_no][$pom[$c2]] =	$elem[$c2];
			  }
				do_control('MDL', $mdl, implode('|', $pom), '', implode('|', $elem));
			}
			if ($ho_val == '') {
			  $ho_val = '==RECOGNIZED==';
				isset($stat['Z_RECOGNIZED']) ? $stat['Z_RECOGNIZED']++ : $stat['Z_RECOGNIZED']=1;
			}
			collect_proc_info($hop_info, $know[$know_gr]['mode'][$c], $hop_no, $ho_val, $elem[0]);
			do_control('vR+', $know[$know_gr]['mode'][$c], $ho_val_prev, '', '|'.$ho_val.'|   {'.$know[$know_gr]['writ'][$c].')');
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
		do_control('EQU', '', $hop_prev, '', $hop.'  {'.implode('|', $equ_list).'}');
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
  do_control('STA', $model, $stat[$model_s], '', '');
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
    do_control('LN1', $fld, $ho_val_prev, '',$ho_val);
  }
  if (preg_match($know['L=N='], $ho_val, $elem)) {   // do it twice for second [.=...]
    $elem[2] = preg_replace("/=/", '~', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/;/", '|', $elem[2]);  // replace ; by ,
    $elem[2] = preg_replace("/\[/", '{', $elem[2]); // replace [] by {}
    $elem[2] = preg_replace("/\]/", '}', $elem[2]); // replace [] by {}
    $ho_val = $elem[1].$elem[2].$elem[3];
    $hol_info['L=N='] = $elem[2];   // collect info about holdings
    collect_proc_info($hop_info, $fld, $hop_no, $ho_val, $elem[1]);
    do_control('LN2', $fld, $ho_val_prev, '',$ho_val);
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
    do_control('LN3', $fld, $ho_val_prev, '',$ho_val);
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
function show_process_info($res_list) {
// ------------------------------------------------------------------------
  // Give Global Statistal Data
  global $stat, $hol_info, $query, $starttime;
  printf("\n\n************ %s: ************\n", 'Process Information');
  printf("  %-23s: %s\n", 'Script executed', basename(__FILE__, '.php'));
  printf("  %-23s: %s\n", 'Start time', $starttime);
  printf("  %-23s: %s\n", 'Query', $query);
  printf("  %-23s: %s\n", 'Records retrieved', sizeof($res_list));

  $stat['A Record retrieved'] = 1; // !! now it only 1
  $stat['A_Parts of holdings'] = 0;
  $hol_info   = array();   // collect info about holding string
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
	global $proc_flag;
	$hol_nrm = array();
  $size = sizeof($hop_info);
  for ($i=0; $i < $size; $i++) {
		if ($proc_flag['debug']) printf("[%3s] : %20s - %s-%s\n", 
			$size, 
			isset($hop_info[$i]['hop'])?$hop_info[$i]['hop']:'---', 
			isset($hop_info[$i]['yeB1'])?$hop_info[$i]['yeB1']:'---', 
			isset($hop_info[$i]['yeE1'])?$hop_info[$i]['yeE1']:'---');	
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
			substr(   ' '.(isset($hop_info[$i]['ICPL'])?$hop_info[$i]['ICPL']:'    '),-1,1));
			substr(   ' '.(isset($hop_info[$i]['ONLINE'])?$hop_info[$i]['ONLINE']:'  '),-1,1));
	}
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

