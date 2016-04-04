<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/auth/clientRequestDAO.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/common/commonMail.php";

//istanzio i DAO
$usr = New UserDAO();
$settingDAO = New SettingDAO();

//conterra' la descrizione dell'errore localizzata
$errorField = "";

/** ------ Gestione Asincrona ------ **/
doAsyncGet($X_langArray);

// Captcha Control
if ( Conf::getInstance()->get('doCaptcha') == 1) {
	$errorField .= checkCaptcha(SIGNIN, $X_langArray);
}

/** ------ Gestione POST ------ **/
$srcPage = $_POST['srcPage'];
$destPage = $_POST['destPage'];

$email = htmlspecialchars( $_POST['newemail'] );
$pwd = htmlspecialchars( $_POST['newpassword'] );
$name = htmlspecialchars( $_POST['name'] );
$terms = $_POST['terms'];

//Controllo aggiuntivo nel caso in cui il parametro 'srcPage' ha dei parametri => contiene un '?'
$puntoInterr = (strpos( $srcPage, '?' ) == false ) ? "?" : "&";

checkEmail($email, $X_langArray);
checkPassword($pwd, $X_langArray);
checkName($name, $X_langArray);
checkTerms($terms, $X_langArray);

//se non ho errori oppure o ho indovinato la captcha => vai avanti
if ($errorField == "") {
	
	//log request 
	$clientReqDAO = NEW ClientRequestDAO();
	$clientReqDAO->logRequest(SIGNIN);

	//l'eistenza della mil la verifico dopo aver fatto una insert nella request
	//in questo modo posso evitare che un macchina verifichi le mail presenti nella base dati
	checkEmailAlreadyExists($email, $X_langArray);
	if ($errorField == "") {
		//inserisco l'utente
		$result = $usr->createUser($email, sha1($pwd), $name);
		
		//parso il risultato (pezza necessaria conj l'introduz del setting)
		$resultArray = explode("##", $result);
		$usrId = $resultArray[0];
		$fwdCode = $resultArray[1];
		
		//salvo le impostazioni di default
		$langCode = $_SESSION['langCode'];
		$settingDAO->saveDefaultSetting($usrId, $langCode);
		
		//invio mail
		$se = sendMail($email, $fwdCode, $name, $X_langArray);
		//se non invio la mail => la stampo
		if ( Conf::getInstance()->get('doMail') == 0) {
			echo $se; exit;
		}
		
		$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['OVERLAY_LOG_SIGN_SEND_MAIL_OK'];
		
		header('Location: '.getURI().$destPage);
		exit;
	}
}

//old params
$oldParams = '&srcPage='.urlencode($srcPage).'&destPage='.urlencode($destPage).'&newemail='.urlencode($email).
			 '&newpassword='.urlencode($pwd).'&name='.urlencode($name).'&terms='.urlencode($terms);
//forward
header('Location: '.getURI().'/'.$srcPage.$puntoInterr."showOverlayLgSg=val".$oldParams.$errorField);
exit;
?>

<?php
/* Gestisce una chiamata Get asincrona */
function doAsyncGet($X_langArray){
	global $errorField;
	
	if ( isset($_GET['newemail']) ) {
		$emailGet = htmlspecialchars( $_GET['newemail'] );
		//TODO $emailGetNo = $_GET['newemail'];
		checkEmail($emailGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
	if ( isset($_GET['newpassword']) ) {
		$passwordGet = htmlspecialchars( $_GET['newpassword'] );
		checkPassword($passwordGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
	if ( isset($_GET['name']) ) {
		$nameGet = htmlspecialchars( $_GET['name'] );
		checkName($nameGet, $X_langArray);
		if ($errorField != "") {
			$errorDesc = explode("=", $errorField);
			echo "KO=".$errorDesc[1];
			exit;
		}
		echo "OK";
		exit;
	}
}

/* Controlla la validita' del campo email: setta l'errore in una variabile globale 
 * N.B Non verifica se la mail esiste nella base dati (per motivi di informaizoni verso l'esterno)
 */
function checkEmail($email, $X_langArray) {
	global $errorField, $usr;
	
	//email vuota
	if (!isset($email) || $email == ''){
		$errorField .= "&newEmailErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_EMPTY_EMAIL_ERR']);
	}
	//se l'email non ha un formato corretto 
	else if ( !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email) ) {
		$errorField .= "&newEmailErrMsg=".urlencode($X_langArray['GEN_IS_NOT_EMAIL_REG']);
	}
}

/* Controlla se la mail esiste 
 * N.B Non richiamare questo metodo nel doAsyncGet.
 */
function checkEmailAlreadyExists($email, $X_langArray) {
	global $errorField, $usr;
	
	//se l'email e' gia' presente
	$exists = $usr->checkEmailAlreadyExists($email);
	if ($exists) {
		$errorField .= "&newEmailErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_EMAIL_EXISTS_ERR']);
	}
}

/* Controlla la validita' del campo password: setta l'errore in una variabile globale */
function checkPassword($pwd, $X_langArray) {
	global $errorField;

	//password vuota
	if (!isset($pwd) || $pwd == ''){
		$errorField .= "&newPasswordErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_EMPTY_PWD_ERR']);
	}
	else{
		//la password deve contenere almeno una maiuscola, una minuscola ed un nuemro tra 7 e 21 caratteri)
		if (!preg_match('/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])/', $pwd) ) {
			$errorField .= "&newPasswordErrMsg=".urlencode($X_langArray['GEN_IS_NOT_PWD_REG']);
		}
		else{
			if (strlen($pwd) < 6 ||  strlen($pwd) > 20 ) {
				$errorField .= "&newPasswordErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_PWD_LENGTH_ERR']);
			}
		}
	}
}

/* Controlla la validita' del campo nome: setta l'errore in una variabile globale */
function checkName($name, $X_langArray) {
	global $errorField, $usr;
	
	//name vuoto
	if (!isset($name) || $name == ''){
		$errorField .= "&nameErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_EMPTY_NCK_ERR']);
	}
	else{
		//il name deve contenere solo numeri e caratteri
		if (!ctype_alnum($name)) {
			$errorField .= "&nameErrMsg=".urlencode($X_langArray['GEN_IS_NOT_NAME_REG']);
		}
		else{
			if (strlen($name) < 4 ||  strlen($name) > 20 ) {
				$errorField .= "&nameErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_NAME_LENGTH_ERR']);
			}
		}
		//se il name e' gia' presente
		$exists = $usr->checkNameAlreadyExists($name);
		if ($exists) {
			$errorField .= "&nameErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_NAME_EXISTS_ERR']);
		}
	}
}

/* Controlla che sia stato selezionato il flag delle condizioni */
function checkTerms($terms, $X_langArray) {
	global $errorField, $usr;
	
	//name vuoto
	if (!isset($terms) || $terms != 'on'){
		$errorField .= "&termsErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_TERMS_ERR']);
	}
}
?>
