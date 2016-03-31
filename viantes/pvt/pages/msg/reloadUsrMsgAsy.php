<?php 
/**
 * Aggiorna il numero di messaggi da leggere per l'utente loggato
 */
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/checkSession4Script.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/msg/msgDAO.php";
require_once $X_root."pvt/pages/msg/msgDO.php";

$userDO = unserialize($_SESSION["USER_LOGGED"]);
$msgDAO = New MsgDAO();
$toBeRead = $msgDAO->getToBeReadMsgNum($userDO->getId());
if ($toBeRead > 0) {
	$userDO->setInBoxMsgNum($toBeRead);	
	$_SESSION['USER_LOGGED'] = serialize($userDO);
}
?>