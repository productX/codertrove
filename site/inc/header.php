<?php
function render_header($page = '') {
	global $appcallbackurl;
	init_page();
?>

<div style="border-bottom:1px #cccccc solid;"/>
<link rel='stylesheet' type='text/css' href='<?= $appcallbackurl ?>/site/inc/style.css?v=2.4' />

<?
}
?>
