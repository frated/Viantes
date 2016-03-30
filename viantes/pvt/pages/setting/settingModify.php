<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/log/log.php";

//conterra' la descrizione dell'errore localizzata
$errorField = "";
$srcPage = "/viantes/pub/pages/profile/setting.php";

if ( isset($_POST['type']) && $_POST['type'] != "" ) {
	
	if ( $_POST['type'] == "inf" ) {
		$langCode = htmlspecialchars( $_POST['lang'] );
		$usrId = $_POST['usrId'];
		
		$settingDAO = New SettingDAO();
		$settingDAO->setInfo($langCode, $usrId);
		
		unset($_SESSION['langArray']);
	}	
	
	if ( $_POST['type'] == "pwd" ) {
		$password = htmlspecialchars( $_POST['password'] );
		$newPwd = htmlspecialchars( $_POST['newPwd'] );
		$repetPwd = htmlspecialchars( $_POST['repetPwd'] );
		
		//leggo utente
		$userDO = unserialize($_SESSION["USER_LOGGED"]);
		
		//controllo i parametri inseriti
		checkPassword($password, $newPwd, $repetPwd, $userDO->getEmail(), $X_langArray);
		if ($errorField != "") {
			header('Location: '.$uri.'/viantes/pub/pages/profile/setting.php?mod=m&t=pwd'.$errorField);
			exit;
		}
		else{
			//aggiorno password
			$userDAO = New UserDAO();
			$userDAO->updatePassword($newPwd, $userDO->getId());
		}
	}
	
	if ( $_POST['type'] == "priv" ) {
		$profileType = htmlspecialchars( $_POST['profileType'] );
		$usrId = $_POST['usrId'];
		
		$settingDAO = New SettingDAO();
		$settingDAO->setProfileType($profileType, $usrId);
	}
	
	$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['GEN_REQUEST_OK'];

	header('Location: '.$uri.'/viantes/pub/pages/profile/setting.php');
}


/* Controlla la validita' delle 3 password e setta l'errore in una variabile globale */
function checkPassword($pwd, $newPwd, $repetPwd , $email, $X_langArray) {
	global $errorField;

	$userDAO = New UserDAO();
	//check is auth
	if ( $userDAO->isAuth($email, sha1($pwd)) ){
		//le due password devono coincidere
		if ($newPwd != $repetPwd){
			$errorField .= "&pwd2ErrMsg=".urlencode($X_langArray['SETTING_PWD_REPET_ERROR']);
		}
		else{
			//password vuota
			if (!isset($newPwd) || $newPwd == ''){
				$errorField .= "&pwd2ErrMsg=".urlencode($X_langArray['SETTING_PWD_MISSING']);
			}
			else{
				//la password deve contenere almeno una maiuscola, una minuscola ed un nuemro tra 7 e 21 caratteri)
				if (!preg_match('/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])/', $newPwd) ) {
					$errorField .= "&pwd2ErrMsg=".urlencode($X_langArray['GEN_IS_NOT_PWD_REG']);
				}
				else{
					if (strlen($newPwd) < 6 ||  strlen($newPwd) > 20 ) {
						$errorField .= "&pwd2ErrMsg=".urlencode($X_langArray['OVERLAY_LOG_SIGN_PWD_LENGTH_ERR']);
					}
				}
			}
		}	
	}
	else{
		$errorField .= "&pwdErrMsg=".urlencode($X_langArray['SETTING_PWD_ERROR']);
	}
}
?>
