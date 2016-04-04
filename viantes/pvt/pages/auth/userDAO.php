<?php
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/auth/starDO.php";
require_once $X_root."pvt/pages/common/commonDAO.php";
require_once $X_root."pvt/pages/log/log.php";

class UserDAO extends CommonDAO { 

    public function __construct(){
    }

	/* Verifica le credenziali utente */
    public function isAuth($email, $pwd){
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT U.ID, U.EMAIL, U.NAME, U.COVERFILENAME, U.BCKCOVERFILENAME, COUNT(*) AS INBOXMSGNUM
						FROM USER U JOIN MESSAGE M ON U.ID = M.TOUSRID
						WHERE U.EMAIL = '%s' AND U.PWD = '%s' AND U.STATUS = 1
						AND M.SENDERSTATUS = 1
						AND M.RECIPIENTSTATUS = 0", $email, $pwd);
		Logger::log("UserDAO :: isAuth :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($userId, $email, $name, $coverFileName, $bckCoverFileName, $inBoxMsgNum);

			//N,B. visto che nella query c'e' una count => torna sempre una riga. 
			//E' necessario controllare che ci sia almeno un campo (l'id preferibile) valorizzato
			if ($stmt->fetch() && isset($userId)) {
				$userDO = New UserDO($userId, $email, $name);
				$userDO->setCoverFileName($coverFileName);
				$userDO->setBckCoverFileName($bckCoverFileName);
				$userDO->setInBoxMsgNum($inBoxMsgNum);
				$stmt->free_result();
				$mysqli->close();
				Logger::log("UserDAO ::isAuth :: si e' loggato l'utente name:".$name, 3);

				//imposta tutte le star e i see dell'utente
				$userDO->setStar($this->buildUserStar( $userDO->getId() ));
				
				return $userDO;
			}
			$stmt->free_result();
		}
		
		//close
		$mysqli->close();
		return FALSE;
    }
	
	/* 
	 * Costruisce la struttura delle star e dei see dell'utente.
	 * 
	 * Ritorna un array di 3 elemnti, uno per ogni tipo di recensione.
	 * Ognuno di questi 3 elementi è costituito come segue:
	 * E' a sua volta un array che ha per chiave l'id della recensione e 
	 * per valore un oggetto un oggetto StarDO contenente informazioni aggiuntive.
	 */
    public function buildUserStar($userId){
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT SI.ID AS PLACEID, SI.SITENAME AS PLACE, U.NAME, R.ID AS REVID, STAR, SEE, POST, 1 
						FROM REVIEW R
							JOIN REVIEW_STAR S ON R.ID = S.REVID 
							JOIN SITE SI       ON R.SITEID = SI.ID 
							JOIN USER U        ON U.ID = R.USRID
						WHERE S.USRID=%d
						UNION
						SELECT CT.ID AS PLACEID, CT.CITYNAME AS PLACE, U.NAME, R.ID AS REVID, STAR, SEE, POST, 2
						FROM CITY_REVIEW R
							JOIN CITY_REV_STAR S ON R.ID = S.REVID 
							JOIN CITY CT         ON R.CITYID = CT.ID 
							JOIN USER U          ON U.ID = R.USRID
						WHERE S.USRID=%d
						UNION
						SELECT CN.ID AS PLACEID, CN.COUNTRY, U.NAME, R.ID AS REVID, STAR, SEE, POST, 3  
						FROM COUNTRY_REVIEW R
							JOIN COUNTRY_REV_STAR S ON R.ID = S.REVID 
							JOIN COUNTRY CN         ON R.COUNTRYID = CN.ID 
							JOIN USER U             ON U.ID = R.USRID
						WHERE S.USRID=%d", 
						$userId, $userId, $userId);
						
		Logger::log("UserDAO :: setUserStar :: query: ".$sql, 3);
		//echo $sql; exit;
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($siteId, $siteName, $author, $reviewId, $star, $see, $post, $revType);
			
			$starArray = array();
			while ($stmt->fetch()) {
				
				$siteStarArray = isset($starArray[$revType]) ? $starArray[$revType] : array();
				
				$starDO = NEW StarDO($siteId, $siteName, $author, $star, $see, $post);
				$siteStarArray[$reviewId] = $starDO ;
				
				$starArray[$revType] = $siteStarArray;
			}
			$stmt->free_result();
		}
		
		/// utile per evitare controlli di esistenza a chi usa l'array
		if (!isset($starArray[SiteReview])) $starArray[SiteReview] = array();
		if (!isset($starArray[CityReview])) $starArray[CityReview] = array();
		if (!isset($starArray[CountryReview])) $starArray[CountryReview] = array();
		
		//close
		$mysqli->close();
		return $starArray;
    }
	
	/* Carica l'utente a partire dall'id */
    public function getLazyUserDO($usrId){
		
		$key = $this->getLazyUserDOKey($usrId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT ID, EMAIL, NAME, COVERFILENAME, BCKCOVERFILENAME 
						FROM USER WHERE ID = %d AND STATUS = 1", $usrId);
		Logger::log("UserDAO :: getLazyUserDO :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($userId, $email, $name, $coverFileName, $bckCoverFileName);
			if ($stmt->fetch()) {
				$userDO = New UserDO($userId, $email, $name);
				$userDO->setCoverFileName($coverFileName);
				$userDO->setBckCoverFileName($bckCoverFileName);
				$stmt->free_result();
				
				//close, cache and return 
				$mysqli->close();
				$this->setCached($key, $userDO);
				return $userDO;
			}
			$stmt->free_result();
		}
		
		//close
		$mysqli->close();
		return FALSE;
    }
	
	/* return getLazyUserDO key */
	private function getLazyUserDOKey($usrId){
		return "UserDAO" . "getLazyUserDO" . $usrId;
	}
	
	/* Verifica se esiste gia' l'email nella tabella USER */
	public function checkEmailAlreadyExists($newemail){
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT EMAIL FROM USER WHERE EMAIL = '%s'", $newemail);
		Logger::log("UserDAO :: checkEmailAlreadyExists :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($usrResult);
			if ($stmt->fetch()) {
				$stmt->free_result();
				$mysqli->close();
				return TRUE;
			}
			$stmt->free_result();
		}

		//close
		$mysqli->close();
		return FALSE;
    }
	
	/* Verifica se esiste gia' il name nella tabella USER */
	public function checkNameAlreadyExists($name){
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT ID FROM USER WHERE NAME = '%s'", $name);
		Logger::log("UserDAO :: checkNameAlreadyExists :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id);
			if ($stmt->fetch()) {
				$stmt->free_result();
				$mysqli->close();
				return $id;
			}
			$stmt->free_result();
		}

		//close
		$mysqli->close();
		return FALSE;
    }
	
	/** Controlla se esiste una riga per la mail ed il codice inviato via mail */
	public function checkEmailAndFwdCodeIsValid($email, $fwdCode){
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT EMAIL FROM USER WHERE EMAIL = '%s' AND FWDCODE = '%s' AND STATUS = 0 
						AND DTINS > DATE_SUB(SYSDATE(), Interval 7 Day)", $email, $fwdCode);
		Logger::log("UserDAO :: checkEmailAndFwdCodeIsValid :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($usrResult);			
			if ($stmt->fetch()) {
				$stmt->free_result();
				$mysqli->close();
				return TRUE;
			}
			$stmt->free_result();
		}

		//close
		$mysqli->close();
		return FALSE;
    }
	
   /** 
    * Controlla se esiste una riga per la mail, la password ed il forward code nella tabella USER 
	* Ritorna un oggetto UserDO
	*/
	public function checkConfirmSingIn($email, $pwd, $fwdCode){
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT ID, EMAIL, NAME, COVERFILENAME, BCKCOVERFILENAME FROM USER WHERE EMAIL = '%s' AND PWD = '%s' AND FWDCODE = '%s' AND STATUS = 0
						AND DTINS > DATE_SUB(SYSDATE(), Interval 7 Day)", $email, $pwd, $fwdCode);
		Logger::log("UserDAO :: checkConfirmSingIn :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($userId, $email, $name, $cover, $bckCoverFileName);
			if ($stmt->fetch()) {
				$userDO = New UserDO($userId, $email, $name);
				$userDO->setcoverFileName($cover);
				$userDO->setBckCoverFileName($bckCoverFileName);
				$stmt->free_result();
				$mysqli->close();
				return $userDO;
			}
			$stmt->free_result();
		}

		//close
		$mysqli->close();
		return FALSE;
    }
	
	/** Crea un nuvo utente inserendo una riga nella tabella USER	*/
	public function createUser($email, $pwd, $name){
		$fwdCode = $this->createFwdCode(60);
		
		$mysqli = $this->getConn();
		
		$mysqli->query("LOCK TABLES USER WRITE");
		
		$sql = sprintf("INSERT INTO USER (EMAIL, PWD, NAME, FWDCODE) VALUES ('%s', '%s', '%s', '%s')", $email, $pwd, $name, $fwdCode);
		$mysqli->query($sql);
		
		Logger::log("UserDAO :: createUser :: query [".$sql."]", 3);
		
		//if ($stmt = $mysqli->prepare("SELECT LAST_INSERT_ID()")) {
		if ($stmt = $mysqli->prepare("SELECT MAX(ID) FROM USER")) {
			$stmt->execute();
			$stmt->bind_result($id);
			if ($stmt->fetch()) {
				Logger::log("UserDAO ::createUser :: REGISTRAZIONE :: si e' appena registrato:".$name." id:".$id, 2);
				
				//free result unlock and close
				$stmt->free_result();
				$mysqli->query("UNLOCK TABLES");
				$mysqli->close();
				return $id.'##'.$fwdCode;
			}
		}

		$mysqli->query("UNLOCK TABLES");
		Logger::log("UserDAO ::createUser :: REGISTRAZIONE :: Impossibile ottenere l'id dell'utente: ".$name." che si e' appena registrato ", 1);
		
		//close
		$mysqli->close();
		return FALSE;
    }
	
	/** Aggiorna lo stato dell'utente da 0 a 1 */
	public function updateUserStatus($email, $pwd, $fwdCode){
		$mysqli = $this->getConn();
		
		$sql = sprintf("UPDATE USER SET STATUS = 1 WHERE EMAIL = '%s' AND PWD = '%s' AND FWDCODE = '%s'", $email, $pwd, $fwdCode);
		Logger::log("UserDAO ::updateUserStatus :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		
		//close
		$mysqli->close();		
		return TRUE;
    }

	/** Aggiorna la password */
	public function updatePassword($pwd, $id){
		$mysqli = $this->getConn();
		
		$sql = sprintf("UPDATE USER SET PWD = '%s' WHERE ID = '%s'", $pwd, $id);
		Logger::log("UserDAO :: updatePassword :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		
		//close
		$mysqli->close();		
		return TRUE;
    }
	
	/** Aggiorna il path dell'immagine di copertina */
	public function updateCover($cover, $id){
		$key = $this->getLazyUserDOKey($id);
		
		$mysqli = $this->getConn();

		$sql = sprintf("UPDATE USER SET COVERFILENAME = '%s' WHERE ID = %d", $cover, $id);
		Logger::log("UserDAO ::updateCover :: query: ".$sql, 3);

		$mysqli->query($sql);
		
		//close
		$mysqli->close();
		$this->delCached($this->getLazyUserDOKey($id));
		return TRUE;
    }

	/** Aggiorna il path dell'immagine di copertina */
	public function updateBckCover($cover, $id){
		$mysqli = $this->getConn();

		$sql = sprintf("UPDATE USER SET BCKCOVERFILENAME = '%s' WHERE ID = %d", $cover, $id);
		Logger::log("UserDAO ::updateCover :: query: ".$sql, 3);

		$mysqli->query($sql);
		
		//close and clean cache
		$mysqli->close();
		$this->delCached($this->getLazyUserDOKey($id));
		return TRUE;
    }
	
	/** Resetta il fwdCode dell'utente e lo restituisce al chiamato
	 *  @see /viantes/pvt/pages/auth/recoverPwd.php
	 */
	public function recoverPwd($email){
		$fwdCode = $this->createFwdCode(60);
	
		$mysqli = $this->getConn();

		$sql = sprintf("UPDATE USER SET FWDCODE = '%s', DTLASTMOD=NOW() WHERE EMAIL = '%s' AND STATUS in (0, 1)", $fwdCode, $email);
		Logger::log("UserDAO ::recoverPwd :: query: ".$sql, 3);

		$mysqli->query($sql);
		
		//close
		$mysqli->close();		
		return $fwdCode;
	}
	
   /** 
    * Controlla se esiste una riga per la mail, ed il forward code nella tabella USER con stato zero e data ancora valida
	* Ritorna un oggetto UserDO
	*/
	public function checkRecoverPwd($email, $fwdCode){
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT ID, EMAIL, NAME, COVERFILENAME FROM USER WHERE EMAIL = '%s' AND FWDCODE = '%s' AND STATUS in (0, 1)
						AND DTLASTMOD > DATE_SUB(SYSDATE(), Interval 2 Day)", $email, $fwdCode);
		Logger::log("UserDAO ::checkRecoverPwd :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($userId, $email, $name, $cover);			
			if ($stmt->fetch()) {
				$userDO = New UserDO($userId, $email, $name);
				$userDO->setcoverFileName($cover);
				$stmt->free_result();
				$mysqli->close();
				return $userDO;
			}
			$stmt->free_result();
		}
		//close
		$mysqli->close();
		return FALSE;
    }
	
	/* Bloccao sblocca l'utente denUsrId ad inviare msg verso usrId */
	public function blockOrUnblockUsr($usrId, $denUsrId, $denStatus){
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT DENSTATUS FROM MSG_DEN WHERE USRID = %d AND DENUSRID = %d", $usrId, $denUsrId);
		Logger::log("UserDAO ::blockOrUnblockUsr :: select query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($oldDenStatus);
			if ($stmt->fetch()) {
				$stmt->free_result();
				$sql = sprintf("UPDATE  MSG_DEN SET DENSTATUS = %d WHERE USRID = %d AND DENUSRID = %d", $denStatus, $usrId, $denUsrId);
			}else {
				$sql = sprintf("INSERT INTO MSG_DEN (USRID, DENUSRID, DENSTATUS) VALUES (%d, %d, %d)", $usrId, $denUsrId, $denStatus);
			}
			
			$this->delCached($this->getHaveBlockedUsrListKey($denUsrId));
			$this->delCached($this->getBlockedUsrListKey($usrId));
			
			Logger::log("UserDAO ::blockOrUnblockUsr :: insert/update query: ".$sql, 3);		
			$mysqli->query($sql);
		}
		//close
		$mysqli->close();
	}
	
	/* Ritorna la lista degli utenti che hanno bloccato l'utente in input */
	public function getHaveBlockedUsrList($usrId){
		
		$key = $this->getHaveBlockedUsrListKey($usrId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT USRID FROM MSG_DEN WHERE DENUSRID = %d AND DENSTATUS = 1", $usrId);
		Logger::log("UserDAO ::getHaveBlockedUsrList :: query: ".$sql, 3);
		
		$usrList = array();
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($usrId);
			while ($stmt->fetch()) {
				array_push($usrList, $usrId);
			}
			$stmt->free_result();
		}
		//close
		$mysqli->close();
		
		//memcached not work if array is empty :(
		if (count($usrList) == 0) array_push($usrList, null);
		$this->setCached($key, $usrList);
		return $usrList;
	}
	
	/* return getHaveBlockedUsrList key */
	private function getHaveBlockedUsrListKey($usrId){
		return "UserDAO" . "getHaveBlockedUsrList" . $usrId;
	}
	
	/* Ritorna la lista degli utenti che ha bloccato */
	public function getBlockedUsrList($usrId){
		
		$key = $this->getBlockedUsrListKey($usrId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT DENUSRID FROM MSG_DEN WHERE USRID = %d AND DENSTATUS = 1", $usrId);
		Logger::log("UserDAO ::blockedUsrList :: query: ".$sql, 3);
		
		$usrList = array();
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($denUsrId);
			while ($stmt->fetch()) {
				array_push($usrList, $denUsrId);
			}
			$stmt->free_result();
		}
		//close
		$mysqli->close();
		
		//memcached not work if array is empty :(
		if (count($usrList) == 0) array_push($usrList, null);
		$this->setCached($key, $usrList);
		return $usrList;
	}
	
	/* return getBlockedUsrList key */
	private function getBlockedUsrListKey($usrId){
		return "UserDAO" . "getBlockedUsrList" . $usrId;
	}
	
	private function createFwdCode($length = 60){
		$chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=~!@$^*_,.;:[]{}|';
		$code = '';
		$max = strlen($chars) - 1;
		for ($i = 0; $i < $length; $i++) {
			$code .= $chars[mt_rand(0, $max)];
		}
		return $code;
	}
	
	
   /* 
    * Ritorna un arraay di nomi di utenti a partire dai parametri di ricerca 
	* Usato nell'autocomplete dell'invio dei messaggi
	*/
	public function searchUserName4Autocompl($keyword){
		$limit = Conf::getInstance()->get('maxResAutocomplSendMsg');
		
		$key = $this->searchUserName4AutocomplKey($keyword);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$autocomplArray = array();
		
		$mysqli = $this->getConn();
		
		$sql = "SELECT NAME, COVERFILENAME FROM USER
				WHERE UPPER( NAME ) LIKE UPPER( '%" .$keyword. "%' )
				LIMIT ".$limit;
				
		Logger::log("ReviewDAO :: searchUserName4Autocompl :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($name, $cover);
			while($stmt->fetch()) {
				$row = array();
				$row['name'] = $name;
				$row['cover'] = $cover;
				array_push($autocomplArray, $row);
			}
		}
		
		//close
		$mysqli->close();
		$this->setCached($key, $autocomplArray);
		return $autocomplArray;
	}
	
	/* return searchUserName4Autocompl key */
	private function searchUserName4AutocomplKey($keyword){
		return "ReviewDAO" . "searchUserName4Autocompl" . $keyword;
	}
}
?>
