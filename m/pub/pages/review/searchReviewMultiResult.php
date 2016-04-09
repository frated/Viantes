<?php
$X_root = "../../../../viantes/";
$X_page = "searchReviewMultiResult";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDO.php";
require_once $X_root."pvt/pages/review/cityReviewDO.php";
require_once $X_root."pvt/pages/review/countryReviewDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

//Controlli sui dati in sessione
if ( !isset($_SESSION["SEARCH_REVIEW_MULTI_RESULT"]) ) {
	header('Location: '.getURI().'/viantes/pub/pages/error.php?reason='.urlencode($X_langArray['ERROR_REASON_SESSION_EXPIRED']));
	exit;
}

$reviewDOArray = unserialize($_SESSION["SEARCH_REVIEW_MULTI_RESULT"]);
if ( count($reviewDOArray) < 2 ) {
	header('Location: '.getURI().'/viantes/pub/pages/error.php?reason='.urlencode($X_langArray['ERROR_REASON_SESSION_EXPIRED']));
	exit;
}
//tipo di recensione
$tp = $_GET['tp'];
?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-US">
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html><!--<![endif]-->

<head>
	<title><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div class="main-div">
		
		<div class="body-div">
			
			<div class="mrg-top-24">
				<a href="/viantes/pub/pages/review/searchReview.php">
					<?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_BACK'] ?>
				</a>
			</div>
			
			<div class="top-header mrg-top-24">
				<h1><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_H1']?></h1>
			</div>
			<div class="second-header-font14">
				<p><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_H2']?></p>
			</div>
				
			<div class="searchRevMultiContentDiv">
				<div class="searchRevMultiHeaderDiv">
					<?php if ($tp == SiteReview) {?>
						<b><p class="searchRevMultiHeaderP1"><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_SITE']?> </p></b>
						<b><p class="searchRevMultiHeaderP2"><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_LOC']?></p></b>
						<b><p class="searchRevMultiHeaderP3"><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_COUNTRY']?> </p></b>
					<?php } else if ($tp == CityReview) {?>
						<b><p class="searchRevMultiHeaderP1"><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_CITY']?> </p></b>
						<b><p class="searchRevMultiHeaderP21"><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_COUNTRY']?></p></b>
					<?php } else if ($tp == CountryReview) {?>
						<b><p class="searchRevMultiHeaderP1"><?php echo $X_langArray['SEARCH_REVIEW_MULTI_RES_COUNTRY']?></p></b>
					<?php } ?>		
				</div>	
				<?php 
				foreach ($reviewDOArray as $key => $reviewDO) { ?>
					<div id="searchRevMultiMainDiv" class="searchRevMultiMainDiv">
						<?php 
						// - SiteReview - 
						if ($tp == SiteReview) {?>
							<a href="/viantes/pvt/pages/review/search/searchReviewFromMultiResult.php?placeId=<?php echo X_code($reviewDO->getSiteId()) ?>&reviewType=1">
								<p class="searchRevMultiP1"><?php 
									echo strlen($reviewDO->getSiteName()) > 20 ?
										 (substr($reviewDO->getSiteName(), 0, 18). "...") :
										 $reviewDO->getSiteName(); 
								?></p>
								<p class="searchRevMultiP2"><?php 
									echo strlen($reviewDO->getLocality()) > 30 ?
										 (substr($reviewDO->getLocality(), 0, 25). "...") :
										 $reviewDO->getLocality();
								?></p>
								<p class="searchRevMultiP3"><?php 
									echo strlen($reviewDO->getCountry()) > 20 ?
										 (substr($reviewDO->getCountry(), 0, 18). "...") :
										 $reviewDO->getCountry();
								?></p>
							</a>
						<?php 
						// - CityReview - 
						} else if ($tp == CityReview) { ?>
							<a href="/viantes/pvt/pages/review/search/searchReviewFromMultiResult.php?placeId=<?php echo X_code($reviewDO->getCityId()) ?>&reviewType=2">
								<p class="searchRevMultiP1"><?php 
									echo strlen($reviewDO->getCityName()) > 20 ?
										 (substr($reviewDO->getCityName(), 0, 18). "...") :
										 $reviewDO->getCityName(); 
								?></p>
								<p class="searchRevMultiP21">
									<?php echo $reviewDO->getCountry(); ?>
								</p></p>
							</a>
						<?php 
						// - CountryReview - 
						} else if ($tp == CountryReview) { ?>
							<a href="/viantes/pvt/pages/review/search/searchReviewFromMultiResult.php?placeId=<?php echo X_code($reviewDO->getCountryId()) ?>&reviewType=3">
								<p class="searchRevMultiP1">
									<?php echo $reviewDO->getCountry(); ?>
								</p>
							</a>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			
		</div><!--body-div-->
		
	</div><!--main-div-->
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
			
</body>
</html>
