<?php
require_once $X_root.'pvt/pages/globalFunction.php';
require_once $X_root.'pvt/pages/extra/mobile/Mobile_Detect.php';

$detect = new Mobile_Detect;

if($detect->isMobile() || $detect->isTablet()) {
    header('Location: '.getURI().'/index.php');
    exit;
}

/**
The above code will now redirect the main site to a mobile version if viewed from a mobile. Some other use cases to redirect are given below.

// Any tablet device.
if( $detect->isTablet()) {
 
}
 
// Exclude tablets.
if( $detect->isMobile() && !$detect->isTablet()) {
 
}
 
// Check for a specific platform with the help of the magic methods:
if( $detect->isiOS()) {

}
 
if( $detect->isAndroidOS()) {
 
}
 
if( $detect->isWindowsPhoneOS()) {
 
}
*/
?>