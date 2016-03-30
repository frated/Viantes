<?php
ini_set('display_errors', 1);
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";


if (!isset($_SESSION["USER_LOGGED"])) {
	echo "01 - Utente non loggato";
	exit;
}

$userDO = unserialize($_SESSION["USER_LOGGED"]);

if ( !isset($_GET['reviewId']) || !isset($_GET['revType']) || !isset($_GET['post']) ) {
	echo "02 -  Parametri mancanti";
	exit;
}

$reviewId = $_GET['reviewId'];		// id
$revType  = $_GET['revType'];		// 1, 2 o 3
$post     = $_GET['post']; 			// 1 per una star, 2 per un see

if (strlen($post) > 140) {
	echo "00";	
	exit;
}

//Istanzio il dao
if ($revType == SiteReview) $dao = NEW ReviewDAO();
if ($revType == CityReview) $dao = NEW CityReviewDAO();
if ($revType == CountryReview) $dao = NEW CountryReviewDAO();

$dao->setReviewPost($userDO->getId(), $reviewId, $revType, $post);

//aggiorno le star/see dell'utente per questa recensione
$usrDAO = NEW UserDAO();
$userDO->setStar($usrDAO->buildUserStar( $userDO->getId() ));
$_SESSION["USER_LOGGED"] = serialize($userDO);
echo "00";
?>
