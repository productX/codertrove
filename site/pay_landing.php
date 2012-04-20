<html><head>
</head><body>
<?php
require_once 'inc/master_inc.php';

$purchaseDone=!is_null($_GET['done']);

render_header();
?>

<div class="main">

	<div class="title">
<?php $purchaseDone?("<span class='highlight'>Thank you</span> for your purchase!! :)"):("<span class='highlight'>Really?</span> You are missing out! :("); ?>
		<br/> 
	</div>

	<div class="body">

		<div class="paylanding_cta_text">
		</div>
<?php $purchaseDone?"We appreciate valuable customers like you & will continue to do our best to grow this site a great tool for meeting amazing coders/developers/engineers! Start your navigation now, below.":"Are you sure you wouldn't like to be connected effortlessly with the most active & relevant professionals on the web? Please reconsider us now or later, and let us know if we can answer any questions in the meantime :)";?>

<?php
if($purchaseDone) {
	$tag_cloud = get_tag_cloud(34);

	?>
	<div class="tag_cloud_paylanding">
	<?php 
	foreach($tag_cloud as $tag){
		?> <a href="http://codertrove.com/top_coders.php?tech=<?php $tag['name'];?>" style="font-size:<?php $tag['font-size']; ?>; <?php 
$someNum = rand(1,10);
?>; margin-left:<?php $someNum; ?>px;"><?php $tag['name']; ?></a>
<?php }
else {
}
?>
			<div class="paylanding_back_button">Go Back!</div>
		</div>
		<?php
}
else {
}
?>
		
	</div>
</div>

<?php
//render_footer();
?>
</body></html>
