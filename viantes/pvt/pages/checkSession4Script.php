<?php
ini_set('display_errors', 1);
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";

$delayFromLastActivity = intval(Conf::getInstance()->get('delayFromLastActivity'));

$X_logged = isLogged($delayFromLastActivity);

if (!$X_logged) {
	echo "99";
	exit;
}
?>