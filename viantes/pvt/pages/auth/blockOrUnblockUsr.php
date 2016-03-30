<?php
$X_root = "../../../";
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/auth/userDAO.php";

$userDAO = New UserDAO();

$usrId     = X_deco($_GET['usrId']);
$denUsrId  = X_deco($_GET['denUsrId']);
$denStatus = $_GET['denStatus']; // denStatus = 1 => block denUsrId, denStatus = 0 => unblock denUsrId

$userDAO->blockOrUnblockUsr($usrId, $denUsrId, $denStatus);
?>