<?php
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/lang/countryDAO.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/review/cityReviewBean.php";

if ( isset($_FILES['userfile']['tmp_name']) &&  $_FILES['userfile']['tmp_name'] != '' ){
	//e' un upload
	if ( isset($_POST['type']) ) {
		require_once $X_root.'pvt/pages/upload/uploadFile.php';
	}
	require_once $X_root.'pvt/pages/upload/uploadCover.php';
}

//istanzio i DAO
$cityReviewDAO = New CityReviewDAO();
$countryDAO = New CountryDAO();

//conterra' la descrizione dell'errore localizzata
$errorField = "";

/** ------ Gestione Asincrona ------ **/
doAsyncGet($X_langArray);

require_once $X_root."pvt/pages/checkSession.php";

/** ------ Gestione POST ------ **/
$city = htmlspecialchars($_POST['city']);			//nome che digita l'utente
$cityName = htmlspecialchars($_POST['cityName']); //nome che correggo io con google maps
$country = htmlspecialchars($_POST['country']);
$langCode = htmlspecialchars($_POST['langCode']);
$descr = $_POST['descr'];
$arrive = htmlspecialchars($_POST['arrive']);
$warn = htmlspecialchars($_POST['warn']);
$whEat = htmlspecialchars($_POST['whEat']);
$cook = htmlspecialchars($_POST['cook']);
$whStay = htmlspecialchars($_POST['whStay']);
$myth = htmlspecialchars($_POST['myth']);
$vote = htmlspecialchars($_POST['vote']);
$vote = preg_match("/^\d{1,2}$/", $vote) && $vote >= 0 && $vote <= 5 ? $vote : 3;

checkCity($city, $X_langArray);
checkDescr($descr, $X_langArray);
checkCover($X_langArray);
checkArrive($arrive, $X_langArray);
checkWarn($warn, $X_langArray);
checkWhEat($whEat, $X_langArray);
checkCook($cook, $X_langArray);
checkWhStay($whStay, $X_langArray);
checkMyth($myth, $X_langArray);
checkInterest($X_langArray);

// GEO Code
$geoSite = array();
$geoSite['cityName'] = $cityName;
$geoSite['country']  = htmlspecialchars($_POST['country']);
checkGeoSite($geoSite, $X_langArray);

//old params
$oldParams = 'tabactive=1&country='.urlencode($country).'&city='.urlencode($city).'&cityName='.urlencode($cityName).'&descr='.urlencode($descr).'&arrive='.urlencode($arrive).
			 '&warn='.urlencode($warn).'&whEat='.urlencode($whEat).'&cook='.urlencode($cook).'&whStay='.urlencode($whStay).
			 '&myth='.urlencode($myth).'&vote='.urlencode($vote);
			 
//no errors
if ($errorField == "") {
	
	//Se non ci sono errori verifico il pulsante di submit
	switch ($_POST['submit']) {
		//Avanti
		case $X_langArray['CREATE_CITY_REV_SUBMIT_VAL']:
			header('Location: '.getURI().'/viantes/pub/pages/review/createCityReview.php?'.$oldParams.'&finish=true');
			exit;
        break;
		//Indietro
		case $X_langArray['CREATE_CITY_REV_CHANGE_VAL']:
			header('Location: '.getURI().'/viantes/pub/pages/review/createCityReview.php?'.$oldParams);
			exit;
		break;
	}
	
	addSlash($city, $cityName, $country, $descr, $arrive, $warn, $whEat, $cook, $whStay, $myth, $geoSite);
	
	$userDO = unserialize($_SESSION["USER_LOGGED"]) ;
	$bean = unserialize($_SESSION[$_POST['beanSessionKey']]);
	
	//recupero l'id della nazione
	$countryId = $countryDAO->createCountryIfNotExis($country, $langCode);
	
	//creo la recensione
	$inter = $bean->getInterest();
	$reviewId = $cityReviewDAO->createCityReview($userDO->getId(), $cityName, $countryId, $langCode, $descr, $arrive, $warn, 
					$whEat, $cook, $whStay, $myth, $vote, $bean, $inter);
	
	//inserisco le (eventuali) immagini 
	if ( count($bean->getImgFileNameArray()) > 0 ) {
		$paths = $bean->getImgRelativeFilePathArray();
		$names  = $bean->getImgFileNameArray();
		$cmnts = $bean->getImgCommentArray();
		$widths = $bean->getImgWidthArray();
		$heights= $bean->getImgHeightArray();
		$cityReviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, IMG, $widths, $heights, $cmnts);
	}
	
	//inserisco gli (eventuali) video */
	if ( count($bean->getMovFileNameArray()) > 0 ) {
		$paths  = $bean->getMovRelativeFilePathArray();
		$names  = $bean->getMovFileNameArray();
		$cmnts  = $bean->getMovCommentArray();
		$widths = $bean->getMovWidthArray();
		$heights= $bean->getMovHeightArray();
		$cityReviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, MOV, $widths, $heights, $cmnts);
	}
	
	//inserisco gli (eventuali) documenti */
	if ( count($bean->getDocFileNameArray()) > 0 ) {
		$paths = $bean->getDocRelativeFilePathArray();
		$names  = $bean->getDocFileNameArray();
		$cmnts = $bean->getDocCommentArray();
		$cityReviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, DOC, $dummy = NULL, $dummy = NULL, $cmnts);
	}
	
	$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['CREATE_CITY_REV_PAGE_TOP_MSG_OK'];
	unset($_SESSION[$_POST['beanSessionKey']]);
	
	header('Location: '.getURI().'/viantes/pub/pages/review/myReview.php');
	exit;
}

//forward
header('Location: '.getURI().'/viantes/pub/pages/review/createCityReview.php?'.$oldParams.$errorField);
exit;
?>

<?php
/* Gestisce una richiesta Get asincrona */
function doAsyncGet($X_langArray){
	global $errorField;
	
	if ( isset($_GET['city']) ) {
		$cityGet = htmlspecialchars( $_GET['city'] );
		//TODO $cityGetNo = $_GET['city'];
		checkCity($cityGet, $X_langArray);
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
		echo strlen($warnGet); 
		checkWarn($warnGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
	
	if ( isset($_GET['whEat']) ) {
		$whEatGet = htmlspecialchars( $_GET['whEat'] );
		checkWhEat($whEatGet, $X_langArray);
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
	
	if ( isset($_GET['whStay']) ) {
		$whStayGet = htmlspecialchars( $_GET['whStay'] );
		checkWhStay($whStayGet, $X_langArray);
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

/* Controlla la validita' del campo city: setta l'errore in una variabile globale */
function checkCity($city, $X_langArray) {
	global $errorField, $reviewDAO;
	
	//city vuoto
	if (!isset($city) || $city == ''){
		$errorField .= "&cityErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_CITY_EMPTY_ERR']);
	} else if ( (strlen($city) < 3 ||  strlen($city) > 60) && (strlen($cityName) < 3 ||  strlen($cityName) > 60)  ) {
		$errorField .= "&cityErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_CITY_LENGTH_ERR']);
	}
}

/* Controlla la validita' del campo descr: setta l'errore in una variabile globale */
function checkDescr($descr, $X_langArray) {
	global $errorField;
	
	//descr vuoto
	if (!isset($descr) || $descr == ''){
		$errorField .= "&descrErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_EMPTY_DS_ERR']);
	}
	else if (strlen($descr) < 50 || strlen($descr) > 2000) {
		$errorField .= "&descrErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_DS_LENGTH_ERR']);
	}
}

/* Controlla la presenza dell'immagine di copertina */
function checkCover($X_langArray) {
	global $errorField;
	$bean = isset($_SESSION["CITY_REVIEWN_BEAN"]) ? unserialize($_SESSION["CITY_REVIEWN_BEAN"]) : null;
	if ( $bean == null || $bean->getCoverFileName() == null || $bean->getCoverFileName() == '' ) {
		$errorField .= "&loadCovImgErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_COVER_ERR']);
	}
}

/* Controlla la validita' del campo arrive: setta l'errore in una variabile globale */
function checkArrive($arrive, $X_langArray) {
	global $errorField;
	
	//arrive vuoto
	if (!isset($arrive) || $arrive == ''){
		$errorField .= "&arriveErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_EMPTY_ARRIVE_ERR']);
	}
	else if ( strlen($arrive) < 50 || strlen($arrive) > 1000 ) {
		$errorField .= "&arriveErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_ARRIVE_LENGTH_ERR']);
	}
}

/* Controlla la validita' del campo warn: setta l'errore in una variabile globale */
function checkWarn($warn, $X_langArray) {
	global $errorField;
	
	if ( strlen($warn) > 0  && strlen($warn) < 10 ) {
		$errorField .= "&warnErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_WARN_LENGTH_MIN_ERR']);
	}
	if ( strlen($warn) > 100 ) {
		$errorField .= "&warnErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_WARN_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo whEat: setta l'errore in una variabile globale */
function checkWhEat($whEat, $X_langArray) {
	global $errorField;
	
	if ( strlen($whEat) > 0 && strlen($whEat) < 25 ) {
		$errorField .= "&whEatErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_WTEAT_LENGTH_MIN_ERR']);
	}
	if ( strlen($whEat) > 500 ) {
		$errorField .= "&whEatErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_WTEAT_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo cook: setta l'errore in una variabile globale */
function checkCook($cook, $X_langArray) {
	global $errorField;
	
	if ( strlen($cook) > 0 && strlen($cook) < 25 ) {
		$errorField .= "&cookErrMsg=".urlencode($X_langArray['CREATE_CITY_COOK_LENGTH_MIN_ERR']);
	}
	if ( strlen($cook) > 500 ) {
		$errorField .= "&cookErrMsg=".urlencode($X_langArray['CREATE_CITY_COOK_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo whStay: setta l'errore in una variabile globale */
function checkWhStay($whStay, $X_langArray) {
	global $errorField;
	
	if ( strlen($whStay) > 0 && strlen($whStay) < 25 ) {
		$errorField .= "&whStayErrMsg=".urlencode($X_langArray['CREATE_CITY_WHSTAY_LENGTH_MIN_ERR']);
	}
	if ( strlen($whStay) > 500 ) {
		$errorField .= "&whStayErrMsg=".urlencode($X_langArray['CREATE_CITY_WHSTAY_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo myth: setta l'errore in una variabile globale */
function checkMyth($myth, $X_langArray) {
	global $errorField;
	
	if ( strlen($myth) > 0 && strlen($myth) < 10 ) {
		$errorField .= "&mythErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_MYTH_LENGTH_MIN_ERR']);
	}
	if ( strlen($myth) > 100 ) {
		$errorField .= "&mythErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_MYTH_LENGTH_MAX_ERR']);
	}
}

/* Controlla che sia stato aggiunnto almeno un luogo di interesse */
function checkInterest($X_langArray) {
	global $errorField;
	
	$key = $_POST['beanSessionKey'];

	if ( !isset($_SESSION[$key])) {
		$errorField .= "&interErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_INTER_EMPTY_ERR']);
	}
	else {
		$bean = unserialize($_SESSION[$key]);
		$inter = $bean->getInterest();
		if ( !isset($inter) || count($inter) == 0 ) {
			$errorField .= "&interErrMsg=".urlencode($X_langArray['CREATE_CITY_REV_INTER_EMPTY_ERR']);
		}
	}
}

/* Controlla Che i dati di geolocalizzazione siano validi e consistenti */
function checkGeoSite($geoSite, $X_langArray) {
	global $errorField;
	
	if ($geoSite['cityName'] == '' || $geoSite['country'] == '' ) {
		$errorField .= "&cityErrMsg=".urlencode($X_langArray['CREATE_CITY_ADDRS_NOT_FOUND']);
	}
}
/* Add slash function */
function addSlash($_city, $_cityName, $_country, $_descr, $_arrive, $_warn, $_whEat, $_cook, $_whStay, $_myth, $_geoSite) {
	global $city, $cityName, $country, $descr, $arrive, $warn, $whEat, $cook, $whStay, $myth, $geoSite;
	
	$city = addslashes($_city);
	$cityName = addslashes($_cityName);
	$country = addslashes($_country);
	$descr = addslashes($_descr);
	$arrive = addslashes($_arrive);
	$warn = addslashes($_warn);
	$whEat = addslashes($_whEat);
	$cook = addslashes($_cook);
	$whStay = addslashes($_whStay);
	$myth = addslashes($_myth);
	$geoSite['cityName'] = addslashes($_geoSite['cityName']);
	$geoSite['country'] = addslashes($_geoSite['country']);
};
?>