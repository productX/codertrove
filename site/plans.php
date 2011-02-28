<html><head>
<script type="text/javascript">
moveAmount=25;

function mouseOverCard(cardID) {
	blockName = "";
	moveMultiplier = 1;
	switch(cardID) {
		case 1: blockName="blocks_1week"; moveMultiplier=-1; break;
		case 2: blockName="blocks_1month"; moveMultiplier=0; break;
		case 3: blockName="blocks_3months"; break;
		case 4: blockName="blocks_1year"; break;
		break;
	}
	if(blockName!="") {
//		document.getElementById(blockName).style.marginLeft+=moveAmount*moveMultiplier;
	}
}

function mouseOutCard(cardID) {
	blockName = "";
	moveMultiplier = 1;
	switch(cardID) {
		case 1: blockName="blocks_1week"; moveMultiplier=-1; break;
		case 2: blockName="blocks_1month"; moveMultiplier=0; break;
		case 4: blockName="blocks_1year"; break;
		case 3: blockName="blocks_3months"; break;
		break;
	}
	if(blockName!="") {
//		document.getElementById(blockName).style.marginLeft-=moveAmount*moveMultiplier;
	}
}

function mouseClicked(cardID) {
	if(cardID) {
		window.location.replace('paypal_redirector.php?planID='+cardID);
	}
}
</script>


</head><body>
<?php
require_once 'inc/master_inc.php';
require_once 'inc/payments.php';

render_header();
?>

<div class="main">

	<div class="title">
		Get full access to <span class="highlight">Top Software Engineers </span> in just seconds!
		<br/> 
	</div>
<?php
$bodyText1 = "- Unlimited access<br/>- Database search<br/>- Extended profiles<br/>- Contact info<br/><font size='1'>&nbsp;<br/></font><font size='5'>$".getPlanPrice(1)."</font>";
$bodyText2 = "- <b>BEST DEAL</b><br/>- Unlimited access<br/>- Database search<br/>- Extended profiles<br/>- Contact info<br/><font size='1'>&nbsp;<br/></font><font size='5'>$".getPlanPrice(2)."</font>";
$bodyText3 = "- Unlimited access<br/>- Database search<br/>- Extended profiles<br/>- Contact info<br/><font size='1'>&nbsp;<br/></font><font size='5'>$".getPlanPrice(3)."</font>";
$bodyText4 = "- Unlimited access<br/>- Database search<br/>- Extended profiles<br/>- Contact info<br/><font size='1'>&nbsp;<br/></font><font size='5'>$".getPlanPrice(4)."</font>";
?>
	<div class="body">

		<div class="plans_1week_block" id="blocks_1week" onMouseOver="mouseOverCard(1);" onMouseOut="mouseOutCard(1);" onClick="mouseClicked(1);">
			<div class="plans_1week_block_cal_icon"></div>
			<div class="plans_block_small_title">Week</div>
			<div class="plans_block_small_bodytext"><?=$bodyText1?></div>
			<div class="plans_block_small_button">Buy Now</div>
		</div>

		<div class="plans_1month_block" id="blocks_1month" onMouseOver="mouseOverCard(2);" onMouseOut="mouseOutCard(2);" onClick="mouseClicked(2);">
			<div class="plans_1month_block_cal_icon"></div>
			<div class="plans_block_large_title">Month</div>
			<div class="plans_block_large_bodytext"><?=$bodyText2?></div>
			<div class="plans_block_large_button">Buy Now</div>
		</div>

		<div class="plans_3month_block" id="blocks_3month" onMouseOver="mouseOverCard(3);" onMouseOut="mouseOutCard(3);" onClick="mouseClicked(3);">
			<div class="plans_3month_block_cal_icon"></div>
			<div class="plans_block_small_title">Months</div>
			<div class="plans_block_small_bodytext"><?=$bodyText3?></div>
			<div class="plans_block_small_button">Buy Now</div>
		</div>

		<div class="plans_1year_block" id="blocks_1year" onMouseOver="mouseOverCard(4);" onMouseOut="mouseOutCard(4);" onClick="mouseClicked(4);">
			<div class="plans_1year_block_cal_icon"></div>
			<div class="plans_block_small_title">Year</div>
			<div class="plans_block_small_bodytext"><?=$bodyText4?></div>
			<div class="plans_block_small_button">Buy Now</div>
		</div>
		
	</div>
	<img style="background-repeat:repeat-x; background-color:#80b0e4; width:990px;" src="images/Ad_WaveBackground.png"/>
</div>

<?php
//render_footer();
?>
</body></html>