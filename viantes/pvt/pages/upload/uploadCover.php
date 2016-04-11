<?php
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/review/cityReviewBean.php";
require_once $X_root."pvt/pages/review/countryReviewBean.php";
require_once $X_root."pvt/pages/review/reviewBean.php";

//URL di ritorno
$backUrl = isset($_POST['backUrl']) ? $_POST['backUrl']."?1=1" : "";
$backUrl .= "&tabactive=1";
//Campi e Messaggi di errore di ritorno
require_once $X_root."pvt/pages/upload/setCommonFieldsAndErrMsg.php";

//nome del file temporaneo (prima di essere stato uploadato)
$tmpFileName = $_FILES['userfile']['tmp_name'];

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
	$beanSessionKey = $_POST['beanSessionKey'];
	$coverType = $_POST['coverType'];
	if ($coverType == "CRT_REV") { 
		$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : New ReviewBean();
	}
	else if ($coverType == "CRT_CTY_REV") {
		$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : New CityReviewBean();
	}
	else if ($coverType == "CRT_CNT_REV") {
		$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : New CountryReviewBean();
	}
	$bean->setCoverFileName($relativeFilePath.$fileName);
	
	list($_width, $_height, $type, $attr) = getimagesize($fullFileName);
	$bean->setCoverWidth($_width);
	$bean->setCoverHeight($_height);
	
	//----------------------------------------------------------------------------------------------------
	//RESCALE 4 INDEX PAGE
	
	//dimensioni della nuova immagine (altezza 128px)
	$rescaledHeight = 128;
	$rescaledWidth  =  ratioImagDimensionFixHeight($_width, $_height, $rescaledHeight);
	
	//immagine originale
    $resurceImgOrig = imagecreatefromjpeg($fullFileName);
	
	//dimensioni originali
	list($widthIn, $heightIn) = getimagesize($fullFileName);
	
	//creao la nuova immagine 
	$scaledImg = imagecreatetruecolor($rescaledWidth, $rescaledHeight);
	//copio, in proporzione, l'orignale su quella scalata
	imagecopyresampled ($scaledImg , $resurceImgOrig , 0 , 0,  0, 0, $rescaledWidth, $rescaledHeight, $_width, $_height);
	
	//salvo con estensione RSZD_FOR_IND
	imagejpeg($scaledImg, $fullFileName.RSZD_FOR_IND, 100);
	//----------------------------------------------------------------------------------------------------
	
	$_SESSION[$beanSessionKey] = serialize($bean);
	
	header('Location: '.$uri.$backUrl);
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
?>