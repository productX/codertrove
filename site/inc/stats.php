<?php

/**
 * PERSISTENT VARS
 */
function var_incr($v, $inc = 1) {
	$inc = floatval($inc);
	$v = mysql_real_escape_string($v);
	mysql_query("insert into vars (varname, val) values ('$v', $inc) 
		     on duplicate key update val = val + $inc");
}

function var_decr($v, $dec = 1) {
	$dec = floatval($dec);
	$v = mysql_real_escape_string($v);
	mysql_query("insert into vars (varname, val) values ('$v', $dec) 
		     on duplicate key update val = val - $dec");
}

function var_get($v) {
	$v = mysql_real_escape_string($v);
	$a = mysql_query("select * from vars where varname = '$v'");
	if ($a) {
		$b = mysql_fetch_assoc($a);
		return $b['val'];
	} else {
		return;
	}
}

function var_set($v, $val = 0) {
	$v = mysql_real_escape_string($v);
	$val = floatval($val);
	mysql_query("insert into vars (varname, val) values ('$v', $val) 
		     on duplicate key update val = '$val'");
}

function get_varnames() {
	$sql = "select varname from vars";
	$rs = mysql_query($sql);
	$varnames = array();
	while($row = mysql_fetch_array($rs)) {
		$varnames[] = $row['varname'];
	}
	return $varnames;
}

// $range_start and range_end are unix time stamps
// $interval: 'minute', 'hour', 'day'
// $delta: if true, get the difference between data points, otherwise return the abs value
function get_var_history($varnames, $interval, $date_start, $date_end, $delta = true) {

	$date_start = intval($date_start);
	$date_end = intval($date_end);
	for($i = 0; $i < count($varnames); $i++) {
		$varname[$i] = mysql_real_escape_string($varname[$i]);
	}
	$interval = intervalStrToInt($interval);
	$sql = "SELECT varname, val, UNIX_TIMESTAMP(ts) as ts FROM vars_log
		WHERE varname IN ('"  . implode("','", $varnames) . "')
		AND ts BETWEEN FROM_UNIXTIME(" . ($date_start - 2 * $interval) . ") AND FROM_UNIXTIME($date_end)";
	$rs = mysql_query($sql);
	
	$histories = array();
	while($row = mysql_fetch_array($rs)) {	
		$histories[$row['varname']][$row['ts'] - ($row['ts'] % 60)] = $row['val'];
	}
	$result = array();
	if ($delta) { // If delta, you gotta take an extra time point
		$date_start = $date_start - ($date_start % $interval) - $interval;
	}
	else {
		$date_start = $date_start - ($date_start % $interval);
	}
	foreach($histories as $var => $history) {
		$previous_ts = 0;
		for($i = $date_start; $i < $date_end; $i += $interval) {
			if (isset($history[$i])) { 
				if ($delta) {
					if ($i - $previous_ts == $interval) {
						$result[$var][$i] = $history[$i] - $previous_val;
					}
					else if ($previous_ts != 0) {
						$result[$var][$i] = 0;
					}
					$previous_val = $history[$i];
					$previous_ts = $i;
				}
				else {
					$result[$var][$i] = $history[$i];
				}
			}
			else {
				$result[$var][$i] = 0;
			}
		}	
	}
	return $result;
}

// Returns values of variables at a certain time point
function get_values($vars, $ts) {
	$ts_beg_range = intval($ts - 60);
	$ts_end_range = intval($ts + 60);	
	$cleaned_vars = array();
	foreach($vars as $var) {
		$cleaned_vars[] = mysql_real_escape_string($var);
	}
	$sql = "SELECT * FROM vars_log 
		WHERE varname IN ('" . implode("','", $cleaned_vars) . "') 
		AND ts BETWEEN FROM_UNIXTIME({$ts_beg_range}) AND FROM_UNIXTIME({$ts_end_range})
		ORDER BY ts LIMIT " . count($cleaned_vars);
	$rs = mysql_query($sql);
	while($row = mysql_fetch_array($rs)) {
		$result[$row['varname']] = $row['val'];
	}	
	return $result;
}

function delta_vars($vars, $time1, $time2) {
	$values1 = get_values($vars, $time1);
	$values2 = get_values($vars, $time2);
	$result = array();
	foreach($vars as $var) {
		$result[$var] = $values2[$var] - $values1[$var];
	}
	return $result;
}

function intervalStrToInt($interval) {
	switch($interval) {
		case 'minute': 
			$interval = 60;
			break;
		case 'hour': 
			$interval = 60 * 60;
			break;
		case 'day': 
			$interval = 24 * 60 * 60;
			break;
	}
	return $interval;
}

function var_history_op($op, $var1, $var2) {
	$result = array();
	if (count($var1) == count($var2)) {
		foreach($var1 as $ts => $val1) {
			$result[$ts] = $op($val1, $var2[$ts]);
		}
	}
	return $result;
}

function div_vars($v1, $v2) {
	if ($v2 != 0) {
		return $v1 / $v2;
	}
	else {
		return 0;
	}
}

function div_var_history($var1, $var2) { return var_history_op('div_vars', $var1, $var2); }

function add_vars($v1, $v2) { return $v1 + $v2; }
function add_var_history($var1, $var2) { return var_history_op('add_vars', $var1, $var2); }

function sub_vars($v1, $v2) { return $v1 - $v2; }
function sub_var_history($var1, $var2) { return var_history_op('sub_vars', $var1, $var2); }

function mul_vars($v1, $v2) { return $v1 * $v2; }
function mul_var_history($var1, $var2) { return var_history_op('mul_vars', $var1, $var2); }

// Interval specifies over what time range since the present
function get_pie($vars, $interval) {
	$interval = intervalStrToInt($interval);
	$deltas = delta_vars($vars, time() - $interval, time());
	$total = 0;
	$result = array();
	foreach($deltas as $var => $delta) { $total += $delta; }
	foreach($deltas as $var => $delta) {
		if ($total != 0) {
			$result['slices'][$var]['val'] = $delta;	
			$result['slices'][$var]['percentage'] = $delta / $total;
		}
	}
	$result['total'] = $total;
	return $result;
}

?>
