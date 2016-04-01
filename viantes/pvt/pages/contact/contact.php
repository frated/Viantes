<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/common/commonMail.php";
require_once $X_root."pvt/pages/lang/initLang.html";

//conterra' la descrizione dell'errore localizzata
$errorField = "";

/** ------ Gestione POST ------ **/
$name = addslashes( htmlspecialchars($_POST['name']) );	 
$email = addslashes( htmlspecialchars($_POST['email']) );
$descr = addslashes( htmlspecialchars($_POST['descr']) );

checkName($name, $X_langArray);
checkEmail($email, $X_langArray);
checkDescr($descr, $X_langArray);

// Captcha Control
if ( Conf::getInstance()->get('doCaptcha') == 1) {
	$errorField .= checkCaptcha(CONTACT, $X_langArray);
}

//old params
$oldParams = 'name='.urlencode($name).'&email='.urlencode($email).'&descr='.urlencode($descr);

//no errors
if ($errorField == "") {
	
	//invio mail a me stesso
	$se = sendMailForComment($name, $email, $descr);
	//se non invio la mail => la stampo
	if ( Conf::getInstance()->get('doMail') == 0) {
		echo $se; exit;
	}

	$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['CONTACT_MSG_SEND_OK'];
	unset($_SESSION[$_POST['beanSessionKey']]);
	
	header('Location: '.getURI().'/viantes/pub/pages/contacts.php');
	exit;
}

//forward
header('Location: '.getURI().'//viantes/pub/pages/contacts.php?'.$oldParams.$errorField);
exit;
?>

<?php
/* Controlla la validita' del campo name: setta l'errore in una variabile globale */
function checkName($name, $X_langArray) {
	global $errorField, $reviewDAO;

	//name vuoto
	if (!isset($name) || $name == ''){
		$errorField .= "&nameErrMsg=".urlencode($X_langArray['CONTACT_EMPTY_NAME_ERR']);
	} else if ( (strlen($name) < 3 ||  strlen($name) > 60) ) {
		$errorField .= "&nameErrMsg=".urlencode($X_langArray['CONTACT_NAME_LENGTH_ERR']);
	}
}

/* Controlla la validita' del campo email: setta l'errore in una variabile globale */
function checkEmail($email, $X_langArray) {
	global $errorField, $reviewDAO;
	
	//email vuoto
	if (!isset($email) || $email == ''){
		$errorField .= "&emailErrMsg=".urlencode($X_langArray['CONTACT_EMPTY_EMAIL_ERR']);
	}
	else if ( !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email) ) {
		$errorField .= "&emailErrMsg=".urlencode($X_langArray['GEN_IS_NOT_EMAIL_REG']);
	}
}


/* Controlla la validita' del campo descr: setta l'errore in una variabile globale */
function checkDescr($descr, $X_langArray) {
	global $errorField;
	
	//descr vuoto
	if (!isset($descr) || $descr == ''){
		$errorField .= "&descrErrMsg=".urlencode($X_langArray['CONTACT_EMPTY_DS_ERR']);
	}
	else if (strlen($descr) < 10 || strlen($descr) > 500) {
		$errorField .= "&descrErrMsg=".urlencode($X_langArray['CONTACT_DS_LENGTH_ERR']);
	}
}
?>