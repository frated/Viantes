<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/msg/msgDAO.php";

$msgDAO = New MsgDAO();
$usrDAO = New UserDAO();

//conterra' la descrizione dell'errore localizzata
$errorField = "";

/** ------ Gestione POST ------ **/
$from = X_deco(addslashes( htmlspecialchars($_POST['from']) ) );
$to = addslashes( htmlspecialchars($_POST['to']) );
$sbjt = addslashes( htmlspecialchars($_POST['sbjt']) );
$message = addslashes($_POST['message']);
$status = addslashes( htmlspecialchars($_POST['status']) );
$usrId = addslashes( htmlspecialchars($_POST['usrId']) );

//URL BACK
$urlBack = addslashes( htmlspecialchars($_POST['overlay-msg-url-back']) );

//se e' una salvataggio in bozza verifico solo il to
if ($status == 0) {
	checkTo($from, $to, $sbjt, $message, $X_langArray);
} else {
	checkAll($from, $to, $sbjt, $message, $X_langArray);
}

//no errors
if ($errorField == "") {

	$toUsrId = $usrDAO->checkNameAlreadyExists($to);
	
	//Se sto salvando un msg che era gia' in bozza (evirtare di fare un'altra insert)
	if ( isset($_POST['msgDrftId']) && $_POST['msgDrftId'] != "-1" ) {
		$msgDrftId = addslashes( htmlspecialchars($_POST['msgDrftId']) );
		$msgDAO->sendMsgInDraft(X_deco($msgDrftId), $from, $toUsrId, $sbjt, $message, $status);
	}
	else {
		$msgDAO->sendMsg($from, $toUsrId, $sbjt, $message, $status);
	}
	
	if ($status == 1)
		$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['MESSAGE_TOP_MSG_OK'];
	else
		$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['MESSAGE_TOP_MSG_DFT_OK'];
	unset($_SESSION[$_POST['beanSessionKey']]);
	
	header('Location: '.getURI().$urlBack.'?tabactive='.(isset($_POST['tabactive']) ? $_POST['tabactive'] : ($status == 1 ? '2' : '3'))
											.(isset($_POST['usrId']) ? '&usrId='.$_POST['usrId'] : '')
											.(isset($_POST['msgId']) ? '&msgId='.$_POST['msgId'] : '') );
	exit;
}

//old params
$oldParams = '&overlay-msg-url-back='.$urlBack.'&to='.urlencode($to).'&sbjt='.urlencode($sbjt).'&message='.urlencode($message).
			 '&msgDrftId='.$_POST['msgDrftId'].'&usrId='.$_POST['usrId'].'&msgId='.$_POST['msgId'].'&tabactive='.$_POST['tabactive'];

//forward
header('Location: '.getURI().$urlBack.'?showOverlay=t'.$oldParams.$errorField);
exit;
?>

<?php
//Check All Fields
function checkAll($from, $to, $sbjt, $message, $X_langArray) {
	global $errorField, $usrDAO;
	
	if ( !isset($to ) || '' == $to) {
		$errorField .= "&toErrMsg=".urlencode($X_langArray['MESSAGE_SEND_TO_EMPTY_ERR']);
	} else if ( !$usrDAO->checkNameAlreadyExists($to) ) {
		$errorField .= "&toErrMsg=".urlencode($X_langArray['MESSAGE_SEND_TO_NOT_EXISTS']);
	}
	
	if ( !isset($sbjt ) || '' == $sbjt) {
		$errorField .= "&sbjtErrMsg=".urlencode($X_langArray['MESSAGE_SEND_SBJT_EMPTY_ERR']);
	}
	else if ( strlen($sbjt) > 100 ) {
		$errorField .= "&sbjtErrMsg=".urlencode($X_langArray['MESSAGE_SEND_SBJT_LENGTH_MIN_ERR']);
	}
	
	if ( !isset($message ) || '' == $message) {
		$errorField .= "&messageErrMsg=".urlencode($X_langArray['MESSAGE_SEND_MESSAGE_EMPTY_ERR']);
	}
	else if ( strlen($message) > 2000 ) {
		$errorField .= "&messageErrMsg=".urlencode($X_langArray['MESSAGE_SEND_MESSAGE_LENGTH_MIN_ERR']);
	}
	
	//Sto scrivendo ad un utente che mi ha bloccato
	$haveBlockedUsrList = $usrDAO->getHaveBlockedUsrList($from);
	$toUsrId = $usrDAO->checkNameAlreadyExists($to);
	if ( in_array($toUsrId, $haveBlockedUsrList) ) {
		$errorField .= "&toErrMsg=".urlencode($X_langArray['MESSAGE_SEND_MESSAGE_NO_PERMITTED']);
	}
}

//Verifica solo che sia valorizzato il campo to
function checkTo($from, $to, $sbjt, $message, $X_langArray) {
	global $errorField, $usrDAO;
	
	if ( !isset($to ) || '' == $to) {
		$errorField .= "&toErrMsg=".urlencode($X_langArray['MESSAGE_SEND_TO_EMPTY_ERR']);
	} else if ( !$usrDAO->checkNameAlreadyExists($to) ) {
		$errorField .= "&toErrMsg=".urlencode($X_langArray['MESSAGE_SEND_TO_NOT_EXISTS']);
	}
}
?>
