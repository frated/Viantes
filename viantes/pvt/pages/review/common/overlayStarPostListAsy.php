<?php
ini_set('display_errors', '1');
/**
 * Invcocato dalla funzione javascript renderSSP(revId, revTp)
 */
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/auth/starDO.php";
require_once $X_root."pvt/pages/review/commonReviewDAO.php";

if (!$X_logged) {
	echo "01";
	exit;
}


//istanzio la classe di ReviewDAO
$dao = New CommonReviewDAO();
$startDOList = $dao->buildRevStar(X_deco($_GET['revId']), $_GET['revTp']); 

header("Content-type: text/x-json");
echo json_encode($startDOList);
?>
