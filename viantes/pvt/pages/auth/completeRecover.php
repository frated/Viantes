<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/log/log.php";

//conterra' la descrizione dell'errore localizzata
$errorField = "";
$srcPage = "/viantes/pub/pages/recoverPwd.php";

$fwdCode = $_POST['fwdCode'];
$email   = $_POST['email'];

$password = htmlspecialchars( isset($_POST['pwd']) ? $_POST['pwd'] : "");
$password2 = htmlspecialchars(isset($_POST['pwd2'])? $_POST['pwd2']: "");
		
//controllo i parametri inseriti
checkPassword($password, $password2, $X_langArray);
if ($errorField != "") {
	header('Location: '.$uri.'/viantes/pub/pages/recoverPwd.php?&fwdCode='.$fwdCode.'&email='.$email.'&pwd='.$password.'&pwd2='.$password2.$errorField);
	exit;
}

$userDAO  = NEW UserDAO();
$userDO = $userDAO->checkRecoverPwd($email, $fwdCode);
if (!is_object($userDO)) {
	header('Location: '.$uri.'/viantes/pub/pages/recoverPwd.php?&fwdCode='.$fwdCode.'&email='.$email);
	exit;
}

$userDAO->updatePassword($password, $userDO->getId());
$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['GEN_REQUEST_OK'];

header('Location: '.$uri.'/index.php');
exit;
?>

<?php
/* Controlla la validita' delle 2 password e setta l'errore in una variabile globale */
function checkPassword($pwd, $pwd2, $X_langArray) {
	global $errorField;

	//le due password devono coincidere
	if ($pwd != $pwd2){
		$errorField .= "&pwd2ErrMsg=".urlencode($X_langArray['SETTING_PWD_REPET_ERROR']);
	}
	else{
		//password vuota
		if (!isset($pwd2) || $pwd2 == ''){
			$errorField .= "&pwd2ErrMsg=".urlencode($X_langArray['RECOVER_PWD_EMPTY_PWD_ERR']);
		}
		else{
			//la password deve contenere almeno una maiuscola, una minuscola ed un nuemro tra 7 e 21 caratteri)
			if (!preg_match('/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])/', $pwd2) ) {
				$errorField .= "&pwd2ErrMsg=".urlencode($X_langArray['GEN_IS_NOT_PWD_REG']);
			}
			else{
				if (strlen($pwd2) < 6 ||  strlen($pwd2) > 20 ) {
					$errorField .= "&pwd2ErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_PWD_LENGTH_ERR']);
				}
			}
		}
	}
}
?>
