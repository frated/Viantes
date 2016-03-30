<?php
/**
 * Questo script salva i luoghi di interesse in un CityReviewBean o le citta' 
 * di interesse in un CountryReviewBean. 
 * @param interestList: lista di id separati da ; che identificano una recensione
 * @param name: contiene la chiave di sessione del bean a runtime
 * @see renderImgFromSession.php, renderMovFromSession.php, renderDocFromSession.php
 */

$X_root = '../../../../';
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/review/cityReviewBean.php";
require_once $X_root."pvt/pages/review/countryReviewBean.php";

$arr = explode(listDelim, $_POST['interestList']);

$bean = isset($_SESSION[$_POST['name']]) ? unserialize($_SESSION[$_POST['name']]) : null;
if ($bean == null){
	$name = $_POST['name'];
	if ($name=='CITY_REVIEWN_BEAN') {
		$bean = new CityReviewBean();
		$bean->setInterest($arr);
	} else if ($name=='COUNTRY_REVIEWN_BEAN') {
		$bean = new CountryReviewBean();
		$bean->setCityInterest($arr);
	}
}
else{
	if ($bean instanceof CityReviewBean) {
		$bean->setInterest($arr);
	}else if ($bean instanceof CountryReviewBean) {
		$bean->setCityInterest($arr);
	}
}
$_SESSION[$_POST['name']] = serialize($bean);
?>
