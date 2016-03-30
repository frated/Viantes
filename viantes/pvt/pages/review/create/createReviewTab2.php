<?php
$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;
$haveElement = $bean != null && count($bean->getImgFileNameArray()) > 0 
?>
		
<p><b><?php echo $haveElement ? $X_langArray['CREATE_REVIEW_IMG_TXT1'] : $X_langArray['CREATE_REVIEW_NO_IMG_TXT1']?></b></p>

<form id="attachImgFrm" enctype="multipart/form-data" action="/viantes/pvt/pages/review/create/createReview.php" method="POST">
	<?php 
	if (!isset($_GET['finish'])) { ?>
		<br><br>
		<p><?php echo $haveElement ?  $X_langArray['CREATE_REVIEW_IMG_TXT2'] : $X_langArray['CREATE_REVIEW_NO_IMG_TXT2']?></p>
		<br><br>

		<!-- Upload Button - il tag img e' visibile per i browser, l'input-type-file per ie -->
		<img class="hideInIE crs-pnt" src="/viantes/pvt/img/review/attachImg_32.png" onclick="$('#attachImgInput').click();">	
		<input id="attachImgInput" class="hideInputButtonFile" name="userfile" type="file" 
			   onchange="setFieldsAndErrMsgReviewPage(2); $('#loadImgT2').show(); $('#submitImg').click();"/> <?php
	}
	if ( isset($_GET['loadImgErrMsg']) ) { ?>
		<p class="articleLoadImgError"><?php echo urldecode($_GET['loadImgErrMsg']) ?></p>
	<?php } ?>
		 
	<br><br>

	<?php include $X_root."pvt/pages/review/common/renderImgFromSession.php"; ?>

	<img id="loadImgT2" class="loadImgDoc" style="display:none" src="/viantes/pvt/img/animate/ld_32_ffffff.gif"/>

	<!-- UPLOAD -->
	<input class="hidden" id="submitImg" name="submit" type="submit" value="GO"/>

	<input type="hidden" name="MAX_FILE_SIZE" value="20000"/>
	<input type="hidden" name="tabactive" value="2" id="imgtabactive"/>
	<input type="hidden" name="type" value="IMG"/>
	<input type="hidden" name="backUrl" value="<?php echo $backUrl?>" />
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
		
	<input id="catRevHidden_2"   type="hidden" name="catRev" value="" />
	<input id="countryHidden_2"  type="hidden" name="country" value="" />
	<input id="localityHidden_2" type="hidden" name="locality" value="" />
	<input id="siteHidden_2"     type="hidden" name="site" value="" />
	<input id="descrHidden_2"    type="hidden" name="descr" value="" />
	<input id="arriveHidden_2"   type="hidden" name="arrive" value="" />
	<input id="warnHidden_2"     type="hidden" name="warn" value="" />
	<input id="whEatHidden_2"    type="hidden" name="whEat" value="" />
	<input id="cookHidden_2"     type="hidden" name="cook" value="" />
	<input id="whStayHidden_2"   type="hidden" name="whStay" value="" />
	<input id="mythHidden_2"     type="hidden" name="myth" value="" />
	<input id="voteHidden_2"     type="hidden" name="vote" value="" />
	
	<input id="catRevErrMsgHidden_2" 	type="hidden" name="catRevErrMsg" value="" />
	<input id="countryErrMsgHidden_2"	type="hidden" name="countryErrMsg" value="" />
	<input id="localityErrMsgHidden_2"  type="hidden" name="localityErrMsg" value="" />
	<input id="siteErrMsgHidden_2"   	type="hidden" name="siteErrMsg" value="" />
	<input id="descrErrMsgHidden_2"  	type="hidden" name="descrErrMsg" value="" />
	<input id="arriveErrMsgHidden_2" 	type="hidden" name="arriveErrMsg" value="" />
	<input id="warnErrMsgHidden_2"   	type="hidden" name="warnErrMsg" value="" />
	<input id="whEatErrMsgHidden_2"  	type="hidden" name="whEatErrMsg" value="" />
	<input id="cookErrMsgHidden_2"  	type="hidden" name="cookErrMsg" value="" />
	<input id="whStayErrMsgHidden_2"  	type="hidden" name="whStayErrMsg" value="" />
	<input id="mythErrMsgHidden_2"  	type="hidden" name="mythErrMsg" value="" />
</form>
