<?php 
/**
 * Cerca e renderizza la lista delle recensioni di un dato utente.
 * Per includere questa pagina settare nella pagina madre le variabili $X_userId ed X_pattern.
 * 
 * @see /viantes/pub/pages/profile/myReview.php
 * @see /viantes/pub/pages/profile/myProfile.php
 * @see /viantes/pub/pages/profile/showProfile.php
 */
 
/**************  SITE REVIEW SECTION **************/
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/reviewDO.php";

//istanzio la classe di ReviewDAO
$reviewDAO = New ReviewDAO();
$reviewDOArray = $reviewDAO->getReviewList($X_userId, $X_pattern);?>

<div class="userReviewTitleDiv">
	<h3><?php echo $X_langArray['MY_REV_SITE_TITLE']?></h3>
</div>
<?php
if (count($reviewDOArray) == 0) { ?>
	<div class="userReviewContentDiv">
		<?php echo $X_langArray['MY_REV_NO_REV']; ?>
	</div>	
<?php 
} else if (count($reviewDOArray) > 0) { 
?>
	<div class="userReviewContentDiv">
		<div class="userReviewHeaderDiv">
			<b><p class="userReviewHeaderP1">  </p></b>
			<b><p class="userReviewHeaderP2"><?php echo $X_langArray['MY_REV_DT_INS']?></p></b>
			<b><p class="userReviewHeaderP3"><?php echo $X_langArray['MY_REV_SITE']?></p></b>
		</div>	
		<?php 
		foreach ($reviewDOArray as $key => $reviewDO) { ?>
			<div id="userReviewMainDiv" class="userReviewMainDiv">
				<a href="/m/viantes/pub/pages/review/showReview.php?revId=<?php echo X_code($reviewDO->getId()) ?>&reviewType=1">
					<img class="userReviewP1" height="36" style="max-width: 72px;" src="<?php echo $reviewDO->getCoverFileName().RSZD_FOR_IND ?>" />
					<p class="userReviewP2"><?php echo $reviewDO->getDtIns() ?></p>
					<p class="userReviewP3"><?php 
						echo strlen($reviewDO->getSiteName()) > 30 ?
							 (substr($reviewDO->getSiteName(), 0, 28). "...") :
							 $reviewDO->getSiteName(); 
					?></p>
				</a>
			</div>
		<?php } ?>
	</div>
<?php 
} 

/**************  CITY REVIEW SECTION **************/
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDO.php";

//istanzio la classe di ReviewDAO
$cityReviewDAO = New CityReviewDAO();
$reviewDOArray = $cityReviewDAO->getReviewList($X_userId, $X_pattern);?>

<div class="userReviewTitleDiv">
	<h3><?php echo $X_langArray['MY_REV_CITY_TITLE']?></h3>
</div>
<?php

// REVIEW TITLE
if (count($reviewDOArray) == 0) { ?>
	<div class="userReviewContentDiv">
		<?php echo $X_langArray['MY_REV_NO_CITY_REV']; ?>
	</div>	
<?php 
} else if (count($reviewDOArray) > 0) { 
?>
	<div class="userReviewContentDiv">
		<div class="userReviewHeaderDiv">
			<b><p class="userReviewHeaderP1">  </p></b>
			<b><p class="userReviewHeaderP2"><?php echo $X_langArray['MY_REV_DT_INS']?></p></b>
			<b><p class="userReviewHeaderP3"><?php echo $X_langArray['MY_REV_CITY']?></p></b>
		</div>	
		<?php 
		foreach ($reviewDOArray as $key => $reviewDO) { ?>
			<div id="userReviewMainDiv" class="userReviewMainDiv">
				<a href="/m/viantes/pub/pages/review/showReview.php?revId=<?php echo X_code($reviewDO->getId()) ?>&reviewType=2">
					<img class="userReviewP1" height="36" style="max-width: 72px;" src="<?php echo $reviewDO->getCoverFileName().RSZD_FOR_IND ?>" />
					<p class="userReviewP2"><?php echo $reviewDO->getDtIns() ?></p>
					<p class="userReviewP3"><?php echo $reviewDO->getCityName() ?></p>
				</a>
			</div>
		<?php } ?>
	</div>
<?php 
} 

/**************  COUNTRY REVIEW SECTION **************/
require_once $X_root."pvt/pages/review/countryReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDO.php";

//istanzio la classe di ReviewDAO
$countryReviewDAO = New CountryReviewDAO();
$reviewDOArray = $countryReviewDAO->getReviewList($X_userId, $X_pattern);?>

<div class="userReviewTitleDiv">
	<h3><?php echo $X_langArray['MY_REV_COUNTRY_TITLE']?></h3>
</div>
<?php

// REVIEW TITLE
if (count($reviewDOArray) == 0) { ?>
	<div class="userReviewContentDiv">
		<?php echo $X_langArray['MY_REV_NO_COUNTRY_REV']; ?>
	</div>	
<?php 
} else if (count($reviewDOArray) > 0) { 
?>
	<div class="userReviewContentDiv">
		<div class="userReviewHeaderDiv">
			<b><p class="userReviewHeaderP1">  </p></b>
			<b><p class="userReviewHeaderP2"><?php echo $X_langArray['MY_REV_DT_INS']?></p></b>
			<b><p class="userReviewHeaderP3"><?php echo $X_langArray['MY_REV_COUNTRY']?></p></b>
		</div>	
		<?php 
		foreach ($reviewDOArray as $key => $reviewDO) { ?>
			<div id="userReviewMainDiv" class="userReviewMainDiv">
				<a href="/m/viantes/pub/pages/review/showReview.php?revId=<?php echo X_code($reviewDO->getId()) ?>&reviewType=3">
					<img class="userReviewP1" height="36" style="max-width: 72px;" src="<?php echo $reviewDO->getCoverFileName().RSZD_FOR_IND ?>" />
					<p class="userReviewP2"><?php echo $reviewDO->getDtIns() ?></p>
					<p class="userReviewP3"><?php echo $reviewDO->getCountry() ?></p>
				</a>
			</div>
		<?php } ?>
	</div>
<?php 
}
?> 
