<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDAO.php";

//read params
$usrId = X_deco($_POST['usrId']);
$fn = $_POST['fn']; //file name
$directoryName = $_POST['directoryName']; //la dir giornaliera del file 
$scala = $_POST['scala'];
$x0 = $_POST['x0'];
$y0 = $_POST['y0'];
$x1 = $_POST['x1'];
$y1 = $_POST['y1'];
$coverType = $_POST['coverType'];

$fullFileName     = UPLOAD_PATH.SLASH.$directoryName.SLASH.$fn;
$croppedFileName  = UPLOAD_PATH.SLASH.$directoryName.SLASH.TMP_CROPPED_FILE_NAME;
$relativeFileName = REL_UP_PATH.$directoryName.SLASH.$fn;

// echo " usrId".$usrId." fn".$fn." directoryName".$directoryName." scala".$scala." x0".$x0." y0".$y0." x1".$x1." y1".$y1; 
//exit;

//*****************CROP IMAGE START **********************
$im = imagecreatefromjpeg($fullFileName);

//CASO 1 -- installando un componente di php
//$to_crop_array = array('x' => $x0*$scala , 'y' => $y0*$scala, 'width' => ($x1-$x0)*$scala, 'height'=> ($y1-$y0)*$scala );
//$thumb_im = imagecrop($im, $to_crop_array);

//CASO 2 - Senza installare nulla
list($widthIn, $heightIn) = getimagesize($fullFileName);
$thumb_im = imagecreatetruecolor($widthIn, $heightIn);
imagecopyresampled ($thumb_im , $im , 0 , 0,  $x0*$scala, $y0*$scala, $widthIn, $heightIn, ($x1-$x0)*$scala, ($y1-$y0)*$scala);

imagejpeg($thumb_im, UPLOAD_PATH.SLASH.$directoryName.SLASH."finalCroppedFile.jpeg", 100);
//*****************CROP IMAGE END **********************

unlink($fullFileName);
unlink($croppedFileName);
chmod(UPLOAD_PATH.SLASH.$directoryName.SLASH."finalCroppedFile.jpeg", 0777); 
rename(UPLOAD_PATH.SLASH.$directoryName.SLASH."finalCroppedFile.jpeg", UPLOAD_PATH.SLASH.$directoryName.SLASH.$fn);

//update database (N.B salvo il nome relativo)
$usrDAO = New UserDAO();
if ($coverType == '1') {
	$usrDAO->updateCover($relativeFileName, $usrId);
	removeMemcacheUsrReview($usrId);
} else if ($coverType == '2') {
	$usrDAO->updateBckCover($relativeFileName, $usrId);
}

//update session
$userDO = unserialize($_SESSION["USER_LOGGED"]);
if ($coverType == '1') {
	$userDO->setCoverFileName($relativeFileName);
} else if ($coverType == '2') {
	$userDO->setBckCoverFileName($relativeFileName);
}
$_SESSION["USER_LOGGED"] = serialize($userDO);

header('Location: '.getURI().'/viantes/pub/pages/profile/myProfile.php');
?>

<?php
/* Cancella da memcache le recensioni dell'utente altrimenti resterebbe sempre la stessa immagine di copertina */
function removeMemcacheUsrReview($usrId) {

	$reviewDAO = NEW ReviewDAO();
	$reviewDAO->removeMemcacheUsrReview($usrId);

	$cityReviewDAO = NEW CityReviewDAO();
	$cityReviewDAO->removeMemcacheUsrReview($usrId);

	$countryReviewDAO = NEW countryReviewDAO();
	$countryReviewDAO->removeMemcacheUsrReview($usrId);
}
?>
