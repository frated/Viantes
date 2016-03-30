<?php
ini_set('display_errors', 1);
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/lang/languageDAO.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/lang/countryDAO.php";

/*
 * Test me to /viantes/tst/testLangAndCountryDAO.php?method=...
 */

// Method= 1 - Test getLanguages
if ($_GET['method']==1){
	$dao = New LanguageDAO();
	
	$dao->getLanguages(); //va sulla base dati
	$dao->getLanguages(); //usa memcached
}


// Method= 2 - Test createCountryIfNotExis
if ($_GET['method']==2){
	$dao = New CountryDAO();
	
	$dao->createCountryIfNotExis("AAA", "it"); //va sulla base dati
	$dao->createCountryIfNotExis("AAA", "it"); //usa memcached
}
?>
