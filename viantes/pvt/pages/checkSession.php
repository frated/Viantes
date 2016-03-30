<?php
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";

$delayFromLastActivity = intval(Conf::getInstance()->get('delayFromLastActivity'));

$X_logged = isLogged($delayFromLastActivity);

if ( !$X_logged )  {
	$destPage = urldecode($_SESSION['DEST_PAGE']);

	//destroy logged user session
	destroyUserSession();
	
	// forward to welcome
	$uri = getURI();
	header('Location: '.$uri.'/index.php?showOverlayLgSg=val&srcPage=index.php&destPage='.$destPage);
}
?>

<?php
function isLogged($delayFromLastActivity) {
	if (isset($_SESSION["USER_LOGGED"]) && isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < $delayFromLastActivity ) {
		return true;
	}
	else if ( isset($_COOKIE["LOGGED_IN"]) && isset($_SESSION["USER_LOGGED"]) ) {
		$_SESSION['LAST_ACTIVITY'] = time();
		return true;
	}
	//echo "===============[" . $_SESSION["USER_LOGGED"] . "][" . $_COOKIE['LOGGED_IN']. "][" . $_SESSION['LAST_ACTIVITY'] ."][". time(). "]";
	return false;
}
?>