<?php
function init_page(){
	$conn = get_db_conn();
}



function newUser($email, $password, $firstname=NULL, $lastname=NULL) {

	$email = mysql_real_escape_string($email);
	$password = mysql_real_escape_string($password);
	$firstname = mysql_real_escape_string($firstname);
	$lastname = mysql_real_escape_string($lastname);

	$sql = "INSERT INTO users (username, password, email, firstname, lastname, registertime) VALUES ('{$email}','{$password}','{$email}','{$firstname}','{$lastname}', NOW())";
	mysql_query($sql);

}


function newCoder($picURL=NULL, $fullname=NULL, $email=NULL, $linkedinURL=NULL, $twitterURL=NULL, $fbURL=NULL, $otherURL=NULL, $handle=NULL) {

	$picURL = mysql_real_escape_string($picURL);
	$fullname = mysql_real_escape_string($fullname);
	$email = mysql_real_escape_string($email);
	$linkedinURL = mysql_real_escape_string($linkedinURL);
	$twitterURL = mysql_real_escape_string($twitterURL);
	$fbURL = mysql_real_escape_string($fbURL);
	$otherURL = mysql_real_escape_string($otherURL);
	$handle = mysql_real_escape_string($handle);

	$sql = "INSERT INTO coders (picURL, fullname, email, linkedinURL, twitterURL, fbURL, otherURL, handle) VALUES ('{$picURL}','{$fullname}','{$email}','{$linkedinURL}','{$twitterURL}', '{$fbURL}', '{$otherURL}', '{$handle}')";
	mysql_query($sql);

}


function newPayment($userid, $amount, $paymenttime, $packagetype) {

	$sql = "INSERT INTO payments (userid, amount, paymenttime, packagetype) VALUES ({$userid}, {$amount}, NOW(), {$packagetype})";
	mysql_query($sql);

}

function emailCoder($from, $subject, $content){

	//This is where we send an email


}

function get_tag_cloud(){

$sql = "SELECT coderskills.coderid, coderskills.skillid, skills.name as name, count(skills.id) as count FROM coderskills, skills WHERE skills.id = coderskills.skillid GROUP BY skills.id ORDER BY count(skills.id) DESC LIMIT 30";
$rs = mysql_query($sql);

$less_relevant_skills = array('Google', 'Apple', 'Amazon', 'Netflix', 'Verizon', 'Y', 'Nintendo');

$tag_cloud = array();
$i = 0;
while ($row = mysql_fetch_array($rs)) {
	if(in_array($row['name'],$less_relevant_skills)){
	}else{
		$tag_cloud[$i]['name'] = $row['name'];
		$tag_cloud[$i]['count'] = $row['count'];
		$tag_cloud[$i]['font-size'] = round(($i+1)+34-$i*1.7);
	}
	$i++;
}
shuffle($tag_cloud);
return $tag_cloud;

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
	$skillIDs = array();

	// check if any of the keywords are in the skills table. If so, get their id and put it in an array.
	$sql = "SELECT id FROM skills WHERE name = '{$keywords}'";
	$rs = mysql_query($sql);

	if(mysql_num_rows($rs)) {
		while ($row = mysql_fetch_array($rs)) {
			$skillIDs["fullMatch"] = $row['id'];
		}
	}
	// Wasn't there some way to release the mysql results to make sure they don't show up later, contaminating something else?

	$allSearchTerms = explode(" ", $keywords);

	foreach ($allSearchTerms as $term) {
		$sql = "SELECT id FROM skills WHERE name = '{$term}'";
		$rs = mysql_query($sql);

		while ($row = mysql_fetch_array($rs)) {
			$skillIDs[] = $row['id'];
		}
	}

	//print_r($skillIDs);

	// Check if there are any coders who have the fullMatch skill, if so put them in a perfectMatch array

	$perfectMatch = array();
	$sql = "SELECT coderid, expertise FROM coderskills WHERE skillid = '{$skillIDs["fullMatch"]}' ORDER BY expertise DESC";
	$rs = mysql_query($sql);

	if(mysql_num_rows($rs)) {
		while ($row = mysql_fetch_array($rs)) {
			$perfectMatch[] = $row['coderid'];
		}
	}
	
	$plainMatches = array();
	foreach($skillIDs as $skill){
		$sql = "SELECT coderid, expertise FROM coderskills WHERE skillid = '{$skill}' ORDER BY expertise DESC";
		$rs = mysql_query($sql);
	
		if(mysql_num_rows($rs)) {
			while ($row = mysql_fetch_array($rs)) {
				$plainMatches["coderid"] = $row['coderid'];
				if (empty($plainMatches["priority"])) { 
					$multiplier = 0; 
				} else { 
					$multiplier = 1.5; 
				}
				$plainMatches["coderid"]["priority"] = $plainMatches["coderid"]["priority"] + $row['expertise'] * $multiplier;
			}
		}

	}















}

?>
