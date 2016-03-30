<?php
ini_set('display_errors', '1');
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/commonReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";
	
//conterra' la descrizione dell'errore localizzata
$errorField = "";

$kwrds = addslashes( htmlspecialchars($_POST['kwrds']) );
checkSiteName($kwrds, $X_langArray);

$type = addslashes( htmlspecialchars($_POST['reviewType']) );

//Leggo i campi inviati
$langCode = $_POST['langCode'];
$orderType = $_POST['orderType'];
$onlyImg = isset($_POST['onlyImg']) && $_POST['onlyImg'] == 'on' ? 1 : 0;
$onlyMov = isset($_POST['onlyMov']) && $_POST['onlyMov'] == 'on' ? 1 : 0;
$onlyDoc = isset($_POST['onlyDoc']) && $_POST['onlyDoc'] == 'on' ? 1 : 0;

//Creo l'array contenenti i criteri di ricerca
$searchCriteria = array();
$searchCriteria['kwrds']     = $kwrds;
$searchCriteria['type']      = $type;
$searchCriteria['onlyImg']   = $onlyImg;
$searchCriteria['onlyMov']   = $onlyMov;
$searchCriteria['onlyDoc']   = $onlyDoc;
$searchCriteria['langCode']  = $langCode;
$searchCriteria['orderType'] = $orderType;

//fissa il baco che si verifica se cambio ordinamento nel caso di multiresult
$placeId = isset($_POST['placeId']) ? X_deco($_POST['placeId']) : '';


//old params
$oldParams = 'kwrds='.urlencode($kwrds).'&reviewType='.$type.'&langCode='.$langCode.'&onlyImg='.$onlyImg.'&onlyMov='.$onlyMov.'&onlyDoc='.$onlyDoc;
if ($errorField != "") {
	//forward
	header('Location: '.getURI().'/viantes/pub/pages/review/searchReview.php?'.$oldParams.$errorField);
	exit;
}

//no errors => salvo i criteri di ricerca per quando torno indietro
$_SESSION["SEARCH_REVIEW_SEARCH_CRITERIA"] = $searchCriteria;

if ($type == SiteReview) {
	$reviewDAO = New ReviewDAO();
	
	//Conto i risultati, se piu di uno => vado sulla searchReviewMultiResult.php
	$reviewDOArray = $reviewDAO->searchReviewsCount($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc);
	//se ho valorizzato la placeId non mi interessa la count dei risultati => sono sicuro di trovarne uno
	if ( count($reviewDOArray) > 1 && $placeId == '' ) {
		//Result
		$_SESSION["SEARCH_REVIEW_MULTI_RESULT"] = serialize($reviewDOArray);
		header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewMultiResult.php?tp='.SiteReview);
		exit;
	}
	
	//Result
	$reviewDOArray = $reviewDAO->searchReviews($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc, $orderType, $placeId);
	$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);
	if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
		//Rate
		$reviewDO = $reviewDOArray[0];
		$rate = $reviewDAO->getRateArray($reviewDO->getSiteId(), SiteReview);
		$_SESSION["SEARCH_REVIEW_RATE_ARRAY"] = $rate;
	}
	else {
		$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize(array());
	}
		
	header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=0');
	exit;
}
else if ($type == CityReview) {
	$reviewDAO = New CityReviewDAO();
	
	//Conto i risultati, se piu di uno => vado sulla searchReviewMultiResult.php
	$reviewDOArray = $reviewDAO->searchReviewsCount($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc);
	//se ho valorizzato la placeId non mi interessa la count dei risultati => sono sicuro di trovarne uno
	if ( count($reviewDOArray) > 1 && $placeId == '' ) {
		//Result
		$_SESSION["SEARCH_REVIEW_MULTI_RESULT"] = serialize($reviewDOArray);
		header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewMultiResult.php?tp='.CityReview);
		exit;
	}
	
	//Result
	$reviewDOArray = $reviewDAO->searchReviews($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc, $orderType, $placeId);
	$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);
	if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
		
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
	
	//Conto i risultati, se piu di uno => vado sulla searchReviewMultiResult.php
	$reviewDOArray = $reviewDAO->searchReviewsCount($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc);
	//se ho valorizzato la placeId non mi interessa la count dei risultati => sono sicuro di trovarne uno
	if ( count($reviewDOArray) > 1 && $placeId == '' ) {
		//Result
		$_SESSION["SEARCH_REVIEW_MULTI_RESULT"] = serialize($reviewDOArray);
		header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewMultiResult.php?tp='.CountryReview);
		exit;
	}
	
	//Result
	$reviewDOArray = $reviewDAO->searchReviews($langCode, $kwrds, $onlyImg, $onlyMov, $onlyDoc, $orderType, $placeId);
	$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);	
	if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
		//Rate
		$countryReviewDO = $reviewDOArray[0];
		$rate = $reviewDAO->getRateArray($countryReviewDO->getCountryId(), CountryReview);
		$_SESSION["SEARCH_REVIEW_RATE_ARRAY"] = $rate;
	}
	header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=0');
	exit;
}
exit;
?>

<?php
/* Controlla la validita' del campo  */
function checkSiteName($kwrds, $X_langArray) {
	global $errorField;
	
	//kwrds vuoto
	if (!isset($kwrds) || $kwrds == ''){
		$errorField .= "&kwrdsErrMsg=".urlencode($X_langArray['SEARCH_REVIEW_EMPTY_KWDS_ERROR']);
	}
	//kwrds vuoto
	else if ( strlen($kwrds) < 3 || strlen($kwrds) > 60 ){
		$errorField .= "&kwrdsErrMsg=".urlencode($X_langArray['SEARCH_REVIEW_EMPTY_KWDS_LENG_ERROR']);
	}
	//kwrds vuoto
	else if (strpos($kwrds, '%') !== false){
		$errorField .= "&kwrdsErrMsg=".urlencode($X_langArray['SEARCH_REVIEW_ILLEGAL_KWDS_ERROR']);
	}
}
?>
