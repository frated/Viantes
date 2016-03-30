<?php
/**
 * Questa classe mappa una riga della tabella CITY 
 * Contiene inoltre le informazione della tabella relazionata 
 * LANG e COUNTRY
 */
 
class CityDO {

	private $_id;
	private $_cityName;
	private $_countryId;
	private $_langCode;
	
    public function __construct($id,  $cityName, $countryId, $langCode){
		$this->_id = $id;
		$this->_cityName = $cityName;
		$this->_countryId = $countryId;
		$this->_langCode = $langCode;
    }

	public function getId(){
		return $this->_id;
	}
	
	public function getCityName(){
		return $this->_cityName;
	}

	public function getCountryId(){
		return $this->_countryId;
	}
	
	public function getLangCode(){
		return $this->_langCode;
	}
}
?>