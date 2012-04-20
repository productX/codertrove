<?php
function render_header($page = '') {
	global $appcallbackurl;
	init_page();
?>
<head>
<title>CoderTrove</title>
</head>


<div style="border-bottom:1px #cccccc solid;"/>
<link rel='stylesheet' type='text/css' href='<?php $appcallbackurl;?>/site/inc/style.css?v=2.4' />
<div class="header">
	<div class="logo"><a style="margine:none;" href="http://codertrove.com"><img  src="../images/logo.png"/></div></a>
	<a class="gen_links" href="sign_up">Sign Up</a>
	<a class="gen_links" href="log_in">Log in</a>
	<a class="gen_links" href="pricing">Pricing</a>
	<a class="gen_links" href="about_us.php">About Us</a>
</div>

<?php
}
?>
