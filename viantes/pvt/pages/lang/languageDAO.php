<?php
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/lang/languageDO.php";
require_once $X_root."pvt/pages/common/commonDAO.php";

class LanguageDAO extends CommonDAO {

    public function __construct(){
    }

	/** Ritorna una lista di oggetti LanguageDO. 
	 *  Ogni LanguageDO mappa una riga della tabella LANG */
	public function getLanguages(){
		
		$key = $this->getLanguagesKey();
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$languageDOArray = array();
		
		$mysqli = $this->getConn();
		
		$sql = "SELECT LANGCODE, LANGDESC FROM LANG";
		Logger::log("LanguageDAO :: getLanguages :: query ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($langCode, $langDesc);
			while($stmt->fetch()) {
				$languageDO = New LanguageDO($langCode, $langDesc);
				array_push($languageDOArray, $languageDO);
			}
			//free result
			$stmt->free_result();
			
			//close
			$mysqli->close();			
			$this->setCached($key, $languageDOArray);
			return $languageDOArray;			
		} 
		
		Logger::log("LanguageDAO :: getLanguages :: Attenzione nessun linguaggio trovato ", 1);
		
		//close
		$mysqli->close();
		
		return $languageDOArray;
	}
	
    /* return getLanguages key */
	private function getLanguagesKey(){
		return "LanguageDAO" . "getLanguages";
	}

}
?>
