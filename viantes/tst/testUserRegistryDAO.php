<?php
ini_set('display_errors', 1);
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/auth/userDO.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/auth/userRegistryDAO.php";

$dao = New UserRegistryDAO();

/*
 * Test me to /viantes/tst/testUserRegistryDAO.php?method=...
 */

$usrId=6;
$pattern = getDatePatternByLangCode('it');

// Method= 1 - Test getUserRegistryByUserId
if ($_GET['method']==1){
	$dao->getUserRegistryByUserId($usrId, $pattern); //va sulla base dati
	$dao->getUserRegistryByUserId($usrId, $pattern); //usa memcached
}

// Method= 2 - Test insertOrUpdate
if ($_GET['method']==2){
	$dao->insertOrUpdate($usrId, "Test Name",  "Test lastNm",  "", 0, "", "", "12345", "", ""); //va sulla base dati
	$dao->insertOrUpdate($usrId, "Test Name2", "Test lastNm2", "", 0, "", "", "54321", "", ""); //usa memcached
}

?>
