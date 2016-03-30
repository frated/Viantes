<?php
$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;
$haveElement = $bean != null && count($bean->getImgFileNameArray()) > 0 
?>

<p><b><?php echo $haveElement ? $X_langArray['CREATE_REVIEW_MOV_TXT1'] : $X_langArray['CREATE_REVIEW_NO_MOV_TXT1']?></b></p>

<form id="attachMovFrm" enctype="multipart/form-data" action="/viantes/pvt/pages/review/create/createReview.php" method="POST">
	<?php 
	if (!isset($_GET['finish'])) { ?>
		<br><br>
		<p><?php echo $haveElement ?  $X_langArray['CREATE_REVIEW_MOV_TXT2'] : $X_langArray['CREATE_REVIEW_NO_MOV_TXT2']?></p>
		<br><br>

		<!-- Upload Button - il tag img e' visibile per i browser, l'input-type-file per ie -->
		<img class="hideInIE crs-pnt" src="/viantes/pvt/img/review/attachMov_32.png" onclick="$('#attachMovInput').click();">
		<input id="attachMovInput" name="userfile" class="hideInputButtonFile" type="file" 
			   onchange="setFieldsAndErrMsgReviewPage(3);$('#submitMov').click();
						 showOverlayForVideo('<?php echo $X_langArray['CREATE_REVIEW_MOV_WAIT_LOAD'] ?>')" /> <?php
	}
	if ( isset($_GET['loadMovErrMsg']) ) { ?>
		<p class="articleLoadMovError"><?php echo urldecode($_GET['loadMovErrMsg']) ?></p>
	<?php } ?>

	<br><br>

	<?php include $X_root."pvt/pages/review/common/renderMovFromSession.php"; ?>
	
	<!-- UPLOAD -->
	<input class="hidden" id="submitMov" name="submit" type="submit" value="GO"/>

	<input type="hidden" name="MAX_FILE_SIZE" value="20000"/>
	<input type="hidden" name="tabactive" value="3" id="movtabactive"/>
	<input type="hidden" name="type" value="MOV"/>
	<input type="hidden" name="backUrl" value="<?php echo $backUrl?>" />
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
		
	<input id="catRevHidden_3"   type="hidden" name="catRev" value="" />
	<input id="countryHidden_3"  type="hidden" name="country" value="" />
	<input id="localityHidden_3" type="hidden" name="locality" value="" />
	<input id="siteHidden_3"     type="hidden" name="site" value="" />
	<input id="descrHidden_3"    type="hidden" name="descr" value="" />
	<input id="arriveHidden_3"   type="hidden" name="arrive" value="" />
	<input id="warnHidden_3"     type="hidden" name="warn" value="" />
	<input id="whEatHidden_3"    type="hidden" name="whEat" value="" />
	<input id="cookHidden_3"     type="hidden" name="cook" value="" />
	<input id="whStayHidden_3"   type="hidden" name="whStay" value="" />
	<input id="mythHidden_3"     type="hidden" name="myth" value="" />
	<input id="voteHidden_3"     type="hidden" name="vote" value="" />
	
	<input id="catRevErrMsgHidden_3" 	type="hidden" name="catRevErrMsg" value="" />
	<input id="countryErrMsgHidden_3"	type="hidden" name="countryErrMsg" value="" />
	<input id="localityErrMsgHidden_3"   type="hidden" name="localityErrMsg" value="" />
	<input id="siteErrMsgHidden_3"   	type="hidden" name="siteErrMsg" value="" />
	<input id="descrErrMsgHidden_3"  	type="hidden" name="descrErrMsg" value="" />
	<input id="arriveErrMsgHidden_3" 	type="hidden" name="arriveErrMsg" value="" />
	<input id="warnErrMsgHidden_3"   	type="hidden" name="warnErrMsg" value="" />
	<input id="whEatErrMsgHidden_3"  	type="hidden" name="whEatErrMsg" value="" />
	<input id="cookErrMsgHidden_3"  	type="hidden" name="cookErrMsg" value="" />
	<input id="whStayErrMsgHidden_3"  	type="hidden" name="whStayErrMsg" value="" />
	<input id="mythErrMsgHidden_3"  	type="hidden" name="mythErrMsg" value="" />

</form>
