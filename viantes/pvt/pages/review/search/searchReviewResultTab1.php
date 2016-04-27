<div class="mrg-bot-40">
	
	<?php 
	//immagine riscalata
	$fileName = $reviewDO->getCoverFileName().RSZD_FOR_RVW;
	$widthIn  = $reviewDO->getCoverWidth();
	$heightIn = $reviewDO->getCoverHeight();
	?>
	
	<div class="showRevRow" style="margin-top: 10px">
		<h1><?php echo $X_langArray['CREATE_REVIEW_FIELD_COVER']?></h1>
		<div class="showRevCover">
			<a onclick="window.open('/viantes/pub/pages/media/viewer.php?revId=<?php echo X_code($reviewDO->getId())?>&revTp=<?php echo $reviewType ?>&revAttachId=-1','_blank')" href="#">
				<img width="320" src="<?php echo $fileName ?>">
			</a>
		</div>
	</div>

	<div class="showRevRow">
		<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_REVIEW']?></h1>
		<p><?php echo stripslashes( $reviewDO->getDescr() )?></p>
	</div>
	
	<?php 
	if ($reviewDO->getHowToArrive() != "") { ?>
		<div class="showRevRow">
			<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_HOW_ARRIVE']?></h1>
			<p>
				<?php echo stripslashes( $reviewDO->getHowToArrive() ) ?>
			</p>
		</div>
	<?php } 
	if ( $reviewType == CityReview || $reviewType == CountryReview) {?>
		<div class="showRevRow">
			<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_TO_VISIT']?></h1>
			<div>
				<?php 
				foreach ($interestArray as $k => $v) {
					$rt = $reviewType == CityReview ? SiteReview : CityReview; ?>
					<div class="innerInterestDiv">
						<a onclick="window.open('/viantes/pub/pages/review/showReview.php?revId=<?php echo X_code($v['id']) ?>&reviewType=<?php echo $rt ?>','_blank')" href="#">
							<img height="64px" src="<?php echo $v['coverFileName']?>" >
							<b><p><?php echo $v['siteName']?></p></b>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>		
	<?php } 
	if ($reviewDO->getWarning() != "") { ?>
		<div class="showRevRow">
			<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_WARN']?></h1>
			<p>
				<?php echo stripslashes( $reviewDO->getWarning() ) ?>
			</p>
		</div>
	<?php } 
	if ($reviewType != CountryReview && $reviewDO->getWhereToEat() != "") { ?>
		<div class="showRevRow">
			<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_WHERE_TO_EAT']?></h1>
			<p>
				<?php echo stripslashes( $reviewDO->getWhereToEat() )?>
			</p>
		</div>
	<?php } 
	if ( $reviewDO->getCooking() != '') {?>
		<div class="showRevRow">
			<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_COOKING']?></h1>
			<p>
				<?php echo stripslashes( $reviewDO->getCooking() )?>
			</p>
		</div>
	<?php }
	if ( $reviewType != CountryReview && $reviewDO->getWhereToStay() != '') {?>
		<div class="showRevRow">
			<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_WHERE_TO_STAY']?></h1>
			<p>
				<?php echo stripslashes( $reviewDO->getWhereToStay() )?>
			</p>
		</div>
	<?php } 
	if ($reviewDO->getMyth() != "") { ?>
		<div class="showRevRow">
			<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_MITH']?></h1>
			<p>
				<?php echo stripslashes( $reviewDO->getMyth() )?>
			</p>
		</div>
	<?php }  ?>
	
	<div class="showRevRow">	
		<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_VOTE']?></h1>
		<?php $vote = $reviewDO->getVote();
		for ( $i = 1; $i <= $vote; $i++ ) { ?>
			<img height="12" width="12" class="showRevVote" src="/viantes/pvt/img/review/vote1.png"/>
		<?php  } ?>	
	</div>

</div>
