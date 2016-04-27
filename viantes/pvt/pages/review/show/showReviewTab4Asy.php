<?php
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
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
				stripslashes( $attachDO->getComment() ) : 
				$X_langArray['SHOW_REVIEW_DOC_NO_COMMENT'];

//get formatted file name
$fn = str_replace(" ", "_", $attachDO->getFileName());
$fnFormatted = strlen($fn) < 25 ? $fn : substr($fn, 0, 12).'...'.substr($fn, -6);
						
echo $fullFileName ."@#@". $fnFormatted ."@#@". $comment;
?>
