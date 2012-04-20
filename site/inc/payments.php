<?php

$pricing = array(500,1000,1500,2000);

function getPlanPrice($planID) {
	global $pricing;
	
	return $pricing[$planID];
}

?>