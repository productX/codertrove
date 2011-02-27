<?php
require_once 'common.php';

$facebook = null;
$skillKeywords = array();
$sourceID = 1;
$statusBlob = null;
setup($facebook, $skillKeywords, $sourceID, $statusBlob);

function buildCoderSourceProfile($sourceID, $siteUserID) {
	$jsonStr = get_url_contents("http://api.ihackernews.com/user?id=$siteUserID");	
	$userData = json_decode($jsonStr);
	$userName = $userData['username'];
	$joinDate = siteTimeTextToTimestamp($userData['createdAgo']);
	$ranking = 0;
	$karma = $userData['karma'];
	$about = $userData['about'];

	$coderID = getOrBuildCoderID($userName, $about);
	doQuery("INSERT INTO codersourceprofiles (coderid, sourceid, username, joindate, ranking, karma, sourcesiteuserid, about) VALUES ($coderID, $sourceID, '$userName', UNIX_TIMESTAMP($joinDate), $ranking, $karma, '$siteUserID', '$about')");		
	return $coderID;
}

function siteTimeTextToTimestamp($timeText) {
	return strtotime("-".str_replace($timeText, " ago", ""));
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
		$postList = json_decode($jsonStr);
		$nextJSONURL = $baseJSONURL."\\".$postList["nextId"];
		$items = $postList["items"];
		for($i=0; $i<count($items); ++$i) {
			$postID = $items[$i]['id'];
			if($postID == $lastPostID) {
				$done=true;
				break;
			}
			if(is_null($nextLastPostID)) {
				$nextLastPostID = $postID;
			}
			$postIDs[] = $postID;
			$timestamp = siteTimeTextToTimestamp($items[$i]['postedAgo']);
			logNewCoderActivity($sourceID, $skillKeywords, "buildCoderSourceProfile", $items[$i]['postedBy'], $items[$i]['title'], "Link: ".$items[$i]['url'], "http://news.ycombinator.com/item?id=".$postID, $items[$i]['points'], $items[$i]['commentCount'], 2, $timestamp);
		}
	}
	else {
		$done=true;
	}
}

for($i=0; $i<count($postIDs); ++$i) {
	$jsonStr = get_url_contents("http://api.ihackernews.com/post/".$postIDs[$i]);	
	if(!is_null($jsonStr) && $jsonStr!="") {
		$post = json_decode($jsonStr);
		$comments = $post['comments'];
		while(count($comments)) {
			$comment = $comments[0];
			logNewCoderActivity($sourceID, $skillKeywords, "buildCoderSourceProfile", $comment['postedBy'], "", $comment['comment'], "http://news.ycombinator.com/item?id=".$comment['id'], $comment['points'], count($comment['children']), 1, siteTimeTextToTimestamp($comment['postedAgo']));
			$comments = array_merge($comments, $comment['children']);
		}
	}
}

if(!is_null($nextLastPostID)) {
	doQuery("UPDATE sources SET statusblob='".serialize(array('lastPostID' => $nextLastPostID))."' WHERE id=$sourceID");
}

?>