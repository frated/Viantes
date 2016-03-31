<?php
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/checkSession4Script.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";

$userDO = unserialize($_SESSION["USER_LOGGED"]);

if ( !isset($_GET['mode']) || !isset($_GET['reviewId']) || !isset($_GET['revType']) || !isset($_GET['star']) ) {
	echo "01 -  Parametri mancanti";
	exit;
}

$mode     = $_GET['mode'];			// do oppure undo
$reviewId = $_GET['reviewId'];		// id
$revType  = $_GET['revType'];		// 1, 2 o 3
$star     = $_GET['star']; 			// 1 per una star, 2 per un see

if ($star != 1 && $star != 2 ) {
	echo "02 - Parametri errati [star]"; 
	exit;
}

if ($mode != 'do' && $mode != 'undo') {
	echo "02 - Parametri errati [mode]"; 
	exit;
}

if ($revType == SiteReview) $dao = NEW ReviewDAO();
if ($revType == CityReview) $dao = NEW CityReviewDAO();
if ($revType == CountryReview) $dao = NEW CountryReviewDAO();

if ($mode=='do') {
	if ($star == 1)
		$dao->setReviewStar($userDO->getId(), $reviewId, $revType);
	else
		$dao->setReviewSee($userDO->getId(), $reviewId, $revType);
}
else if ($mode=='undo') {
	if ($star == 1)
		$dao->unsetReviewStar($userDO->getId(), $reviewId, $revType);	
	else	
		$dao->unsetReviewSee($userDO->getId(), $reviewId, $revType);	
}

//aggiorno le star/see dell'utente per questa recensione
$usrDAO = NEW UserDAO();
$userDO->setStar($usrDAO->buildUserStar( $userDO->getId() ));
$_SESSION["USER_LOGGED"] = serialize($userDO);
echo "00";
?>
