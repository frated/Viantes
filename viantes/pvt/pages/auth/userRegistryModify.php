<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/auth/userRegistryDAO.php";
require_once $X_root."pvt/pages/lang/languageDAO.php";
require_once $X_root."pvt/pages/lang/countryDAO.php";
require_once $X_root."pvt/pages/setting/settingDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";

//Recupero il langCode
$userDO = unserialize($_SESSION["USER_LOGGED"]);

$settingDAO = New SettingDAO();

$settingDO = $settingDAO->getSetting($userDO->getId());
$langCode = $settingDO->getLangCode();

//conterra' la descrizione dell'errore localizzata
$errorField = "";
$srcPage = "/viantes/pub/pages/auth/userRegistry.php";

$usrId       = $_POST['usrId']; //non puo' non essere settato
$firstName   = isset($_POST['firstName']) ? $_POST['firstName'] : "";
$lastName    = isset($_POST['lastName']) ? $_POST['lastName'] : "";
$mobileNum   = isset($_POST['mobileNum']) ? $_POST['mobileNum'] : "";
$gender      = isset($_POST['gender']) ? $_POST['gender'] : 0;
$dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : ""; 
$city        = isset($_POST['city']) ? $_POST['city'] : ""; 
$postcode    = isset($_POST['postcode']) ? $_POST['postcode'] : ""; 
$country     = isset($_POST['country']) ? $_POST['country'] : "";

checkParams($firstName, $lastName, $mobileNum, $dateOfBirth, $city, $postcode, $country, $X_langArray, $langCode);

//old param
$oldParam = "&firstName=".$firstName."&lastName=".$lastName."&mobileNum=".$mobileNum."&dateOfBirth=".$dateOfBirth."&city=".$city."&postcode=".$postcode."&country=".$country;

if ($errorField != "") {
	header('Location: '.$uri.'/viantes/pub/pages/profile/myProfile.php?mod=m'.$errorField.$oldParam);
	exit;
}
else{
	$userRegistryDAO = New UserRegistryDAO();
	
	$pattern = getDatePatternByLangCode($langCode);
	Logger::log("userRegistryModify :: pattern per data rilevato :: ".$pattern, 3);
	
	$userRegistryDAO->insertOrUpdate($usrId, str_replace("'", "''", $firstName), str_replace("'", "''", $lastName), $mobileNum, $gender, $dateOfBirth, $city, $postcode, $country, $pattern);
}

$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['GEN_REQUEST_OK'];
	
header('Location: '.$uri.'/viantes/pub/pages/profile/myProfile.php');
exit;
?>

<?php
/* Controlla la validita' delle 3 password e setta l'errore in una variabile globale */
function checkParams($firstName, $lastName, $mobileNum, $dateOfBirth, $city, $postcode, $country, $X_langArray, $langCode) {

	global $errorField;
	
	//First Name
	if ( strlen($firstName) > 60 ) {
		$errorField .= "&firstNameErrMsg=".urlencode($X_langArray['MY_PROFILE_FIRST_NAME_LENGTH_ERR']);
	} else{
		if ( strlen($firstName) > 0 && !preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $firstName) ) {
			$errorField .= "&firstNameErrMsg=".urlencode($X_langArray['MY_PROFILE_FIRST_NAME_PATTERN_ERR']);
		}
	}
	
	//Last Name
	if ( strlen($lastName) > 60 ) {
		$errorField .= "&lastNameErrMsg=".urlencode($X_langArray['MY_PROFILE_LAST_NAME_LENGTH_ERR']);
	} else{
		if ( strlen($lastName) > 0 && !preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $lastName) ) {
			$errorField .= "&lastNameErrMsg=".urlencode($X_langArray['MY_PROFILE_LAST_NAME_PATTERN_ERR']);
		}
	}
	
	//Mobile Num
	if ( strlen($mobileNum) > 0 && !preg_match("/^[0-9]+$/", $mobileNum) ) {
		$errorField .= "&mobileNumErrMsg=".urlencode($X_langArray['MY_PROFILE_MOB_NUM_PATTERN_ERR']);
	}
	else if ( strlen($mobileNum) > 15 ) {
		$errorField .= "&mobileNumErrMsg=".urlencode($X_langArray['MY_PROFILE_MOB_NUM_LENGTH_ERR']);
	}
	else if ( strlen($mobileNum) > 0 && strlen($mobileNum) < 7 ) {
		$errorField .= "&mobileNumErrMsg=".urlencode($X_langArray['MY_PROFILE_MOB_NUM_LENGTH_ERR']);
	}
		
	//Date Of Birth
	$regex = getDateRegexByLangCode($langCode);
	if (  !preg_match($regex, $dateOfBirth) ) {
		$errorField .= "&dateOfBirthErrMsg=".urlencode($X_langArray['MY_PROFILE_DATE_OF_BIRTH_ERR']);
	}
	if ( strlen($dateOfBirth) != 0 && preg_match($regex, $dateOfBirth)  ) {
		$year = getYearDateByLangCode($langCode,$dateOfBirth);
		$month = getMonthDateByLangCode($langCode,$dateOfBirth);
		$day = getDayDateByLangCode($langCode,$dateOfBirth);
		if (!checkdate ( $month , $day , $year ) ) {
			$errorField .= "&dateOfBirthErrMsg=".urlencode($X_langArray['MY_PROFILE_DATE_OF_BIRTH_ERR']);
		}
	}
	
	//City
	if ( strlen($city) > 60 ) {
		$errorField .= "&cityErrMsg=".urlencode($X_langArray['MY_PROFILE_CITY_LENGTH_ERR']);
	} 
	else if ( strlen($city) > 0 && strlen($city) < 3 ) {
		$errorField .= "&cityErrMsg=".urlencode($X_langArray['MY_PROFILE_CITY_LENGTH_ERR']);
	}
	else{
		if ( strlen($city) > 0 && !preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]+$/u", $city) ) {
			$errorField .= "&cityErrMsg=".urlencode($X_langArray['MY_PROFILE_CITY_PATTERN_ERR']);
		}
	}
	
	//Postcode
	//if ( strlen($postcode) > 0 && !preg_match("/^[0-9a-zA-Z]{5,10}$/", $postcode) ) {
	if ( strlen($postcode) > 10 ) {
		$errorField .= "&postcodeErrMsg=".urlencode($X_langArray['MY_PROFILE_POSTCODE_LEN_ERR']);
	}
	else if ( strlen($postcode) > 0 && strlen($postcode) < 3 ) {
		$errorField .= "&postcodeErrMsg=".urlencode($X_langArray['MY_PROFILE_POSTCODE_LEN_ERR']);
	}

	//Country
	if ( strlen($country) > 60 ) {
		$errorField .= "&countryErrMsg=".urlencode($X_langArray['MY_PROFILE_COUNTRY_LENGTH_ERR']);
	} 
	else if ( strlen($country) > 0 && strlen($country) < 3 ) {
		$errorField .= "&countryErrMsg=".urlencode($X_langArray['MY_PROFILE_COUNTRY_LENGTH_ERR']);
	}/* else{
		if ( strlen($country) > 0 ) {
			$countryDAO = New CountryDAO();
			$countryArray = $countryDAO->getCountriesUpper($langCode);
			if (!in_array( strtoupper($country), $countryArray)) {
				$errorField .= "&countryErrMsg=".urlencode($X_langArray['CREATE_REVIEW_COUNTRY_ERR'])."&country=".$country;
			}
		}
	} */	
}
?>

