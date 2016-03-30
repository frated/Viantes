<?php
session_start();
if (isset($_POST['name'])) {
	$ojb = $_SESSION[$_POST['name']];
	if ( is_array($ojb) && isset($_POST['index']) && $_POST['index'] > -1) {
		$indx = $_POST['index'];
		unset($ojb[$indx]);
		$ojb = array_values($ojb);
		$_SESSION[$_POST['name']] = $ojb;
	}
	else{
		unset($_SESSION[$_POST['name']]);
	}
}
?>