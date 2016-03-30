<?php
ini_set('display_errors', 1);
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/setting/settingDAO.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/setting/settingDO.php";

$dao = New SettingDAO();

/*
 * Test me to /viantes/tst/testSettingDAO.php?method=...
 */
$usrId=6;

// Method= 1 - Test getUserRegistryByUserId
if ($_GET['method']==1){
	$settingDO = $dao->getSetting($usrId); //va sulla base dati
	$settingDO = $dao->getSetting($usrId); //usa memcached
	
	$dao->setInfo($settingDO->getLangCode(), $usrId);
	$settingDO = $dao->getSetting($usrId); //va sulla base dati
	
	$dao->setProfileType($settingDO->getProfileType(), $usrId);
	$settingDO = $dao->getSetting($usrId); //va sulla base dati
}

?>
