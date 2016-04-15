<?php
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";
require_once $X_root."pvt/pages/review/reviewDAO.php";

//se dalla searchReview o dalla searchReviewResult uso la ricerca dell'header => avrei l'array (SEARCH_REVIEW_SEARCH_CRITERIA) ancora in sessione
//nota che non vale il contrario, cioe' nella searchReviewFromAdv.php posso non cancellare SEARCH_REV_HEADER_SEARCH_CRIT, perche' il test 
//che si fa nella searchReviewResultPaginator verifica per prima l'array SEARCH_REVIEW_SEARCH_CRITERIA che deve essere quindi rimosso
unset($_SESSION['SEARCH_REVIEW_SEARCH_CRITERIA']);

//conterra' la descrizione dell'errore localizzata
$errorField = "";
$kwrds = addslashes( htmlspecialchars($_POST['kwrds']) );
$langCode = $_POST['langCode'];
$type = addslashes( htmlspecialchars($_POST['reviewType']) );

//Salvo in sessione l'array dei criteri di ricerca (con un altro nome) per rendere la pagina simile alla searchReviewFromAdv.php
//Nota che in questo modo la combo che implementa l'ordina funziona per entrambe le pagine
$searchCriteria = array();
$searchCriteria['kwrds']     = $kwrds;
$searchCriteria['type']      = $type;
$searchCriteria['langCode']  = $langCode;
$searchCriteria['onlyImg']   = 0;
$searchCriteria['onlyMov']   = 0;
$searchCriteria['onlyDoc']   = 0;
$searchCriteria['orderType'] = isset($_POST['orderType']) ? $_POST['orderType'] : 0;
$_SESSION["SEARCH_REV_HEADER_SEARCH_CRIT"] = $searchCriteria;

$errorField = "";
checkSiteName($kwrds, $X_langArray);

//no errors
if ($errorField == "") {
	if ($type == SiteReview) {
		$reviewDAO = New ReviewDAO();
		$reviewDOArray = $reviewDAO->searchReviewsPostAutocomplHeader($langCode, $kwrds);
		if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
			//Result
			$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);
			
			//Rate
			$reviewDO = $reviewDOArray[0];
			$rate = $reviewDAO->getRateArray($reviewDO->getSiteId(), SiteReview);
			$_SESSION["SEARCH_REVIEW_RATE_ARRAY"] = $rate;
			header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=0');
			exit;
		}
	} else if ($type == CityReview) {
		$reviewDAO = New CityReviewDAO();
		$reviewDOArray = $reviewDAO->searchReviewsPostAutocomplHeader($langCode, $kwrds);
		if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
			//Result
			$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);
			
			//Rate 
			$cityReviewDO = $reviewDOArray[0];
			$rate = $reviewDAO->getRateArray($cityReviewDO->getCityId(), CityReview);
			$_SESSION["SEARCH_REVIEW_RATE_ARRAY"] = $rate;
			
			header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=0');
			exit;
		}
	} else if ($type == CountryReview) {
		$reviewDAO = New CountryReviewDAO();
		$reviewDOArray = $reviewDAO->searchReviewsPostAutocomplHeader($langCode, $kwrds);
		if ( isset($reviewDOArray) && count($reviewDOArray) > 0  ) {
			//Result
			$_SESSION["SEARCH_REVIEW_RESULT_ARRAY"] = serialize($reviewDOArray);
			
			//Rate
			$countryReviewDO = $reviewDOArray[0];
			$rate = $reviewDAO->getRateArray($countryReviewDO->getCountryId(), CountryReview);
			$_SESSION["SEARCH_REVIEW_RATE_ARRAY"] = $rate;
			
			header('Location: '.getURI().'/viantes/pub/pages/review/searchReviewResult.php?searchRevResCur=0');
			exit;
		}
	}
}
//forward - se non trovo nulla torno nella home ma NON dovrebbe mai poter succedere
header('Location: '.getURI().'/index.php');
exit;
?>

<?php
/* Controlla la validita' del campo  */
function checkSiteName($kwrds, $X_langArray) {
	global $errorField; 
	
	//kwrds vuoto
	if (!isset($kwrds) || $kwrds == ''){
		$errorField .= "KO";
	}
	//kwrds troopo corto o troppo lungo (serve ?)
	else if ( strlen($kwrds) < 3 || strlen($kwrds) > 60 ){
		$errorField .= "KO";
	}
}
?>
