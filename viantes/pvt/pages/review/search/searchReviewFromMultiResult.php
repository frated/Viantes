<?php
ini_set('display_errors', '1');
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/commonReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";

$placeId = X_deco($_GET['placeId']);
$type = addslashes( htmlspecialchars($_GET['reviewType']) );

//Verifico la sessione
if (!isset( $_SESSION["SEARCH_REVIEW_SEARCH_CRITERIA"]) ) {
	header('Location: '.getURI().'/viantes/pub/pages/error.php?reason='.urlencode($X_langArray['ERROR_REASON_SESSION_EXPIRED']));
	exit;
}

//verifico il range della variabile type
if ($type != SiteReview && $type != CityReview && $type != CountryReview) {
	header('Location: '.getURI().'/viantes/pub/pages/error.php');
	exit;
}

//Leggo l'array contenenti i criteri di ricerca
$searchCriteria = $_SESSION["SEARCH_REVIEW_SEARCH_CRITERIA"];

$kwrds     = $searchCriteria['kwrds'];
$type      = $searchCriteria['type'];
$onlyImg   = $searchCriteria['onlyImg'];
$onlyMov   = $searchCriteria['onlyMov'];
$onlyDoc   = $searchCriteria['onlyDoc'];
$langCode  = $searchCriteria['langCode'];
$orderType = $searchCriteria['orderType'];

if ($type == SiteReview) {
	$reviewDAO = New ReviewDAO();	
	
	$reviewDOArray = $reviewDAO->searchReviews($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc, $orderType, $placeId);
	if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
		//Result
		$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);
		
		//Rate
		$reviewDO = $reviewDOArray[0];
		$rate = $reviewDAO->getRateArray($reviewDO->getSiteId(), SiteReview);
		$_SESSION["SEARCH_REVIEW_RATE_ARRAY"] = $rate;
	}
	header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=0');
	exit;
}
else if ($type == CityReview) {
	$reviewDAO = New CityReviewDAO();
	
	$reviewDOArray = $reviewDAO->searchReviews($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc, $orderType, $placeId);
	if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
		//Result
		$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);
		
		//Rate
		$cityReviewDO = $reviewDOArray[0];
		$rate = $reviewDAO->getRateArray($cityReviewDO->getCityId(), CityReview);
		$_SESSION["SEARCH_REVIEW_RATE_ARRAY"] = $rate;
	}
	header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=0');
	exit;
}
else if ($type == CountryReview) {
	$reviewDAO = New CountryReviewDAO();
	
	$reviewDOArray = $reviewDAO->searchReviews($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc, $orderType, $placeId);
	if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
		//Result
		$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);
		
		//Rate
		$countryReviewDO = $reviewDOArray[0];
		$rate = $reviewDAO->getRateArray($countryReviewDO->getCountryId(), CountryReview);
		$_SESSION["SEARCH_REVIEW_RATE_ARRAY"] = $rate;
	}
	header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=0');
	exit;
}
?>
