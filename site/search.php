<?php
require_once 'inc/master_inc.php';
render_header();

$skill = $_REQUEST['tech'];

$testSearch = searchForCoders("ios php python developer");
$tag_cloud = get_tag_cloud(26);

$info = getCoderResults($skill, 8);

//echo "<pre>";
//print_r($info);
//echo "</pre>";

$numSkills = count($info[0]['skills']);
$numSources = count($info[0]['sources']);
$numPeople = count($info) - 1;

?>
<div class="main">

	<div class="title">
		The web's <span class="highlight">Top <?=$skill?> Coders </span>
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
					<img class="coder_thumb_ad" src='<?=$info[0]['pic']?>'/>
						<div class="name_folder_ad"><?=substr($info[0]['handle'],1,5)?><br/>
							<a href="http://codertrove.com" name="location"></a>
						</div>
					</div>
					<div class="skill_folder_ad">
					<p>Skills: <?php for($i=1;$i<$numSkills;$i++){echo $info[0]['skills'][$i]['name']; echo ", ";}?></p>
						<br/>

 <?php for($i=0;$i<$numSources;$i++){?>
 						<div class="coder_small_source_ad">
						<p><?=$info[0]['sources'][$i+1]['name'];?> <br/>(<?=$info[0]['sources'][$i+1]['karma'];?>)</p>
						</div> 
<?}?>
					</div>
					<div class="sample_comment">
					<p>"<?php if(strlen($info[0]['activity'][1]['commentbody']) > 140){ substr($info[0]['activity'][1]['commentbody'],0,140);?>..<?}else{ echo $info[0]['activity'][1]['commentbody']; }?>"</p>	
					</div>					
					</div>
				</div>

 <?php for($i=1;$i<$numPeople;$i++){?>
				<div class="compact_folder">
					<div class="compact_folder_container_internal">
					<div class="compact_folder_photo">
						<img class="coder_thumb_ad" src='<?=$info[$i]['pic']?>'/>
						<div class="name_folder_ad"><?=substr($info[$i]['handle'],1,5)?><br/>
							<a href="http://codertrove.com"></a>
						</div>
					</div>
					<div class="skill_folder_ad">
						<p>Skills: <?php for($d=1;$d<$numSkills;$d++){echo $info[$i]['skills'][$d]['name']; echo ", ";}?></p>
						<br/>	
 <?php for($g=0;$g<$numSources;$g++){?>
						<div class="coder_small_source_ad">
							<p><?=$info[$i]['sources'][$g+1]['name'];?> <br/>(<?=$info[$i]['sources'][$g+1]['karma'];?>)</p>
						</div> 
<?}?>

					</div>
					</div>
				</div>
<?}?>
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
