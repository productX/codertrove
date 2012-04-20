<?php
//echo 'hot';
require_once("config.php");
require_once("db_func.php");
require_once("gen_func.php");
require_once("functions.php");

if ($is_ie == TRUE){
	require_once("css/ie_style.css");
}
else{
	require_once("css/style.css");
}

require_once("header.php");
require_once("footer.php");
require_once("pagination.php");
?>
