<?php
$X_root = "../../../../";
session_start();
require_once $X_root."pvt/pages/review/reviewDAO.php";

$langCode = $_SESSION['langCode'];
$keyword = $_GET['k'];

$reviewDAO = New ReviewDAO();
$resultArray = $reviewDAO->searchReviews4AutocomplHeader($langCode, $keyword);

header("Content-type: text/x-json");
echo json_encode($resultArray);
?>
