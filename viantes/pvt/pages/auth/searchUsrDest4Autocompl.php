<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/checkSession4Script.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/review/reviewDAO.php";

$usrName = $_GET['usrName'];

$userDAO = New UserDAO();
$resultArray = $userDAO->searchUserName4Autocompl($usrName);

header("Content-type: text/x-json");
echo json_encode($resultArray);
?>