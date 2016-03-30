<?php
ini_set('display_errors',1);
$X_root = "../../../";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/review/reviewDAO.php";

$usrName = $_GET['usrName'];

$userDAO = New UserDAO();
$resultArray = $userDAO->searchUserName4Autocompl($usrName);

header("Content-type: text/x-json");
echo json_encode($resultArray);
?>