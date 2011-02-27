<?php
require_once 'common.php';
require_once './lib/github/Autoloader.php';

$facebook = null;
$skillKeywords = array();
$sourceID = 1;
$statusBlob = null;
setup($facebook, $skillKeywords, $sourceID, $statusBlob);

function buildCoderSourceProfile($sourceID, $siteUserID) {
	$jsonStr = get_url_contents("http://api.ihackernews.com/profile/$siteUserID");	
	$userData = json_decode($jsonStr, true);
	$userName = $userData['username'];
	$joinDate = siteTimeTextToTimestamp($userData['createdAgo']);
	$ranking = 0;
	$karma = $userData['karma'];
	$about = $userData['about'];

	$coderID = getOrBuildCoderID($userName, $about);
	doQuery("INSERT INTO codersourceprofiles (coderid, sourceid, username, joindate, ranking, karma, sourcesiteuserid, about) VALUES ($coderID, $sourceID, ".getSQLStrParamStr($userName).", UNIX_TIMESTAMP($joinDate), $ranking, $karma, ".getSQLStrParamStr($siteUserID).", ".getSQLStrParamStr($about).")");		
	return $coderID;
}

/*
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
}*/

Github_Autoloader::register();
$github = new Github_Client();
$myRepos = $github->getUserApi()->show('ornicar');
var_dump($myRepos);

?>