<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/auth/clientRequestDAO.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/lang/initLang.html";

$srcPage = $_POST['srcPage'];
$destPage = $_POST['destPage'];

$email = htmlspecialchars( $_POST['email'] );
$password = htmlspecialchars( $_POST['password'] );

//Controllo aggiuntivo nel caso in cui il parametro 'srcPage' ha dei parametri => contiene un '?'
$puntoInterr = (strpos( $srcPage, '?' ) == false ) ? "?" : "&";

//descrizione errore  localizzato
$errStr = "";

// Captcha Control 
if ( Conf::getInstance()->get('doCaptcha') == 1 ) {
	$errStr .= checkCaptcha(LOGIN, $X_langArray);
}

// Se Captcha OK e password ed email valorizzati
if (isset($email) && $email != '' && isset($password) && $password != '' && $errStr == '') {

	$usrDAO = New UserDAO();

	//log request
	$clientReqDAO = NEW ClientRequestDAO();
	$clientReqDAO->logRequest(LOGIN);

	//check is auth
	$userDO = $usrDAO->isAuth($email, sha1($password));
	if (is_object($userDO)) {
		//save userDO in sessione
		$_SESSION["USER_LOGGED"] = serialize($userDO); 
		
		//Opzione Ricrdami
		if( isset( $_POST['rememberMe'] ) ) {
			setcookie("LOGGED_IN", "OK", time() + 4 * 365 * 24 * 60 * 60, "/"); // 4 anni!!
		} else {
			$sessionTimeOut = intval(Conf::getInstance()->get('sessionTimeOut'));
			setcookie("LOGGED_IN", "OK", time() + $sessionTimeOut, "/");
		}
		
		//Forzo il ricaricamento del linguaggio, di modo che, se l'utente ha settato un suo linguaggio, prende quello
		unset($_SESSION['langArray']);

		//fwd to page
		header('Location: '.getURI().'/'.$destPage);
		exit;
	}
	else{
		//fwd to page with error
		$errStr .= "&srcPage=".urlencode($srcPage)."&destPage=".urlencode($destPage)."&email=".$email.
				   "&globalErrMsgFrm1=".urlencode($X_langArray['OVERLAY_LOG_SIGN_EMAIL_OR_PWD_INC_ERR']).
				   "&ovl-ls-mode=".OVL_LS_MODE_LOGIN;
		header('Location: '.getURI().'/'.$srcPage.$puntoInterr."showOverlayLgSg=val".$errStr);
		exit;
	}
}

//set error
$errStr .= "&srcPage=".urlencode($srcPage)."&destPage=".urlencode($destPage)."&email=".$email;
if (!isset($email) || $email == ''){
	$errStr .= "&emailErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_EMPTY_EMAIL_ERR']);
}
if (!isset($password) || $password == ''){
	$errStr .= "&passwordErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_EMPTY_PWD_ERR']);
}
$errStr .= "&ovl-ls-mode=".OVL_LS_MODE_LOGIN;

//Forward
header('Location: '.getURI().'/'.$srcPage.$puntoInterr."showOverlayLgSg=val".$errStr);
?>
