<?php
require_once $X_root."pvt/pages/common/commonDAO.php";
require_once $X_root."pvt/pages/log/log.php";

class ClientRequestDAO extends CommonDAO { 

    public function __construct(){
    }

   /* 
	* Verifica se il client ha fatto piu' di abbia fatto gia' altre richieste nele credenziali utente 
	* @$type type of request 
	* @$maxNum
	* @$interval
	* @see define('LOGIN', 1); define('SIGNIN', 2); define('PWDRECOVER', 3); 
	* @see define('LG_MAX_REQ_NUM', 3); define('SG_MAX_REQ_NUM', 1); define('RC_MAX_REQ_NUM', 1); 
	*/
    public function isMaxRequest($type, $maxNum, $interval = '30'){
		$ip	= substr( (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']), 0, 16); ;

		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT COUNT(*) AS NUM FROM REQUEST 
						WHERE IP = '%s'  AND REQTP = %d
						AND DTINS > DATE_SUB(NOW(), INTERVAL ".$interval." MINUTE)", $ip, $type);
		Logger::log("ClientRequestDAO :: isMaxRequest :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($num);
			if ($stmt->fetch()) {
				return $num  >= $maxNum;
			}
			$stmt->free_result();
		}
		
		//close
		$mysqli->close();
		return FALSE;
    }
	
   /*
	* log current request 
	* @$type type of request 
	* @see define('LOGIN', 1); define('SIGNIN', 2); define('PWDRECOVER', 3); 
	*/
	public function logRequest($type){
		$interval =  Conf::getInstance()->get('clientRequestTimeStore');
		$request = '';
		switch($_SERVER['REQUEST_METHOD']){
			case 'GET': $request = $_GET; break;
			case 'POST': $request = $_POST; break;
		}

		//ip
		$ip	= substr( (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']), 0, 16); ;
		
		//request
		$req = "";
		foreach($request as $key => $value){
			$req.= $key.'='.$value."&";
		}
		$req = substr($req, 0, 250 );
		
		//agent
		$agent = substr(  $_SERVER['HTTP_USER_AGENT'], 0, 250);
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("INSERT INTO REQUEST (REQTP, IP, USERAGENT, REQUEST) VALUES ('%s', '%s', '%s', '%s')", $type, $ip, $agent, $req);
		$mysqli->query($sql);
		
		Logger::log("ClientRequestDAO :: logRequest :: query : ".$sql, 3);

		//Clean Old Record
		$sqlDel = sprintf("delete FROM request WHERE DTINS <= DATE_SUB(NOW(), INTERVAL %d MINUTE)", $interval);
		$mysqli->query($sqlDel);
		
		Logger::log("ClientRequestDAO :: logRequest :: query : ".$sqlDel, 3);

		//close
		$mysqli->close();
		return FALSE;
    }
	
	
	
   /* 
	* Verifica se il client puo' fare una recover password. 
	* N.B dopo 3 richieste deve aspettare 24 ore (1440 minuti)
	* @see define('LOGIN', 1); define('SIGNIN', 2); define('PWDRECOVER', 3); 
	* @see define('LG_MAX_REQ_NUM', 3); define('SG_MAX_REQ_NUM', 1); define('RC_MAX_REQ_NUM', 1); 
	*/
    public function isRecoverable(){
		$ip	= substr( (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']), 0, 16); ;

		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT COUNT(*) AS NUM FROM REQUEST WHERE IP = '%s'  AND REQTP = " .PWDRECOVER. " 
					    AND DTINS > DATE_SUB(NOW(), INTERVAL 1440 MINUTE)", $ip);
		Logger::log("ClientRequestDAO :: isRecoverable :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($num);
			if ($stmt->fetch()) {
				return $num  < 3;
			}
			$stmt->free_result();
		}
		//close
		$mysqli->close();
		return FALSE;
    }
	
	
}
?>