<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/checkSession4Script.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/msg/msgDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";

$msgDAO = New MsgDAO();

/* id dei messaggi da cancellare separati da ; (il separatore non puo' essere usato)
 * in caso di messaggi da ripristinare sono nella forma:
 * id1|senderStatus1|toStatus1;id2|senderStatus2|toStatus2;... */
$codeIds = explode(";", $_GET['msgList']);

$mode = $_GET['mode'];
$ids = array();

//Decodifico i codeIds 
foreach($codeIds as $key => $val) array_push($ids, X_deco($val));

if ($mode == 1 ) $msgDAO->delInMsg($ids);
if ($mode == 2 ) $msgDAO->delSentMsg($ids); 
if ($mode == 3 ) $msgDAO->delDraftMsg($ids);
if ($mode == 4 ) {
	$userDO = unserialize($_SESSION["USER_LOGGED"]);
	$usrId = $userDO->getId();
	$msgDAO->delTrashMsg($ids, $usrId);
}
if ($mode == 5 ) {
	$userDO = unserialize($_SESSION["USER_LOGGED"]);
	$usrId = $userDO->getId();
	$msgDAO->restoreDelMsg($ids, $usrId);
}
?>
