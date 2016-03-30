<?php
$X_root = "../../";
session_start();
require_once $X_root."pvt/pages/globalFunction.php";

destroyUserSession();

if (isset($_GET['destPage'])){
	//trucchetto di sostituire la & con ^, faccio l'inverso 
	$destPage = str_replace('^', '&', $_GET['destPage']);
	header('Location: '.getURI().$destPage);
}
else {
	header('Location: '.getURI().'/viantes/pub/pages/error.php');
}
?>