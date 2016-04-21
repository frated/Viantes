<?php
require_once $X_root."pvt/pages/review/cityReviewBean.php";
require_once $X_root."pvt/pages/review/countryReviewBean.php";
require_once $X_root."pvt/pages/review/reviewBean.php";
require_once $X_root."pvt/pages/upload/createImgRescaledUtil.php";

//URL di ritorno
$backUrl = isset($_POST['backUrl']) ? $_POST['backUrl']."?1=1" : "";
//Parametro della URL che memorizza il tab attivo
$backUrl .= isset($_POST['tabactive']) ? "&tabactive=".$_POST['tabactive'] : "";
//Campi e Messaggi di errore di ritorno
require_once $X_root."pvt/pages/upload/setCommonFieldsAndErrMsg.php";

//Id della recensione (presente nella showReview.php)
if ( isset($_POST['revId']) ) $backUrl .= "&revId=".urlencode($_POST['revId']);

//nome del file temporaneo (prima di essere stato uploadato)
$tmpFileName = $_FILES['userfile']['tmp_name'];

//the uri to came back
$uri = getURI();

//Not is an Image
if ( $_POST['type'] == IMG && !exif_imagetype( $tmpFileName ) ) {
	$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_IMG_ERR_TYPE']);
	header('Location: '.$uri.$backUrl);
	exit;
}
//Not is a PDF
if ( $_POST['type'] == DOC && !isPdf( $tmpFileName ) ) {
	$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_DOC_ERR_TYPE']);
	header('Location: '.$uri.$backUrl);
	//unlink 
	exit;
}

//Nome del file destinazione (dopo essere stato uploadato)
$fileName = $_FILES['userfile']['name'];

//Nome file troppo lungo
if ( strlen($fileName) > 60 ) {
	$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_NAME_TOO_LONG']);
	header('Location: '.$uri.$backUrl);
	exit;
}

//Dimensioni immagine (se e' un'immagine)
if ( $_POST['type'] == IMG && exif_imagetype( $tmpFileName ) ) {
	list($widthIn, $heightIn) = getimagesize($tmpFileName);
	if ( $widthIn < Conf::getInstance()->get('imgMinDimension') || $heightIn < Conf::getInstance()->get('imgMinDimension') ) {
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_IMG_TOO_SMALL']);
		header('Location: '.$uri.$backUrl);
		exit;
	}
	if ( $widthIn > Conf::getInstance()->get('imgMaxDimension') || $heightIn > Conf::getInstance()->get('imgMaxDimension') ) {
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_IMG_TOO_BIG']);
		header('Location: '.$uri.$backUrl);
		exit;
	}
}

if ( $_POST['type'] == MOV && !isVideo() ){
	$backUrl .=  getErrParamname().urlencode($X_langArray['UPLOAD_MOV_ERR_TYPE']);
	header('Location: '.$uri.$backUrl);
	exit;
}

//Max uploadable objects
$imgMaxFileCreReview = intval(Conf::getInstance()->get('imgMaxFileCreReview'));
$movMaxFileCreReview = intval(Conf::getInstance()->get('movMaxFileCreReview'));
$docMaxFileCreReview = intval(Conf::getInstance()->get('docMaxFileCreReview'));

$beanSessionKey = $_POST['beanSessionKey'];
$bean = isset($beanSessionKey) && isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;

if ( $bean != null && $_POST['type'] == IMG && $imgMaxFileCreReview <= ( count ($bean->getImgFileNameArray() ) ) ) {
	$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_IMG_MAX_NUM_EXCEEDED']);
	header('Location: '.$uri.$backUrl);
	exit;	
}
if ( $bean != null && $_POST['type'] == MOV && $movMaxFileCreReview <= ( count ($bean->getMovFileNameArray() ) ) ) {
	$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_MOV_MAX_NUM_EXCEEDED']);
	header('Location: '.$uri.$backUrl);
	exit;
}
if ( $bean != null && $_POST['type'] == DOC &&  $docMaxFileCreReview <= ( count ($bean->getDocFileNameArray() ) ) ) {
	$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_DOC_MAX_NUM_EXCEEDED']);
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

	//Create Bean if is null
	if ($bean == null) {
		if ($beanSessionKey == "REVIEWN_BEAN") {
			$bean = New ReviewBean();
		}
		else if ($beanSessionKey == "CITY_REVIEWN_BEAN") {
			$bean = New CityReviewBean();
		}
		else if ($beanSessionKey == "COUNTRY_REVIEWN_BEAN") { 
			$bean = New CountryReviewBean();
		}
	}
	
	//IMG
	if ($_POST['type'] == IMG) {
		$fileNameArray = $bean->getImgFileNameArray();
		array_push($fileNameArray, $fileName);
		$bean->setImgFileNameArray($fileNameArray);
		
		$imgRelativeFilePathArray = $bean->getImgRelativeFilePathArray();
		array_push($imgRelativeFilePathArray, $relativeFilePath);
		$bean->setImgRelativeFilePathArray($imgRelativeFilePathArray);
		
		list($_width, $_height, $type, $attr) = getimagesize($fullFileName);

		//------- Create new and rescale ---------
		createAndRescaleForReview($fullFileName);
		//----------------------------------------
		
		$widthArray = $bean->getImgWidthArray();
		array_push($widthArray, $_width);
		$bean->setImgWidthArray($widthArray);
		
		$heightArray = $bean->getImgHeightArray();
		array_push($heightArray, $_height);
		$bean->setImgHeightArray($heightArray);

		//push fake comment
		$fakeCmnt = $bean->getImgCommentArray();
		array_push($fakeCmnt, "");
		$bean->setImgCommentArray($fakeCmnt);
	}
	//MOV
	if ($_POST['type'] == MOV) {
		$_SESSION['MOV_PATH'] = $relativeFilePath;

		$userDO = unserialize( $_SESSION["USER_LOGGED"] ); 
		$outFileName = uniqid($userDO->getId(), true).'.swf';
		$fullOutFileName = $filePath.basename($outFileName);
		
		//converto 
		$videoInfo = convertVideo($fullFileName, $fullOutFileName);
		
		//cancello il file origine
		unlink($fullFileName);
		
		//Se NON riesco a convertire il file o riesco perche' e' un'immagine
		if (exif_imagetype( $fullFileName ) || !$videoInfo) {
			$backUrl .=  getErrParamname().urlencode($X_langArray['UPLOAD_MOV_ERR_FORMAT']);
			header('Location: '.$uri.$backUrl);
			exit;
		}
		
		$fileNameArray = $bean->getMovFileNameArray();
		array_push($fileNameArray, $outFileName);
		$bean->setMovFileNameArray($fileNameArray);
			
		$movRelativeFilePathArray = $bean->getMovRelativeFilePathArray();
		array_push($movRelativeFilePathArray, $relativeFilePath);
		$bean->setMovRelativeFilePathArray($movRelativeFilePathArray);
		
		$widthArray = $bean->getMovWidthArray();
		array_push($widthArray, $videoInfo['width']);
		$bean->setMovWidthArray($widthArray);
		
		$heightArray = $bean->getMovHeightArray();
		array_push($heightArray, $videoInfo['height']);
		$bean->setMovHeightArray($heightArray);
		
		//push fake comment
		$fakeCmnt = $bean->getMovCommentArray();
		array_push($fakeCmnt, "");
		$bean->setMovCommentArray($fakeCmnt);
	}
	//DOC
	if ($_POST['type'] == DOC) {
		$fileNameArray = $bean->getDocFileNameArray();
		array_push($fileNameArray, $fileName);
		$bean->setDocFileNameArray($fileNameArray);
		
		$docRelativeFilePathArray = $bean->getDocRelativeFilePathArray();
		array_push($docRelativeFilePathArray, $relativeFilePath);
		$bean->setDocRelativeFilePathArray($docRelativeFilePathArray);
		
		//push fake comment
		$fakeCmnt = $bean->getDocCommentArray();
		array_push($fakeCmnt, "");
		$bean->setDocCommentArray($fakeCmnt);
	}
	$_SESSION[$beanSessionKey] = serialize($bean);
	
	header('Location: '.$uri.$backUrl);
}
//There are errors, the file not uploaded.
else {
	//The uploaded file exceeds the upload_max_filesize directive in php.ini.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_INI_SIZE) {
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_INI_SIZE']);
		header('Location: '.$uri.$backUrl);
	}
	//The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_FORM_SIZE) {
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_INI_SIZE']);
		header('Location: '.$uri.$backUrl);
	}
	//The uploaded file was only partially uploaded.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_PARTIAL){
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_PARTIAL']);
		header('Location: '.$uri.$backUrl);
	}
	//No file was uploaded.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_NO_FILE){
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_NO_FILE']);
		header('Location: '.$uri.$backUrl);
	}
	//Missing a temporary folder. Introduced in PHP 5.0.3.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_NO_TMP_DIR){
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_NO_TMP_DIR']);
		header('Location: '.$uri.$backUrl);
	}
	//Failed to write file to disk. Introduced in PHP 5.1.0.
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_CANT_WRITE){
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_CANT_WRITE']);
		header('Location: '.$uri.$backUrl);
	}
	//A PHP extension stopped the file upload. 		
	if ($_FILES['userfile']['error'] === UPLOAD_ERR_EXTENSION){
		$backUrl .= getErrParamname().urlencode($X_langArray['UPLOAD_ERR_PART_UPLOADED']);
		header('Location: '.$uri.$backUrl);
	}
}
exit;
?>

<?php
function convertVideo($inputFileName, $outFileName){
	try {
		$cmd = FFMPEG_CMD .$inputFileName.' '.$outFileName.' 2>&1';
		$result = shell_exec($cmd);
		//echo $cmd."<br><br>"; echo $result; exit;
		
		if ( preg_match("/Error number (.+) occurred/", $result) ) return false;
		
		//Dimension Info
		$regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/";
		if (preg_match($regex_sizes, $result, $regs)) {
			$codec = $regs [1] ? $regs [1] : null;
			$width = $regs [3] ? $regs [3] : null;
			$height = $regs [4] ? $regs [4] : null;
		}
		//Duration Info
		$regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
		if (preg_match($regex_duration, $result, $regs)) {
			$hours = $regs [1] ? $regs [1] : null;
			$mins = $regs [2] ? $regs [2] : null;
			$secs = $regs [3] ? $regs [3] : null;
			$ms = $regs [4] ? $regs [4] : null;
		}
	
		if (strpos($result, 'Invalid data found when processing input')) {
			Logger::log("uploadFile.php :: convertVideo :: errore nel tentativo di caricare un file".$inputFileName, 2);
			Logger::log("uploadFile.php :: convertVideo :: risultato: ".$result);
			return false;
		}
		
		return array ('codec' => $codec,
            'width' => $width,
            'height' => $height,
            'hours' => $hours,
            'mins' => $mins,
            'secs' => $secs,
            'ms' => $ms);
	}
	catch (Exception $e) {
		Logger::log("uploadFile.php :: convertVideo :: errore nel tentativo di caricare un file".$inputFileName, 2);
		Logger::log("uploadFile.php :: convertVideo :: eccezioen sollebata: ".$e->getMessage());
		return false;
	}
}

function isVideo(){
	//echo $_FILES['userfile']['type'];
	//echo preg_match('/^video/s', $_FILES['userfile']['type']);
	return preg_match('/^video/s', $_FILES['userfile']['type']);
}

function isPdf($tmpFileName ){
	$file = fopen($tmpFileName,'rb');
	if ($file) {
		$bytes6 = fread($file,6);
		fclose($file); 
		return strpos($bytes6, 'PDF');
	}
	else{
		echo "non posos aprire file";
		fclose($file); 
	}
	exit;
}

function getErrParamname(){
	if ($_POST['type'] == IMG) return "&loadImgErrMsg=";
	if ($_POST['type'] == MOV) return "&loadMovErrMsg=";
	if ($_POST['type'] == DOC) return "&loadDocErrMsg=";
}
?>
