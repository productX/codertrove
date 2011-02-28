<?php
require_once 'inc/master_inc.php';

render_header();

$tech = $_REQUEST['tech'];

$testSearch = searchForCoders("ios php python developer");
$tag_cloud = get_tag_cloud(26);
//print_r($tag_cloud);
?>
<div class="main">

	<div class="title">
		The web's <span class="highlight">Top <?=$tech?> Coders </span>
		<br/> 
		<span class="subtext">Gleaned by analysis of millions of online documents, comments, posts and discussions</span>
	</div>


	<div class="body">

		<div class="left_pane">
			<div class="folder_container">
			<img class="paperclip_ad_large" src="images/Ad_Paperclip.png"/>
				<div class="expanded_folder">
					<div class="folder_container_internal">
					<div class="expanded_folder_photo">
						<img class="coder_thumb_ad" src='http://profile.ak.fbcdn.net/hprofile-ak-snc4/50111_13900723_3002705_s.jpg'/>
						<div class="name_folder_ad">Roger Dickey<br/>
							<a href="http://codertrove.com">Connecticut</a>
						</div>
					</div>
					<div class="skill_folder_ad">
						<p>Skills: PHP, Python, Ruby, iOS, Android</p>
						<br/>	
						<div class="coder_small_source_ad">
							<p>Quora<br/>(13)</p>
						</div> 
						<div class="coder_small_source_ad">
							<p>Stack Overflow<br/>(13)</p>
						</div> 
						<div class="coder_small_source_ad">
							<p>Github<br/>(13)</p>
						</div> 
						<div class="coder_small_source_ad">
							<p>Hacker News<br/>(13)</p>
						</div> 
					</div>
					<div class="sample_comment">
						<p>"ha ha ha, that was the funniest flame war e-vah! Can't believe paul graham fell for it wa ha ha ha ha frrrp! :D"</p>	
					</div>					


					</div>


				</div>
			</div>



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
		<div style="clear:both;"/>
		<div class="tag_cloud_small" style="float:left;">
<?php 
	foreach($tag_cloud as $tag){
		?> <a href="http://codertrove.com/<?=$tag['name']?>" style="font-size:<?=$tag['font-size']?>; <?php 
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
