<?php
require_once $X_root."pvt/pages/globalFunction.php";

if ( isUnix() ) {
	define('PATH_SEPARAT', '/');

	//Path assoluto della root dei file. Nota che su db salvo la seconda parte (/viantes/pvt/upload/2015-10-28/f1.jpg) 
	//==> posso identificare un file con un path assoluto (per fare una move, o una getimagesize())
	define('HT_ROOT', "/var/www/html/");
	
	//Path assoluto dove vengono uplodati i file (cover review, foto, video, user cover)
	define('UPLOAD_PATH', '/var/www/html/viantes/pvt/upload/');
	
	/* FFMPEG */
	define('FFMPEG_CMD', '/usr/bin/ffmpeg -i ');
}
if ( isDOS() ) {
	define('PATH_SEPARAT', '\\');
	
	//Path assoluto della root dei file. Nota che su db salvo la seconda parte (/viantes/pvt/upload/2015-10-28/f1.jpg) 
	//==> posso identificare un file con un path assoluto (per fare una move, o una getimagesize())
	define('HT_ROOT', "C:\\bin\\xampp\\htdocs\\");
	
	//Path assoluto dove vengono uplodati i file (cover review, foto, video, user cover)
	define('UPLOAD_PATH', 'C:\\bin\\xampp\\htdocs\viantes\pvt\upload\\');
	
	/* FFMPEG */
	define('FFMPEG_CMD', 'c:\\bin\\ffmpeg\\bin\\ffmpeg.exe -i ');
}

/* Path relativo delle risorse */
define('REL_UP_PATH', '/viantes/pvt/upload/');

define('SLASH', '/');

/* Messaggi Globali */
define('GLOBAL_TOP_MSG_SUCCESS', 'glblTpMsgOk');
define('GLOBAL_TOP_MSG_ERROR'  , 'glblTpMsgKo');

/* Tipi File Upload */
define('IMG', 'IMG');
define('MOV', 'MOV');
define('DOC', 'DOC');

/* Costanti di sessione */
define('DEST_PAGE', 'DEST_PAGE'); //Pagina di destinazione

/* Tipologie di imamgini del profilo */
define('COVER_TYPE_PROFILE',  '1');
define('COVER_TYPE_BCK_PROF', '2');

//Dimensioni delle immagini
define('IMG_748_290',' width="748" height="290" '); // immagine copertina nella pagina del profilo
define('IMG_100_126',' width="100" height="126" '); // usata per le icone del pdf
define('IMG_128_128',' width="128" height="128" '); // immagine profilo   nella pagina del profilo
define('IMG_25_25',  ' width="25"  height="25" ' );
define('IMG_36_36',  ' width="36"  height="36" ' );

/* Tipologie di imamgini del profilo */
define('OVL_LS_MODE_LOGIN', '1');
define('OVL_LS_MODE_SININ', '2');
define('OVL_LS_MODE_RECOVER', '3');

/* Nome temporaneo del file cropped */
define('TMP_CROPPED_FILE_NAME', 'tmpCropped');

/* Delimitatori */
define('attributeDelim', '@#@');
define('listDelim', '#@#');

/* Tipi di Recensioni */
define('SiteReview', '1');
define('CityReview', '2');
define('CountryReview', '3');

/* Tipi di Richiesta (usati nel Captcha) */
define('LOGIN', 1);
define('SIGNIN', 2);
define('PWDRECOVER', 3);
define('CONTACT', 4);

/* Tipi di ordinamento */
define('ORD_BY_MDE_DSC', " DESC");
define('ORD_BY_MDE_ASC', " ASC");

/* Resized Images */
define('RSZD_FOR_IND', ".rszd.hm.jpg"); //resized 4 index page
?>
