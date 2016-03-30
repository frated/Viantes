<?php
ini_set('display_errors', 1);
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";

$delayFromLastActivity = intval(Conf::getInstance()->get('delayFromLastActivity'));

$X_logged = isLogged($delayFromLastActivity);

$X_is_public = true; //questa variabile e' usata nell'header.html

function isLogged($delayFromLastActivity) {
	if ( isset($_COOKIE["LOGGED_IN"]) && isset($_SESSION["USER_LOGGED"]) ) {
		return true;
	}
	if ( isset($_COOKIE["LOGGED_IN"]) && !isset($_SESSION["USER_LOGGED"]) ) {
		return false;
	}
	// TODO capire se serve, io l'ho levata
	if ( isset($_SESSION["USER_LOGGED"]) && isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < $delayFromLastActivity ){
		return true;	
	}
	return false;
}
?>
