<?php
$X_root = "../../../";
$X_page = "showReview";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/reviewDO.php";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

$revId = isset($_GET['revId']) ? X_deco($_GET['revId']) : -1;
if ( $revId == -1 ){
	header('Location: '.getURI().'/viantes/pub/pages/error.php');
	exit;
}

$reviewType = $_GET['reviewType'];
if (!isset($revId) ){
	header('Location: '.getURI().'/viantes/pub/pages/error.php');
	exit;
}

//chi setta questo parametro non vuole che si resettino i dati in sessione
if (!isset($_GET['noCleanSess'])) {
	cleanSesison($X_page);
}

//echo $reviewType; exit;
//istanzio la classe ReviewDAO
if ( $reviewType == SiteReview ) {
	$reviewDAO = New ReviewDAO();
} else if ( $reviewType == CityReview ) {
	$reviewDAO = New CityReviewDAO();
	$interestArray = $reviewDAO->getInterestByRevId($revId);
}
else if ( $reviewType == CountryReview ) {
	$reviewDAO = New CountryReviewDAO();
	$interestArray = $reviewDAO->getInterestByRevId($revId);
}
//ReviewDO
$reviewDO = $reviewDAO->getReviewById($revId);


//Star and See
$X_star_cnt = isset($reviewDO) ? $reviewDO->getCntStar() : 0;
$X_see_cnt  = isset($reviewDO) ? $reviewDO->getCntSee()  : 0;
$X_post_cnt = isset($reviewDO) ? $reviewDO->getCntPost() : 0;

//===================== GEO ===========================
//Vince su du tutti, se true non ci sono mappe
$X_GEO_disableMAP = false;
//Se true mostra la mappa se false no (nota la mappa viene sempre caricata e' solo nascosta o meno)
$X_GEO_loadMap = true;
//Se true disabilita i comandi utente sulla mappa
$X_GEO_disableUI = true;  

$X_GEO_mapType = 'roadmap'; 

if ( isset($reviewDO) ) {
	if ( $reviewType == SiteReview ) {
		$placeName = $reviewDO->getSiteName();
		$X_GEO_site = $placeName;
		$X_GEO_locality = $reviewDO->getLocality();
		$X_GEO_zoom = 17;
		$X_GEO_ERR_MSG = $X_langArray['CREATE_REVIEW_ADDRS_NOT_VALID'];
		$requiredJSMap = "pvt/pages/geo/siteMap.html";
	}
	else if ( $reviewType == CityReview ) {
		$placeName = $reviewDO->getCityName();
		$X_GEO_city = $placeName;
		$X_GEO_zoom = 11;
		$X_GEO_ERR_MSG = $X_langArray['CREATE_CITY_ADDRS_NOT_FOUND'];
		$requiredJSMap = "pvt/pages/geo/cityMap.html";
	}
	else if ( $reviewType == CountryReview ) {
		$placeName = $reviewDO->getCountry();
		$X_GEO_country = $placeName;
		$X_GEO_zoom = 5;
		$X_GEO_ERR_MSG = $X_langArray['CREATE_COUNTRY_ADDRS_NOT_FOUND'];
		$requiredJSMap = "pvt/pages/geo/countryMap.html";
	}
}
else {
	header('Location: '.getURI().'/viantes/pub/pages/error.php');
	exit;
}
//====================================================

$activeTabIdx = 1;
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
	<title>
		<?php echo $placeName;?>
	</title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>


<?php 
//Overlay-login-signin
include $X_root."pvt/pages/common/overlay-login-signin.html";
$page="/viantes/pub/pages/review/showReview.php?revId=".$_GET['revId']."&reviewType=".$_GET['reviewType'];

//Overlay-star-post-list
include $X_root."pvt/pages/review/common/overlayStarPostList.html";
?>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html";
		  if (!$X_GEO_disableMAP) require_once $X_root.$requiredJSMap;?>
	
	<div id="main-div" class="main-div">
		
		<div class="body-div">

			<?php include $X_root."pvt/pages/common/globalTopMsg.php"; ?>

			<div id="showReview">

				<!-- Place name -->
				<div>
					<div class="top-header">
						<h1><?php echo $placeName;?></h1>
					</div>
					<div>
						<!-- Indirizzo -->
						<?php if ( $reviewType == SiteReview ) {?>
							<p class="showRevHader"><?php echo $reviewDO->getLocality() ?></p>
						<?php } else if ( $reviewType == CityReview ) {?>
							<p class="showRevHader"><?php echo $reviewDO->getFormattedLocality() ?></p>
						<?php } ?>
					</div>
				</div>
				
				<!-- THE MAP -->
				<?php if (!$X_GEO_disableMAP) { ?>
					<div class="showRevMapDiv">
						<div id="map"     class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>" style="height: 200px;"></div>
						<div id="loadMap" class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>" style="height: 200px;">
							<img id="loadMapImg" width="48" src="/viantes/pvt/img/animate/ld_32_ffffff.gif" />
						</div>
					</div>
				<?php } ?>
				
				<?php include $X_root."pvt/pages/review/common/starAndAuthor.php"; ?>
				
				<div id="postDivId" class="postDiv" style="position:relative">
					<p class="dspl-inln-blk mrg-top-24">Post a comment</p>
					<textarea style="height: 55px; width: 500px;" name="postTxtArea" id="postTxtArea"></textarea>
					
					<button class="personalButton" onclick="doPost('<?php echo $reviewDO->getId()?>', '<?php echo $reviewType ?>')">
						Post
					</button>
					<img src="/viantes/pvt/img/common/close_666.png" onclick="$('#postDivId').hide()" width="16" 
						 class="flt-r" style="margin-top: -4px; cursor: pointer"/>
				</div>
				
				<div class="tabs">
					<input type="hidden" name="tabactive" value="<?php echo $activeTabIdx ?>" id="tabactive"/>
					<?php if ( isset($reviewDO) ) { ?>
						<ul class="tab-links">
							<li <?php echo ($activeTabIdx == 1) ? 'class="active"' : '' ?>>
								<a href="#tab1"><?php echo $X_langArray['CREATE_REVIEW_TAB_REV_TITLE'] ?></a>
							</li>
							<li <?php echo ($activeTabIdx == 2) ? 'class="active"' : '' ?>>
								<a href="#tab2"><?php echo $X_langArray['CREATE_REVIEW_TAB_PIC_TITLE'] ?></a>
							</li>
							<li <?php echo ($activeTabIdx == 3) ? 'class="active"' : '' ?>>
								<a href="#tab3"><?php echo $X_langArray['CREATE_REVIEW_TAB_VID_TITLE'] ?></a>
							</li>
							<li <?php echo ($activeTabIdx == 4) ? 'class="active"' : '' ?>>
								<a href="#tab4"><?php echo $X_langArray['CREATE_REVIEW_TAB_DOC_TITLE'] ?></a>
							</li>
						</ul>
						
						<div class="tab-content">
							<div id="tab1" <?php echo ($activeTabIdx == 1) ? 'class="tab active"' : 'class="tab"' ?> >
								<?php require_once $X_root."pvt/pages/review/show/showReviewTab1.php"; ?>
							</div>
							<div id="tab2" <?php echo ($activeTabIdx == 2) ? 'class="tab active"' : 'class="tab"' ?> >
								<?php require_once $X_root."pvt/pages/review/show/showReviewTab2.php"; ?>
							</div>
							<div id="tab3" <?php echo ($activeTabIdx == 3) ? 'class="tab active"' : 'class="tab"' ?> >
								<?php require_once $X_root."pvt/pages/review/show/showReviewTab3.php"; ?>
							</div>
							<div id="tab4" <?php echo ($activeTabIdx == 4) ? 'class="tab active"' : 'class="tab"' ?> >
								<?php require_once $X_root."pvt/pages/review/show/showReviewTab4.php"; ?>
							</div>	
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		
		<?php require_once $X_root."pvt/pages/common/right_section.html"; ?>
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
	

	<input type="hidden" id="ovrly-initial-src-page" value="<?php echo $page?>" />
	<input type="hidden" id="ovrly-initial-login-dst-page" value="<?php echo $page?>" />
	<input type="hidden" id="ovrly-initial-sign-dst-page" value="<?php echo $page?>" />	
</body>
</html>
