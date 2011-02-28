<?php
require_once 'inc/master_inc.php';

render_header();

$testSearch = searchForCoders("ios php python developer");
$tag_cloud = get_tag_cloud(34);
//print_r($tag_cloud);
?>
<div class="main">

	<div class="title">Find <span class="highlight">Top Coders </span>through their online activity</div>
	<div class="body">

		<div class="photo_collage">
			<img src="../images/Home_Graphics.png"/>
		</div>


		<div class="right_block">
		<div class="search_area">
			<form name="my_search">
				<input class="search_box" id="search" type="text" size="12" maxlength="44" value="iOS and PHP developer">
				<br/>
				<div style="width:250; margin:auto;">
					<input class="search_button" value="Find Top Coders" />
				</div>
			</form>
		</div>
		<div class="tag_cloud">
<?php 
	foreach($tag_cloud as $tag){
		?> <a href="http://codertrove.com/top_coders.php?tech=<?=$tag['name']?>" style="font-size:<?=$tag['font-size']?>; <?php 
$someNum = rand(1,10);
?>; margin-left:<?=$someNum?>px;"><?=$tag['name']?></a>
<?}?>
		</div>
		</div>
	</div>
	<img style="background-repeat:repeat-x; background-color:#80b0e4; width:990px;" src="images/Ad_WaveBackground.png"/>



</div>


<script>
var textfield = document.getElementById('search');
textfield.onfocus = function() {
	this.className = this.className.replace('search_box', 'search_entry');
	textfield.value = '';
};
textfield.onblur = function() {
	   this.className = this.className.replace('search_entry', 'search_box');
}
</script>


<?php
//render_footer();
?>
