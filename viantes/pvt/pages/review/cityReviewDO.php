<?php
/**
 * Questa classe mappa una riga della tabella CITY_REVIEW 
 * Contiene inoltre le informazione delle tabelle relazionate USER e CITY
 */
$root = "../../../";
require_once $X_root."pvt/pages/review/commonReviewDO.php";

class CityReviewDO extends CommonReviewDO {

	private $_cityID;
	private $_whereToEat;
	private $_whereToStay;
	
	/* Oggetti Relazionati della tabella City */
	private $_cityName;
		
    public function __construct($id, $usrId, $cityId, $langCode, $descr, $howToArrive, $warning, 
								$whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName,
								$xdim=0, $ydim=0){

		parent::__construct($id, $usrId, $langCode, $descr, $howToArrive, $warning, $cooking, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);

		$this->_cityID = $cityId;
		$this->_whereToEat = $whereToEat;
		$this->_whereToStay = $whereToStay;
    }
	
	
	/**********/
	/* Getter */
	/**********/
	
	public function getCityId(){
		return $this->_cityID;
	}

	public function getWhereToEat(){
		return $this->_whereToEat;
	}
	
	public function getWhereToStay(){
		return $this->_whereToStay;
	}
	
	
	/**********************************************/
	/* Metodi degli oggetti relazionati da CityDO */
	/**********************************************/
	
	public function getCityName(){
		return $this->_cityName;
	}
	
	public function setCityName($cityName){
		$this->_cityName = $cityName;
	}
	
	
	/**********************/
	/* Metodi di utilita' */
	/**********************/
	
	/* Ritorna la localita' completa e formattata
	*/
	public function getFormattedLocality(){
		return $this->_cityName .", ". parent::getCountry();
	}
}
?>