<?php
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/auth/userRegistryDO.php";
require_once $X_root."pvt/pages/common/commonDAO.php";


class UserRegistryDAO extends CommonDAO {

    public function __construct(){
    }

	/** Ritorna i dati dell'anagrafe dell'utente */
	public function getUserRegistryByUserId($usrId, $pattern) {	
		
		$key = $this->getUserRegistryByUserIdKey($usrId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT FIRSTNAME, LASTNAME, MOBILENUM, GENDER, DATE_FORMAT(DATEOFBIRTH, '%s') AS DATEOFBIRTH, CITY, POSTCODE, COUNTRY 
						FROM USER_REGISTRY 
						WHERE USRID = %d", $pattern, $usrId);
		Logger::log("UserRegistryDAO :: getUserregistryByUserId :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($firstName, $lastName, $mobileNum, $gender, $dateOfBirth, $city, $postcode, $country);
			if($stmt->fetch()) {
				$userRegistryDO = New UserRegistryDO($usrId, $firstName, $lastName, $mobileNum, $gender, $dateOfBirth, $city, $postcode, $country);
				$stmt->free_result();
				//close, cache and return 
				$mysqli->close();
				$this->setCached($key, $userRegistryDO);
				return $userRegistryDO;
			}
		}
		//close, cache and return 
		$mysqli->close();
		$userRegistryDO = New UserRegistryDO($usrId, "", "", "", 0, "", "", "", "", "");
		$this->setCached($key, $userRegistryDO);
		return $userRegistryDO;
	}
	
	/* return getUserRegistryByUserId key */
	private function getUserRegistryByUserIdKey($usrId){
		return "UserRegistryDAO" . "getUserRegistryByUserId" . $usrId;
	}
	
	/** Ritorna true se l'utente esiste, false viceversa */
	private function existsUserRegistry($mysqli, $usrId) {
	
		$key = $this->existsUserRegistryKey($usrId);
		$result = $this->getCached($key);
		if ($result) return $result;
		
		$sql = sprintf("SELECT FIRSTNAME, LASTNAME, MOBILENUM, DATEOFBIRTH, CITY, POSTCODE, COUNTRY FROM USER_REGISTRY WHERE USRID = %d", $usrId);
		Logger::log("UserRegistryDAO :: existsUserRegistry :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($firstName, $lastName, $mobileNum, $dateOfBirth, $city, $postcode, $country);
			if($stmt->fetch()) {
				$stmt->free_result();
				$this->setCached($key, true);
				return true;
			}
		}
		
		//close
		return false;
	}
	
	/* return getUserRegistryByUserId key */
	private function existsUserRegistryKey($usrId){
		return "UserRegistryDAO" . "existsUserRegistry" . $usrId;
	}
	
	/**
	 * Inserisce i dati in input se non esiste alcun record per chiave usrId, viceversa fa un update con i valori ricevuti
	 */
	public function insertOrUpdate($usrId, $firstName, $lastName, $mobileNum, $gender, $dateOfBirth, $city, $postcode, $country, $pattern) {
		
		$mysqli = $this->getConn();
		
		if ($this->existsUserRegistry($mysqli, $usrId)) {
			Logger::log("UserRegistryDAO :: insertOrUpdate :: faccio update", 3);
		
			//faccio update
			$sql = " UPDATE USER_REGISTRY SET FIRSTNAME = '$firstName', LASTNAME = '$lastName', MOBILENUM = '$mobileNum', GENDER = '$gender'," ;
			$sql.= trim($dateOfBirth) == "" ? "DATEOFBIRTH =  null, " : " DATEOFBIRTH = STR_TO_DATE('$dateOfBirth', '$pattern'), ";
			$sql.= " CITY = '$city', POSTCODE = '$postcode', COUNTRY = '$country' WHERE USRID = $usrId";
			
			Logger::log("UserRegistryDAO :: insertOrUpdate :: Update query: ".$sql, 3);
			
			$mysqli->query($sql);
			
			//close
			$mysqli->close();
			$this->delCached($this->getUserRegistryByUserIdKey($usrId));
			return;
		}
		
		//faccio insert
		//$dateOfBirth.= trim($dateOfBirth) == "" ? "'NULL'" : " STR_TO_DATE('$dateOfBirth', '$pattern')";
		if (trim($dateOfBirth) == "" )
			$sql = "INSERT INTO USER_REGISTRY (USRID, FIRSTNAME, LASTNAME, MOBILENUM, CITY, POSTCODE, COUNTRY, GENDER )".
				   "VALUES ($usrId, '$firstName', '$lastName', '$mobileNum', '$city', '$postcode', '$country', '$gender')";
		else
			$sql = "INSERT INTO USER_REGISTRY VALUES ($usrId, '$firstName', '$lastName', '$mobileNum', STR_TO_DATE('$dateOfBirth', '$pattern'), '$city', '$postcode', '$country', '$gender')";

		Logger::log("UserRegistryDAO :: insertOrUpdate :: Insert query: ".$sql, 3);
		
		$mysqli->query($sql);
		
		//close and clean cache
		
		$mysqli->close();
		$this->delCached($this->getUserRegistryByUserIdKey($usrId));
		return;
	}
}
?>
