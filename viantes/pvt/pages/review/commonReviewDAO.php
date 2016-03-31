<?php
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/auth/starDO.php";
require_once $X_root."pvt/pages/common/commonDAO.php";
require_once $X_root."pvt/pages/log/log.php";

class CommonReviewDAO extends CommonDAO {
	
    public function __construct(){
    }

   /** 
	* Ritorna un numero pari a numTotRev di recensioni il cui id e' minore al reviewId
	* Se reviewId = -1 ritorna gli ultimi numTotRev recensioni.
	*/
	public function getReviews($reviewId, $cityRevId, $countryRevId, $numTotRev, $mode){
		$compare = $mode == "PUSH" ? " > " : " < ";
		
		//Condizione sulla tabella REVIEW
		$lastCondition = "";
		if ($reviewId != -1) $lastCondition = "AND R.ID ". $compare. " ".$reviewId ;
		
		//Condizione sulla tabella CITY_REV
		$lastConditionCity = "";
		if ($cityRevId != -1) $lastConditionCity = "AND R.ID ". $compare. " ".$cityRevId ;
		
		//Condizione sulla tabella COUNTRY_REV
		$lastConditionCountry = "";
		if ($countryRevId != -1) $lastConditionCountry = "AND R.ID ". $compare. " ".$countryRevId ;
		
		$reviewDOArray = array();
		// LIMIT 0, %d , $numTotRev
		
		$mysqli = $this->getConn();
			$sql = sprintf("SELECT * FROM (
								SELECT R.ID, R.USRID, R.SITEID, R.CATREVID, R.LANGCODE, R.DESCR, R.DTINS, R.COVERFILENAME, U.NAME, U.COVERFILENAME as usrCover, 
									   S.SITENAME, S.LOCALITY, 1, SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
								FROM REVIEW R
									JOIN USER U ON U.ID = R.USRID
									LEFT JOIN REVIEW_STAR ST ON ST.REVID = R.ID
									JOIN SITE S ON S.ID = R.SITEID %s
								GROUP BY R.ID
								UNION 
								SELECT R.ID, R.USRID, R.CITYID, -1, R.LANGCODE, R.DESCR, R.DTINS, R.COVERFILENAME, U.NAME, U.COVERFILENAME as usrCover, 
									   C.CITYNAME, C.CITYNAME, 2, SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
								FROM CITY_REVIEW R
									JOIN USER U ON U.ID = R.USRID
									LEFT JOIN CITY_REV_STAR ST ON ST.REVID = R.ID
									JOIN CITY C ON C.ID = R.CITYID %s
								GROUP BY R.ID
								UNION
								SELECT R.ID, R.USRID, R.COUNTRYID, -1, R.LANGCODE, R.DESCR, R.DTINS, R.COVERFILENAME, U.NAME, U.COVERFILENAME as usrCover, 
									   C.COUNTRY, C.COUNTRY, 3, SUM(ST.STAR) AS STAR, SUM(ST.SEE) AS SEE, SUM( CASE WHEN (POST = '' OR POST IS NULL ) THEN 0 ELSE 1 END ) AS POST
								FROM COUNTRY_REVIEW R
									JOIN USER U ON U.ID = R.USRID
									LEFT JOIN COUNTRY_REV_STAR ST ON ST.REVID = R.ID
									JOIN COUNTRY C ON C.ID = R.COUNTRYID %s
								GROUP BY R.ID
							) REV
							ORDER BY REV.DTINS DESC 
							LIMIT 0, %d"
						, $lastCondition, $lastConditionCity, $lastConditionCountry, $numTotRev);
			   
		Logger::log("ReviewDAO :: getReviews :: query: ".$sql, 3);
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $usrId, $siteId, $catRevId, $langCode, $descr, $dtIns, $coverFileName, $userName, 
							   $userCoverFileName, $siteName, $localityName, $revType, $star, $see, $post);
			while($stmt->fetch()) {
				
				$descr = str_replace("\r\n",'<br>', $descr);
				
				if ($revType == SiteReview) {
					//Costruttore
					$reviewDO = New ReviewDO($id, $usrId, $siteId, $catRevId, $langCode, $descr, "", "", "",  "", "", "", $dtIns, 0, $coverFileName);
					$reviewDO->setSiteName($siteName);
					$reviewDO->setLocality($localityName);
				}
				else if ($revType == CityReview) {
					//Costruttore
					$reviewDO = New CityReviewDO($id, $usrId, -1, $langCode, $descr, "", "", "", "",  "", "", $dtIns, 0, $coverFileName);
					$reviewDO->setCityName($siteName);
				} else if ($revType == CountryReview) {
					//Costruttore
					$reviewDO = New CountryReviewDO($id, $usrId, -1, $langCode, $descr, "", "", "", "", $dtIns, 0, $coverFileName);
					$reviewDO->setCountry($siteName);
				}				
				//campi comuni
				$reviewDO->setUsrName($userName);
				$reviewDO->setUserCoverFileName($userCoverFileName);
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
	* Usato dallo script asincrono per implementare l'autocomplete nell'header
	*/
	public function searchReviews4AutocomplHeader($langCode, $keyword){
		
		$limit = Conf::getInstance()->get('maxResAutocomplHeader');
		
		$autocomplArray = array();
		
		$mysqli = $this->getConn();
		
		$sql = "SELECT S.SITENAME as NAME, ".SiteReview." as TYPE, S.LOCALITY AS LOC
				FROM SITE S JOIN REVIEW R ON S.ID = R.SITEID
				WHERE UPPER( S.SITENAME ) LIKE UPPER( '%" .$keyword. "%' )
				AND S.LANGCODE = '" .$langCode. "' 
				UNION
				SELECT CT.CITYNAME as NAME, ".CityReview." as TYPE, C.COUNTRY AS LOC
				FROM CITY CT JOIN COUNTRY C on CT.COUNTRYID = C.ID
				WHERE (
					UPPER( CT.CITYNAME ) LIKE UPPER( '%" .$keyword. "%' ) OR
					UPPER( C.COUNTRY ) LIKE UPPER( '%" .$keyword. "%' )
				)
				AND CT.LANGCODE = '" .$langCode. "'
				UNION
				SELECT C.COUNTRY as NAME, ".CountryReview." as TYPE, C.COUNTRY as LOC
				FROM COUNTRY C
				WHERE UPPER( C.COUNTRY ) LIKE UPPER( '%" .$keyword. "%' )
				AND C.LANGCODE = '" .$langCode. "'
				LIMIT ".$limit;
				
		Logger::log("ReviewDAO :: searchReviews4AutocomplHeader :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($name, $type, $locality);
			while($stmt->fetch()) {
				$row = array();
				$row['name'] = $name .attributeDelim. $locality;
				$row['type'] = $type;
				array_push($autocomplArray, $row);
			}
		}
		
		//close
		$mysqli->close();
		return $autocomplArray;
	}
	
	
   /** 
	* Ritorna un array di 5 elementi contenente il numero di voti per ogni positione.
	*/
	public function getRateArray($siteId, $type){
		
		$key = $this->getRateArrayKey($siteId, $type);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$rates = array();
		
		$mysqli = $this->getConn();
		
		if ($type == SiteReview)
			$sql = sprintf("SELECT VOTE, COUNT(VOTE) FROM REVIEW  WHERE SITEID = %d  GROUP BY VOTE ORDER BY VOTE DESC", $siteId);
		else if ($type == CityReview) 
			$sql = sprintf("SELECT VOTE, COUNT(VOTE) FROM CITY_REVIEW  WHERE CITYID = %d  GROUP BY VOTE ORDER BY VOTE DESC", $siteId);
		else if ($type == CountryReview)
			$sql = sprintf("SELECT VOTE, COUNT(VOTE) FROM COUNTRY_REVIEW  WHERE COUNTRYID = %d  GROUP BY VOTE ORDER BY VOTE DESC", $siteId);
		
		Logger::log("CommonReviewDAO :: getRateArray :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($vote, $count);			
			while($stmt->fetch()) {
				$rates[$vote] =  $count;
			}
			$stmt->free_result();
		}
		
		//close
		$mysqli->close();
		
		//memcached not work if array is empty :(
		//non dovrebbe mai succedere che il risultato sia un array di 0 elementi
		if (count($rates) == 0) array_push($rates, null);
		$this->setCached($key, $rates);
		return $rates;
	}
	
	/* return getRateArray key */
	private function getRateArrayKey($siteId, $type){
		return "CommonReviewDAO" . "getRateArray" . $siteId . $type;
	}
	
   /** 
	* Ritorna un array di stringhe contenenti i path di tutti i media della recensione
	*/
	public function getAllMedia($reviewId, $type){
		
		$key = $this->getAllMediaKey($reviewId, $type);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$media = array();
		
		$mysqli = $this->getConn();
		
		if ($type == SiteReview)
			$sql = sprintf("SELECT ID AS ID, NAME, TYP, xDIM, yDIM, 1 as REVTP FROM (
								SELECT ID AS ID, CONCAT( FILEPATH, FILENAME) AS NAME,TYP AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM REVIEW_ATTACH 
								WHERE REVIEWID = %d
								AND TYP != 'DOC'
								UNION 
								SELECT ID AS ID, COVERFILENAME AS NAME, 'CVR' AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM REVIEW 
								WHERE ID = %d
							) AS T
							ORDER BY DTINS DESC", $reviewId, $reviewId);
		else if ($type == CityReview) 
			$sql = sprintf("SELECT ID, NAME, TYP, xDIM, yDIM, 2 as REVTP  FROM (
								SELECT ID AS ID, CONCAT( FILEPATH, FILENAME) AS NAME, TYP AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM CITY_REV_ATT 
								WHERE CITYREVID = %d
								AND TYP != 'DOC'
								UNION
								SELECT ID AS ID, COVERFILENAME AS NAME, 'CVR' AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM  
								FROM CITY_REVIEW WHERE ID = %d
								UNION 
								SELECT ID AS ID, CONCAT( FILEPATH, FILENAME) AS NAME,TYP AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM REVIEW_ATTACH 
								WHERE REVIEWID IN (
									SELECT REVIEWID FROM REVIEW_CITY_REVIEW WHERE CITYREVID = %d
								)
								AND TYP != 'DOC'
								UNION 
								SELECT ID AS ID, COVERFILENAME AS NAME, 'CVR' AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM REVIEW WHERE ID IN (
									SELECT REVIEWID FROM REVIEW_CITY_REVIEW WHERE CITYREVID = %d
								)
							) AS T
							ORDER BY DTINS DESC", $reviewId, $reviewId, $reviewId, $reviewId);
		else if ($type == CountryReview) 
			$sql = sprintf("SELECT ID, NAME, TYP, xDIM, yDIM, 3 as REVTP  FROM (
								SELECT ID AS ID, CONCAT( FILEPATH, FILENAME) AS NAME,TYP AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM COUNTRY_REV_ATT 
								WHERE COUNTRYREVID = %d
								AND TYP != 'DOC'
								UNION 
								SELECT ID AS ID, COVERFILENAME AS NAME, 'CVR' AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM COUNTRY_REVIEW 
								WHERE ID = %d
								UNION
								SELECT ID AS ID, CONCAT( FILEPATH, FILENAME) AS NAME,TYP AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM CITY_REV_ATT 
								WHERE CITYREVID IN (
									SELECT CITYREVID FROM CITY_REV_COUNTRY_REV WHERE COUNTRYREVID = %d
								)
								UNION
								SELECT ID AS ID, COVERFILENAME AS NAME, 'CVR' AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM CITY_REVIEW WHERE ID IN (
									SELECT CITYREVID FROM CITY_REV_COUNTRY_REV WHERE COUNTRYREVID = %d
								)
								UNION
								SELECT ID AS ID, CONCAT( FILEPATH, FILENAME) AS NAME,TYP AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM REVIEW_ATTACH 
								WHERE REVIEWID IN (
									SELECT REVIEWID FROM REVIEW_CITY_REVIEW WHERE CITYREVID IN (
										SELECT CITYREVID FROM CITY_REV_COUNTRY_REV WHERE COUNTRYREVID = %d
									)
								)
								AND TYP != 'DOC'
								UNION 
								SELECT ID AS ID, COVERFILENAME AS NAME, 'CVR' AS TYP, DTINS AS DTINS, xDIM as xDIM, yDIM as yDIM
								FROM REVIEW WHERE ID IN (
									SELECT REVIEWID FROM REVIEW_CITY_REVIEW WHERE CITYREVID IN (
										SELECT CITYREVID FROM CITY_REV_COUNTRY_REV WHERE COUNTRYREVID = %d
									)
								)
							) AS T
							ORDER BY DTINS DESC",$reviewId,$reviewId,$reviewId,$reviewId,$reviewId,$reviewId);
		
		Logger::log("CommonReviewDAO :: getAllMedia :: query: ".$sql, 3);
		//echo $sql; exit;
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $name, $type, $xdim, $ydim, $revtp);
			while($stmt->fetch()) {
				$row = array();
				$row['id']   = $id;
				$row['name'] = $name;
				$row['type'] = $type;
				$row['xdim'] = $xdim;
				$row['ydim'] = $ydim;
				$row['revtp'] = $revtp;
				array_push($media, $row);
			}
			$stmt->free_result();
		}
		
		//close
		$mysqli->close();
		
		//memcached not work if array is empty :(
		//non dovrebbe mai succedere che il risultato sia un array di 0 elementi
		if (count($media) == 0) array_push($media, null);
		$this->setCached($key, $media);
		return $media;
	}
	
	/* return getAllMedia key */
	private function getAllMediaKey($siteId, $type){
		return "CommonReviewDAO" . "getAllMedia" . $siteId . $type;
	}
	
   /** 
	* Associa una "star" di un utente ad una data recensione
	*/
	public function setReviewStar($usrId, $reviewId, $type){
		$mysqli = $this->getConn();
		
		if ($type == SiteReview) {
			$sql = sprintf("UPDATE REVIEW_STAR SET STAR=%d, DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", 1, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewStar :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO REVIEW_STAR (USRID, REVID, STAR) VALUES (%d, %d, %d)", $usrId, $reviewId, 1);
			Logger::log("CommonReviewDAO :: setReviewStar :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		else if ($type == CityReview) {
			$sql = sprintf("UPDATE CITY_REV_STAR SET STAR=%d, DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", 1, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewStar :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO CITY_REV_STAR (USRID, REVID, STAR) VALUES (%d, %d, %d)", $usrId, $reviewId, 1);
			Logger::log("CommonReviewDAO :: setReviewStar :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		else if ($type == CountryReview) {
			$sql = sprintf("UPDATE COUNTRY_REV_STAR SET STAR=%d, DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", 1, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewStar :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO COUNTRY_REV_STAR (USRID, REVID, STAR) VALUES (%d, %d, %d)", $usrId, $reviewId, 1);
			Logger::log("CommonReviewDAO :: setReviewStar :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		
		$mysqli->close();
	}
	
   /** 
	* Associa un "see" di un utente ad una data recensione
	*/
	public function setReviewSee($usrId, $reviewId, $type){
		$mysqli = $this->getConn();
		
		if ($type == SiteReview) {
			$sql = sprintf("UPDATE REVIEW_STAR SET SEE=%d, DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", 1, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewSee :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO REVIEW_STAR (USRID, REVID, SEE) VALUES (%d, %d, %d)", $usrId, $reviewId, 1);
			Logger::log("CommonReviewDAO :: setReviewSee :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		else if ($type == CityReview) {
			$sql = sprintf("UPDATE CITY_REV_STAR SET SEE=%d, DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", 1, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewSee :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO CITY_REV_STAR (USRID, REVID, SEE) VALUES (%d, %d, %d)", $usrId, $reviewId, 1);
			Logger::log("CommonReviewDAO :: setReviewSee :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		else if ($type == CountryReview) {
			$sql = sprintf("UPDATE COUNTRY_REV_STAR SET SEE=%d, DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", 1, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewSee :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO COUNTRY_REV_STAR (USRID, REVID, SEE) VALUES (%d, %d, %d)", $usrId, $reviewId, 1);
			Logger::log("CommonReviewDAO :: setReviewSee :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		
		$mysqli->close();
	}
	
   /** 
	* Associa un "post" di un utente ad una data recensione
	*/
	public function setReviewPost($usrId, $reviewId, $type, $post){
		$mysqli = $this->getConn();
		
		if ($type == SiteReview) {
			$sql = sprintf("UPDATE REVIEW_STAR SET POST='%s', DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", $post, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewSee :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO REVIEW_STAR (USRID, REVID, POST) VALUES (%d, %d, '%s')",  $usrId, $reviewId, $post);
			Logger::log("CommonReviewDAO :: setReviewSee :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		else if ($type == CityReview) {
			$sql = sprintf("UPDATE CITY_REV_STAR SET POST='%s', DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", $post, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewStar :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO CITY_REV_STAR (USRID, REVID, POST) VALUES (%d, %d, '%s')", $usrId, $reviewId, $post);
			Logger::log("CommonReviewDAO :: setReviewStar :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		else if ($type == CountryReview) {
			$sql = sprintf("UPDATE COUNTRY_REV_STAR SET POST='%s', DTINS = CURRENT_TIMESTAMP WHERE USRID=%d AND REVID=%d", $post, $usrId, $reviewId);
			Logger::log("CommonReviewDAO :: setReviewStar :: update query: ".$sql, 3);
			$mysqli->query($sql);
			
			//faccio la insert al massimo fallisce
			$sql = sprintf("INSERT INTO COUNTRY_REV_STAR (USRID, REVID, POST) VALUES (%d, %d, '%s')", $usrId, $reviewId, $post);
			Logger::log("CommonReviewDAO :: setReviewStar :: insert query: ".$sql, 3);
			$mysqli->query($sql);
			
			$this->delCached($this->getReviewByIdKey($reviewId));
		}
		
		$mysqli->close();
	}
	
   /** 
	* Cancella un'associazione di una "star" di un utente ad una data recensione
	*/
	public function unsetReviewStar($usrId, $reviewId, $type){
		$arrayStar = array();
		
		$mysqli = $this->getConn();
		
		if ($type == SiteReview)
			$sql = sprintf("UPDATE REVIEW_STAR SET STAR=%d WHERE USRID=%d AND REVID=%d AND STAR=%d", 0, $usrId, $reviewId, 1);
		
		else if ($type == CityReview) 
			$sql = sprintf("UPDATE CITY_REV_STAR SET STAR=%d WHERE USRID=%d AND REVID=%d AND STAR=%d", 0, $usrId, $reviewId, 1);
			
		else if ($type == CountryReview) 
			$sql = sprintf("UPDATE COUNTRY_REV_STAR SET STAR=%d WHERE USRID=%d AND REVID=%d AND STAR=%d", 0, $usrId, $reviewId, 1);
			
		Logger::log("CommonReviewDAO :: unsetReviewStar :: query: ".$sql, 3);
		
		//echo $sql; exit;
		$mysqli->query($sql);
		$mysqli->close();
		$this->delCached($this->getReviewByIdKey($reviewId));
		return $arrayStar;
	}
	
   /** 
	* Cancella un'associazione di un "see" di un utente ad una data recensione
	*/
	public function unsetReviewSee($usrId, $reviewId, $type){
		$arrayStar = array();
		
		$mysqli = $this->getConn();
		
		if ($type == SiteReview)
			$sql = sprintf("UPDATE REVIEW_STAR SET SEE=%d WHERE USRID=%d AND REVID=%d AND SEE=%d", 0, $usrId, $reviewId, 1);
		
		else if ($type == CityReview) 
			$sql = sprintf("UPDATE CITY_REV_STAR SET SEE=%d WHERE USRID=%d AND REVID=%d AND SEE=%d", 0, $usrId, $reviewId, 1);
			
		else if ($type == CountryReview) 
			$sql = sprintf("UPDATE COUNTRY_REV_STAR SET SEE=%d WHERE USRID=%d AND REVID=%d AND SEE=%d", 0, $usrId, $reviewId, 1);
			
		Logger::log("CommonReviewDAO :: unsetReviewSee :: query: ".$sql, 3);
		
		//echo $sql; exit;
		$mysqli->query($sql);
		$mysqli->close();
		$this->delCached($this->getReviewByIdKey($reviewId));
		return $arrayStar;
	}

	/* 
	 * Costruisce la struttura delle star e dei see della recensione.
	 * Metodo duale del buildUserStar. Quest'ultimo crea la lista di $tarDO dell'utente,
	 * questo metodo, invece, crea la lista di $tarDO della recensione corrente
	 * 
	 * Ritorna un array di 3 elemnti, uno per ogni tipo di recensione.
	 * Ognuno di questi 3 elementi è costituito come segue:
	 * E' a sua volta un array che ha per chiave l'id della recensione e 
	 * per valore un oggetto un oggetto StarDO contenente informazioni aggiuntive.
	 */
    public function buildRevStar($revId, $type){
		$starArray = array();
		
		$mysqli = $this->getConn();
		
		if ($type == SiteReview) {
			$sql = sprintf("SELECT SI.ID AS PLACEID, SI.SITENAME AS PLACE, U.NAME, R.ID AS REVID, STAR, SEE, CASE WHEN POST IS NULL THEN '' ELSE POST END AS POST, U.ID, U.COVERFILENAME
							FROM REVIEW R
								JOIN REVIEW_STAR S ON R.ID = S.REVID 
								JOIN SITE SI       ON R.SITEID = SI.ID,
								USER U
							WHERE R.ID=%d
							AND U.ID = S.USRID
							ORDER BY S.DTINS DESC", $revId);
		}
		else if ($type == CityReview) {
			$sql = sprintf("SELECT CT.ID AS PLACEID, CT.CITYNAME AS PLACE, U.NAME, R.ID AS REVID, STAR, SEE, CASE WHEN POST IS NULL THEN '' ELSE POST END AS POST, U.ID, U.COVERFILENAME
							FROM CITY_REVIEW R
								JOIN CITY_REV_STAR S ON R.ID = S.REVID 
								JOIN CITY CT         ON R.CITYID = CT.ID,
								USER U
							WHERE R.ID=%d
							AND U.ID = S.USRID
							ORDER BY S.DTINS DESC", $revId);
		}
		else if ($type == CountryReview)  {				
			$sql = sprintf("SELECT CN.ID AS PLACEID, CN.COUNTRY, U.NAME, R.ID AS REVID, STAR, SEE, CASE WHEN POST IS NULL THEN '' ELSE POST END AS POST, U.ID, U.COVERFILENAME
							FROM COUNTRY_REVIEW R
								JOIN COUNTRY_REV_STAR S ON R.ID = S.REVID 
								JOIN COUNTRY CN         ON R.COUNTRYID = CN.ID,
								USER U
							WHERE R.ID=%d
							AND U.ID = S.USRID
							ORDER BY S.DTINS DESC", $revId);
		}
		
		Logger::log("CommonReviewDAO :: unsetReviewStar :: query: ".$sql, 3);
		
		//echo $sql; exit;
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($siteId, $siteName, $author, $reviewId, $star, $see, $post, $usrId, $usrCover);
			while ($stmt->fetch()) {
				
				$starDO = NEW StarDO($siteId, $siteName, $author, $star, $see, $post, $usrCover);
				$starDO->setUsrId(X_code($usrId));
				$starDO->setUsrCover($usrCover);
				//uso il toArray perche' il json_encode lavora solo con array di array
				array_push($starArray, $starDO->toArray());
			}
			$stmt->free_result();
		}
		
		//close
		$mysqli->close();
		return $starArray;
    }
    
   /* 
	* Ritorna la order by a partire dal tipo 
	* 0 <=> DTINS DESC
	* 1 <=> DTINS ASC
	* 2 <=> VOTE  DESC
	* 3 <=> VOTE  ASC
	* 4 <=> STAR  DESC
	* 5 <=> STAR  ASC
	* @$orderType tipo di ordinamento
	*/
	protected function getOrderByString($orderType) {
		switch ($orderType) {
			case "0":
				return " ORDER BY R.DTINS DESC ";
			break;
			case "1":
				return " ORDER BY R.DTINS ASC ";
			break;
			case "2":
				return " ORDER BY VOTE DESC ";
			break;
			case "3":
				return " ORDER BY VOTE ASC ";
			break;
			case "4":
				return " ORDER BY STAR DESC ";
			break;
			case "5":
				return " ORDER BY STAR ASC ";
			break;
			default :
				return " ORDER BY R.DTINS DESC ";
			break;	
		}
	}

	/*#######################################################
	 * Rimuove dalla cache tutte le recensioni dell'utente */
	public function removeMemcacheUsrReview($usrId){
		
		$pattern = '%d/%m/%Y'; //uno qualsiasi tanto non devo visualizzarlo
		
		$reviews = $this->getReviewList($usrId, $pattern);
		for ($i = 0; $i < count($reviews); $i++) {
			$revId = $reviews[$i]->getId();
			$this->delCached($this->getReviewByIdKey($revId));
		}
		$this->delCached($this->getReviewListKey($usrId));
	}
}
