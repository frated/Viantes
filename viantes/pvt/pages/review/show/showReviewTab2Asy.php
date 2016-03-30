<?php
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";
require_once $X_root."pvt/pages/review/attachDO.php";

$id = isset($_GET['id']) ? $_GET['id'] : -1;
$revTp = isset($_GET['revTp']) ? $_GET['revTp'] : -1;

//Get DAO for review type
if ( $revTp == SiteReview )
	$reviewDAO = New ReviewDAO();
else if ( $revTp == CityReview )
	$reviewDAO = New CityReviewDAO();
else if ( $revTp == CountryReview )
	$reviewDAO = New CountryReviewDAO();

$attachDO = $reviewDAO->getAttachDOById($id);
$fullFileName = $attachDO->getFilePath().$attachDO->getFileName();
$comment = $attachDO->getComment() != '' ? 
				$attachDO->getComment() : 
				$X_langArray['SHOW_REVIEW_IMG_NO_COMMENT'];

echo $fullFileName ."@#@". $comment;

//Save or update an arry in session for a viewver new features
/**
if( isset($_SESSION['showRevImgResult']) ) {
	$showRevImgResult = $_SESSION['showRevImgResult'];
} else {
	$showRevImgResult = array();
}
$showRevImgResult[count($showRevImgResult)] = $fullFileName ."@#@". $comment;

$_SESSION['showRevImgResult'] = $showRevImgResult;
* */
	
/* GESTIONE CON BLBO 
$dataFile = $reviewDAO->getAttachById($id);
echo base64_encode($dataFile);
************************/
?>
