<?php
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/globalFunction.php";

class Conf {
	
	protected static $instance = null;

	private static $config = array(
		'logName' 				=> 'vnts.log',			//nome del file di log
		'logLevel' 				=> '3',   	            //livello di log = DEBBUG
		'useCache' 				=> '0',					//se 1 usa la cache
		'doCaptcha'				=> '0',					//se e' 1 esegue il controllo del captcha (0 => no captcha)
		'doMail'				=> '0',					//se e' 1 invia le mail altrimenti e' cortocircuitata
		'sessionTimeOut' 		=> '1800', 				//time out della sessione (in secondi)
		'delayFromLastActivity'	=> '1800', 				//ogni nuova attivita' il time out di estende di x (in secondi)
		'reloadItemEvery' 		=> '60000', 			//ricarica gli item della welcome page ogni (millisecondi)
		'maxReviewItem'			=> '20', 				//numero di item della welcome page
		'searchResultNum'		=> '10',				//numero di risultati nella pagina di ricerca
		'imgMaxFileCreReview' 	=> '8',					//numero max di immagini da allegare in crea review
		'movMaxFileCreReview' 	=> '3',					//numero max di video da allegare in crea review
		'docMaxFileCreReview' 	=> '4',					//numero max di documenti da allegare in crea review
		'imgMaxDimension'		=> '4000',				//massima dimensione in pixel (x lato) di un'immagine
		'imgMinDimension'		=> '400',				//minima dimensione in pixel (x lato) di un'immagine
		'reloadUsrMsg'	 		=> '60000',	 			//ricarica i messaggi dell'utente ogni (millisecondi)
		'loginMaxNumOfRequest'	=> '3',	 				//masismo numero di richieste che si possono fare nella login (prima del recapcha)
		'signinMaxNumOfRequest'	=> '1',	 				//masismo numero di richieste che si possono fare nella signin (prima del recapcha)
		'recoverMaxNumOfRequest'=> '1',	 				//masismo numero di richieste che si possono fare nella recover pwd (primadel recapcha)
		'clientRequestTimeStore'=> '4320',	 			//cancella dalla tabella REQUEST i record inseriti piu' di x minuti fa
		'maxResAutocomplHeader' => '10',	 			//numero massimo di risultati nell'autocomplete dell'header
		'maxResAutocomplSendMsg'=> '10'	 				//numero massimo di risultati nell'autocomplete dei messaggi
	);
	
    protected function __construct() { }
	
	
    public static function getInstance() {
		
        if (!isset(static::$instance)) {
            static::$instance = new Conf();
			
			//app user
			$ini_array = parse_ini_file( (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'C:\\temp\\user.ini' : '/usr/local/etc/user.ini') );
			self::$config['usr'] = $ini_array['usr'];
			self::$config['pwd'] = $ini_array['pwd'];
			self::$config['mailUsr'] = $ini_array['mailUsr'];
			self::$config['mailPwd'] = $ini_array['mailPwd'];
			
			//log dir 
			self::$config['logDir'] = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? 'C:\temp\a\\' : '/var/log/viantes/';
        }
        return static::$instance;
    }
	
	public function get($key) {
		return self::$config[$key];
	}
}
?>
