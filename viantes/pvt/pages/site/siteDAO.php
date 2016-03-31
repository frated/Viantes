<?php
require_once $X_root."pvt/pages/common/commonDAO.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/site/siteDO.php";

class SiteDAO  extends CommonDAO {

    public function __construct(){
    }

	/** Censisce il sito se non esiste */
	public function createSiteIFNotEx($geoSiteArr, $countryId, $langCode){
		
		$key = $this->createSiteIFNotExKey($geoSiteArr, $countryId, $langCode);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$placeId = $geoSiteArr['placeId'];	//identificativo letto da googlemaps

		$mysqli = $this->getConn();
		
		//Lock Site Table
		$mysqli->query("LOCK TABLES SITE WRITE");
		$mysqli->query("LOCK TABLES SITE GEO_SITE");
		
		//=========================================================
		// 1.  Get if exists
		$sql = sprintf("SELECT S.ID, S.SITENAME, S.COUNTRYID, S.LOCALITY, S.GEOSITEID, G.PLACEID, G.LAT, G.LNG
						FROM SITE S 
							 JOIN GEO_SITE G ON S.GEOSITEID = G.ID 
						WHERE G.PLACEID = '%s' 
						AND G.LANGCODE = '%s' 
						AND S.LANGCODE = '%s' ", $placeId, $langCode, $langCode);
						
		Logger::log("SiteDAO :: createSiteIFNotEx :: select query: ".$sql, 3);
	
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($idF, $siteF, $countryIdF, $localityF, $geoSiteIdF, $placeIdF, $latF, $lngF);
			if($stmt->fetch()) {
				$mysqli->query("UNLOCK TABLES");
				return $this->buildSiteDO($idF, $siteF, $countryIdF, $localityF, $langCode, $geoSiteIdF, $placeIdF, $latF, $lngF);
			}
		}
		
		
		//=========================================================
		// 2.  Insert Into Geo_Site
		$placeId = $geoSiteArr['placeId'];
		$lat = $geoSiteArr['lat'];
		$lng = $geoSiteArr['lng'];
		
		$sql = sprintf("INSERT INTO GEO_SITE (PLACEID, LANGCODE, LAT, LNG) VALUES ('%s', '%s', %9.6f, %9.6f)", $placeId, $langCode, $lat, $lng);
		$mysqli->query($sql);
		Logger::log("SiteDAO :: createSiteIFNotEx :: insert query: ".$sql, 3);
		
		//Get Geo_Site id
		$geoSiteId = -1;
		$sql = sprintf("SELECT ID FROM GEO_SITE WHERE PLACEID = '%s' AND LANGCODE = '%s'", $placeId, $langCode);
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($geoSiteId);
			if ($stmt->fetch()) {
				Logger::log("SiteDAO :: createSiteIFNotEx :: select query: ".$sql, 3);
				$stmt->free_result();
			}
		}
		
		
		//=========================================================
		// 3.  Insert Into Site
		$siteName = $geoSiteArr['siteName'];
		$frmtdAdrs = $geoSiteArr['frmtdAdrs'];
		$sql = sprintf("INSERT INTO SITE (SITENAME, COUNTRYID, LOCALITY, LANGCODE, GEOSITEID) VALUES ('%s', '%d', '%s', '%s', %d)", $siteName, $countryId, $frmtdAdrs, $langCode, $geoSiteId);
		$mysqli->query($sql);
		Logger::log("SiteDAO :: createSiteIFNotEx :: insert query: ".$sql, 3);

		//Get Site Id
		$sql = sprintf("SELECT ID FROM SITE WHERE GEOSITEID = '%s' AND LANGCODE = '%s'", $geoSiteId, $langCode);
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($idF);
			if ($stmt->fetch()) {
				Logger::log("SiteDAO :: createSiteIFNotEx :: select max id query: ".$sql, 3);
				
				//build and save $siteDO
				$siteDO = $this->buildSiteDO($idF, $siteName, $countryId, $frmtdAdrs, $langCode, $geoSiteId, $placeId, $lat, $lng);
				$this->setCached($key, $siteDO);
				
				$stmt->free_result();
				$mysqli->close();
				return $siteDO;
			}
		}
		
		Logger::log("SiteDAO :: createSiteIFNotEx :: Attenzione, nessun oggetto SiteDO creato!!!", 1);
		
		//close
		$mysqli->close();
		return FALSE;
    }
    
    /* return createSiteIFNotEx key */
    public function createSiteIFNotExKey($geoSiteArr, $countryId, $langCode){
		return "SiteDAO" . "createSiteIFNotEx" . $geoSiteArr['placeId'] . "_" . $countryId . "_" . $langCode;
	}
	
	private function buildSiteDO($id, $site, $countryId, $locality, $langCode, $geoSiteId, $placeId, $lat, $lng) {
		
		$siteDO = New SiteDO($id, $site, $countryId, $locality, $langCode, $geoSiteId);
		$siteDO->setPlaceId($placeId);
		$siteDO->setLat($lat);
		$siteDO->setLng($lng);
		
		return $siteDO;
	}
}
?>
