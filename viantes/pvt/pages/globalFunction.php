<?php

$userAgent = $_SERVER['HTTP_USER_AGENT'];

/* 
 * Codifica gli id
 */
function X_code($id){
	return (($id*17)+11)*13;
}

/* 
 * Decodifica gli id
 */
function X_deco($id){
	return (($id/13)-11)/17;
}

/* 
 * Codifica le mail
 * Nota i caratteri "utili" vanno dal 33 al 126 
 *
function X_code_mail($str){
	$code = "";
	for( $i = 0; $i < strlen( $str ); $i++ ) {
		$char = substr( $str, $i, 1 );
		$ascii = ord($char);
		//echo $i." orig: ".$ascii."<br>";
		
		$inc = $ascii + $i*3 + 7;

		//se arrivo alla fine (128, 129.. ) voglio ricominciare ma non da zero ma da 33
		if ($inc > 126) {
			//echo $i." shft: ".$inc."<br>";
			$inc = $inc - 127 + 33; 
		}

		//il 34 <=> " , il 39 <=> ' ed il 96 <=> ' 
		if ($inc == 34 || $inc == 39  || $inc == 96) $inc++;

		//echo $i." code: ".$inc."<br>";	
		$code .= chr($inc);
	}
	return $code;
}*/

/* 
 * Decodifica le mail
 *
function X_deco_mail($code){
	$decode = "";
	for( $i = 0; $i < strlen( $code ); $i++ ) {
		$char = substr( $code, $i, 1 );
		$ascii = ord($char);
		//echo $i." deco: ".$ascii."<br>";	
		$inc = $ascii - $i*3 - 7;
		
		if ($inc < 33) {
			//echo $i." shft: ".$inc."<br>";
			$inc = 127 - 33 + $inc;
		}

		//il 34 <=> " , il 39 <=> ' ed il 96 <=> ' 
		if ($inc == 34 || $inc == 39  || $inc == 96) $inc++;

		//echo $i." deco: ".$inc."<br>";	
		$decode .= chr($inc);
		//echo $decode;
	}
	return $decode;
}*/

/* 
 * Ritorna l'uri del server 
 */
function getURI(){
	return ((!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];
}

/*
 * Verifica se la sessione dell'utente e' ancora valida
 */
function isLogged($delayFromLastActivity) {
	if (isset($_SESSION["USER_LOGGED"]) && isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < $delayFromLastActivity ) {
		return true;
	}
	else if ( isset($_COOKIE["LOGGED_IN"]) && isset($_SESSION["USER_LOGGED"]) ) {
		$_SESSION['LAST_ACTIVITY'] = time();
		return true;
	}
	//echo "===============[" . $_SESSION["USER_LOGGED"] . "][" . $_COOKIE['LOGGED_IN']. "][" . $_SESSION['LAST_ACTIVITY'] ."][". time(). "]";
	return false;
}

/* 
 * Ritorna il Sistema Operativo in uso
 */
function getOS() { 
    global $userAgent;
    $osPlatform = "Unknown OS Platform";
    $os_array = array('/windows nt 6.4/i'     =>  'Windows 10.0',
					  '/windows nt 6.3/i'     =>  'Windows 8.1',
                      '/windows nt 6.2/i'     =>  'Windows 8',
                      '/windows nt 6.1/i'     =>  'Windows 7',
                      '/windows nt 6.0/i'     =>  'Windows Vista',
                      '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                      '/windows nt 5.1/i'     =>  'Windows XP',
                      '/windows xp/i'         =>  'Windows XP',
                      '/windows nt 5.0/i'     =>  'Windows 2000',
                      '/windows me/i'         =>  'Windows ME',
                      '/win98/i'              =>  'Windows 98',
                      '/win95/i'              =>  'Windows 95',
                      '/win16/i'              =>  'Windows 3.11',
                      '/macintosh|mac os x/i' =>  'Mac OS X',
                      '/mac_powerpc/i'        =>  'Mac OS 9',
                      '/linux/i'              =>  'Linux',
                      '/ubuntu/i'             =>  'Ubuntu',
                      '/iphone/i'             =>  'iPhone',
                      '/ipod/i'               =>  'iPod',
                      '/ipad/i'               =>  'iPad',
                      '/android/i'            =>  'Android',
                      '/blackberry/i'         =>  'BlackBerry',
                      '/webos/i'              =>  'Mobile'
                      );
    foreach ($os_array as $regex => $value) { 
        if (preg_match($regex, $userAgent)) {
            $osPlatform    =   $value;
        }
    }   
    return $osPlatform;
}

/* 
 * Ritorna true se l'utenten usa un Sistema Operativo Unix
 */
function isUnix(){
	$os = getOS();
	return $os == 'Ubuntu' || $os == 'Linux' || $os == 'Mac OS X' || $os == 'Mac OS 9';
}

/* 
 * Ritorna true se l'utenten usa un Sistema Operativo Windows
 */
function isDOS(){
	return substr(getOS(), 0, 3) == 'Win';
}


/* 
 * Ritorna il tipo di browser usato
 */
function getBrowser() {
    global $userAgent;
    $browser = "Unknown Browser";
    $browserArray = array('/msie/i'       =>  'Internet Explorer',
                          '/firefox/i'    =>  'Firefox',
                          '/safari/i'     =>  'Safari',
                          '/chrome/i'     =>  'Chrome',
                          '/opera/i'      =>  'Opera',
                          '/netscape/i'   =>  'Netscape',
                          '/maxthon/i'    =>  'Maxthon',
                          '/konqueror/i'  =>  'Konqueror',
                          '/mobile/i'     =>  'Handheld Browser'
                         );
    foreach ($browserArray as $regex => $value) { 
        if (preg_match($regex, $userAgent)) {
            $browser = $value;
        }
    }
    return $browser;
}

/* 
 * Riproporziona larghezza e lunghezza di un'immagine a partire dalle dimensioni originali 
 * e dall'ampiezza massima consentita ad una delle due grandezze
 */
function ratioImagDimension($widthIn, $heightIn, $maxValue = 200){
	
	if ($widthIn < $maxValue && $heightIn < $maxValue ) return array($widthIn, $heightIn);
	
	$widthOut  = null;
	$heightOut = null;
	
	if ($widthIn > $heightIn) {
		$ratio = round($widthIn / $maxValue, 2);
		$widthOut = $maxValue;
		$heightOut = round($heightIn / $ratio, 0);
	}
	else{	
		$ratio = round($heightIn / $maxValue, 2);
		$heightOut = $maxValue;
		$widthOut = round($widthIn / $ratio, 0);
	}
	//echo $widthOut. " ". $heightOut;exit;
	return array($widthOut, $heightOut);
}

/* 
 * Fissata l'altezza dell'immagine determina in proporzione la larghezza
 */
function ratioImagDimensionFixHeight($widthIn, $heightIn, $heightFixed = 160){
	$ratio = round($heightIn / $heightFixed, 2);
	return round($widthIn / $ratio, 0);
}

/* 
 * Fissata la larghezza dell'immagine determina in proporzione la larghezza
 */
function ratioImagDimensionFixWidth($widthIn, $heightIn, $widthFixed = 520){
	$ratio = round($widthIn / $widthFixed, 2);
	return round($heightIn / $ratio, 0);
}

/*
 * Distrugge la sessione dell'Utente
 */
function destroyUserSession(){
	//cancello l'oggetto dalla sessione
	unset($_SESSION['USER_LOGGED']);
	//session_unset();     // unset $_SESSION variable for the run-time 
	//session_destroy();   // destroy session data in storage

	//cancello il cookie
	unset($_COOKIE["LOGGED_IN"]);
	setcookie("LOGGED_IN", "", time() - 3600, "/"); 
}

/* 
 * Salva la pagina che si sta richiedendo in sessione. Utile qualora fosse scaduta la sessione,
 * appena l'utente si logga, viene rediretto verso la sua ultima pagina richiesta.
 */
function savePageRequest($destPage){
	$_SESSION['DEST_PAGE'] = urlencode($destPage);
}

/* 
 * Rimuove gli oggetti dalla sessione.
 * @X_page pagina corrente
 */
function cleanSesison($X_page) {
	
	if ( $X_page != "createReview" ) unset($_SESSION['REVIEWN_BEAN']);
	if ( $X_page != "createCityReview" ) unset($_SESSION['CITY_REVIEWN_BEAN']);
	if ( $X_page != "createCountryReview" ) unset($_SESSION['COUNTRY_REVIEWN_BEAN']);
	
	if ( $X_page != "searchReviewResult") {
		unset($_SESSION['SEARCH_REV_HEADER_SEARCH_CRIT']); //cancello i criteri dell'header
		unset($_SESSION["SEARCH_REVIEW_RESULT_ARRAY"]);
		unset($_SESSION["SEARCH_REVIEW_RATE_ARRAY"]);
	}
	
	if ( $X_page != "searchReview"  && $X_page != "searchReviewResult") {
		unset($_SESSION['SEARCH_REVIEW_SEARCH_CRITERIA']); //cancello i criteri dell'adv search
	}
}

/*=====================================
/* 		COMMON DATE FUNCTION
/*=====================================
/* 
 * Ritorna il pattern della data in base alla lingua dell'utente
 */
function getDatePatternByLangCode($langCode){
	if($langCode == "en") return '%Y-%m-%d';
	if($langCode == "it") return '%d/%m/%Y';
	return '%Y-%m-%d';
}

/* 
 * Ritorna la regex della data in base alla lingua dell'utente
 */
function getDateRegexByLangCode($langCode){
	if($langCode == "en") return "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
	if($langCode == "it") return "/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/";
	return "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
}

/* 
 * Ritorna l'anno della data in base alla lingua dell'utente
 */
function getYearDateByLangCode($langCode, $date){
	if($langCode == "en") return substr($date, 0, 4);
	if($langCode == "it") return substr($date, -4);
	return substr($date, 0, 4);
}

/* 
 * Ritorna il mese della data in base alla lingua dell'utente
 */
function getMonthDateByLangCode($langCode, $date){
	if($langCode == "en") return substr($date, 5, 2);
	if($langCode == "it") return substr($date, 3, 2);
	return substr($date, 5, 2);
}

/* 
 * Ritorna il giorno della data in base alla lingua dell'utente
 */
function getDayDateByLangCode($langCode, $date){
	if($langCode == "en") return substr($date, -2);
	if($langCode == "it") return substr($date, 0, 2);
	return substr($date, -2);
}

/* 
 * Ritorna il pattern da usare per il datepicker JS component
 */
function getJavaScriptDatePattern($langCode){
	if($langCode == "en") return 'yy-mm-dd';
	if($langCode == "it") return 'dd/mm/yy';
	return 'yy/mm/dd';
}

/* 
 * Ritorna i nomi dei mesi da usare per il datepicker JS component
 */
function getJavaScriptDateMonth($langCode){
	if($langCode == "en") return '["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]';
	if($langCode == "it") return '["Gen", "Feb", "Mar", "Apr", "Mag", "Giu", "Lug", "Ago", "Set", "Ott", "Nov", "Dic"]';
	return '["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]';
}

/* 
 * Ritorna i nomi dei giorni da usare per il datepicker JS component
 */
function getJavaScriptDateDay($langCode){
	if($langCode == "en") return '["Su","Mo","Tu","We","Th","Fr","Sa"]';
	if($langCode == "it") return '["Do","Lu","Ma","Me","Gi","Ve","Sa"]';
	return '["Su","Mo","Tu","We","Th","Fr","Sa"]';
}

/* 
 * Ritorna i nomi dei giorni da usare per il datepicker JS component
 */
function getJavaScriptDateFirstDay($langCode){
	if($langCode == "en") return '0'; // 0 -> domenica, 1-> lunedi', 2 -> martedi'....
	if($langCode == "it") return '1';
	return '0';
}


/*=====================================
/* 		COMMON FIELD CHECK
/*=====================================

/* 
 * Verifica il codice di sicurezza 
 * @$reqType il tipo di richiesta 
 * @$X_langArray array della lingua corrente
 */
function checkCaptcha($reqType, $X_langArray) {
	
	$digitArr = $_SESSION['digitArr'];

	//Se il codice non e' stato generato => esco
	if (!isset($digitArr) || !isset($digitArr[$reqType]) ) {
		return "";
	}
	
	$cptchGenCode = $digitArr[$reqType];
	
	//Verifico che sia uguale a quello digitato dall'utente
	if( isset($_POST['captcha_code']) && $_POST['captcha_code'] != $cptchGenCode) {
		return '&cptchErrMsg='.urlencode($X_langArray['OVERLAY_LOG_SIGN_CPTCH_CD_ERROR']);
	}	

	return ""; //tutto OK
}
?>
