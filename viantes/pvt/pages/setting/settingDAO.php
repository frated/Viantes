<?php
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/setting/settingDO.php";
require_once $X_root."pvt/pages/common/commonDAO.php";

class SettingDAO  extends CommonDAO {

    public function __construct(){
    }

	/** Salva le impostazioni di default */
	public function saveDefaultSetting($usrId, $langCode){
		$mysqli = $this->getConn();
		
		$sql = sprintf("INSERT INTO SETTING_USER (USRID, LANGCODE) VALUES ('%d', '%s')", $usrId, $langCode);
		Logger::log("SettingDAO :: saveDefaultSetting :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		
		//close
		$mysqli->close();
		return TRUE;
    }
	
	/** Ritorna il settaggio dell'utente */
	public function getSetting($usrId){
		
		$key = $this->getSettingKey($usrId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT LANGCODE, PROFILETYPE FROM SETTING_USER WHERE USRID=%d", $usrId);
		Logger::log("SettingDAO :: getSetting :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $profileType);
			if($stmt->fetch()) {
				
				//free result and cloe
				$stmt->free_result();
				$mysqli->close();
				
				// create DO, set in cache and return it
				$settingDO = New SettingDO($id, $profileType);
				$this->setCached($key, $settingDO);
				return $settingDO;
			}
		}
		
		Logger::log("SettingDAO :: getSetting :: Attenzione nessun linguaggio trovato per l'utente:".$usrId, 1);
		
		//close
		$mysqli->close();		
		return false;
    }
    
    /* return getSetting key */
	private function getSettingKey($usrId){
		return "SettingDAO" . "getSetting" . $usrId;
	}
	
	/** Aggiorna la sezione INFO delle impostazioni utente */
	public function setInfo($langCode, $usrId){
		$mysqli = $this->getConn();
		
		$sql = sprintf("UPDATE SETTING_USER SET LANGCODE = '%s' WHERE USRID = %d", $langCode, $usrId);
		Logger::log("SettingDAO :: setInfo :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		
		//close
		$mysqli->close();
		$this->delCached($this->getSettingKey($usrId));
		return TRUE;
    }
	
	/** Aggiorna il tipo di profilo dell'utente */
	public function setProfileType($profileType, $usrId){
		$mysqli = $this->getConn();
		
		$sql = sprintf("UPDATE SETTING_USER SET PROFILETYPE = %d WHERE USRID = %d", $profileType, $usrId);
		Logger::log("SettingDAO :: setProfileType :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		
		//close
		$mysqli->close();
		$this->delCached($this->getSettingKey($usrId));
		return TRUE;
    }
}
?>
