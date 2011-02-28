<?php
require_once 'common.php';
//require_once './lib/github/Autoloader.php';

$facebook = null;
$skillKeywords = array();
$sourceID = 3;
$statusBlob = null;
setup($facebook, $skillKeywords, $sourceID, $statusBlob);

function buildCoderSourceProfile($sourceID, $siteUserID, $stashVar) {
	$userData = $stashVar;
	var_dump($userData);

	$userName = $siteUserID; //$userData['name'];
	$joinDate = strtotime($userData['created_at']);
	$ranking = 0;
	$karma = $userData['followers_count'];
	$about = $userData['name']." , ".$userData['company']." , ".$userData['location']." , ".$userData['blog']." , ".$userData['email'];

	$coderID = getOrBuildCoderID($userName, $about);
	doQuery("INSERT INTO codersourceprofiles (coderid, sourceid, username, joindate, ranking, karma, sourcesiteuserid, about) VALUES ($coderID, $sourceID, ".getSQLStrParamStr($userName).", UNIX_TIMESTAMP($joinDate), $ranking, $karma, ".getSQLStrParamStr($siteUserID).", ".getSQLStrParamStr($about).")");		
	return $coderID;
}


$userIDs=array();
for($numFollowers=3; $numFollowers<4; ++$numFollowers) {
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

//$userIDs = array_reverse($userIDs);
for($i=0; $i<count($userIDs); ++$i) {
	echo "SET UP USER: $i/".count($userIDs)."\n";
	$userID = $userIDs[$i];
	$userURL = "http://github.com/api/v2/json/user/show/$userID";
	$userDataList = json_decode(get_url_contents($userURL), true);
//	var_dump($userDataList);
	if(is_array($userDataList) && !is_null($userDataList['user'])) {
		$userData = $userDataList[$user];
		
		$repoListURL = "http://github.com/api/v2/json/repos/show/$userID";
		$repoList = json_decode(get_url_contents($repoListURL), true);
		if(is_array($repoList) && !is_null($repoList['repositories'])) {
			for($j=0; $j<count($repoList['repositories']); ++$j) {
				$repo = $repoList['repositories'][$j];
				
				$title=$repo['name'];
				$body=$repo['description'];
				$url=$repo['url'];
				$likes = $repo['watchers'];
				$replies = $repo['forks'];
				$createdAt = strtotime($repo['created_at']);

				//logNewCoderActivity($sourceID, $skillKeywords, $buildCoderSourceProfileFunc, $siteUserID, $stashVar, $title, $body, $url, $likes, $replies, $ballsRating, $postTime)
				logNewCoderActivity($sourceID, $skillKeywords, "buildCoderSourceProfile", $userID, $userData, $title, $body, $url, $likes, $replies, 0, $createdAt);
			}
		}
	}
}

/*Github_Autoloader::register();
$github = new Github_Client();
$myRepos = $github->getUserApi()->show('ornicar');
var_dump($myRepos);*/

?>
