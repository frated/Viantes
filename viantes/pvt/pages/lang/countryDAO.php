<?php
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/common/commonDAO.php";

class CountryDAO extends CommonDAO {

    public function __construct(){
    }
	
	/** 
	 * Crea la nazione se non esiste. 
	 * Ritorna l'id della nazione creata o esistente
	 */
	public function createCountryIfNotExis($country, $langCode){
		
		$key = $this->createCountryIfNotExisKey($country, $langCode);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("INSERT INTO COUNTRY (COUNTRY, LANGCODE) VALUES ('%s', '%s')", $country, $langCode);
		Logger::log("CountryDAO :: createCountryIfNotExis :: insert query: ".$sql, 3);
		$mysqli->query($sql);
		
		$sql = sprintf("SELECT ID FROM COUNTRY WHERE UPPER(COUNTRY) = UPPER('%s') and LANGCODE = ('%s')", $country, $langCode);
		Logger::log("CountryDAO :: createCountryIfNotExis :: select query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id);
			if($stmt->fetch()) {
				
				//free result
				$stmt->free_result();
				
				//close
				$mysqli->close();
				$this->setCached($key, $id);
				return $id;
			}
		}
		
		Logger::log("CountryDAO :: createCountryIfNotExis :: Attenzione nessuna nazione trovata per country=" .$country. " e langCode=" .$langCode, 1);
		
		//close
		$mysqli->close();
		return -1;
	}
	
	/* return createCountryIfNotExis key */
	private function createCountryIfNotExisKey($country, $langCode){
		return "CountryDAO" . "createCountryIfNotExis" . $country . $langCode;
	}
}
?>
