<?php
ini_set('display_errors', 1);
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
require_once $X_root."pvt/pages/site/siteDO.php";
require_once $X_root."pvt/pages/site/siteDAO.php";

$siteDAO = New SiteDAO();

/*
 * Test me to /viantes/tst/testSiteDAO.php?method=...
 */

// Method= 1 - Test createSiteIFNotEx
if ($_GET['method']==1){
	$site = "sit1 en 22";
	$countryId = 1;
	$locality = "Solo un nome di prova";
	$langCode = 'it';

	$geoSite = array();
	$geoSite['siteName']  = "GE_NOME 22";
	$geoSite['frmtdAdrs'] = "GE_FRMT_ADDRS 22";
	$geoSite['lat'] 	  = 123.456789012;
	$geoSite['lng'] 	  = 012.345678344;
	$geoSite['placeId']   = "GEO_PLACE_ID2";

	$siteDO = $siteDAO->createSiteIFNotEx($geoSite, $countryId, $langCode); //crea il sito
	$siteDO = $siteDAO->createSiteIFNotEx($geoSite, $countryId, $langCode); //no crea il sito
	print_r($siteDO);
}

?>
