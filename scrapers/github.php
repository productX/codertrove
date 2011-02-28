<?php
require_once 'common.php';
require_once './lib/github/Autoloader.php';

$facebook = null;
$skillKeywords = array();
$sourceID = 3;
$statusBlob = null;
setup($facebook, $skillKeywords, $sourceID, $statusBlob);

function buildCoderSourceProfile($sourceID, $siteUserID) {
	global $userData; //hack

	$userName = $userData['defunkt'];
	$joinDate = strtotime($userData['created_at']);
	$ranking = 0;
	$karma = $userData['followers_count'];
	$about = $userData['name']." , ".$userData['company']." , ".$userData['location']." , ".$userData['blog']." , ".$userData['email'];

	$coderID = getOrBuildCoderID($userName, $about);
	doQuery("INSERT INTO codersourceprofiles (coderid, sourceid, username, joindate, ranking, karma, sourcesiteuserid, about) VALUES ($coderID, $sourceID, ".getSQLStrParamStr($userName).", UNIX_TIMESTAMP($joinDate), $ranking, $karma, ".getSQLStrParamStr($siteUserID).", ".getSQLStrParamStr($about).")");		
	return $coderID;
}


$userIDs=array();
for($numFollowers=0; $numFollowers<1800; ++$numFollowers) {
	echo "\n\nFOLLOWERS = $numFollowers\n\n";
	$done = false;
	for($page=1; !$done; ++$page) {
		echo "($page) ";
		$pageURL = "https://github.com/search?langOverride=&language=&q=followers:$numFollowers&repo=&start_value=$page&type=Users&x=0&y=0";
		$pageHTML = get_url_contents($pageURL);
		$results = explode("<div class=\"result\">", $pageHTML);
		if(count($results)==1) {
			$done=true;
			continue;
		}
		for($i=1; $i<count($results); ++$i) {
			$parts1 = explode("<a href=\"/", $results[$i]);
			$parts2 = explode("\">", $parts1[1]);
			$userIDs[] = $parts2[0];
			echo $parts2[0];
		}
	}
}

$userData=array();
for($i=0; $i<count($userIDs); ++$i) {
	$userID = $userIDs[$i];
	$userURL = "http://github.com/api/v2/json/user/show/$userID";
	$userDataList = json_decode(get_url_contents($userURL));
	if(is_array($userDataList) && !is_null($userDataList['user'])) {
		$userData = $userDataList[$user];
		$likes = floor($userData['followers_count']-3)+$userData['followers_count']*5;
		$replies = 0;
		//logNewCoderActivity($sourceID, $skillKeywords, $buildCoderSourceProfileFunc, $siteUserID, $title, $body, $url, $likes, $replies, $ballsRating, $postTime)
		logNewCoderActivity($sourceID, $skillKeywords, "buildCoderSourceProfile", $userID, "", "", "http://github.com/$userID", $likes, $replies, 0, now());
	}
}

/*Github_Autoloader::register();
$github = new Github_Client();
$myRepos = $github->getUserApi()->show('ornicar');
var_dump($myRepos);*/

?>