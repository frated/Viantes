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