<?php
require_once '../site/inc/config.php';
require_once '../site/inc/db_func.php';
require_once '../site/inc/gen_func.php';
require_once 'lib/fb/facebook.php';

function setup(&$facebook, &$skillKeywords, $sourceID, &$statusBlob) {
	$conn = get_db_conn();

	$facebook = new Facebook(array(
					'appId'  => '167490979968239',
					'secret' => '91fa9cf75cb76d5c632d8d40ab3eeac7',
					'cookie' => false
					));

	$result = doQuery("SELECT id, name, alternatenames FROM skills");
	if(mysql_num_rows($result) != 0) {
		while($row = mysql_fetch_assoc($result)) {
			$skillKeywords[$row['name']] = $row['id'];
			if($row['alternatenames']!="") {
				$otherKeywords = explode(",", $row['alternatenames']);
				for($i=0; $i<count($otherKeywords); ++$i) {
					$skillKeywords[$otherKeywords[$i]] = $row['id'];
				}
			}
		}
	}

	$result = doQuery("SELECT statusblob FROM sources where id=$sourceID");
	if(mysql_num_rows($result) != 1) {
		exit;
	}
	$row = mysql_fetch_assoc($result);
	$statusBlob = $row['statusblob'];
}

function getOrBuildCoderID($siteUserName, $aboutText) {
	// derived
	$email = getEmailInString($aboutText);
	$contactURLs = getContactURLsInString($aboutText);
	$linkedinURL = $contactURLs['linkedin'];
	$twitterURL = $contactURLs['twitter'];
	$fbURL = $contactURLs['facebook'];
	$otherURL = $contactURLs['other'];
	$picURL = null;
	if(!is_null($email)) {
		$picURL = getPicURLGivenEmail($email);
	}
	
	$result = doQuery("SELECT id, handle, email, linkedinURL, twitterURL, fbURL FROM coders WHERE handle='".mysql_real_escape_string($siteUserName)."' OR email='".mysql_real_escape_string($email)."' OR twitterURL='".mysql_real_escape_string($twitterURL)."' OR linkedinURL='".mysql_real_escape_string($linkedinURL)."' OR fbURL='".mysql_real_escape_string($fbURL)."'");
	$coderID = null;
	if(mysql_num_rows($result) == 0) {
		doQuery("INSERT INTO coders (handle, email, linkedinURL, twitterURL, fbURL, otherURL, picURL) VALUES ('".mysql_real_escape_string($siteUserName)."', ".getSQLStrParamStr($email).", ".getSQLStrParamStr($linkedinURL).", ".getSQLStrParamStr($twitterURL).", ".getSQLStrParamStr($fbURL).", ".getSQLStrParamStr($otherURL).", ".getSQLStrParamStr($picURL).")");
		$coderID = mysql_insert_id();
	}
	else {
		$setParams = array();
		if(!is_null($email)) {
			$setParams[] = "email=".getSQLStrParamStr($email);
		}
		if(!is_null($linkedinURL)) {
			$setParams[] = "linkedinURL=".getSQLStrParamStr($linkedinURL);
		}
		if(!is_null($twitterURL)) {
			$setParams[] = "twitterURL=".getSQLStrParamStr($twitterURL);
		}
		if(!is_null($fbURL)) {
			$setParams[] = "fbURL=".getSQLStrParamStr($fbURL);
		}
		if(!is_null($otherURL)) {
			$setParams[] = "otherURL=".getSQLStrParamStr($otherURL);
		}
		if(!is_null($picURL)) {
			$setParams[] = "picURL=".getSQLStrParamStr($picURL);
		}
		$paramStr = implode(", ", $setParams);
		if(count($setParams)) {
			$row = mysql_fetch_assoc($result);
			$coderID = $row['id'];
			doQuery("UPDATE coders SET $paramStr WHERE id=$coderID");
		}
	}
	return $coderID;
}

function logNewCoderActivity($sourceID, $skillKeywords, $buildCoderSourceProfileFunc, $siteUserID, $title, $body, $url, $likes, $replies, $ballsRating, $postTime) {
	$result = doQuery("SELECT coderid FROM codersourceprofiles WHERE sourceid=$sourceID AND sourcesiteuserid=".getSQLStrParamStr($siteUserID));
	$coderID = null;
	if(mysql_num_rows($result) == 0) {
		$coderID = $buildCoderSourceProfileFunc($sourceID, $siteUserID);	
	}
	else {
		$row = mysql_fetch_assoc($result);
		$coderID = $row['coderid'];
	}
	
	doQuery("INSERT INTO coderactivity (coderid, sourceid, commenttitle, commentbody, numlikes, commentURL, numreplies, ballsrating, posttime) VALUES ($coderID, $sourceID, ".getSQLStrParamStr($title).", ".getSQLStrParamStr($body).", $likes, ".getSQLStrParamStr($url).", $replies, $ballsRating, UNIX_TIMESTAMP($postTime))");
	
	foreach($skillKeywords as $keyword => $skillID) {
		if(!(stripos($title.$body, " ".$keyword." ")===false)) {
			doQuery("INSERT INTO coderskills (coderid, skillid, expertise, numposts) VALUES ($coderID, $skillID, $likes, 1) ON DUPLICATE KEY UPDATE numposts=numposts+1, expertise=expertise+$likes");	
		}
	}
}

function getSQLStrParamStr($str) {
	$result = "NULL";
	if(!is_null($str)) {
		$result = "'".mysql_real_escape_string($str)."'";
	}
	return $result;
}

function getURLsInString($str) {
	$urls = array();
	preg_match_all('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', $str, $urls);
	return $urls[0];
}

function getContactURLsInString($str) {
	echo "|".$str."|\n";
	$urls = getURLsInString($str);
	$contactBaseURLs = array("facebook"=>array("facebook.com"), "twitter"=>array("twitter.com"), "linkedin"=>array("linkedin.com", "linkd.in"));
	$result = array("facebook"=>null, "twitter"=>null, "linkedin"=>null, "other"=>null);
	for($i=0; $i<count($urls); ++$i) {
		$isOther = true;
		foreach($contactBaseURLs as $contactType => $baseURLs) {
			for($j=0; $j<count($baseURLs); ++$j) {
				if(	!(stripos($urls[$i], $baseURLs[$j])===false)) {
					$isOther = false;
					$result[$contactType] = $urls[$i];
					break 2;
				}
			}
		}
		if($isOther) {
			$result["other"]=$urls[$i];
		}
	}
	return $result;
}

/*function getPicURLGivenEmail($email) {
	global $facebook;
	
	$picURL = null;
	try {
		$result = $facebook->api("/search?q=$email&type=user");
		if(!is_null($result) && !is_null($result["data"]) && count($result["data"])) {
			$fbID = $result["data"][0]["id"];
			try {
				$result = $facebook->api("/$fbID/picture?type=small");
				if(!is_null($result)) {
					$picURL = $result;
				}
			}
			catch (FacebookApiException $e2) {
			}
		}
	}
	catch (FacebookApiException $e) {
	}
	return $picURL;
}*/

function getPicURLGivenEmail($email) {
	$str = "http://www.facebook.com/search.php?q=".urlencode($email);
	$fbPage = get_url_contents($str);
	$pieces1 = explode("UIImageBlock_ENT_Image\" href=\"http://www.facebook.com/", $fbPage);
	$picURL = null;
	if(count($pieces1)) {
			$pieces2 = explode("\" onclick=\"search_logged_ajax", $pieces1[1]);
			if(count($pieces2)) {
					$fbID = $pieces2[0];

					$url = "http://graph.facebook.com/$fbID/picture?type=normal";
					$crl = curl_init($url);
					$timeout = 5;
					curl_setopt ($crl, CURLOPT_HEADER, true);
					curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, true);
					curl_setopt ($crl, CURLOPT_RETURNTRANSFER, true);
					curl_setopt ($crl, CURLOPT_MAXREDIRS, 10);
					curl_setopt ($crl, CURLOPT_ENCODING, "");
					curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
					curl_setopt ($crl, CURLOPT_USERAGENT, "Mozilla/5.0");
					$ret = curl_exec($crl);
					$header = curl_getinfo($crl);
                    curl_close($crl);
                    $picURL = $header['url'];
            }
    }
    return $picURL;
}

/*function getURLIfPresent($str, $baseURLs) {
	$urls = getURLsInString($str);
	$result = null;
	for($i=0; $i<count($urls); ++$i) {
		for($j=0; $j<count($baseURLs); ++$j) {
			if(	!(stripos($urls[$i], $baseURLs[$j])===false)) {
				$result = $urls[$i];
				break 2;
			}
		}
	}
	return $result;
}

function getLinkedinURLInString($str) {
	return getURLIfPresent($str, array("linkedin.com", "linkd.in"));
}

function getFirstURLInString($str) {
	$urls = getURLsInString($str);
	$result = null;
	if(count($urls)) {
		$result = $urls[0];
	}
	return $result;
}*/

function getEmailInString($str) {
	$emails = array();
	preg_match("/[^@ \/:]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+/", $str, $emails);
	if(count($emails)) {
		return $emails[0];
	}
	return null;
}

//$queryDebug=true;
function doQuery($queryStr) {
	if(true) {
		echo $queryStr."\n";
	}
	return mysql_query($queryStr);
}

?>
