<?php
/**
 * Questa classe mappa una riga della tabella USER_REGISTRY 
 * Non contiene FK verso altre tabelle.
 */
 
class UserRegistryDO {

	private $_userId;
	private $_firstName;
	private $_lastName;
	private $_mobileNum;
	private $_gender;
	private $_dateOfBirth;
	private $_city;
	private $_postcode;
	private $_country;
		
    public function __construct($userId, $firstName, $lastName, $mobileNum, $gender, $dateOfBirth, $city, $postcode, $country){
		$this->_userId = $userId;
		$this->_firstName = $firstName;
		$this->_lastName = $lastName;
		$this->_mobileNum = $mobileNum;
		$this->_gender = $gender;
		$this->_dateOfBirth = $dateOfBirth;
		$this->_city = $city;
		$this->_postcode = $postcode;
		$this->_country = $country;
    }
	
	public function getUserId(){
		return $this->_userId;
	}
	
	public function getFirstName(){
		return $this->_firstName;
	}

	public function setFirstName($firstName){
		$this->_firstName = $firstName;
	}

	public function getLastName(){
		return $this->_lastName;
	}

	public function setLastName($lastName){
		$this->_lastName = $lastName;
	}
	
	public function getMobileNum(){
		return $this->_mobileNum;
	}
	
	public function setMobileNum($mobileNum){
		$this->_mobileNum = $mobileNum;
	}	
	
	public function getGender(){
		return $this->_gender;
	}
	
	public function setGender($gender){
		$this->_gender = $gender;
	}
	
	public function getDateOfBirth(){
		return $this->_dateOfBirth;
	}

	public function setDateOfBirth($dateOfBirth){
		$this->_dateOfBirth = $dateOfBirth;
	}	
	
	public function getCity(){
		return $this->_city;
	}

	public function setCity($city){
		$this->_city = $city;
	}

	public function getPostcode(){
		return $this->_postcode;
	}

	public function setPostcode($postcode){
		$this->_postcode = $postcode;
	}

	public function getCountry(){
		return $this->_country;
	}

	public function setCountry($country){
		$this->_country = $country;
	}	
}
?>