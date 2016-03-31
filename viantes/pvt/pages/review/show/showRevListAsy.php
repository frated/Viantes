<?php
$X_root = "../../../../";
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/attachDO.php";
//require_once $X_root."pvt/pages/review/reviewDO.php";
//require_once $X_root."pvt/pages/review/cityReviewDO.php";

if ( !isset($_POST['reviewId']) || !isset($_POST['numOfBox']) || !isset($_POST['mode'])   ) {
	//logg error
	$msg = "";
	$msg .= !isset($_POST['reviewId']) ? " :: parametro reviewId non passato nella POST" : "";
	$msg .= !isset($_POST['numOfBox']) ? " :: parametro numOfBox non passato nella POST" : "";
	$msg .= !isset($_POST['mode']) ? " :: parametro mode non passato nella POST" : "";
	Logger::log("showrevListAsy.php". $msg, 1);
	exit;
}
$reviewId     = X_deco($_POST['reviewId']);
$cityRevId    = X_deco($_POST['cityRevId']);
$countryRevId = X_deco($_POST['countryRevId']);
$numOfBox = $_POST['numOfBox'];
$mode = $_POST['mode'];

$reviewDAO = New ReviewDAO();

$result = "";

Logger::log("showRevListAsy :: main :: parameters: reviewId:" .$reviewId. "cityRevId:" .$cityRevId. "countryRevId:" .$countryRevId. " numOfBox:" .$numOfBox. " mode:" .$mode, 3);
$reviewDOArray = $reviewDAO->getReviews($reviewId, $cityRevId, $countryRevId, $numOfBox, $mode);

foreach($reviewDOArray as $reviewDO) {
	//get initial dimension
	list($widthIn, $heightIn) = getimagesize(HT_ROOT.$reviewDO->getCoverFileName());
	
	//scale in ratio
	$width = ratioImagDimensionFixHeight($widthIn, $heightIn, 128);
	
	if ( $reviewDO instanceof ReviewDO) {
		$result .= X_code($reviewDO->getId()).attributeDelim. X_code($reviewDO->getUsrId()).attributeDelim. $reviewDO->getSiteName().attributeDelim.  
			   $reviewDO->getDtIns().attributeDelim. $reviewDO->getDescr().attributeDelim. $reviewDO->getCoverFileName().attributeDelim.  
   			   $reviewDO->getUsrName().attributeDelim.$reviewDO->getUserCoverFileName().attributeDelim.$width.attributeDelim.SiteReview.attributeDelim.
   			   $reviewDO->getCntStar().attributeDelim.$reviewDO->getCntSee().attributeDelim.$reviewDO->getCntPost().listDelim;
	} 
	else if ( $reviewDO instanceof CityReviewDO) {
		$result .= X_code($reviewDO->getId()).attributeDelim. X_code($reviewDO->getUsrId()).attributeDelim. $reviewDO->getCityName().attributeDelim.  
			   $reviewDO->getDtIns().attributeDelim. $reviewDO->getDescr().attributeDelim. $reviewDO->getCoverFileName().attributeDelim.  
   			   $reviewDO->getUsrName().attributeDelim.$reviewDO->getUserCoverFileName().attributeDelim.$width.attributeDelim.CityReview.attributeDelim.
   			   $reviewDO->getCntStar().attributeDelim.$reviewDO->getCntSee().attributeDelim.$reviewDO->getCntPost().listDelim;
	}
	else if ( $reviewDO instanceof CountryReviewDO) {
		$result .= X_code($reviewDO->getId()).attributeDelim. X_code($reviewDO->getUsrId()).attributeDelim. $reviewDO->getCountry().attributeDelim.  
			   $reviewDO->getDtIns().attributeDelim. $reviewDO->getDescr().attributeDelim. $reviewDO->getCoverFileName().attributeDelim.  
   			   $reviewDO->getUsrName().attributeDelim.$reviewDO->getUserCoverFileName().attributeDelim.$width.attributeDelim.CountryReview.attributeDelim.
   			   $reviewDO->getCntStar().attributeDelim.$reviewDO->getCntSee().attributeDelim.$reviewDO->getCntPost().listDelim;
	}	
}

echo substr($result, 0, -3);
exit;
?>
