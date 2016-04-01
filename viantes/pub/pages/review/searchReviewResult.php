<?php 
$X_root = "../../../";
$X_page = "searchReviewResult";
session_start();
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDO.php";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDO.php";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

if (!isset($_SESSION["SEARCH_REVIEW_RESULT_ARRAY"]) ){
	header('Location: '.getURI().'/viantes/pub/pages/error.php?reason='.urlencode($X_langArray['ERROR_REASON_SESSION_EXPIRED']));
	exit;
}

//Recupero il DO della recensione corrente
$reviewDOArray = unserialize($_SESSION["SEARCH_REVIEW_RESULT_ARRAY"]);
$current = isset($_GET['searchRevResCur']) && $_GET['searchRevResCur'] < count($reviewDOArray) ? 
				$_GET['searchRevResCur'] : 0;

$reviewDO = isset($reviewDOArray[$current]) ? $reviewDOArray[$current] : null;

if (isset($reviewDO)) {
	//Nel caso di recensione di sito
	if ( $reviewDO instanceof ReviewDO) {
		$reviewType = SiteReview;
		$reviewDAO = New ReviewDAO();
			
		//GEO
		$placeName = $reviewDO->getSiteName();
		$X_GEO_site = $placeName;
		$X_GEO_locality = $reviewDO->getLocality();
		$X_GEO_zoom = 17;
		$X_GEO_ERR_MSG = $X_langArray['CREATE_REVIEW_ADDRS_NOT_VALID'];
		$requiredJSMap = "pvt/pages/geo/siteMap.html";
	}
	//Nel caso di recensione di citta'
	else if ( $reviewDO instanceof CityReviewDO) {
		$reviewType = CityReview;
		$reviewDAO = New CityReviewDAO();
		$interestArray = $reviewDAO->getInterestByRevId( $reviewDO->getId() );
		
		//GEO
		$placeName = $reviewDO->getCityName();
		$X_GEO_city = $placeName;
		$X_GEO_zoom = 11; //zoom
		$X_GEO_ERR_MSG = $X_langArray['CREATE_CITY_ADDRS_NOT_FOUND'];
		$requiredJSMap = "pvt/pages/geo/cityMap.html";
	}
	//Nel caso di recensione di nazioni
	else if ( $reviewDO instanceof CountryReviewDO) {
		$reviewType = CountryReview;
		$reviewDAO = New CountryReviewDAO();
		$interestArray = $reviewDAO->getInterestByRevId( $reviewDO->getId() );
		
		//GEO
		$placeName = $reviewDO->getCountry();
		$X_GEO_country = $placeName;
		$X_GEO_zoom = 5;
		$X_GEO_ERR_MSG = $X_langArray['CREATE_COUNTRY_ADDRS_NOT_FOUND'];
		$requiredJSMap = "pvt/pages/geo/countryMap.html";
	}
	//Errore, ho nell'array un oggetto che non e' una recensione
	else {
		header('Location: '.getURI().'/viantes/pub/pages/error.php');
		exit;
	}
}

//Rate
if (isset($_SESSION["SEARCH_REVIEW_RATE_ARRAY"])) {
	$rate = $_SESSION["SEARCH_REVIEW_RATE_ARRAY"];
	$tot = 0;
	$num = 0;
	foreach ( $rate as $vote => $numOfVote) {
		$tot += $vote * $numOfVote;
		$num += $numOfVote;
	}
	$avg = $num != 0 ? $tot / $num : 3;
}

//Star and See
$X_star_cnt = isset($reviewDO) ? $reviewDO->getCntStar() : 0;
$X_see_cnt  = isset($reviewDO) ? $reviewDO->getCntSee()  : 0;
$X_post_cnt = isset($reviewDO) ? $reviewDO->getCntPost() : 0;

//===================== GEO ===========================
//Vince su du tutti, se true non ci sono mappe
$X_GEO_disableMAP = false;
//Se true mostra la mappa se false no (nota la mappa viene sempre caricata e' solo nascosta o meno)
$X_GEO_loadMap = isset($reviewDO) ? true : false;
//Se true disabilita i comandi utente sulla mappa
$X_GEO_disableUI = true;  

$X_GEO_mapType = 'roadmap'; 


$dim = count($reviewDOArray);

$activeTabIdx = 1;

cleanSesison($X_page);
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
	<title><?php echo $X_langArray['SEARCH_REVIEW_RESULT_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>


<?php 
//Overlay-login-signin
include $X_root."pvt/pages/common/overlay-login-signin.html";
$page="/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=".$current;

//Overlay-star-post-list
include $X_root."pvt/pages/review/common/overlayStarPostList.html";
?>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; 
		  if (!$X_GEO_disableMAP) require_once $X_root.$requiredJSMap;?>

	<div id="main-div" class="main-div-correct-ie7 main-div">
		
		<div class="body-div">
			<?php include $X_root."pvt/pages/common/globalTopMsg.php"; ?>

			<div class="mrg-top-24">
				<a href="/viantes/pub/pages/review/searchReview.php">
					<?php echo $X_langArray['SEARCH_REVIEW_RESULT_BACK'] ?>
				</a>
			</div>
			<?php if ( isset($reviewDOArray) && count($reviewDOArray) > 0 && isset($reviewDO) ) { ?>
				
				<div class="searchRevLftHeaderDiv">
					<div class="second-header">
						<h1><?php echo $placeName;?></h1>
						<!-- Indirizzo -->
						<?php if ( $reviewType == SiteReview ) {?>
							<p class="showRevHader"><?php echo $reviewDO->getLocality() ?></p>
						<?php } else if ( $reviewType == CityReview ) {?>
							<p class="showRevHader"><?php echo $reviewDO->getFormattedLocality() ?></p>
						<?php } ?>
					</div>
				</div>
				
				<div class="searchRevRgtHeaderDiv">
					<div class="searchRevRgtHeaderDivTitle">
						<b><p>
							<?php echo $X_langArray['SEARCH_REVIEW_RESULT_OVER_EVAL'] ?>
						</p></b>
					</div>
					<div class="searchRevRgtHeaderDivVote">
						<?php  if (!filter_var($avg, FILTER_VALIDATE_INT)) { ?>
							<img class="vote" src="/viantes/pvt/img/review/vote1-2.png" height="12" width="12"/>
						<?php  }
						for ( $i = 1; $i <= $avg; $i++ ) { ?>
							<img class="vote" src="/viantes/pvt/img/review/vote1.png" height="12" width="12"/>
						<?php } ?>
					</div>
					
					<!-- Rate -->
					<?php for ( $j = 5; $j >= 1; $j-- ) { ?>
						<div class="searchRevRgtHeaderDivVote" style="margin: 0px">
							<div style="display: inline-block; margin-bottom: 1px; width: 5%; float: right; margin-left:5%">
								<p><?php echo isset ($rate[$j]) ? $rate[$j] : 0; ?></p>
							</div>
							<div style="display: inline-block; margin-bottom: 1px; width: 40%; float: right">
								<table style="width: 100%; border: 1px solid #fa0; border-collapse:collapse">
									<tr>
									<?php for ( $i = 1; $i <= $j; $i++ ) { ?>
										<td style="height: 9px; width: 9px; background-color: #fa0"></td>
									<?php } 
									for ( $i = $j; $i < 5; $i++ ) { ?>
										<td style="height: 9px; width: 9px; background-color: #fff;"></td>
									<?php } ?>
									</tr>
								</table>
							</div>
						</div>
					<?php } ?>	
				</div>
				
				<?php include $X_root."pvt/pages/review/search/searchReviewResultPaginator.php"; ?>
				
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
				
				<div id="searchReviewResult" class="tabs mrg-bot-24">
					<input type="hidden" name="tabactive" value="<?php echo $activeTabIdx ?>" id="tabactive"/>
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
							<?php require_once $X_root."pvt/pages/review/search/searchReviewResultTab1.php"; ?>
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
				</div>

				<?php include $X_root."pvt/pages/review/search/searchReviewResultPaginator.php"; ?>	

			<?php } else { ?>
				<div>
					<div class="top-header mrg-top-24">
						<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_H1']?></h1>
					</div>
					<div class="second-header-font14">
						<h1><?php echo $X_langArray['SEARCH_REVIEW_RESULT_NO_RESULT'] ?></h1>
					</div>
				</div>
			<?php }  ?>
			
		</div>
		
		<?php require_once $X_root."pvt/pages/common/right_section.html"; ?>
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>

	<input type="hidden" id="ovrly-initial-src-page" value="<?php echo $page?>" />
	<input type="hidden" id="ovrly-initial-login-dst-page" value="<?php echo $page?>" />
	<input type="hidden" id="ovrly-initial-sign-dst-page" value="<?php echo $page?>" />	
	
</body>
</html>
