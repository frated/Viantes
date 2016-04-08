<?php
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/review/attachDO.php";
require_once $X_root."pvt/pages/review/commonReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDO.php";

class CountryReviewDAO extends CommonReviewDAO {

    public function __construct(){
    }
	
   /** 
	* Crea una nuva recensione inserendo una riga nella tabella COUNTRY_REVIEW 
	* Ritorna l'id della recensione inserita
	*/
	public function createCountryReview($usrId, $countryId, $langCode, $descr, $arrive, $warn, $cook, $myth, $vote, $bean, &$interest){

		$coverFileName =  $bean->getCoverFileName();
		$xdim =  $bean->getCoverWidth();
		$ydim =  $bean->getCoverHeight();
									  
		$mysqli = $this->getConn();
		
		//$mysqli->autocommit(FALSE); 
		//$mysqli->begin_transaction()
		
		//inserts HTML line breaks before all newlines
		$formattedDescr = nl2br($descr);
		
		$mysqli->query("LOCK TABLES COUNTRY_REVIEW WRITE");
		
		//insert 
		$sql = sprintf("INSERT INTO COUNTRY_REVIEW (USRID, COUNTRYID, LANGCODE, DESCR, HOWTOARRIVE, WARNING, COOKING,  MYTH, VOTE, COVERFILENAME, XDIM, YDIM) 
						VALUES (%d, %d, '%s', '%s', '%s', '%s', '%s', '%s', %d, '%s', %d, %d)",
						$usrId, $countryId, $langCode, $formattedDescr, $arrive, $warn, $cook, $myth, $vote, $coverFileName, $xdim, $ydim);
		Logger::log("CountryReviewDAO :: createReview :: query: ".$sql, 3);
		
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
	 * Inserisce le citta' di interesse di una nazione/regione
	 */
	private function insertInterest($mysqli, $inter, $countryRevid){
		//Insert 
		foreach ($inter as $k => $v) {
			$revid = explode(attributeDelim, $v)[0];
			$sql = sprintf("INSERT INTO CITY_REV_COUNTRY_REV (COUNTRYREVID, CITYREVID) VALUES (%d, %d )", $countryRevid, $revid);
			$mysqli->query($sql);
			Logger::log("CountryReviewDAO :: insertInterest :: query: ".$sql, 3);
		}
		return true;
	}

	/**
	 * Inserisce i luoghi di interesse della nazione
	 */
	public function getInterestByRevId($countryRevid){
		
		$key = $this->getInterestByRevIdKey($countryRevid);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$sql = sprintf("SELECT R.ID, R.COVERFILENAME, C.CITYNAME
						FROM CITY_REVIEW R 
							JOIN CITY_REV_COUNTRY_REV CRCR ON R.ID = CRCR.CITYREVID 
							JOIN CITY C ON R.CITYID = C.ID 
						WHERE CRCR.COUNTRYREVID = %d
						ORDER BY DTINS DESC", $countryRevid);
		
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
		return "CountryReviewDAO" . "getInterestByRevId" . $cityRevid;
	}

	
   /** 
	* Ritorna tutte le recensioni dell'utente
	*/
	public function getReviewList($usrId,  $pattern){
		
		$key = $this->getReviewListKey($usrId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT R.ID, R.USRID, R.COUNTRYID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, R.COOKING, 
							   R.MYTH, DATE_FORMAT(R.DTINS, '%s'), R.VOTE, R.COVERFILENAME, U.NAME, U.COVERFILENAME, C.COUNTRY
						FROM COUNTRY_REVIEW R 
							JOIN COUNTRY C ON R.COUNTRYID = C.ID
							JOIN USER U ON U.ID = R.USRID
						WHERE R.USRID = %d 
						AND C.ID = R.COUNTRYID
						ORDER BY R.DTINS DESC", $pattern, $usrId);
		Logger::log("CountryReviewDAO :: getReviewList :: query: ".$sql, 3);
	
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, $cooking,
							   $myth, $dtIns, $vote, $coverFileName, $userName, $userCoverFileName, $country);
			while($stmt->fetch()) {
				//echo $descr() . "<br>" ;
				$descr = str_replace("\r\n",'<br>', $descr);
				
				//Costruttore
				$reviewDO = New CountryReviewDO($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, $cooking, $myth, $dtIns, $vote, $coverFileName);
				
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
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
		return "CountryReviewDAO" . "getReviewList" . $usrId;
	}
	
	/** Ritorna una recensione a partire dall'id */
	public function getReviewById($revId) {	
	
		$key = $this->getReviewByIdKey($revId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT R.ID, R.USRID, R.COUNTRYID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, R.COOKING, 
							   R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME, R.XDIM, R.YDIM, U.NAME, U.COVERFILENAME, C.COUNTRY, 
							   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
						FROM COUNTRY_REVIEW R 
							JOIN COUNTRY C ON R.COUNTRYID = C.ID
							JOIN USER U ON U.ID = R.USRID
							LEFT JOIN COUNTRY_REV_STAR ST ON ST.REVID = R.ID
						WHERE R.ID = %d 
						AND C.ID = R.COUNTRYID
						ORDER BY DTINS DESC", $revId);
		Logger::log("CountryReviewDAO :: getReviewById :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, $cooking, $myth, $dtIns, $vote, 
							   $coverFileName, $xdim, $ydim, $userName, $userCoverFileName, $country, $star, $see, $post);
			if($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				//Costruttore
				$reviewDO = New CountryReviewDO($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, $cooking, $myth, 
												$dtIns, $vote, $coverFileName, $xdim, $ydim);
										
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
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
		return "CountryReviewDAO" . "getReviewById" . $revId;
	}
	
	/* Ritorna il numero di nazioni (non assoluto ma distinct per nazione) che incontrano i criteri di ricerca. 
	 * Da usare prima della @searchReviews($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc) per capire 
	 * se il risultato e' unico (nel senso che ho "n" recensioni ma di un solo sito) o meno. */
	public function searchReviewsCount($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc){
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$imgJoin = $onlyImg == "1" ? " AND EXISTS (SELECT ID FROM COUNTRY_REV_ATT RA WHERE RA.COUNTRYREVID = R.ID AND RA.TYP = 'IMG')" : "";
		$movJoin = $onlyMov == "1" ? " AND EXISTS (SELECT ID FROM COUNTRY_REV_ATT RA WHERE RA.COUNTRYREVID = R.ID AND RA.TYP = 'MOV')" : "";
		$docJoin = $onlyDoc == "1" ? " AND EXISTS (SELECT ID FROM COUNTRY_REV_ATT RA WHERE RA.COUNTRYREVID = R.ID AND RA.TYP = 'DOC')" : "";
		
		$sql = "SELECT DISTINCT (C.ID), C.COUNTRY
				FROM COUNTRY_REVIEW R JOIN COUNTRY C ON R.COUNTRYID = C.ID
				WHERE UPPER( C.COUNTRY ) LIKE UPPER( '%" .$keyword. "%' )
				AND R.LANGCODE = '" .$langCode. "'"
				. $imgJoin . $movJoin . $docJoin;
		
		Logger::log("CountryReviewDAO :: searchReviewsCount :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($countryId, $country);
			while($stmt->fetch()) {
				//Costruttore
				$reviewDO = New CountryReviewDO(-1, "", $countryId, "", "", "", "", "", "", "", 3, "");
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
	 * @see searchReviewFromAdv.php
	 * @see searchReviewFromMultiResult.php
	 */
	public function searchReviews($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc, $orderTp, $countryId = ''){
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$imgJoin = $onlyImg == "1" ? " AND EXISTS (SELECT ID FROM COUNTRY_REV_ATT RA WHERE RA.COUNTRYREVID = R.ID AND RA.TYP = 'IMG')" : "";
		$movJoin = $onlyMov == "1" ? " AND EXISTS (SELECT ID FROM COUNTRY_REV_ATT RA WHERE RA.COUNTRYREVID = R.ID AND RA.TYP = 'MOV')" : "";
		$docJoin = $onlyDoc == "1" ? " AND EXISTS (SELECT ID FROM COUNTRY_REV_ATT RA WHERE RA.COUNTRYREVID = R.ID AND RA.TYP = 'DOC')" : "";
		
		$sql = "SELECT R.ID, R.USRID, R.COUNTRYID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, R.COOKING,
					   R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME, R.XDIM, R.YDIM, U.NAME, U.COVERFILENAME, C.COUNTRY, 
					   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
				FROM COUNTRY_REVIEW R 
					JOIN COUNTRY C ON R.COUNTRYID = C.ID
					JOIN USER U ON U.ID = R.USRID
					LEFT JOIN COUNTRY_REV_STAR ST ON ST.REVID = R.ID
				WHERE " .( $countryId != '' ? (" C.ID =".$countryId) : "UPPER( C.COUNTRY ) LIKE UPPER( '%" .$keyword. "%' )" ). "
				AND R.LANGCODE = '" .$langCode. "'"
				. $imgJoin . $movJoin . $docJoin. "
				GROUP BY R.ID ". $this->getOrderByString($orderTp);
		
		Logger::log("CountryReviewDAO :: searchReviews :: query: ".$sql, 3);
		//echo $sql; exit;
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, $cooking,$myth, 
							   $dtIns, $vote, $coverFileName, $xdim, $ydim, $userName, $userCoverFileName, $country, $star, $see, $post);
			while($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				//Costruttore
				$reviewDO = New CountryReviewDO($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, $cooking, 
												$myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);
				
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
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
	* valorizzati programmaticamente dopo l'autocomplete. 
	* Viene richiamato dopo il click sul cerca dell'header
	* @see searchReviewFromHeader.php
	*/
	public function searchReviewsPostAutocomplHeader($langCode, $countryName){
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$sql = "SELECT R.ID, R.USRID, R.COUNTRYID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, R.COOKING,
					   R.XDIM, R.YDIM, R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME, U.NAME, U.COVERFILENAME, C.COUNTRY, 
					   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
				FROM COUNTRY_REVIEW R 
					JOIN COUNTRY C ON R.COUNTRYID = C.ID
					JOIN USER U ON U.ID = R.USRID
					LEFT JOIN COUNTRY_REV_STAR ST ON ST.REVID = R.ID
				WHERE UPPER( C.COUNTRY ) = UPPER( '" .$countryName. "' )
				AND R.LANGCODE = '" .$langCode. "'
				GROUP BY R.ID";
		
		Logger::log("CountryReviewDAO :: searchReviews :: query: ".$sql, 3);
	
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, $cooking, $myth, $dtIns, 
							   $vote, $coverFileName, $xdim, $ydim, $userName, $userCoverFileName, $country, $star, $see, $post);
			while($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				//Costruttore
				$reviewDO = New CountryReviewDO($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, $cooking, 
												$myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
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
	 * Inserisce una serie di file nella tabella COUNTRY_REV_ATT 
	 * A differenza del metodo "insertFileArray" NON inserisce i Blob ma solo il path della directory 
	 * dove viene conservato su FileSystem
	 */
	public function insertFileArrayNoBlob($countryRevId, $usrId, &$filePathArray, &$fileNameArray, $typ, &$xDimArray, &$yDimArray, &$commentArray){
		$mysqli = $this->getConn();
		
		//Lock City Table
		$mysqli->query("LOCK TABLES COUNTRY_REV_ATT WRITE");
		//$mysqli->autocommit(FALSE); 
		//$mysqli->begin_transaction()
		
		foreach ($filePathArray as $k => $v) {
			$xDim = $xDimArray == null ? 0 : $xDimArray[$k];
			$yDim = $yDimArray == null ? 0 : $yDimArray[$k];
			$sql = $this->insertFileNoBlob( $mysqli, $usrId, $countryRevId, $v, $fileNameArray[$k], $xDim, $yDim, $typ, $commentArray[$k] );
		}
		
		//$mysqli->commit()
		$mysqli->query("UNLOCK TABLES");
		
		//close
		$mysqli->close();
		return $sql;
	}

	/** Inserisce un nuovo reconr nella tabella COUNTRY_REV_ATT (NON inserisce il Blob)*/
	private function insertFileNoBlob($mysqli, $usrId, $countryRevId, $filePath, $fileName, $xDim, $yDim, $typ, $comment){
		$sql = sprintf("INSERT INTO COUNTRY_REV_ATT (COUNTRYREVID, USRID, FILEPATH, FILENAME, XDIM, YDIM, TYP, COMMENT) 
						VALUES ( %d, %d, '%s', '%s', %d, %d, '%s', '%s')" , $countryRevId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
		
		Logger::log("CountryReviewDAO :: insertFileNoBlob :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		return $sql;
	}
	
   /** 
	* Ritorna gli identificativi degli allegati di una data recensione per un dato tipo
	*/
	public function getAttachIdListByReviewIdAndType($countryRevId, $type){
		
		$key = $this->getAttachIdListByReviewIdAndTypeKey($countryRevId, $type);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$ids = array();
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT ID FROM COUNTRY_REV_ATT WHERE COUNTRYREVID = %d AND TYP = '%s' ORDER BY DTINS DESC", $countryRevId, $type);
		
		Logger::log("CountryReviewDAO :: getAttachIdListByreviewIdAndType :: query: ".$sql, 3);
		
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
	private function getAttachIdListByReviewIdAndTypeKey($countryRevId, $type){
		return "CountryReviewDAO" . "getAttachIdListByReviewIdAndType" . $countryRevId . $type;
	}
	
	/** Ritorna un AttachDO a aprtire dall'id del file allegato */
	public function getAttachDOById($id) {	
	
		$key = $this->getAttachDOByIdKey($id);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		$sql = sprintf("SELECT COUNTRYREVID, USRID, FILEPATH, FILENAME, XDIM, YDIM, TYP, COMMENT FROM COUNTRY_REV_ATT WHERE ID = %d", $id);
		
		Logger::log("CountryReviewDAO :: getAttachDOById :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($countryRevId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
			if($stmt->fetch()) {
				$attachDO = New AttachDO($id, $countryRevId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
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
		return "CountryReviewDAO" . "getAttachDOById" . $usrId;
	}
}
?>
