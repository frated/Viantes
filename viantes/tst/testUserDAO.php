<?php
ini_set('display_errors', 1);
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/auth/userDO.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/auth/userDAO.php";

$userDAO = New UserDAO();

/*
 * Test me to /viantes/tst/testUserDAO.php?method=...
 */
$mail="a@a.com";
$pwd ="AAaa11";
$usrId=6;
$usrId2=7;

// Method= 1 - Test getLazyUserDO
if ($_GET['method']==1){
	$userDO = $userDAO->getLazyUserDO($usrId); //va sulla base dati
	$userDO = $userDAO->getLazyUserDO($usrId); //usa memcache
}

// Method= 2 - Test getCoverFileName()
if ($_GET['method']==2){
	$userDO = $userDAO->getLazyUserDO($usrId);
	$temp = $userDO->getCoverFileName();
	$userDAO->updateCover("nuova cover", $usrId); //se modificao la bck cover devo cancellare la chiave
	$userDO = $userDAO->getLazyUserDO($usrId); //va sulla abse dati
	$userDAO->updateCover($temp, $usrId); //ripristino in dato iniziale
}

// Method= 3 - Test getBckCoverFileName()
if ($_GET['method']==3){
	$userDO = $userDAO->getLazyUserDO($usrId);
	$temp = $userDO->getBckCoverFileName();
	$userDAO->updateBckCover("nuova bck cover", $usrId); //se modificao la bck cover devo cancellare la chiave
	$userDO = $userDAO->getLazyUserDO($usrId); //va sulla abse dati
	$userDAO->updateBckCover($temp, $usrId);//ripristino in dato iniziale
}

// Method= 4 - Test getHaveBlockedUsrList()
if ($_GET['method']==4){
	$userDAO->getHaveBlockedUsrList($usrId); //ottengo la lista da db
	$userDAO->getHaveBlockedUsrList($usrId); //ottengo la lista da memcached
	$userDAO->blockOrUnblockUsr($usrId2, $usrId, 0);     //2 blocca 1
	$userDAO->getHaveBlockedUsrList($usrId); //ottengo la lista dal db
	
	$userDAO->getHaveBlockedUsrList($usrId2); //ottengo la lista dal db
	$userDAO->getHaveBlockedUsrList($usrId2); //ottengo la lista da memcached
	
	$userDAO->getBlockedUsrList($usrId); //ottengo la lista da db
	$userDAO->getBlockedUsrList($usrId); //ottengo la lista da memcached

	$userDAO->getBlockedUsrList($usrId2); //ottengo la lista da db
	$userDAO->getBlockedUsrList($usrId2); //ottengo la lista da memcached
}

// Method= 5 - Test getBlockedUsrList()
if ($_GET['method']==5){
	$userDAO->getBlockedUsrList($usrId2); //ottengo la lista da db
	$userDAO->getBlockedUsrList($usrId2); //ottengo la lista da memcached
	$userDAO->blockOrUnblockUsr($usrId2, $usrId, 1);     //2 blocca 1
	$userDAO->getBlockedUsrList($usrId2); //ottengo la lista dal db
	
	$userDAO->getBlockedUsrList($usrId); //ottengo la lista da db
	$userDAO->getBlockedUsrList($usrId); //ottengo la lista da memcached
	
	$userDAO->getHaveBlockedUsrList($usrId); //ottengo la lista dal db
	$userDAO->getHaveBlockedUsrList($usrId); //ottengo la lista da memcached
	
	$userDAO->getHaveBlockedUsrList($usrId2); //ottengo la lista dal db
	$userDAO->getHaveBlockedUsrList($usrId2); //ottengo la lista da memcached
}

// Method=  - Test buildUserStar()
if ($_GET['method']==6){
	print_r( $userDAO->buildUserStar($usrId));
	
}
?>
