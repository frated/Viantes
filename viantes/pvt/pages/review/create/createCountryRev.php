<?php
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/lang/countryDAO.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/review/countryReviewBean.php";

if ( isset($_FILES['userfile']['tmp_name']) &&  $_FILES['userfile']['tmp_name'] != '' ){
	//e' un upload
	if ( isset($_POST['type']) ) {
		require_once $X_root.'pvt/pages/upload/uploadFile.php';
	}
	require_once $X_root.'pvt/pages/upload/uploadCover.php';
}

//istanzio i DAO
$countryReviewDAO = New CountryReviewDAO();
$countryDAO = New CountryDAO();

//conterra' la descrizione dell'errore localizzata
$errorField = "";

/** ------ Gestione Asincrona ------ **/
doAsyncGet($X_langArray);

require_once $X_root."pvt/pages/checkSession.php";

/** ------ Gestione POST ------ **/
$country = addslashes( htmlspecialchars($_POST['country']) );
$langCode = addslashes( htmlspecialchars($_POST['langCode']) );
$descr = addslashes($_POST['descr']);
$warn = addslashes( htmlspecialchars($_POST['warn']) );
$arrive = addslashes( htmlspecialchars($_POST['arrive']) );
$cook = addslashes( htmlspecialchars($_POST['cook']) );
$myth = addslashes( htmlspecialchars($_POST['myth']) );
$vote = addslashes( htmlspecialchars($_POST['vote']) );
$vote = preg_match("/^\d{1,2}$/", $vote) && $vote >= 0 && $vote <= 5 ? $vote : 3;

checkCountry($country, $X_langArray);
checkDescr($descr, $X_langArray);
checkCover($X_langArray);
checkArrive($arrive, $X_langArray);
checkWarn($warn, $X_langArray);
checkCook($cook, $X_langArray);
checkMyth($myth, $X_langArray);
//checkInterest($X_langArray);

// GEO Code
$countryName = addslashes( htmlspecialchars($_POST['countryName']) );
checkGeoSite($countryName, $X_langArray);

//old params
$oldParams = 'tabactive=1&country='.urlencode($country).'&countryName='.urlencode($countryName).'&descr='.urlencode($descr).
			 '&arrive='.urlencode($arrive).'&cook='.urlencode($cook).'&warn='.urlencode($warn).
			 '&myth='.urlencode($myth).'&vote='.urlencode($vote);

//no errors
if ($errorField == "") {

	//Se non ci sono errori verifico il pulsante di submit
	switch ($_POST['submit']) {
		//Avanti
		case $X_langArray['CREATE_COUNTRY_REV_SUBMIT_VAL']:
			header('Location: '.$uri.'/viantes/pub/pages/review/createCountryReview.php?'.$oldParams.'&finish=true');
			exit;
        break;
		//Indietro
		case $X_langArray['CREATE_COUNTRY_REV_CHANGE_VAL']:
			header('Location: '.$uri.'/viantes/pub/pages/review/createCountryReview.php?'.$oldParams);
			exit;
		break;
	}
	
	$userDO = unserialize($_SESSION["USER_LOGGED"]) ;
	$bean = unserialize($_SESSION[$_POST['beanSessionKey']]);	
	
	//recupero l'id della nazione
	$countryId = $countryDAO->createCountryIfNotExis($countryName, $langCode);
	
	//creo la recensione
	$inter = $bean->getCityInterest();
	$reviewId = $countryReviewDAO->createCountryReview($userDO->getId(), $countryId, $langCode, $descr, $arrive, $warn, 
					$cook, $myth, $vote, $bean, $inter);
	
	//inserisco le (eventuali) immagini 
	if ( count($bean->getImgFileNameArray()) > 0 ) {
		$paths = $bean->getImgRelativeFilePathArray();
		$names  = $bean->getImgFileNameArray();
		$cmnts = $bean->getImgCommentArray();
		$widths = $bean->getImgWidthArray();
		$heights= $bean->getImgHeightArray();
		$countryReviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, IMG, $widths, $heights, $cmnts);
	}
	
	//inserisco gli (eventuali) video */
	if ( count($bean->getMovFileNameArray()) > 0 ) {
		$paths  = $bean->getMovRelativeFilePathArray();
		$names  = $bean->getMovFileNameArray();
		$cmnts  = $bean->getMovCommentArray();
		$widths = $bean->getMovWidthArray();
		$heights= $bean->getMovHeightArray();
		$countryReviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, MOV, $widths, $heights, $cmnts);
	}
	
	//inserisco gli (eventuali) documenti */
	if ( count($bean->getDocFileNameArray()) > 0 ) {
		$paths = $bean->getDocRelativeFilePathArray();
		$names  = $bean->getDocFileNameArray();
		$cmnts = $bean->getDocCommentArray();
		$countryReviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, DOC, $dummy = NULL, $dummy = NULL, $cmnts);
	}
	
	$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['CREATE_COUNTRY_REV_PAGE_TOP_MSG_OK'];
	unset($_SESSION["COUNTRY_REVIEWN_BEAN"]);
	
	header('Location: '.$uri.'/viantes/pub/pages/review/myReview.php');
	exit;
}

//forward
header('Location: '.$uri.'/viantes/pub/pages/review/createCountryReview.php?'.$oldParams.$errorField);
exit;
?>

<?php
/* Gestisce una richiesta Get asincrona */
function doAsyncGet($X_langArray){
	
	global $errorField;
	
	if ( isset($_GET['country']) ) {
		
		$country = $_GET['country'];
		//TODO $countryNo = $_GET['country'];
		checkCountry($country, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
	
	if ( isset($_GET['descr']) ) {
		$descrGet = htmlspecialchars( $_GET['descr'] );
		checkDescr($descrGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
	
	if ( isset($_GET['arrive']) ) {
		$arriveGet = htmlspecialchars( $_GET['arrive'] );
		checkArrive($arriveGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
	if ( isset($_GET['warn']) ) {
		$warnGet = htmlspecialchars( $_GET['warn'] );
		checkWarn($warnGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}

	if ( isset($_GET['cook']) ) {
		$cookGet = htmlspecialchars( $_GET['cook'] );
		checkCook($cookGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}

	if ( isset($_GET['myth']) ) {
		$mythGet = htmlspecialchars( $_GET['myth'] );
		checkMyth($mythGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}	
}

/* Controlla la validita' del campo country: setta l'errore in una variabile globale */
function checkCountry($country, $X_langArray) {
	global $errorField, $countryDAO;
	
	//country vuoto
	if ( !isset($country) || $country == '' ) {
		$errorField .= "&countryErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_COUNTRY_EMPTY_ERR']);
	}
	else if (strlen($country) > 50 ) {
		$errorField .= "&countryErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_COUNTRY_LENGTH_ERR']);
	}
}

/* Controlla la validita' del campo descr: setta l'errore in una variabile globale */
function checkDescr($descr, $X_langArray) {
	global $errorField;
	
	//descr vuoto
	if (!isset($descr) || $descr == ''){
		$errorField .= "&descrErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_EMPTY_DS_ERR']);
	}
	else if (strlen($descr) < 50 || strlen($descr) > 2000) {
		$errorField .= "&descrErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_DS_LENGTH_ERR']);
	}
}

/* Controlla la presenza dell'immagine di copertina */
function checkCover($X_langArray) {
	global $errorField;
	$bean = isset($_SESSION["COUNTRY_REVIEWN_BEAN"]) ? unserialize($_SESSION["COUNTRY_REVIEWN_BEAN"]) : null;
	if ( $bean == null || $bean->getCoverFileName() == null || $bean->getCoverFileName() == '' ) {
		$errorField .= "&loadCovImgErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_COVER_ERR']);
	}
}

/* Controlla la validita' del campo arrive: setta l'errore in una variabile globale */
function checkArrive($arrive, $X_langArray) {
	global $errorField;
	
	//arrive vuoto
	if ( strlen($arrive) > 0  && strlen($arrive) < 25 ) {
		$errorField .= "&arriveErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_ARRIVE_LENGTH_MIN_ERR']);
	}
	if ( strlen($arrive) > 500 ) {
		$errorField .= "&arriveErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_ARRIVE_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo warn: setta l'errore in una variabile globale */
function checkWarn($warn, $X_langArray) {
	global $errorField;
	
	if ( strlen($warn) > 0  && strlen($warn) < 10 ) {
		$errorField .= "&warnErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_WARN_LENGTH_MIN_ERR']);
	}
	if ( strlen($warn) > 100 ) {
		$errorField .= "&warnErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_WARN_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo cook: setta l'errore in una variabile globale */
function checkCook($cook, $X_langArray) {
	global $errorField;
	
	if ( strlen($cook) > 0 && strlen($cook) < 25 ) {
		$errorField .= "&cookErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_COOK_LENGTH_MIN_ERR']);
	}
	if ( strlen($cook) > 500 ) {
		$errorField .= "&cookErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_COOK_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo myth: setta l'errore in una variabile globale */
function checkMyth($myth, $X_langArray) {
	global $errorField;
	
	if ( strlen($myth) > 0 && strlen($myth) < 10 ) {
		$errorField .= "&mythErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_MYTH_LENGTH_MIN_ERR']);
	}
	if ( strlen($myth) > 100 ) {
		$errorField .= "&mythErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_MYTH_LENGTH_MAX_ERR']);
	}
}

/* Controlla che sia stato aggiunnto almeno una citta' di interesse */
function checkInterest($X_langArray) {
	global $errorField;
	
	$key = $_POST['beanSessionKey'];

	if ( !isset($_SESSION[$key])) {
		$errorField .= "&countryErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_INTER_EMPTY_ERR']);
	}
	else {
		$bean = unserialize($_SESSION[$key]);
		$inter = $bean->getInterest();
		if ( !isset($inter) || count($inter) == 0 ) {
			$errorField .= "&interErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_REV_INTER_EMPTY_ERR']);
		}
	}
}
/* Controlla Che i dati di geolocalizzazione siano validi e consistenti */
function checkGeoSite($countryName, $X_langArray) {
	global $errorField;
	
	if ($countryName == '') {
		$errorField .= "&cityErrMsg=".urlencode($X_langArray['CREATE_COUNTRY_ADDRS_NOT_FOUND']);
	}
}
?>
