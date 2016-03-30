<?php
ini_set('display_errors', 1);
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
require_once $X_root."pvt/pages/review/commonReviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewBean.php";

$dao = New CommonReviewDAO();

/*
 * Test me to /viantes/tst/testCommonReviewDAO.php?method=...
 */
 
$palceId=1;
$revId=1;
$revType=1;
$lang='it';


// Method= 1 - Test getRateArray
if ($_GET['method']==1){
	$dao->getRateArray($palceId, $revType); //va sulla base dati
	$dao->getRateArray($palceId, $revType); //usa memcache
}

// Method= 2 - Test getAllMedia
if ($_GET['method']==2){
	$dao->getAllMedia($revId,$revType); //va sulla base dati
	$dao->getAllMedia($revId,$revType); //usa memcache
}

// Method= 3 - Test setReviewStar
if ($_GET['method']==3){
	$usrId=6;
	$reviewId=3;
	$type=1; 
	$star=1;
	$dao->setReviewStar($usrId, $reviewId, $type, $star);
}

// Method= 4 - Test unsetReviewStar
if ($_GET['method']==4){
	$usrId=6;
	$reviewId=3;
	$type=1; 
	$star=1;
	$dao->unsetReviewStar($usrId, $reviewId, $type, $star);
}

// Method= 5 - Test setReviewSee
if ($_GET['method']==5){
	$usrId=6;
	$reviewId=3;
	$type=1; 
	$star=1;
	$dao->setReviewSee($usrId, $reviewId, $type, $star);
}

// Method= 6 - Test unsetReviewSee
if ($_GET['method']==6){
	$usrId=6;
	$reviewId=3;
	$type=1; 
	$star=1;
	$dao->unsetReviewSee($usrId, $reviewId, $type, $star);
}

// Method= 7 - Test unsetReviewSee
if ($_GET['method']==7){
	$usrId=6;
	$reviewId=3;
	$type=1; 
	$star=1;
	$dao->setReviewPost($usrId, $reviewId, $type, "il mio 4 post");
}

// Method= 8 - Test unsetReviewSee
if ($_GET['method']==8){
	$reviewId=3;
	$type=1; 
	print_r($dao->buildRevStar($reviewId, $type));
}
?>
