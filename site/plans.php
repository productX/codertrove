<html><head>

<script type="text/javascript">
moveAmount = 25;

function mouseOverCard(cardID) {
	blockName = "";
	moveMultiplier = 1;
	switch(cardID) {
		break;
		case 1: blockName="blocks_1week"; moveMultiplier=-1; break;
		case 2: blockName="blocks_1month"; moveMultiplier=0; break;
		case 3: blockName="blocks_3month"; break;
		case 4: blockName="blocks_1year"; break;
	}
	if(blockName!="") {
		document.getElementById(blockName).style.marginLeft+=moveAmount*moveMultiplier;
	}
}

function mouseOutCard(cardID) {
	blockName = "";
	moveMultiplier = 1;
	switch(cardID) {
		break;
		case 1: blockName="blocks_1week"; moveMultiplier=-1; break;
		case 2: blockName="blocks_1month"; moveMultiplier=0; break;
		case 3: blockName="blocks_3month"; break;
		case 4: blockName="blocks_1year"; break;
	}
	if(blockName!="") {
		document.getElementById(blockName).style.marginLeft-=moveAmount*moveMultiplier;
	}
}
</script>
</head><body>

<?php
require_once 'inc/master_inc.php';

render_header();

?>

<div class="main">

	<div class="title">
		Get full access to <span class="highlight">Top Software Engineers </span> in just seconds!
		<br/> 
	</div>
<?php
$bodyTextSmall = "- Unlimited access<br/>- Database search<br/>- Extended profiles<br/>- Contact info";
$bodyTextLarge = "- BEST DEAL<br/>- Unlimited access<br/>- Database search<br/>- Extended profiles<br/>- Contact info";
?>
	<div class="body">

		<div class="plans_1week_block" id="blocks_1week" onMouseOver="mouseOverCard(1);" onMouseOut="mouseOutCard(1);">
			<div class="plans_1week_block_cal_icon"></div>
			<div class="plans_block_small_title">Week</div>
			<div class="plans_block_small_bodytext"><?=$bodyTextSmall?></div>
			<div class="plans_block_small_button">Buy Now</div>
		</div>

		<div class="plans_1month_block" id="blocks_1month" onMouseOver="mouseOverCard(2);" onMouseOut="mouseOutCard(2);">
			<div class="plans_1month_block_cal_icon"></div>
			<div class="plans_block_large_title">Month</div>
			<div class="plans_block_large_bodytext"><?=$bodyTextLarge?></div>
			<div class="plans_block_large_button">Buy Now</div>
		</div>

		<div class="plans_3month_block" id="blocks_3month" onMouseOver="mouseOverCard(3);" onMouseOut="mouseOutCard(3);">
			<div class="plans_3month_block_cal_icon"></div>
			<div class="plans_block_small_title">Months</div>
			<div class="plans_block_small_bodytext"><?=$bodyTextSmall?></div>
			<div class="plans_block_small_button">Buy Now</div>
		</div>

		<div class="plans_1year_block" id="blocks_1year" onMouseOver="mouseOverCard(4);" onMouseOut="mouseOutCard(4);">
			<div class="plans_1year_block_cal_icon"></div>
			<div class="plans_block_small_title">Year</div>
			<div class="plans_block_small_bodytext"><?=$bodyTextSmall?></div>
			<div class="plans_block_small_button">Buy Now</div>
		</div>
		
	</div>
	<img style="background-repeat:repeat-x; background-color:#80b0e4; width:990px;" src="images/Ad_WaveBackground.png"/>
</div>
<?php

//render_footer();
?>

</body></html>
