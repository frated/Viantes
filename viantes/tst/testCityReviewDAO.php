<?php
ini_set('display_errors', 1);
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewBean.php";

$dao = New CityReviewDAO();

/*
 * Test me to /viantes/tst/testCityReviewDAO.php?method=...
 */

$usrId=6;
$reviewId=1;
$lang='en';
$pattern = getDatePatternByLangCode($lang);


// Method= 1 - Test getReviewList
if ($_GET['method']==1){
	$dao->getReviewList($usrId, $pattern); //va sulla base dati
	$dao->getReviewList($usrId, $pattern); //usa memcache
	
	/* Create fake review *
	$city = "bla"; 
	$countryId=1;
	$bean = new CityReviewBean();
	$interest = array();
	$bean->setCoverFileName("");
	$bean->setCoverWidth(0);
	$bean->setCoverHeight(0);
	$dao->createCityReview($usrId, $city,  $countryId, $lang, "", "", "", "", "", "", "", 0, $bean, $interest);
	$dao->getReviewList($usrId, $pattern); //va sulla base dati
	$dao->getReviewList($usrId, $pattern); //usa memcache
	*/
}

// Method= 2 - Test getReviewById
if ($_GET['method']==2){
	$dao->getReviewById($reviewId); //va sulla base dati
	$dao->getReviewById($reviewId); //usa memcache
}

// Method= 3 - Test getAttachIdListByReviewIdAndType
if ($_GET['method']==3){
	$dao->getAttachIdListByReviewIdAndType($reviewId, 'MOV'); //va sulla base dati
	$dao->getAttachIdListByReviewIdAndType($reviewId, 'MOV'); //usa memcache
}

// Method= 4 - Test getAttachDOById
if ($_GET['method']==4){
	$id=1;
	$dao->getAttachDOById($id); //va sulla base dati
	$dao->getAttachDOById($id); //usa memcache
}

// Method= 5 - Test getInterestByRevId
if ($_GET['method']==5){
	$id=1;
	$dao->getInterestByRevId($reviewId); //va sulla base dati
	$dao->getInterestByRevId($reviewId); //usa memcache
}

// Method= 6 - Test searchReviews
if ($_GET['method']==6){
	$keyword = "Pisa";
	$orderType = "5"; //star decrescente
	$res = $dao->searchReviews($lang, $keyword, 0, 0, 0, $orderType) ;
	foreach ($res as $key => $review ){
		//print_r($review);
		echo $review->getCityName(). " - Data Inserimento: " . $review->getDtIns(). " - Voto: ".$review->getVote(). " - Stelle: ".$review->getCntStar();
		echo "<br/>";
	}
}
?>
