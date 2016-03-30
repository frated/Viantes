<?php
/**
 * Questa classe mappa una riga della tabella REVIEW 
 * Contiene inoltre le informazione delle tabelle relazionate 
 * USER, SITE, CATEGORY_REVIEW e LANG
 */
$root = "../../../";
require_once $X_root."pvt/pages/review/commonReviewDO.php";

class ReviewDO extends CommonReviewDO {

	private $_siteId;
	private $_catRevId;
	private $_whereToEat;
	private $_whereToStay;
	
	/* Oggetti Relazionati della tabella Site */
	private $_siteName;
	private $_localityName;
		
    public function __construct($id, $usrId, $siteId, $catRevId, $langCode, $descr, $howToArrive, $warning, 
								$whereToEat, $cooking, $whereToStay, $myth, $dtIns, $vote, $coverFileName,
								$xdim=0, $ydim=0){

		parent::__construct($id, $usrId, $langCode, $descr, $howToArrive, $warning, $cooking, $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);
		$this->_siteId = $siteId;
		$this->_catRevId = $catRevId;
		$this->_whereToEat = $whereToEat;
		$this->_whereToStay = $whereToStay;
    }
	
	
	/**********/
	/* Getter */
	/**********/
	
	public function getSiteId(){
		return $this->_siteId;
	}
	
	public function getCatRevId(){
		return $this->_catrevId;
	}

	public function getWhereToEat(){
		return $this->_whereToEat;
	}
	
	public function getWhereToStay(){
		return $this->_whereToStay;
	}
	
	
	/**********************************************/
	/* Metodi degli oggetti relazionati da UserDO */
	/**********************************************/
	
	public function getSiteName(){
		return $this->_siteName;
	}
	
	public function setSiteName($siteName){
		$this->_siteName = $siteName;
	}
	
	public function getLocality(){
		return $this->_localityName;
	}
	
	public function setLocality($localityName){
		$this->_localityName = $localityName;
	}
	
	/**********************/
	/* Metodi di utilita' */
	/**********************/
	
   /* Ritorna la localita' completa e formattata
	* @deprecato 
	*/
	public function getFullLocality(){
		return $this->_localityName .", ". parent::getCountry();
	}
}
?>