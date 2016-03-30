<?php
$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;
$haveElement = $bean != null && count($bean->getImgFileNameArray()) > 0 
?>

<p><b><?php echo $haveElement ? $X_langArray['CREATE_REVIEW_DOC_TXT1'] : $X_langArray['CREATE_REVIEW_NO_DOC_TXT1']?></b></p>

<form id="attachDocFrm" enctype="multipart/form-data" action="/viantes/pvt/pages/review/create/createCityRev.php" method="POST">
	<?php 
	if (!isset($_GET['finish'])) { ?>
		<br><br>
		<p><?php echo $haveElement ?  $X_langArray['CREATE_REVIEW_DOC_TXT2'] : $X_langArray['CREATE_REVIEW_NO_DOC_TXT2']?></p>
		<br><br>

		<!-- Upload Button - il tag img e' visibile per i browser, l'input-type-file per ie -->
		<img class="hideInIE crs-pnt" src="/viantes/pvt/img/review/attachDoc_32.png" onclick="$('#attachDocInput').click();" >
		<input id="attachDocInput" name="userfile" type="file" class="hideInputButtonFile" 
			   onchange="setFieldsAndErrMsgReviewPage(4); $('#loadImgT4').show(); $('#submitDoc').click();"/> <?php
	} 	 
	if ( isset($_GET['loadDocErrMsg']) ) { ?>
		<p class="articleLoadDocError"><?php echo urldecode($_GET['loadDocErrMsg']) ?></p>
	<?php } ?>

	<br><br>

	<?php include $X_root."pvt/pages/review/common/renderDocFromSession.php"; ?>

	<img id="loadImgT4" class="loadImgDoc" style="display: none" src="/viantes/pvt/img/animate/ld_32_ffffff.gif"/>

	<!-- UPLOAD -->
	<input class="hidden" id="submitDoc" name="submit" type="submit" value="GO"/>
	
	<input type="hidden" name="MAX_FILE_SIZE" value="20000"/>
	<input type="hidden" name="tabactive" value="4" id="doctabactive"/>
	<input type="hidden" name="type" value="DOC"/>
	<input type="hidden" name="backUrl" value="<?php echo $backUrl?>" />
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />			
		
	<input id="cityHidden_4"     type="hidden" name="city" value="" />
	<input id="catRevHidden_4"   type="hidden" name="catRev" value="" />
	<input id="countryHidden_4"  type="hidden" name="country" value="" />
	<input id="localityHidden_4" type="hidden" name="locality" value="" />
	<input id="siteHidden_4"     type="hidden" name="site" value="" />
	<input id="descrHidden_4"    type="hidden" name="descr" value="" />
	<input id="arriveHidden_4"   type="hidden" name="arrive" value="" />
	<input id="warnHidden_4"     type="hidden" name="warn" value="" />
	<input id="whEatHidden_4"    type="hidden" name="whEat" value="" />
	<input id="cookHidden_4"     type="hidden" name="cook" value="" />
	<input id="whStayHidden_4"   type="hidden" name="whStay" value="" />
	<input id="mythHidden_4"     type="hidden" name="myth" value="" />
	<input id="voteHidden_4"     type="hidden" name="vote" value="" />
	
	<input id="cityErrMsgHidden_4"      type="hidden" name="cityErrMsg" value="" />
	<input id="catRevErrMsgHidden_4" 	type="hidden" name="catRevErrMsg" value="" />
	<input id="countryErrMsgHidden_4"	type="hidden" name="countryErrMsg" value="" />
	<input id="localityErrMsgHidden_4"  type="hidden" name="localityErrMsg" value="" />
	<input id="siteErrMsgHidden_4"   	type="hidden" name="siteErrMsg" value="" />
	<input id="descrErrMsgHidden_4"  	type="hidden" name="descrErrMsg" value="" />
	<input id="arriveErrMsgHidden_4" 	type="hidden" name="arriveErrMsg" value="" />
	<input id="warnErrMsgHidden_4"   	type="hidden" name="warnErrMsg" value="" />
	<input id="whEatErrMsgHidden_4"  	type="hidden" name="whEatErrMsg" value="" />
	<input id="cookErrMsgHidden_4"  	type="hidden" name="cookErrMsg" value="" />
	<input id="whStayErrMsgHidden_4"  	type="hidden" name="whStayErrMsg" value="" />
	<input id="mythErrMsgHidden_4"  	type="hidden" name="mythErrMsg" value="" />
</form>
