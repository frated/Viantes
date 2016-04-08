<?php
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/review/attachDO.php";
require_once $X_root."pvt/pages/review/cityReviewDO.php";
require_once $X_root."pvt/pages/review/commonReviewDAO.php";
require_once $X_root."pvt/pages/site/cityDO.php";

class CityReviewDAO extends CommonReviewDAO {

    public function __construct(){
    }
	
	/**
	 * Crea una citta' e la restituisce come CityDO se non esiste, altrimenti ritorna quella esistente
	 */
	private function createCityIfNotEx($mysqli, $city, $countryId, $langCode){
	
		//Lock City Table
		$mysqli->query("LOCK TABLES CITY WRITE");
		
		$sql = sprintf("SELECT ID, CITYNAME, COUNTRYID, LANGCODE FROM CITY 
						WHERE UPPER(CITYNAME)=UPPER('%s') 
						AND LANGCODE = '%s'", $city, $langCode);
		Logger::log("CityReviewDAO :: createCityIfNotEx :: check city if exists :: select query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($idF, $cityF, $countryIdF, $langCodeF);
			if($stmt->fetch()) {
				$cityDO = New CityDO($idF, $cityF, $countryIdF, $langCodeF);
				$mysqli->query("UNLOCK TABLES");
				return $cityDO;
			}
		}
		
		//Insert City
		$sql = sprintf("INSERT INTO CITY (CITYNAME, COUNTRYID, LANGCODE) VALUES ('%s', %d, '%s')", $city, $countryId, $langCode);
		$mysqli->query($sql);
		Logger::log("CityReviewDAO :: createCityIfNotEx :: insert query: ".$sql, 3);
		
		//Get City Id
		if ($stmt = $mysqli->prepare("SELECT MAX(ID) FROM CITY")) {
			$stmt->execute();
			$stmt->bind_result($id);
			if ($stmt->fetch()) {
				Logger::log("CityReviewDAO :: createCityIfNotEx :: query: ".$sql, 3);
				
				//free result unlock and close
				$stmt->free_result();
				$mysqli->query("UNLOCK TABLES");
				$cityDO = New CityDO($id, $city, $countryId,$langCode);
				return $cityDO;
			}
		}
		
		$mysqli->query("UNLOCK TABLES");
		Logger::log("CityReviewDAO :: createCityIfNotEx :: Errore, non &egrave; stata trovata nessuna riga ed non &egrave; stato possibile inserirne una nuova nella tabella CITY.", 0);

		return null;
	}
	
   /** 
	* Crea una nuva recensione inserendo una riga nella tabella CITY_REVIEW 
	* Ritorna l'id della recensione inserita
	*/
	public function createCityReview($usrId, $city,  $countryId, $langCode, $descr, $arrive, 
			$warn, $whEat, $cook, $whStay, $myth, $vote, $bean, &$interest){

		$coverFileName =  $bean->getCoverFileName();
		$xdim =  $bean->getCoverWidth();
		$ydim =  $bean->getCoverHeight();
									  
		$mysqli = $this->getConn();
		
		//$mysqli->autocommit(FALSE); 
		//$mysqli->begin_transaction()
		
		$cityDO = $this->createCityIfNotEx($mysqli, $city, $countryId, $langCode);
		
		//inserts HTML line breaks before all newlines
		$formattedDescr = nl2br($descr);
		
		$mysqli->query("LOCK TABLES CITY_REVIEW WRITE");
		
		//insert 
		$sql = sprintf("INSERT INTO CITY_REVIEW (USRID, CITYID, LANGCODE, DESCR, HOWTOARRIVE, WARNING, WHERETOEAT, COOKING, WHERETOSTAY, MYTH, VOTE, COVERFILENAME, XDIM, YDIM) 
						VALUES (%d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, '%s', %d, %d)", 
						$usrId, $cityDO->getId(), $langCode, $formattedDescr, $arrive, $warn, $whEat, $cook, $whStay, $myth, $vote, $coverFileName, $xdim, $ydim);
		Logger::log("CityReviewDAO :: createReview :: query: ".$sql, 3);
		
		$mysqli->query($sql);		
		
		//cancello la lista di recensioni dell'utente dalla cache
		$this->delCached($this->getReviewListKey($usrId));
		
		//TODO Capire come cancellare il nome del sito inserito in una ricerca precedente
		//$this->delCached($this->searchReviewsKey($langCode, $siteName));
		
		//get last id
		if ($stmt = $mysqli->prepare("SELECT LAST_INSERT_ID()")) {
			$stmt->execute();
			$stmt->bind_result($id);
			if ($stmt->fetch()) {
				$stmt->free_result();
				//$mysqli->commit()
				$mysqli->query("UNLOCK TABLES");
				
				//Insert Interest
				$this->insertInterest($mysqli, $interest, $id);
				
				$mysqli->close();
				return $id;
			}
		}
		
		//close
		//$mysqli->commit()
		$mysqli->query("UNLOCK TABLES");
		$mysqli->close();
		return -1;
    }
	
	/**
	 * Inserisce i luoghi di interesse della citta'
	 */
	private function insertInterest($mysqli, $inter, $cityRevid){
		//Insert 
		foreach ($inter as $k => $v) {
			$revid = explode(attributeDelim, $v)[0];
			$sql = sprintf("INSERT INTO REVIEW_CITY_REVIEW (REVIEWID, CITYREVID) VALUES (%d, %d )", $revid, $cityRevid);
			$mysqli->query($sql);
			Logger::log("CityReviewDAO :: insertInterest :: query: ".$sql, 3);
		}
		return true;
	}

	/**
	 * Inserisce i luoghi di interesse della citta'
	 */
	public function getInterestByRevId($cityRevid){
		
		$key = $this->getInterestByRevIdKey($cityRevid);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$sql = sprintf("SELECT R.ID, R.COVERFILENAME, S.SITENAME 
						FROM REVIEW R 
							JOIN REVIEW_CITY_REVIEW RCR ON R.ID = RCR.REVIEWID 
							JOIN SITE S ON R.SITEID = S.ID 
						WHERE RCR.CITYREVID = %d
						ORDER BY DTINS DESC", $cityRevid);
		
		Logger::log("CityReviewDAO :: getInterestByRevId :: query: ".$sql, 3);
		
		$mysqli = $this->getConn();
		
		$result = array();
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $coverFileName, $siteName);
			while($stmt->fetch()) {
				$row = array();
				$row['id'] = $id;
				$row['coverFileName'] = $coverFileName;
				$row['siteName'] = $siteName;
				array_push($result,$row);
			}
			$stmt->free_result();
		}
		//close and cache
		$mysqli->close();
		$this->setCached($key, $result);
		return $result;
	}
	
	/* return getInterestByRevId key */
	private function getInterestByRevIdKey($cityRevid){
		return "CityReviewDAO" . "getInterestByRevId" . $cityRevid;
	}
	
   /** 
	* Ritorna tutte le recensioni dell'utente
	*/
	public function getReviewList($usrId, $pattern){
		
		$key = $this->getReviewListKey($usrId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT R.ID, R.USRID, R.CITYID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, 
							   R.WHERETOEAT, R.COOKING, R.WHERETOSTAY, R.MYTH, DATE_FORMAT(R.DTINS, '%s'), 
							   R.VOTE, R.COVERFILENAME, U.NAME, U.COVERFILENAME, CT.CITYNAME,  C.COUNTRY
						FROM CITY_REVIEW R
							JOIN USER U ON U.ID = R.USRID
							JOIN CITY CT ON CT.ID = R.CITYID,
							COUNTRY C
						WHERE R.USRID = %d 
						AND C.ID = CT.COUNTRYID
						ORDER BY R.DTINS DESC",  $pattern, $usrId);
		Logger::log("CityReviewDAO :: getReviewList :: query: ".$sql, 3);
	
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, 
							   $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, 
							   $coverFileName, $userName, $userCoverFileName, $cityName, $country);
			while($stmt->fetch()) {
				//echo $descr() . "<br>" ;
				$descr = str_replace("\r\n",'<br>', $descr);
				
				//Costruttore
				$reviewDO = New CityReviewDO($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName);
				
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
				$reviewDO->setCityName($cityName);
				$reviewDO->setCountry($country);
				
				//push elemento
				array_push($reviewDOArray, $reviewDO);
			}
			$stmt->free_result();
		}
		
		//close
		$mysqli->close();
		$this->setCached($key, $reviewDOArray);
		return $reviewDOArray;
    }
	
	/* return getReviewList key */
	protected function getReviewListKey($usrId){
		return "CityReviewDAO" . "getReviewList" . $usrId;
	}
	
	/** Ritorna una recensione a partire dall'id */
	public function getReviewById($revId) {	
	
		$key = $this->getReviewByIdKey($revId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT R.ID, R.USRID, R.CITYID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, 
							   R.WHERETOEAT, R.COOKING, R.WHERETOSTAY, R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME, 
							   R.XDIM, R.YDIM, U.NAME, U.COVERFILENAME, CT.CITYNAME,  C.COUNTRY, 
							   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
						FROM CITY_REVIEW R
							JOIN USER U ON U.ID = R.USRID
							JOIN CITY CT ON CT.ID = R.CITYID
							LEFT JOIN CITY_REV_STAR ST ON ST.REVID = R.ID,
							COUNTRY C
						WHERE R.ID = %d 
						AND C.ID = CT.COUNTRYID
						ORDER BY DTINS DESC", $revId);
		Logger::log("CityReviewDAO :: getReviewById :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, 
							   $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, 
							   $xdim, $ydim, $userName, $userCoverFileName, $cityName, $country, $star, $see, $post);
			if($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				
				//Costruttore
				$reviewDO = New CityReviewDO($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, $whereToEat, $cooking, 
											 $whereToStay, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);
				
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
				$reviewDO->setCityName($cityName);
				$reviewDO->setCountry($country);
				$reviewDO->setCntStar(isset($star)?$star:0);
				$reviewDO->setCntSee(isset($see)?$see:0);
				$reviewDO->setCntPost(isset($post)?$post:0);
				
				$stmt->free_result();
				$mysqli->close();
				$this->setCached($key, $reviewDO);
				return $reviewDO;
			}
		}
		
		//close
		$mysqli->close();
		return;
	}

	/* return getReviewById key */
	protected function getReviewByIdKey($revId){
		return "CityReviewDAO" . "getReviewById" . $revId;
	}
	
	/* Ritorna il numero di citta' (non assoluto ma distinct per citta') che incontrano i criteri di ricerca. 
	 * Da usare prima della @searchReviews($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc) per capire 
	 * se il risultato e' unico (nel senso che ho "n" recensioni ma di un solo sito) o meno. */
	public function searchReviewsCount($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc){
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$imgJoin = $onlyImg == "1" ? " AND EXISTS (SELECT ID FROM CITY_REV_ATT RA WHERE RA.CITYREVID = R.ID AND RA.TYP = 'IMG')" : "";
		$movJoin = $onlyMov == "1" ? " AND EXISTS (SELECT ID FROM CITY_REV_ATT RA WHERE RA.CITYREVID = R.ID AND RA.TYP = 'MOV')" : "";
		$docJoin = $onlyDoc == "1" ? " AND EXISTS (SELECT ID FROM CITY_REV_ATT RA WHERE RA.CITYREVID = R.ID AND RA.TYP = 'DOC')" : "";
		
		$sql = "SELECT DISTINCT (CT.ID), CT.CITYNAME,  C.COUNTRY
				FROM CITY_REVIEW R JOIN CITY CT ON CT.ID = R.CITYID, COUNTRY C
				WHERE UPPER( CT.CITYNAME ) LIKE UPPER( '%" .$keyword. "%' )
				AND C.ID = CT.COUNTRYID
				AND R.LANGCODE = '" .$langCode. "'"
				. $imgJoin . $movJoin . $docJoin;
		
		Logger::log("CityReviewDAO :: searchReviewsCount :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($cityId, $cityName, $country);
			while($stmt->fetch()) {				
				//Costruttore
				$reviewDO = New CityReviewDO(-1, "", $cityId, "", "", "", "", "", "", "", "", "", 6, "");
				
				//Setto i campi in relazione
				$reviewDO->setCityName($cityName);
				$reviewDO->setCountry($country);
				
				//push elemento
				array_push($reviewDOArray, $reviewDO);
			}
			$stmt->free_result();
		}
		
		//close
		$mysqli->close();
		return $reviewDOArray;
	}
			
	/* 
	 * Ritorna un array di recensioni a partire dai parametri di ricerca 
	 * @see searchReviewFromAdv.php ($cityId NON valorizzato)
	 * @see searchReviewFromMultiResult.php ($cityId NON valorizzato)
	 */
	public function searchReviews($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc, $orderTp, $cityId = ''){
	
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$imgJoin = $onlyImg == "1" ? " AND EXISTS (SELECT ID FROM CITY_REV_ATT RA WHERE RA.CITYREVID = R.ID AND RA.TYP = 'IMG')" : "";
		$movJoin = $onlyMov == "1" ? " AND EXISTS (SELECT ID FROM CITY_REV_ATT RA WHERE RA.CITYREVID = R.ID AND RA.TYP = 'MOV')" : "";
		$docJoin = $onlyDoc == "1" ? " AND EXISTS (SELECT ID FROM CITY_REV_ATT RA WHERE RA.CITYREVID = R.ID AND RA.TYP = 'DOC')" : "";
		
		$sql = "SELECT R.ID, R.USRID, R.CITYID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, 
					   R.WHERETOEAT, R.COOKING, R.WHERETOSTAY, R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME, 
					   R.XDIM, R.YDIM, U.NAME, U.COVERFILENAME, CT.CITYNAME,  C.COUNTRY, 
					   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
				FROM CITY_REVIEW R
					JOIN USER U ON U.ID = R.USRID
					JOIN CITY CT ON CT.ID = R.CITYID
					LEFT JOIN CITY_REV_STAR ST ON ST.REVID = R.ID, 
					COUNTRY C
				WHERE " .( $cityId != '' ? (" CT.ID =".$cityId) : "UPPER( CT.CITYNAME ) LIKE UPPER( '%" .$keyword. "%' )" ). "
				AND C.ID = CT.COUNTRYID
				AND R.LANGCODE = '" .$langCode. "'"
				. $imgJoin . $movJoin . $docJoin. "
				GROUP BY R.ID ". $this->getOrderByString($orderTp);
		
		Logger::log("CityReviewDAO :: searchReviews :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, 
							   $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, 
							   $xdim, $ydim, $userName, $userCoverFileName, $cityName, $country, $star, $see, $post);
			while($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				
				//Costruttore
				$reviewDO = New CityReviewDO($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, $whereToEat, $cooking, 
											 $whereToStay, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
				$reviewDO->setCityName($cityName);
				$reviewDO->setCountry($country);
				$reviewDO->setCntStar(isset($star)?$star:0);
				$reviewDO->setCntSee(isset($see)?$see:0);
				$reviewDO->setCntPost(isset($post)?$post:0);
				
				//push elemento
				array_push($reviewDOArray, $reviewDO);
			}
			$stmt->free_result();
		}
		//close
		$mysqli->close();
		return $reviewDOArray;
	}
	
	
	/* 
    * Ritorna un array di recensioni a partire dai parametri di ricerca 
	* valorizzati programmaticamente dopo l'autocomplete
	* Viene richiamato dopo il click sul cerca dell'header
	* @see searchReviewFromHeader.php
	*/
	public function searchReviewsPostAutocomplHeader($langCode, $cityName){
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$sql = "SELECT R.ID, R.USRID, R.CITYID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, 
					   R.WHERETOEAT, R.COOKING, R.WHERETOSTAY, R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME, 
					   R.XDIM, R.YDIM, U.NAME, U.COVERFILENAME, CT.CITYNAME,  C.COUNTRY, 
					   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
				FROM CITY_REVIEW R
					JOIN USER U ON U.ID = R.USRID
					JOIN CITY CT ON CT.ID = R.CITYID
					LEFT JOIN CITY_REV_STAR ST ON ST.REVID = R.ID, 
					COUNTRY C
				WHERE UPPER( CT.CITYNAME ) = UPPER( '" .$cityName. "' )
				AND C.ID = CT.COUNTRYID
				AND R.LANGCODE = '" .$langCode. "'
				GROUP BY R.ID";

		Logger::log("CityReviewDAO :: searchReviewsPostAutocomplHeader :: query: ".$sql, 3);
	
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, 
							   $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, 
							   $xdim, $ydim, $userName, $userCoverFileName, $cityName, $country, $star, $see, $post);
			while($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				
				//Costruttore
				$reviewDO = New CityReviewDO($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, $whereToEat, 
											 $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);
				
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
				$reviewDO->setCityName($cityName);
				$reviewDO->setCountry($country);
				$reviewDO->setCntStar(isset($star)?$star:0);
				$reviewDO->setCntSee(isset($see)?$see:0);
				$reviewDO->setCntPost(isset($post)?$post:0);
				
				//push elemento
				array_push($reviewDOArray, $reviewDO);
			}
			$stmt->free_result();
		}
		//close
		$mysqli->close();
		return $reviewDOArray;
	}
	
	/** 
	 * Inserisce una serie di file nella tabella CITY_REV_ATT 
	 * A differenza del metodo "insertFileArray" NON inserisce i Blob ma solo il path della directory 
	 * dove viene conservato su FileSystem
	 */
	public function insertFileArrayNoBlob($cityRevId, $usrId, &$filePathArray, &$fileNameArray, $typ, &$xDimArray, &$yDimArray, &$commentArray){
		$mysqli = $this->getConn();
		
		//Lock City Table
		$mysqli->query("LOCK TABLES CITY_REV_ATT WRITE");
		//$mysqli->autocommit(FALSE); 
		//$mysqli->begin_transaction()
		
		foreach ($filePathArray as $k => $v) {
			$xDim = $xDimArray == null ? 0 : $xDimArray[$k];
			$yDim = $yDimArray == null ? 0 : $yDimArray[$k];
			$sql = $this->insertFileNoBlob( $mysqli, $usrId, $cityRevId, $v, $fileNameArray[$k], $xDim, $yDim, $typ, $commentArray[$k] );
		}
		
		//$mysqli->commit()
		$mysqli->query("UNLOCK TABLES");
		
		//close
		$mysqli->close();
		return $sql;
	}

	/** Inserisce un nuovo reconr nella tabella CITY_REV_ATT (NON inserisce il Blob)*/
	private function insertFileNoBlob($mysqli, $usrId, $cityRevId, $filePath, $fileName, $xDim, $yDim, $typ, $comment){
		$sql = sprintf("INSERT INTO CITY_REV_ATT (CITYREVID, USRID, FILEPATH, FILENAME, XDIM, YDIM, TYP, COMMENT) 
						VALUES ( %d, %d, '%s', '%s', %d, %d, '%s', '%s')" , $cityRevId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
		
		Logger::log("CityReviewDAO :: insertFileNoBlob :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		return $sql;
	}
	
   /** 
	* Ritorna gli identificativi degli allegati di una data recensione per un dato tipo
	*/
	public function getAttachIdListByReviewIdAndType($cityRevId, $type){
		
		$key = $this->getAttachIdListByReviewIdAndTypeKey($cityRevId, $type);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$ids = array();
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT ID FROM CITY_REV_ATT WHERE CITYREVID = %d AND TYP = '%s' ORDER BY DTINS DESC", $cityRevId, $type);
		
		Logger::log("CityReviewDAO :: getAttachIdListByreviewIdAndType :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id);			
			while($stmt->fetch()) {
				array_push($ids, $id);
			}
			$stmt->free_result();
		}	
		//close
		$mysqli->close();
		
		//memcached not work if array is empty :(
		if (count($ids) == 0) array_push($ids, null);
		$this->setCached($key, $ids);
		return $ids;
    }
	
	/* return getAttachIdListByReviewIdAndType key */
	private function getAttachIdListByReviewIdAndTypeKey($cityRevId, $type){
		return "CityReviewDAO" . "getAttachIdListByReviewIdAndType" . $cityRevId . $type;
	}
	
	/** Ritorna un AttachDO a aprtire dall'id del file allegato */
	public function getAttachDOById($id) {
		
		$key = $this->getAttachDOByIdKey($id);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		$sql = sprintf("SELECT CITYREVID, USRID, FILEPATH, FILENAME, XDIM, YDIM, TYP, COMMENT FROM CITY_REV_ATT WHERE ID = %d", $id);
		
		Logger::log("CityReviewDAO :: getAttachDOById :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($cityRevId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
			if($stmt->fetch()) {
				$attachDO = New AttachDO($id, $cityRevId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
				$stmt->free_result();
				$mysqli->close();
				$this->setCached($key, $attachDO);
				return $attachDO;
			}
		}
		//close
		$mysqli->close();
		return;
	}	
	
	/* return getAttachDOById key */
	private function getAttachDOByIdKey($usrId){
		return "CityReviewDAO" . "getAttachDOById" . $usrId;
	}
}
?>
