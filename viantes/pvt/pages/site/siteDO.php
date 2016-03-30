<?php
/**
 * Questa classe mappa una riga della tabella SITE 
 * Contiene inoltre le informazione della tabella relazionata LANG
 */
 
class SiteDO {

	private $_id;
	private $_siteName;
	private $_countryId;
	private $_locality;
	private $_langCode;
	
	/* Oggetti Relazionati della tabella Geo_Site */
	private $_geoSiteId;
	private $_placeId;
	private $_lat;
	private $_lng;
	
    public function __construct($id,  $siteName, $countryId, $locality, $langCode, $geoSiteId){
		$this->_id = $id;
		$this->_siteName = $siteName;
		$this->_countryId = $countryId;
		$this->_locality = $locality;
		$this->_langCode = $langCode;
		$this->_geoSiteId = $geoSiteId;
    }

	public function getId(){
		return $this->_id;
	}
	
	public function getSiteName(){
		return $this->_siteName;
	}
	
	public function getCountryId(){
		return $this->_countryId;
	}

	public function getLocality(){
		return $this->_locality;
	}
	
	public function getLangCode(){
		return $this->_langCode;
	}
	
	
	/************************************************/
	/* Metodi degli oggetti relazionati da Geo_Site */
	/************************************************/
	
	/* placeId */
	public function setPlaceId($placeId){
		$this->_placeId = $placeId;
	}
	
	public function getPlaceId(){
		return $this->_placeId;
	}
	
	/* lat */
	public function setLat($lat){
		$this->_lat = $lat;
	}
	
	public function getLat(){
		return $this->_lat;
	}
	
	/* lng */
	public function setLng($lng){
		$this->_lng = $lng;
	}
	
	public function getLng(){
		return $this->_lng;
	}
	
}
?>