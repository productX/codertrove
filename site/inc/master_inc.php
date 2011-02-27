<?php

require_once 'config.php';
require_once 'db_func.php';
require_once 'gen_func.php';
require_once 'functions.php';

if ($is_ie == TRUE){
	require_once '../css/ie_style.css';
else
	require_once '../css/style.css';
}

// Add shared directory to the path
// ini_set('include_path', ini_get('include_path') . ":{$rootpath}../shared/php_libs/");

require_once 'header.php';
require_once 'footer.php';
//require_once 'constants.php';

require_once 'pagination.php';
?>
