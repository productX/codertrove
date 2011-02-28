<?php
require_once 'common.php';

$facebook = null;
$skillKeywords = array();
$sourceID = 1;
$statusBlob = null;
setup($facebook, $skillKeywords, $sourceID, $statusBlob);

function buildCoderSourceProfile($sourceID, $siteUserID, $stashVar) {
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

function siteTimeTextToTimestamp($timeText) {
	return strtotime("-".str_replace(" ago", "", $timeText));
}

$lastPostID = null;
if(!is_null($statusBlob)) {
	$status = unserialize($statusBlob);
	$lastPostID = $status['lastPostID'];
}

$done = false;
$nextLastPostID = null;
$baseJSONURL = "http://api.ihackernews.com/new";
$nextJSONURL = $baseJSONURL;
$postIDs = array();
while(!$done) {
	$jsonStr = get_url_contents($nextJSONURL);
	if(!is_null($jsonStr) && $jsonStr!="") {
		$postList = json_decode($jsonStr, true);
		$nextJSONURL = $baseJSONURL."\\".$postList["nextId"];
		$items = $postList["items"];
		if(!is_array($items)) {
			continue;
		}
		for($i=0; $i<count($items); ++$i) {
			if(!is_array($items[$i])) {
				continue;
			}
			$postID = $items[$i]['id'];
			echo "\n[$postID]\n";
			if($postID == $lastPostID) {
				$done=true;
				break;
			}
			if(is_null($nextLastPostID)) {
				$nextLastPostID = $postID;
			}
			$postIDs[] = $postID;
			$timestamp = siteTimeTextToTimestamp($items[$i]['postedAgo']);
			logNewCoderActivity($sourceID, $skillKeywords, "buildCoderSourceProfile", $items[$i]['postedBy'], false, $items[$i]['title'], "Link: ".$items[$i]['url'], "http://news.ycombinator.com/item?id=".$postID, $items[$i]['points'], $items[$i]['commentCount'], 2, $timestamp);
		}
	}
	else {
		$done=true;
	}
}

for($i=0; $i<count($postIDs); ++$i) {
	$jsonStr = get_url_contents("http://api.ihackernews.com/post/".$postIDs[$i]);	
	if(!is_null($jsonStr) && $jsonStr!="") {
		$post = json_decode($jsonStr, true);
		$comments = $post['comments'];
		while(count($comments)) {
			$comment = $comments[0];
			logNewCoderActivity($sourceID, $skillKeywords, "buildCoderSourceProfile", $comment['postedBy'], false, "", $comment['comment'], "http://news.ycombinator.com/item?id=".$comment['id'], $comment['points'], count($comment['children']), 1, siteTimeTextToTimestamp($comment['postedAgo']));
			$comments = array_merge($comments, $comment['children']);
		}
	}
}

if(!is_null($nextLastPostID)) {
	doQuery("UPDATE sources SET statusblob=".getSQLStrParamStr(serialize(array('lastPostID' => $nextLastPostID)))." WHERE id=$sourceID");
}

?>