<?php
$X_show_cookie = false;
if ( !isset($_COOKIE["INFO_COOKIE"] ) ){
	setcookie("INFO_COOKIE", "any value", time() + 4 * 365 * 24 * 60 * 60, "/"); // 4 anni!!
	$X_show_cookie = true;
}
?>