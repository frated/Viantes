<?php
$X_root = "../../../";
require_once $X_root."pvt/pages/lang/countryDAO.php";

$countryStart = $_GET['country'];
$langCode     = $_GET['langCode'];

$countryDAO = New CountryDAO();
$resultArray = array(); //metodo cancellato $countryDAO->getCountryListByStartName($countryStart, $langCode);

header("Content-type: text/x-json");
echo json_encode($resultArray);
?>