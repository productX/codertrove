<?php
require_once 'common.php';
//require_once './lib/github/Autoloader.php';

$facebook = null;
$skillKeywords = array();
$sourceID = 3;
$statusBlob = null;
setup($facebook, $skillKeywords, $sourceID, $statusBlob);

$userDataGlobal = array();
function buildCoderSourceProfile($sourceID, $siteUserID) {
	global $userDataGlobal; //hack
	var_dump($userDataGlobal);

	$userName = $siteUserID; //$userDataGlobal['name'];
	$joinDate = strtotime($userDataGlobal['created_at']);
	$ranking = 0;
	$karma = $userDataGlobal['followers_count'];
	$about = $userDataGlobal['name']." , ".$useDataGlobal['company']." , ".$userDataGlobal['location']." , ".$userDataGlobal['blog']." , ".$userDataGlobal['email'];

	$coderID = getOrBuildCoderID($userName, $about);
	doQuery("INSERT INTO codersourceprofiles (coderid, sourceid, username, joindate, ranking, karma, sourcesiteuserid, about) VALUES ($coderID, $sourceID, ".getSQLStrParamStr($userName).", UNIX_TIMESTAMP($joinDate), $ranking, $karma, ".getSQLStrParamStr($siteUserID).", ".getSQLStrParamStr($about).")");		
	return $coderID;
}


$userIDs=array();
for($numFollowers=2; $numFollowers<3; ++$numFollowers) {
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
			echo $parts2[0].",";
		}
		$done=true;
	}
}

for($i=0; $i<count($userIDs); ++$i) {
	echo "SET UP USER: $i/".count($userIDs)."\n";
	$userID = $userIDs[$i];
	$userURL = "http://github.com/api/v2/json/user/show/$userID";
	$userDataList = json_decode(get_url_contents($userURL), true);
//	var_dump($userDataList);
	if(is_array($userDataList) && !is_null($userDataList['user'])) {
		$userDataGlobal = $userData = $userDataList[$user];
		$likes = ($userData['followers_count']-3)+$userData['public_repo_count']*5;
		$likes = ($likes<0)?0:$likes;
		$replies = 0;
		//logNewCoderActivity($sourceID, $skillKeywords, $buildCoderSourceProfileFunc, $siteUserID, $title, $body, $url, $likes, $replies, $ballsRating, $postTime)
		logNewCoderActivity($sourceID, $skillKeywords, "buildCoderSourceProfile", $userID, "", "", "http://github.com/$userID", $likes, $replies, 0, time());
	}
}

/*Github_Autoloader::register();
$github = new Github_Client();
$myRepos = $github->getUserApi()->show('ornicar');
var_dump($myRepos);*/

?>
