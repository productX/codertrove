<?php 

define('WS_ROOT', 'http://codertrove.dev/');
define('IMAGES', 'http://codertrove.dev/site/images/');

// DB Credentials
$db_ip = 'localhost';
$db_user = 'root';
$db_pass = 'faramir';
#$db_pass = 'n1leriv3r';
$db_name = 'codertrove';

ini_set('display_errors', 1);

function is_ie()
{
    if (isset($_SERVER['HTTP_USER_AGENT']) && 
       (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
       return true;
    else
       return false;
}

$is_ie = is_ie();

?>
