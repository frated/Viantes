<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/auth/clientRequestDAO.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/common/commonMail.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/log/log.php";

$uri = (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) ? 'https://' : 'http://';
$uri .= $_SERVER['HTTP_HOST'];

//conterra' la descrizione dell'errore localizzata
$errorField = "";

$srcPage = $_POST['srcPage'];
$destPage = $_POST['destPage'];

//Controllo aggiuntivo nel caso in cui il parametro 'srcPage' ha dei parametri => contiene un '?'
$puntoInterr = (strpos( $srcPage, '?' ) == false ) ? "?" : "&";

$email = htmlspecialchars( $_POST['recemail'] );

// Captcha Control 
if ( Conf::getInstance()->get('doCaptcha') == 1) {
	$errorField .= checkCaptcha(PWDRECOVER, $X_langArray);
}

checkEmail($email, $X_langArray);

if ($errorField != "") {
	$errStr = "&srcPage=".urlencode($srcPage)."&destPage=".urlencode($destPage)."&recemail=".$email.$errorField.
			  "&ovl-ls-mode=".OVL_LS_MODE_RECOVER;
	header('Location: '.$uri.'/'.$srcPage.$puntoInterr."showOverlayLgSg=val".$errStr);
	exit;
} else{
	
	//massimo 3 recovery ogni 24 ore
	$clientReqDAO = NEW ClientRequestDAO();
	if (!$clientReqDAO->isRecoverable()) {
		header('Location: '.$uri.'/'.$destPage);
		$_SESSION[GLOBAL_TOP_MSG_ERROR] = $X_langArray['RECOVER_PWD_MAX_REQUEST_ERROR'];
		exit;
	}
	
	//log request 
	$clientReqDAO->logRequest(PWDRECOVER);
	
	$usrDAO = New UserDAO();	
	$fwdCode = $usrDAO->recoverPwd($email);
	
	if ($fwdCode) {
		//invio mail
		$se = sendMailForRecover($email, $fwdCode, $X_langArray);
		//se non invio la mail => la stampo
		if ( Conf::getInstance()->get('doMail') == 0) { 
			echo $se; exit;
		}
	}
	
	$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['OVERLAY_LOG_SIGN_RECOVER_OK'];
	
	header('Location: '.$uri.'/'.$destPage);
	exit;
}
?>

<?php
/* Controlla la validita' del campo email: setta l'errore in una variabile globale */
function checkEmail($email, $X_langArray) {

	global $errorField, $usr;
	
	//email vuota
	if (!isset($email) || $email == ''){
		$errorField .= "&recPwdEmailErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_EMPTY_EMAIL_ERR']);
	}
	else{
		//se l'email non e' una email
		if ( !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email) ) {
			$errorField .= "&recPwdEmailErrMsg=".urlencode($X_langArray['GEN_IS_NOT_EMAIL_REG']);
		}
		else{	
			$usrDAO = New UserDAO();	
			$exists = $usrDAO->checkEmailAlreadyExists($email);
			if ( !$exists) {
				$errorField .= "&recPwdEmailErrMsg=".urlencode($X_langArray['RECOVER_PWD_EMAIL_NOT_EX']);
			}
		}
	}
}
?>