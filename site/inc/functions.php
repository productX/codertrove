<?php
function init_page(){
	$conn = get_db_conn();
}



// Get the information for a particular coder including user interests
function getUserInfo($coderID){

	$sql = "SELECT * FROM coders WHERE id = {$coderID}";
	$rs = mysql_query($sql);

	$coderID = array();
	while ($row = mysql_fetch_array($rs)) {
		$coderID['handle'] = $row['shorthandle'];
		$coderID['pic'] = $row['picURL'];
		$coderID['fullname'] = $row['fullname'];
		$coderID['email'] = $row['email'];
		$coderID['linkedinURL'] = $row['linkedinURL'];
	}

	return $coderID;
}

// Search for a particular key word, key word combinationi, sort the 

function searchForCoders($keywords){

	// check if any of the keywords are in the skills table. If so, get their id and put it in an array.
	
	$sql = "SELECT id FROM skills WHERE '{$keywords}' == name";
	$rs = mysql_query($sql);

	$skillIDs = array();
	while ($row = mysql_fetch_array($rs)) {
		$skillIDs["fullMatch"] = $row['id'];
	}
	// Wasn't there some way to release the mysql results to make sure they don't show up later, contaminating something else?

	$allSearchTerms = explode(" ", $keywords);


	$sql = "SELECT id FROM skills WHERE '{$keywords}' == name";
	$rs = mysql_query($sql);

	$skillIDs = array();
	while ($row = mysql_fetch_array($rs)) {
		$skillIDs["fullMatch"] = $row['id'];
	}
	











	// For each skill id, get all the coders who have that id in the coderskills table and sort them by descending order. Put them in an array.

	$sql = "SELECT * FROM coderactivity WHERE {$keywords} IN commenttitle OR {$kewords} IN commentbody";
	$rs = mysql_query($sql);

	$coderID = array();
	while ($row = mysql_fetch_array($rs)) {
		$coderID['handle'] = $row['shorthandle'];
		$coderID['pic'] = $row['picURL'];
		$coderID['fullname'] = $row['fullname'];
		$coderID['email'] = $row['email'];
		$coderID['linkedinURL'] = $row['linkedinURL'];
	}

	return $coderID;






}




// Get the payment history for a user
























?>
