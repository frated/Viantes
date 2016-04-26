<?php 
/**
 * Pagina comune che renderizza e gestisce la riga contenente l'immagine star, l'immagine see e l'autore
 * 
 * @see in /viantes/pub/pages/review/searchReviewResult.php
 * @see in /viantes/pub/pages/review/showReview.php
 */
?>
<div>
	<!-- STAR/SEE -->
	<div class="flt-l" style="width 45%; margin-top: 32px;">
		
		<!-- Logged User -->
		<?php if ($X_logged) {
			$userDO = unserialize($_SESSION["USER_LOGGED"]);
			$userStarForRevType = $userDO->getStar()[$reviewType];
			
			//l'utente ha messo un star per questa recensione
			if ( !isset($userStarForRevType[$reviewDO->getId()]) ) $visibleStar = false;
			else $visibleStar = $userStarForRevType[$reviewDO->getId()]->getStar(); ?>
			<div id="starDivId" class="dspl-inln-blk">
				<!-- STAR IMG YES -->
				<img id="userStarYes" src="/viantes/pvt/img/review/star.png" class="starImg <?php if (!$visibleStar) echo " hidden"?>"
					 onclick="doStar('undo', '<?php echo $reviewDO->getId()?>', '<?php echo $reviewType ?>', 1); 
							  cangeStarVal('-1'); $('#userStarYes').hide(); $('#userStarNo').show();" />
				<!-- STAR IMG NO -->
				<img id="userStarNo" src="/viantes/pvt/img/review/star_666.png" class="starImg <?php if ($visibleStar) echo " hidden"?>"
					 onclick="doStar('do', '<?php echo $reviewDO->getId()?>', '<?php echo $reviewType ?>', 1); 
							  cangeStarVal('+1'); $('#userStarNo').hide(); $('#userStarYes').show();" />
				<b><p class="starP"><?php echo $X_star_cnt?></p></b>
			</div>
			<?php
			//l'utente ha messo un see per questa recensione
			if ( !isset($userStarForRevType[$reviewDO->getId()]) ) $visibleSee = false;
			else $visibleSee = $userStarForRevType[$reviewDO->getId()]->getSee(); ?>
			<div id="seeDivId" class="dspl-inln-blk">
				<!-- SEE IMG YES -->
				<img id="userSeeYes" src="/viantes/pvt/img/review/see.png" class="seeImg <?php if (!$visibleSee) echo " hidden"?>"
					 onclick="doStar('undo', '<?php echo $reviewDO->getId()?>', '<?php echo $reviewType ?>', 2); 
							  cangeSeeVal('-1'); $('#userSeeYes').hide(); $('#userSeeNo').show();" /> 
				<!-- SEE IMG NO -->
				<img id="userSeeNo" src="/viantes/pvt/img/review/see_666.png" class="seeImg <?php if ($visibleSee) echo " hidden"?>"
					 onclick="doStar('do', '<?php echo $reviewDO->getId()?>', '<?php echo $reviewType ?>', 2); 
							  cangeSeeVal('+1'); $('#userSeeNo').hide();$('#userSeeYes').show();" /> 
				<b><p class="seeP"><?php echo $X_see_cnt?></p></b>
			</div>
			<?php
			//Determino se l'utente ha gia' fatto un post
			if ( !isset($userStarForRevType[$reviewDO->getId()]) ) $justPost = false;
			else $justPost = $userStarForRevType[$reviewDO->getId()]->getPost() != ''; ?>
			<div id="postDivId" class="dspl-inln-blk">
				<img id="userPostYes" src="/viantes/pvt/img/review/post<?php if (!$justPost) echo "_666"?>.png" 
					 class="postImg" onclick="$('#postDivBox').css('display', 'inline-block')" /> 
				<b><p class="postP"><?php echo $X_post_cnt?></p></b>
			</div>
			
			<!-- SSP link -->
			<div id="postDivId" class="sspDiv">
				<a href="#"	onclick="renderSSP(<?php echo "'".X_code($reviewDO->getId())."','".$reviewType."'"?>);"><?php echo $X_langArray['SEARCH_REVIEW_SEE_ALL'] ?></a>
			</div>
				
		<!-- NO Logged User -->		
		<?php } else {
			$dest = "/viantes/pub/pages/profile/showProfile.php?usrId=".X_code($reviewDO->getUsrId());?>
			<div id="starDivId" class="dspl-inln-blk">
				<img src="/viantes/pvt/img/review/star_666.png" class="starImg"
					 onclick="onclick=$('#ovrly-initial-login-dst-page').val('<?php echo $page?>'); showOvrlyLgSg();" />
				<b><p class="starP"><?php echo $X_star_cnt?></p></b>
			</div>	
			<div id="starDivId" class="dspl-inln-blk">	
				<img src="/viantes/pvt/img/review/see_666.png" class="seeImg"
					 onclick="onclick=$('#ovrly-initial-login-dst-page').val('<?php echo $page?>'); showOvrlyLgSg();" />
				<b><p class="seeP"><?php echo $X_see_cnt?></p></b>
			</div>		
			<div id="postDivId" class="dspl-inln-blk">	
				<img src="/viantes/pvt/img/review/post_666.png" class="postImg"
					 onclick="onclick=$('#ovrly-initial-login-dst-page').val('<?php echo $page?>'); showOvrlyLgSg();" />
				<b><p class="postP"><?php echo $X_post_cnt?></p></b>
			</div>
		<?php }?>
	</div>
	
	<!-- AUTORE -->
	<div class="flt-r mrg-bot-36" style="width 54%">
		<b><p class="dspl-inln-blk">
			<?php echo $X_langArray['SEARCH_REVIEW_RESULT_AUTHOR'] ?>
		</p></b>
		<?php 
		if ($X_logged){  
			$userDO = unserialize($_SESSION["USER_LOGGED"]); ?>
			<a class="dspl-inln-blk" href="/viantes/pub/pages/profile/showProfile.php?usrId=<?php echo X_code($reviewDO->getUsrId()); ?>" >
				<?php echo $reviewDO->getUsrName() ?>
				<img <?php echo IMG_36_36 ?> src="<?php echo $reviewDO->getUserCoverFileName() ?>" />
			</a>
		<?php 
		} else {
			if ( isMobile() || isTablet() ) {
				$event = "showViewLogin();";
			} else {
				$event = "$('#ovrly-initial-login-dst-page').val('". $dest ."'); showOvrlyLgSg();";	
			} ?>
			<a class="dspl-inln-blk" href="#" onclick="<?php echo $event?>" >
				<?php echo $reviewDO->getUsrName() ?>
				<img <?php echo IMG_36_36 ?> src="<?php echo $reviewDO->getUserCoverFileName() ?>" />
			</a>
		<?php }?>
	</div>
</div>

<!-- Post Div -->
<?php if (isset($userDO)) { ?>	
	<div id="postDivBox" style="width: 100%; margin-top: -12px" class="postDiv">
		<p class="dspl-inln-blk mrg-lft-3">
			<?php echo $X_langArray['SSO_POST_COMMENT'] ?>
		</p>
		<img src="/viantes/pvt/img/common/close_666.png" onclick="$('#postDivBox').hide()" class="flt-r" width="16">
		<textarea style="height: 36px; width: 86%; margin-left:3px" maxlength="140"
				  name="postTxtArea" id="postTxtArea"><?php if ($justPost) echo $userStarForRevType[$reviewDO->getId()]->getPost()?></textarea>
		<button class="personalButton mrg-top-6" onclick="doPost('<?php echo $reviewDO->getId()?>', '<?php echo $reviewType ?>', <?php echo $justPost ? "true" : "false" ?>)">
			<?php echo $X_langArray['SSO_POST_SEND'] ?>
		</button>
	</div>
<?php } ?>
