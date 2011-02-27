<?

function get_db_conn() {
	$conn = mysql_connect($GLOBALS['db_ip'], $GLOBALS['db_user'], $GLOBALS['db_pass']);
	mysql_select_db($GLOBALS['db_name'], $conn);
	return $conn;
}

// Add user to new_users_log table if not already in there
function log_user_if_new() {
	// Determine if user is in our new_users_log table
	$sql = "SELECT uid FROM users WHERE uid = {$user}";
	$rs = mysql_query($sql);
	if (mysql_num_rows($rs) == 0) {
		$sql = "INSERT INTO users ( uid, date_posted ) VALUES( {$user}, now() )";
		mysql_query($sql);
	}
	// Determine if user is in our users table
	$sql2 = "SELECT uid FROM users WHERE uid = {$user}";
	$rs2 = mysql_query($sql2);
	if (mysql_num_rows($rs2) == 0) {
		$sql3 = "INSERT INTO users ( uid, date_posted ) VALUES( {$user}, now() )";
		mysql_query($sql3);
	}
}

function log_user_session($user){
	$sql = "SELECT uid, session_id FROM session_id where uid = {$user}";
	$result = mysql_query($sql);
	$old_session_id = mysql_fetch_assoc($result);
	if(mysql_num_rows($result) == 0) {
		$sql2 = "INSERT INTO session_id (uid, session_id, date_posted) VALUES ({$user}, '{$session_key}', now())";
		mysql_query($sql2);
	}elseif($current_session_key != $old_session_id['session_id'] ) {
		$sql3 = "UPDATE session_id SET session_id = '{$current_session_key}' WHERE uid = {$user}";
		mysql_query($sql3);
	}
}

// Add user to new_users_log table if not already in there
function get_user_info() {

		$ip = $_SERVER['REMOTE_ADDR'];
		$ips = explode('.', $ip);
		$ip_num = $ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256; 

		$result2 = mysql_query("SELECT cntry_name FROM ip_to_location WHERE ip_from < {$ip_num} AND ip_to > {$ip_num}");

		$country_name = mysql_fetch_array($result2);

		$tv = $result[0]['tv'];
		$music = $result[0]['music'];
		$movies = $result[0]['movies'];
		$interests = $result[0]['interests'];
		$activities = $result[0]['activities'];
		$books = $result[0]['books'];
		$country = $country_name[0];

//	 mysql_query("UPDATE users SET hometown = '{$home_city}', current_location = '{$cur_city}', network = '{$affiliation}', dob = '{$birthday}', sex = {$sex}  WHERE uid = {$user}");
	       
		$sql = "SELECT * FROM interests_test WHERE uid = {$user}";	
		$has_data = mysql_query($sql);
		if (mysql_num_rows($has_data) == 0 ){
		mysql_query("INSERT INTO interests_test (uid, hometown, current_location, network, dob, sex, interests, activities, music, movies, tv, political, books, country) VALUES ({$user}, '{$home_city}', '{$cur_city}', '{$affiliation}', '{$birthday}', {$sex}, '{$interests}', '{$activities}', '{$music}', '{$movies}', '{$tv}', '{$political}', '{$books}', '{$country}')");
		}else{};

		return $result;
}

// Sets that the user was just updated
function set_last_updated($uid) {
	$clean_uid = doubleval($uid);
	$sql = "INSERT INTO last_updated (uid, ts) 
		VALUES({$clean_uid}, now())
		ON DUPLICATE KEY UPDATE ts = now()";
	mysql_query($sql);
}

?>
