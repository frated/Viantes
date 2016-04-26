<?php
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/review/attachDO.php";
require_once $X_root."pvt/pages/review/reviewDO.php";
require_once $X_root."pvt/pages/review/cityReviewDO.php";
require_once $X_root."pvt/pages/review/commonReviewDAO.php";
require_once $X_root."pvt/pages/review/countryReviewDO.php";

class ReviewDAO extends CommonReviewDAO {

    public function __construct(){
    }
	
   /** 
	* Crea una nuva recensione inserendo una riga nella tabella REVIEW 
	* Ritorna l'id della recensione inserita
	*/
	public function createReview($usrId, $siteId, $catRev, $langCode, $descr, $arrive, $warn, $whEat, $cook, $whStay, $myth, $vote, $bean){
		$coverFileName =  $bean->getCoverFileName();
		$xdim =  $bean->getCoverWidth();
		$ydim =  $bean->getCoverHeight();

		//inserts HTML line breaks before all newlines
		$formattedDescr = nl2br($descr);

		$mysqli = $this->getConn();		
		
		//insert 
		$sql = sprintf("INSERT INTO REVIEW (USRID, SITEID, CATREVID, LANGCODE, DESCR, HOWTOARRIVE, WARNING,
											WHERETOEAT, COOKING, WHERETOSTAY, MYTH, VOTE, COVERFILENAME, XDIM, YDIM) 
						VALUES (%d, %d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, '%s', %d, %d)",
						$usrId, $siteId, $catRev, $langCode, $formattedDescr, $arrive, $warn, 
						$whEat, $cook, $whStay, $myth, $vote, $coverFileName, $xdim, $ydim);
		Logger::log("ReviewDAO :: createReview :: query: ".$sql, 3);
		
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
				$mysqli->close();
				return $id;
			}
		}
		//close
		$mysqli->close();
		return -1;
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
		
		$sql = sprintf("SELECT R.ID, R.USRID, R.SITEID, R.CATREVID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, 
							   R.WHERETOEAT, R.COOKING, R.WHERETOSTAY, R.MYTH, DATE_FORMAT(R.DTINS, '%s'), 
							   R.VOTE, R.COVERFILENAME, U.NAME, U.COVERFILENAME, S.SITENAME, 
							   S.LOCALITY, C.COUNTRY
						FROM REVIEW R 
							JOIN SITE S ON R.SITEID = S.ID 
							JOIN USER U ON U.ID = R.USRID,
							COUNTRY C
						WHERE R.USRID = %d 
						AND C.ID = S.COUNTRYID
						ORDER BY R.DTINS DESC",  $pattern, $usrId);
		Logger::log("ReviewDAO :: getReviewList :: query: ".$sql, 3);
	
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, 
							   $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, $userName, $userCoverFileName, $siteName, 
							   $localityName, $country);
			while($stmt->fetch()) {
				//echo $descr() . "<br>" ;
				$descr = str_replace("\r\n",'<br>', $descr);
				
				//Costruttore
				$reviewDO = New ReviewDO($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, $whereToEat,  $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName);
				
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
				$reviewDO->setSiteName($siteName);
				$reviewDO->setLocality($localityName);
				$reviewDO->setCountry($country);
				
				$reviewDO->setLocality($localityName);
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
		return "ReviewDAO" . "getReviewList" . $usrId;
	}
	
	/** Ritorna una recensione a partire dall'id */
	public function getReviewById($revId) {	
		
		$key = $this->getReviewByIdKey($revId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT R.ID, R.USRID, R.SITEID, R.CATREVID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, 
							   R.WHERETOEAT, R.COOKING, R.WHERETOSTAY, R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME, 
							   R.XDIM, R.YDIM, U.NAME, U.COVERFILENAME, S.SITENAME, S.LOCALITY, C.COUNTRY, G.PLACEID,
							   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
						FROM REVIEW R 
							JOIN SITE S ON R.SITEID = S.ID
							JOIN GEO_SITE G ON G.ID = S.GEOSITEID
							JOIN USER U ON U.ID = R.USRID
							LEFT JOIN REVIEW_STAR ST ON ST.REVID = R.ID,
							COUNTRY C
						WHERE R.ID = %d 
						AND C.ID = S.COUNTRYID
						ORDER BY DTINS DESC", $revId);
		Logger::log("ReviewDAO :: getReviewById :: query: ".$sql, 3);
		//echo $sql; exit;
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, 
							   $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim,
							   $userName, $userCoverFileName, $siteName, $localityName, $country, $placeId, $star, $see, $post);
			if($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				//Costruttore
				$reviewDO = New ReviewDO($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, $whereToEat,  $cooking, 
										 $whereToStay, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);

				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
				$reviewDO->setSiteName($siteName);
				$reviewDO->setLocality($localityName);
				$reviewDO->setCountry($country);
				$reviewDO->setPlaceId($placeId);
				
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
		return "ReviewDAO" . "getReviewById" . $revId;
	}
	
	/* Ritorna il numero di siti (non assoluto ma distinct per sito) che incontrano i criteri di ricerca. 
	 * Da usare prima della @searchReviews($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc) per capire 
	 * se il risultato e' unico (nel senso che ho "n" recensioni ma di un solo sito) o meno. */
	public function searchReviewsCount($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc){
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$imgJoin = $onlyImg == "1" ? " AND EXISTS (SELECT ID FROM REVIEW_ATTACH RA WHERE RA.REVIEWID = R.ID AND RA.TYP = 'IMG')" : "";
		$movJoin = $onlyMov == "1" ? " AND EXISTS (SELECT ID FROM REVIEW_ATTACH RA WHERE RA.REVIEWID = R.ID AND RA.TYP = 'MOV')" : "";
		$docJoin = $onlyDoc == "1" ? " AND EXISTS (SELECT ID FROM REVIEW_ATTACH RA WHERE RA.REVIEWID = R.ID AND RA.TYP = 'DOC')" : "";
		
		$sql = "SELECT DISTINCT (S.ID), S.SITENAME, S.LOCALITY, C.COUNTRY
				FROM REVIEW R JOIN SITE S ON S.ID = R.SITEID, COUNTRY C
				WHERE UPPER( S.SITENAME ) LIKE UPPER( '%" .$keyword. "%' )
				AND C.ID = S.COUNTRYID
				AND R.LANGCODE = '" .$langCode. "'"
				. $imgJoin . $movJoin . $docJoin;
		
		Logger::log("ReviewDAO :: searchReviewsCount :: query: ".$sql, 3);
	
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($siteId, $siteName, $localityName, $country);
			while($stmt->fetch()) {
				//Costruttore
				$reviewDO = New ReviewDO(-1, "", $siteId, -1, "", "", "", "", "",  "", "", "","", 3, "");
				
				$reviewDO->setSiteName($siteName);
				$reviewDO->setLocality($localityName);
				$reviewDO->setCountry($country);
				
				//push elemento
				array_push($reviewDOArray, $reviewDO);
			}
			$stmt->free_result();
		}
		$mysqli->close();
		return $reviewDOArray;
	}
	
	
	/* 
	 * Ritorna un array di recensioni a partire dai parametri di ricerca 
	 * @see searchReviewFromAdv.php ($siteId NON valorizzato)
	 * @see searchReviewFromMultiResult.php ($siteId NON valorizzato)
	 */
	public function searchReviews($langCode, $keyword, $onlyImg, $onlyMov, $onlyDoc, $orderTp, $siteId = ''){
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$imgJoin = $onlyImg == "1" ? " AND EXISTS (SELECT ID FROM REVIEW_ATTACH RA WHERE RA.REVIEWID = R.ID AND RA.TYP = 'IMG')" : "";
		$movJoin = $onlyMov == "1" ? " AND EXISTS (SELECT ID FROM REVIEW_ATTACH RA WHERE RA.REVIEWID = R.ID AND RA.TYP = 'MOV')" : "";
		$docJoin = $onlyDoc == "1" ? " AND EXISTS (SELECT ID FROM REVIEW_ATTACH RA WHERE RA.REVIEWID = R.ID AND RA.TYP = 'DOC')" : "";
		
		$sql = "SELECT R.ID, R.USRID, R.SITEID, R.CATREVID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING,
					   R.WHERETOEAT, R.COOKING, R.WHERETOSTAY, R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME,
					   R.XDIM, R.YDIM, U.NAME, U.COVERFILENAME, S.SITENAME, S.LOCALITY, C.COUNTRY, G.PLACEID,
					   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
				FROM REVIEW R
					JOIN USER U ON U.ID = R.USRID
					JOIN SITE S ON S.ID = R.SITEID
					JOIN GEO_SITE G ON G.ID = S.GEOSITEID
					LEFT JOIN REVIEW_STAR ST ON ST.REVID = R.ID,
					COUNTRY C
				WHERE " .( $siteId != '' ? (" S.ID =".$siteId) : "UPPER( S.SITENAME ) LIKE UPPER( '%" .$keyword. "%' )" ). "
				AND C.ID = S.COUNTRYID
				AND R.LANGCODE = '" .$langCode. "'"
				. $imgJoin . $movJoin . $docJoin. "
				GROUP BY R.ID ". $this->getOrderByString($orderTp);
		
		Logger::log("ReviewDAO :: searchReviews :: query: ".$sql, 3);
		//echo $sql; exit;
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, 
							   $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, 
							   $xdim, $ydim, $userName, $userCoverFileName, $siteName, $localityName, $country, 
							   $placeId, $star, $see, $post);
			while($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				//Costruttore
				$reviewDO = New ReviewDO($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, $whereToEat,  
										 $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);
				
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
				$reviewDO->setSiteName($siteName);
				$reviewDO->setLocality($localityName);
				$reviewDO->setCountry($country);
				$reviewDO->setPlaceId($placeId);
				
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
	public function searchReviewsPostAutocomplHeader($langCode, $siteName){
		
		$reviewDOArray = array();
		
		$mysqli = $this->getConn();
		
		$sql = "SELECT R.ID, R.USRID, R.SITEID, R.CATREVID, R.LANGCODE, R.DESCR, R.HOWTOARRIVE, R.WARNING, 
					   R.WHERETOEAT, R.COOKING, R.WHERETOSTAY, R.MYTH, R.DTINS, R.VOTE, R.COVERFILENAME, 
					   R.XDIM, R.YDIM, U.NAME, U.COVERFILENAME, S.SITENAME, S.LOCALITY, C.COUNTRY, G.PLACEID,
					   SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
				FROM REVIEW R
					JOIN USER U ON U.ID = R.USRID
					JOIN SITE S ON S.ID = R.SITEID
					JOIN GEO_SITE G ON G.ID = S.GEOSITEID
					LEFT JOIN REVIEW_STAR ST ON ST.REVID = R.ID,
					COUNTRY C
				WHERE UPPER( S.SITENAME ) = UPPER( '" .$siteName. "' )
				AND C.ID = S.COUNTRYID
				AND R.LANGCODE = '" .$langCode. "'
				GROUP BY R.ID";
		
		Logger::log("ReviewDAO :: searchReviewsPostAutocomplHeader :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, 
							   $whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, 
							   $xdim, $ydim, $userName, $userCoverFileName, $siteName, $localityName, $country, 
							   $placeId, $star, $see, $post);
			while($stmt->fetch()) {
				$descr = str_replace("\r\n",'<br>', $descr);
				//Costruttore
				$reviewDO = New ReviewDO($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, $whereToEat,  
										 $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);
				
				//Setto i campi in relazione
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
				$reviewDO->setSiteName($siteName);
				$reviewDO->setLocality($localityName);
				$reviewDO->setCountry($country);
				$reviewDO->setPlaceId($placeId);
				
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
	
	/** Inserisce una serie di file nella tabella ART_ATTACH *
	public function insertFileArray($reviewId, $dataFileArray, $fileNameArray, $typArray){
		$mysqli = $this->getConn();
		
		foreach ($dataFileArray as $k => $v) {
			$sql = $this->insertFile( $mysqli, $reviewId, $v, $fileNameArray[$k], $typArray[$k] );
		}
		
		//close
		$mysqli->close();
		return $sql;
	}*/

	/** Inserisce un nuovo file nella tabella REVIEW_ATTACH *
	private function insertFile($mysqli, $reviewId, $dataFile, $fileName, $typ){
		$sql = sprintf("INSERT INTO REVIEW_ATTACH (ARTICLEID, DATAFILE, FILENAME, TYP) 
						VALUES ( %d, '%s', '%s','%s')" , $reviewId, $dataFile, $fileName, $typ);
		
		Logger::log("ReviewDAO :: insertFile :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		return $sql;
	}*/
		
	
	/** 
	 * Inserisce una serie di file nella tabella REVIEW_ATTACH 
	 * A differenza del metodo "insertFileArray" NON inserisce i Blob ma solo il path della directory 
	 * dove viene conservato su FileSystem
	 */
	public function insertFileArrayNoBlob($reviewId, $usrId, &$filePathArray, &$fileNameArray, $typ, &$xDimArray, &$yDimArray, &$commentArray){
		$mysqli = $this->getConn();
		
		//Lock City Table
		$mysqli->query("LOCK TABLES REVIEW_ATTACH WRITE");
		//$mysqli->autocommit(FALSE); 
		//$mysqli->begin_transaction()
		
		foreach ($filePathArray as $k => $v) {
			$xDim = $xDimArray == null ? 0 : $xDimArray[$k];
			$yDim = $yDimArray == null ? 0 : $yDimArray[$k];
			$sql = $this->insertFileNoBlob( $mysqli, $usrId, $reviewId, $v, $fileNameArray[$k], $xDim, $yDim, $typ, $commentArray[$k] );
		}
		
		//$mysqli->commit()
		$mysqli->query("UNLOCK TABLES");

		//close
		$mysqli->close();
		return $sql;
	}

	/** Inserisce un nuovo reconr nella tabella REVIEW_ATTACH (NON inserisce il Blob)*/
	private function insertFileNoBlob($mysqli, $usrId, $reviewId, $filePath, $fileName, $xDim, $yDim, $typ, $comment){
		$sql = sprintf("INSERT INTO REVIEW_ATTACH (REVIEWID, USRID, FILEPATH, FILENAME, XDIM, YDIM, TYP, COMMENT) 
						VALUES ( %d, %d, '%s', '%s', %d, %d, '%s', '%s')" , $reviewId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
		
		Logger::log("ReviewDAO :: insertFileNoBlob :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		return $sql;
	}
	
   /** 
	* Ritorna gli identificativi degli allegati di una data recensione per un dato tipo
	*/
	public function getAttachIdListByReviewIdAndType($reviewId, $type){
		
		$key = $this->getAttachIdListByReviewIdAndTypeKey($reviewId, $type);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$ids = array();
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT ID FROM REVIEW_ATTACH WHERE REVIEWID = %d AND TYP = '%s' ORDER BY DTINS DESC", $reviewId, $type);
		
		Logger::log("ReviewDAO :: getAttachIdListByreviewIdAndType :: query: ".$sql, 3);
		
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
	private function getAttachIdListByReviewIdAndTypeKey($reviewId, $type){
		return "ReviewDAO" . "getAttachIdListByReviewIdAndType" . $reviewId . $type;
	}
	
	/** Ritorna un dato di tipo BLOB a aprtire dall'id del file allegato *
	public function getAttachById($id) {	
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT DATAFILE FROM REVIEW_ATTACH WHERE ID = %d", $id);
		
		Logger::log("ReviewDAO :: getAttachById :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($dataFile);
			if($stmt->fetch()) {
				$stmt->free_result();
				$mysqli->close();
				return $dataFile;
			}
		}
		
		//close
		$mysqli->close();
		return;
	}*/
	
	/** Ritorna un AttachDO a aprtire dall'id del file allegato */
	public function getAttachDOById($id) {
		
		$key = $this->getAttachDOByIdKey($id);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		$sql = sprintf("SELECT REVIEWID, USRID, FILEPATH, FILENAME, XDIM, YDIM, TYP, COMMENT FROM REVIEW_ATTACH WHERE ID = %d", $id);
		
		Logger::log("ReviewDAO :: getAttachDOById :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($reviewId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
			if($stmt->fetch()) {
				$attachDO = New AttachDO($id, $reviewId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment);
				$stmt->free_result();
				
				//close and set in cache
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
		return "ReviewDAO" . "getAttachDOById" . $usrId;
	}
}
?>
