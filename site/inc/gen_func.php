<?

// Appends $appending_url to $url for redirecting after a request 
// or notification is made. 
// If num_next = 0, just return $appending_url 
function append_encoded_url($url, $appending_url, $num_next) {
	if ($num_next == 0) {
		return $appending_url;	
	}

	$appending_url = '?to=' . $appending_url;
	$next = '&next=';
	for ($i = 0; $i < $num_next; $i++) { 
		$appending_url = urlencode($appending_url);
	}
	for ($i = 0; $i < $num_next - 1; $i++) {
		$next = urlencode($next);
	}	
	return $url . $next . $appending_url . '&canvas';
}

function parseCsvLine($str) {
        $delimier = ',';
        $qualifier = '"';
        $qualifierEscape = '\\';

        $fields = array();
        while (strlen($str) > 0) {
		if ($str{0} == $delimier)
			$str = substr($str, 1);
		if ($str{0} == $qualifier) {
			$value = '';
			for ($i = 1; $i < strlen($str); $i++) {
				if (($str{$i} == $qualifier) && ($str{$i-1} != $qualifierEscape)) {
					$str = substr($str, (strlen($value) + 2));
					$value = str_replace(($qualifierEscape.$qualifier), $qualifier, $value);
					break;
				}
				$value .= $str{$i};
			}
		} else {
			$end = strpos($str, $delimier);
			$value = ($end !== false) ? substr($str, 0, $end) : $str;
			$str = substr($str, strlen($value));
		}
		$fields[] = $value;
	}
	return $fields;
}		

/**
 * Truncates text to the first space prior to num_chars and appends '...'
 */
function preview_text($text, $num_chars) {
	if (strlen($text) < $num_chars) {
		return $text;
	}
	$text = substr($text, 0, $num_chars);
	$text = explode(' ', $text);
	$text = array_slice($text, 0, -1);
	$text = implode(' ', $text);
	return $text . '...';
}

function format_mysql_date($mysql_date) {
	return strftime("%b %d @ %l:%M %p", strtotime($mysql_date));
}

function hash_generate($string) {
	$seed="sdflskdhfweo234lkshe094";
	return substr(md5($string.$seed),0,10);
}

function hash_verify($string,$hash) {
	if (hash_generate($string) == $hash)
		return TRUE;
	else
		return FALSE;
}		

function array_swap($ar, $one, $two) {

	$new_val_two = $ar[$one];
	$ar[$one] = $ar[$two];
	$ar[$two] = $new_val_two;

	return $ar;
}

function get_full_url () {
	$full_url = 'http';
	if($_SERVER['HTTPS']=='on'){$full_url .=  's';}
		$full_url .=  '://';
	if($_SERVER['SERVER_PORT']!='80') 
		$full_url .= $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'] .
			     $_SERVER['SCRIPT_NAME'];
	else
		$full_url .= $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
	return $full_url;
}

function getmicrotime() {
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

function get_url_contents($url){
        $crl = curl_init();
        $timeout = 5;
        curl_setopt ($crl, CURLOPT_URL,$url);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt ($crl, CURLOPT_USERAGENT, "Mozilla/5.0");
        $ret = curl_exec($crl);
        curl_close($crl);
        return $ret;
}

?>
