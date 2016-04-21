<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";

//URL di ritorno
$backUrl = isset($_POST['backUrl']) ? $_POST['backUrl']."?1=1" : "";

//nome del file temporaneo (prima di essere stato uploadato)
$tmpFileName = $_FILES['userfile']['tmp_name'];

//the uri to came back
$uri = getURI();

//Not is an Image
if ( !exif_imagetype( $tmpFileName ) ) {
	$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_IMG_ERR_TYPE']);
	header('Location: '.$uri.$backUrl);
	exit;
}

//Dimensioni immagine
list($widthIn, $heightIn) = getimagesize($tmpFileName);
if ( $widthIn < Conf::getInstance()->get('imgMinDimension') || $heightIn < Conf::getInstance()->get('imgMinDimension') ) {
	$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_IMG_TOO_SMALL']);
	header('Location: '.$uri.$backUrl);
	exit;
}
if ( $widthIn > Conf::getInstance()->get('imgMaxDimension') || $heightIn > Conf::getInstance()->get('imgMaxDimension') ) {
	$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_IMG_TOO_BIG']);
	header('Location: '.$uri.$backUrl);
	exit;
}


//Nome del file destinazione (dopo essere stato uploadato)
$fileName = $_FILES['userfile']['name'];

//Nome file troppo lungo
if ( strlen($fileName) > 60 ) {
	$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_NAME_TOO_LONG']);
	header('Location: '.$uri.$backUrl);
	exit;
}

//Non e' una jpeg 
if(exif_imagetype($tmpFileName) != IMAGETYPE_JPEG){
	$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_ONLY_JPEG']);
	header('Location: '.$uri.$backUrl);
	exit;
}

//Folder a partire dalla data
$dataFolder = date('Y-m-d');

//Directory assoluta di destinazione
$filePath = UPLOAD_PATH.$dataFolder.PATH_SEPARAT;

if (!file_exists($filePath)) {
    mkdir($filePath, 0777, true);
}

//Directory relativa di destinazione
$relativeFilePath = REL_UP_PATH.$dataFolder.SLASH;

$fullFileName = $filePath.basename($fileName);

//There is no error, the file uploaded with success.
if (move_uploaded_file($tmpFileName, $fullFileName)) {

	//pulisco il nome del file
	$fileName = str_replace("#", "", str_replace(" ", "", $fileName));
    rename($fullFileName, $filePath.basename($fileName));
	
	$fullFileName = $filePath.basename($fileName);
	chmod($fullFileName, 0777); 
	
	//get userId
	$userDO = unserialize($_SESSION["USER_LOGGED"]);
	
	$coverType = $_POST['coverType'];
	if ($coverType == 1) 
		header('Location: '.$uri.'/viantes/pub/pages/profile/imgCropper.php?fn='.$fileName.'&dir='.$dataFolder.'&uid='. X_code($userDO->getId()) );
	else
		header('Location: '.$uri.'/viantes/pub/pages/profile/imgCropper2.php?fn='.$fileName.'&dir='.$dataFolder.'&uid='. X_code($userDO->getId()) );
}
//There are errors, the file not uploaded.
else {
	//The uploaded file exceeds the upload_max_filesize directive in php.ini.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_INI_SIZE) {
		$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_INI_SIZE']);
		header('Location: '.$uri.$backUrl);
	}
	//The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_FORM_SIZE) {
		$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_INI_SIZE']);
		header('Location: '.$uri.$backUrl);
	}
	//The uploaded file was only partially uploaded.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_PARTIAL){
		$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_PARTIAL']);
		header('Location: '.$uri.$backUrl);
	}
	//No file was uploaded.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_NO_FILE){
		$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_NO_FILE']);
		header('Location: '.$uri.$backUrl);
	}
	//Missing a temporary folder. Introduced in PHP 5.0.3.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_NO_TMP_DIR){
		$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_NO_TMP_DIR']);
		header('Location: '.$uri.$backUrl);
	}
	//Failed to write file to disk. Introduced in PHP 5.1.0.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_CANT_WRITE){
		$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_CANT_WRITE']);
		header('Location: '.$uri.$backUrl);
	}
	//A PHP extension stopped the file upload. 		
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_EXTENSION){
		$backUrl .= "&loadCovImgErrMsg=".urlencode($X_langArray['UPLOAD_ERR_PART_UPLOADED']);
		header('Location: '.$uri.$backUrl);
	}
}
exit;
?>
