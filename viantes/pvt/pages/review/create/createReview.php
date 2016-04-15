<?php
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/lang/countryDAO.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/site/siteDAO.php";
require_once $X_root."pvt/pages/site/siteDO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/review/reviewBean.php";

if ( isset($_FILES['userfile']['tmp_name']) &&  $_FILES['userfile']['tmp_name'] != '' ){
	//e' un upload
	if ( isset($_POST['type']) ) {
		require_once $X_root.'pvt/pages/upload/uploadFile.php';
	}
	require_once $X_root.'pvt/pages/upload/uploadCover.php';
}

//istanzio i DAO
$siteDAO = New SiteDAO();
$reviewDAO = New ReviewDAO();
$countryDAO = New CountryDAO();

//conterra' la descrizione dell'errore localizzata
$errorField = "";

/** ------ Gestione Asincrona ------ **/
doAsyncGet($X_langArray);

require_once $X_root."pvt/pages/checkSession.php";

/** ------ Gestione POST ------ **/
$catRev =  htmlspecialchars($_POST['catRev']);
$langCode =  htmlspecialchars($_POST['langCode']);
$site = htmlspecialchars($_POST['site']); //nome che digita l'utente
$siteName = htmlspecialchars($_POST['siteName']); //nome che correggo io con google maps
$locality = htmlspecialchars($_POST['locality']); //indirizzo che digita l'utente
$frmtdAdrs = htmlspecialchars($_POST['frmtdAdrs']); //indirizzo che correggo io con google maps
$descr = addslashes($_POST['descr']);
$arrive = htmlspecialchars($_POST['arrive']);
$warn = htmlspecialchars($_POST['warn']);
$whEat = htmlspecialchars($_POST['whEat']);
$cook = htmlspecialchars($_POST['cook']);
$whStay = htmlspecialchars($_POST['whStay']);
$myth = htmlspecialchars($_POST['myth']);
$vote = htmlspecialchars($_POST['vote']);
$vote = preg_match("/^\d{1,2}$/", $vote) && $vote >= 0 && $vote <= 5 ? $vote : 3;

//echo  "country".$country. "". "langCode". $langCode. "locality".$locality. "site".$site. ""."descr".$descr. ""."arrive".$arrive. ""."warn".$warn. ""."myth".$myth. ""."whEat".$whEat. "";
//print_r($_SESSION['IMG_COMMENT_ARRAY']); echo "<br>";print_r($_SESSION['IMG_FILENAME_ARRAY']);echo "<br>";

checkCatRev($catRev, $X_langArray);
checkSite($site, $X_langArray);
checkLocality($locality, $X_langArray);
checkDescr($descr, $X_langArray);
checkCover($X_langArray);
checkArrive($arrive, $X_langArray);
checkWarn($warn, $X_langArray);
checkWhEat($whEat, $X_langArray);
checkCook($cook, $X_langArray);
checkWhStay($whStay, $X_langArray);
checkMyth($myth, $X_langArray);

// GEO Code
$geoSite = array();
$geoSite['siteName']  = $siteName;
$geoSite['frmtdAdrs'] = $frmtdAdrs;
$geoSite['country']   = htmlspecialchars($_POST['country']);
$geoSite['lat'] 	  = htmlspecialchars($_POST['lat']);
$geoSite['lng'] 	  = htmlspecialchars($_POST['lng']);
$geoSite['placeId']   = htmlspecialchars($_POST['placeId']);
checkGeoSite($geoSite, $X_langArray);

//Stringa dei parametri inseriti
$oldParams = 'tabactive=1&catRev='.urlencode($_POST['catRev']).'&locality='.urlencode(stripslashes($locality)).'&frmtdAdrs='.urlencode(stripslashes($frmtdAdrs)).
			 '&site='.urlencode(stripslashes($site)).'&siteName='.urlencode(stripslashes($siteName)).'&descr='.urlencode($_POST['descr']).
			 '&arrive='.urlencode($_POST['arrive']).'&warn='.urlencode(stripslashes($warn)).'&myth='.urlencode(stripslashes($myth)).'&whEat='.urlencode($_POST['whEat']).
			 '&cook='.urlencode($_POST['cook']).'&whStay='.urlencode($_POST['whStay']).'&vote='.urlencode($vote);
//no errors
if ($errorField == "") {
	
	//Se non ci sono errori verifico il pulsante di submit
	switch ($_POST['submit']) {
		//Avanti
		case $X_langArray['CREATE_REVIEW_SUBMIT_VAL']:
			header('Location: '.getURI().'/viantes/pub/pages/review/createReview.php?'.$oldParams.'&finish=true');
			exit;
        break;
		//Indietro
		case $X_langArray['CREATE_REVIEW_CHANGE_VAL']:
			header('Location: '.getURI().'/viantes/pub/pages/review/createReview.php?'.$oldParams);
			exit;
		break;
	}
	
	addSlash($catRev, $langCode, $site, $siteName, $locality, $frmtdAdrs, $descr, $arrive, $warn, $whEat, $cook, $whStay, $myth, $geoSite);
	
	$userDO = unserialize($_SESSION["USER_LOGGED"]) ;
	$bean = unserialize($_SESSION[$_POST['beanSessionKey']]);
	
	//recupero l'id della nazione
	$countryId = $countryDAO->createCountryIfNotExis($geoSite['country'], $langCode);
	
	//creo la recensione
	$siteDO = $siteDAO->createSiteIFNotEx($geoSite, $countryId, $langCode);
	
	//creo la recensione
	$reviewId = $reviewDAO->createReview($userDO->getId(), $siteDO->getId(), $catRev, $langCode, $descr, $arrive, $warn, 
					$whEat, $cook, $whStay, $myth, $vote, $bean);	
	
	//inserisco le (eventuali) immagini 
	if ( count($bean->getImgFileNameArray()) > 0 ) {
		$paths = $bean->getImgRelativeFilePathArray();
		$names  = $bean->getImgFileNameArray();
		$cmnts = $bean->getImgCommentArray();
		$widths = $bean->getImgWidthArray();
		$heights= $bean->getImgHeightArray();
		$reviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, IMG, $widths, $heights, $cmnts);
	}
	
	//inserisco gli (eventuali) video */
	if ( count($bean->getMovFileNameArray()) > 0 ) {
		$paths  = $bean->getMovRelativeFilePathArray();
		$names  = $bean->getMovFileNameArray();
		$cmnts  = $bean->getMovCommentArray();
		$widths = $bean->getMovWidthArray();
		$heights= $bean->getMovHeightArray();
		$reviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, MOV, $widths, $heights, $cmnts);
	}
	
	//inserisco gli (eventuali) documenti */
	if ( count($bean->getDocFileNameArray()) > 0 ) {
		$paths = $bean->getDocRelativeFilePathArray();
		$names  = $bean->getDocFileNameArray();
		$cmnts = $bean->getDocCommentArray();
		$reviewDAO->insertFileArrayNoBlob($reviewId, $userDO->getId(), $paths, $names, DOC, $dummy = NULL, $dummy = NULL, $cmnts);
	}
	
	$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['CREATE_REVIEW_PAGE_TOP_MSG_OK'];
	unset($_SESSION[$_POST['beanSessionKey']]);
	
	header('Location: '.getURI().'/viantes/pub/pages/review/myReview.php');
	exit;
}

//forward
header('Location: '.getURI().'/viantes/pub/pages/review/createReview.php?'.$oldParams.$errorField);
exit;
?>

<?php
/* Gestisce una richiesta Get asincrona */
function doAsyncGet($X_langArray){
	global $errorField;
	
	if ( isset($_GET['catRev']) ) {
		$catRevGet = htmlspecialchars( $_GET['catRev'] );
		//TODO $catRevGetNo = $_GET['catRev'];
		checkCatRev($catRevGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
	
	if ( isset($_GET['locality']) ) {
		$localityGet = htmlspecialchars( $_GET['locality'] );
		//TODO $localityGetNo = $_GET['locality'];
		checkLocality($localityGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
	
	if ( isset($_GET['site']) ) {
		$siteGet = htmlspecialchars( $_GET['site'] );
		//TODO $siteGetNo = $_GET['site'];
		checkSite($siteGet, $X_langArray);
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

/* Controlla la validita' del campo catRev: setta l'errore in una variabile globale */
function checkCatRev($catRev, $X_langArray) {
	global $errorField;
	
	//site vuoto
	if ($catRev == '-1'){
		$errorField .= "&catRevErrMsg=".urlencode($X_langArray['CREATE_REVIEW_EMPTY_CATREV_ERR']);
	}
}

/* Controlla la validita' del campo site: setta l'errore in una variabile globale */
function checkSite($site, $X_langArray) {
	global $errorField, $reviewDAO;
	
	//site vuoto
	//if (  (!isset($site) || $site == '') && (!isset($siteName) || $siteName == '')  ){
	if (  !isset($site) || $site == '' ){	
		$errorField .= "&siteErrMsg=".urlencode($X_langArray['CREATE_REVIEW_SITE_EMPTY_ERR']);
	}
	else if ( strlen($site) < 3 ||  strlen($site) > 40 ) {
		$errorField .= "&siteErrMsg=".urlencode($X_langArray['CREATE_REVIEW_SITE_LENGTH_ERR']);
	}
}

/* Controlla la validita' del campo locality: setta l'errore in una variabile globale */
function checkLocality($locality, $X_langArray) {
	global $errorField, $reviewDAO;
	
	//locality vuoto
	if (!isset($locality) || $locality == ''){
		$errorField .= "&localityErrMsg=".urlencode($X_langArray['CREATE_REVIEW_LOCALITY_EMPTY_ERR']);
	}
	else if (strlen($locality) < 3 || strlen($locality) > 80) {
		$errorField .= "&localityErrMsg=".urlencode($X_langArray['CREATE_REVIEW_LCOALITY_LENGTH_ERR']);
	}
}

/* Controlla la validita' del campo descr: setta l'errore in una variabile globale */
function checkDescr($descr, $X_langArray) {
	global $errorField;
	
	//descr vuoto
	if (!isset($descr) || $descr == ''){
		$errorField .= "&descrErrMsg=".urlencode($X_langArray['CREATE_REVIEW_EMPTY_DS_ERR']);
	}
	else if (strlen($descr) < 50 || strlen($descr) > 2000) {
		$errorField .= "&descrErrMsg=".urlencode($X_langArray['CREATE_REVIEW_DS_LENGTH_ERR']);
	}
}

/* Controlla la validita' del campo arrive: setta l'errore in una variabile globale */
function checkArrive($arrive, $X_langArray) {
	global $errorField;
	
	if ( strlen($arrive) > 0 && strlen($arrive) < 25 ) {
		$errorField .= "&arriveErrMsg=".urlencode($X_langArray['CREATE_REVIEW_ARRIVE_LENGTH_MIN_ERR']);
	}
	if ( strlen($arrive) > 500 ) {
		$errorField .= "&arriveErrMsg=".urlencode($X_langArray['CREATE_REVIEW_ARRIVE_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo warn: setta l'errore in una variabile globale */
function checkWarn($warn, $X_langArray) {
	global $errorField;
	
	if ( strlen($warn) > 0  && strlen($warn) < 10 ) {
		$errorField .= "&warnErrMsg=".urlencode($X_langArray['CREATE_REVIEW_WARN_LENGTH_MIN_ERR']);
	}
	if ( strlen($warn) > 100 ) {
		$errorField .= "&warnErrMsg=".urlencode($X_langArray['CREATE_REVIEW_WARN_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo whEat: setta l'errore in una variabile globale */
function checkWhEat($whEat, $X_langArray) {
	global $errorField;
	
	if ( strlen($whEat) > 0 && strlen($whEat) < 25 ) {
		$errorField .= "&whEatErrMsg=".urlencode($X_langArray['CREATE_REVIEW_WTEAT_LENGTH_MIN_ERR']);
	}
	if ( strlen($whEat) > 500 ) {
		$errorField .= "&whEatErrMsg=".urlencode($X_langArray['CREATE_REVIEW_WTEAT_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo cook: setta l'errore in una variabile globale */
function checkCook($cook, $X_langArray) {
	global $errorField;
	
	if ( strlen($cook) > 0 && strlen($cook) < 25 ) {
		$errorField .= "&cookErrMsg=".urlencode($X_langArray['CREATE_REVIEW_COOK_LENGTH_MIN_ERR']);
	}
	if ( strlen($cook) > 500 ) {
		$errorField .= "&cookErrMsg=".urlencode($X_langArray['CREATE_REVIEW_COOK_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo whStay: setta l'errore in una variabile globale */
function checkWhStay($whStay, $X_langArray) {
	global $errorField;
	
	if ( strlen($whStay) > 0 && strlen($whStay) < 25 ) {
		$errorField .= "&whStayErrMsg=".urlencode($X_langArray['CREATE_REVIEW_WHSTAY_LENGTH_MIN_ERR']);
	}
	if ( strlen($whStay) > 500 ) {
		$errorField .= "&whStayErrMsg=".urlencode($X_langArray['CREATE_REVIEW_WHSTAY_LENGTH_MAX_ERR']);
	}
}

/* Controlla la validita' del campo myth: setta l'errore in una variabile globale */
function checkMyth($myth, $X_langArray) {
	global $errorField;
	
	if ( strlen($myth) > 0 && strlen($myth) < 10 ) {
		$errorField .= "&mythErrMsg=".urlencode($X_langArray['CREATE_REVIEW_MYTH_LENGTH_MIN_ERR']);
	}
	if ( strlen($myth) > 100 ) {
		$errorField .= "&mythErrMsg=".urlencode($X_langArray['CREATE_REVIEW_MYTH_LENGTH_MAX_ERR']);
	}
}

/* Controlla la presenza dell'immagine di copertina */
function checkCover($X_langArray) {
	global $errorField;
	$bean = isset($_SESSION["REVIEWN_BEAN"]) ? unserialize($_SESSION["REVIEWN_BEAN"]) : null;
	if ( $bean == null || $bean->getCoverFileName() == null || $bean->getCoverFileName() == '' ) {
		$errorField .= "&loadCovImgErrMsg=".urlencode($X_langArray['CREATE_REVIEW_COVER_ERR']);
	}
}

/* Controlla Che i dati di geolocalizzazione siano validi e consistenti */
function checkGeoSite($geoSite, $X_langArray) {
	global $errorField;
	
	if ($geoSite['siteName'] == '' || $geoSite['frmtdAdrs'] == '' || $geoSite['country'] == '' || $geoSite['placeId'] == '') {
		$errorField .= "&localityErrMsg=".urlencode($X_langArray['CREATE_REVIEW_ADDRS_NOT_VALID']);
	}
}	
/* Add slash function */
function addSlash($_catRev, $_langCode, $_site, $_siteName, $_locality, $_frmtdAdrs, $_descr, $_arrive, $_warn, $_whEat, $_cook, $_whStay, $_myth, $_geoSite) {
	global $catRev, $langCode, $site, $siteName, $locality, $frmtdAdrs, $descr, $arrive, $warn, $whEat, $cook, $whStay, $myth, $geoSite;
	
	$catRev = addslashes($_catRev);
	$langCode = addslashes($_langCode);
	$site = addslashes($site);
	$siteName = addslashes($_siteName);
	$locality = addslashes($_locality);
	$frmtdAdrs = addslashes($_frmtdAdrs);
	$descr = addslashes($_descr);
	$arrive = addslashes($_arrive);
	$warn = addslashes($_warn);
	$whEat = addslashes($_whEat);
	$cook = addslashes($_cook);
	$whStay = addslashes($_whStay);
	$myth = addslashes($_myth);
	$geoSite['siteName'] = addslashes($_geoSite['siteName']);
	$geoSite['frmtdAdrs'] =	addslashes($_geoSite['frmtdAdrs']);
	$geoSite['placeId'] = addslashes($_geoSite['placeId']);
	$geoSite['country'] = addslashes($_geoSite['country']);
	$geoSite['lat'] = addslashes($_geoSite['lat']);
	$geoSite['lng'] = addslashes($_geoSite['lng']);
};
?>
