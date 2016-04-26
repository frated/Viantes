<?php
/**
 * Questa classe mappa le colonne comuni delle tabelle REVIEW, CITY_REVIEW, COUNTRY_REVIEW
 */
 
class commonReviewDO {

	private $_id;
	private $_usrId;
	private $_langCode;
	private $_descr;
	private $_howToArrive;
	private $_warning;
	private $_cooking;
	private $_myth;
	private $_dtIns;
	private $_vote;
	private $_coverFileName;
	private $_coverWidth;
	private $_coverHeight;
	
	/* Oggetti Relazionati della tabella User */
	private $_usrName;
	private $_usrCoverFileName;

	/* Campo comune ma NON relazionato (vedi commento sotto) */		
	private $_country;
	
	/* Campo comune ma NON relazionato (vedi commento sotto) */	
	private $_placeId;

	/* Oggetti Relazionati della tabella REVIEW_/CITY_REV_/COUNTRY_REV_ STAR */
	private $_cntStar;
	private $_cntSee;
	private $_cntPost;
	
	public function __construct($id, $usrId, $langCode, $descr, $howToArrive, $warning, $cooking, 
								 $myth, $dtIns, $vote, $coverFileName, $xdim, $ydim) {
		$this->_id = $id;
		$this->_usrId = $usrId;
		$this->_langCode = $langCode;
		$this->_descr = $descr;
		$this->_howToArrive = $howToArrive;
		$this->_warning = $warning;
		$this->_cooking = $cooking;
		$this->_myth = $myth;
		$this->_dtIns = $dtIns;
		$this->_vote = $vote;
		$this->_coverFileName = $coverFileName;
		$this->_coverWidth = $xdim;
		$this->_coverHeight = $ydim;
    }
	
	/**********/
	/* Getter */
	/**********/
	
	public function getId(){
		return $this->_id;
	}
	
	public function getUsrId(){
		return $this->_usrId;
	}
	
	public function getLangCode(){
		return $this->_langCode;
	}
	
	public function getDescr(){
		return $this->_descr;
	}
	
	public function getHowToArrive(){
		return $this->_howToArrive;
	}
	
	public function getWarning(){
		return $this->_warning;
	}

	public function getCooking(){
		return $this->_cooking;
	}
	
	public function getMyth(){
		return $this->_myth;
	}
	
	public function getDtIns(){
		return $this->_dtIns;
	}
	
	public function getVote(){
		return $this->_vote;
	}
	
	public function getCoverFileName(){
		return $this->_coverFileName;
	}
	
	public function getCoverWidth(){
		return $this->_coverWidth;
	}

	public function getCoverHeight(){
		return $this->_coverHeight;
	}
	
	
	/**********************************************/
	/* Metodi degli oggetti relazionati da UserDO 
	 * Questi metodi sono comuni perché sia REVIEW che 
	 * CITY_REVIEW che COUNTRY_REVIEW hanno una relazione
	 * verso la tabella USER                 
	/**********************************************/
	
	public function setUsrName($usrName){
		$this->_usrName = $usrName;
	}

	public function getUsrName(){
		return $this->_usrName;
	}

	public function setUserCoverFileName($usrCoverFileName){
		$this->_usrCoverFileName = $usrCoverFileName;
	}

	public function getUserCoverFileName(){
		return $this->_usrCoverFileName;
	}


	/**********************************************/
	/* Questi metodi sono messi qui poiche' usati dai
	 * 3 bena che ereditano questa classe pur NON avendo
     * una FK verso una tabella comune. Il fatto e' che la 
	 * La tabella REVIEW e' collegata alla SITE che ha un campo country,
	 * La tabella CITY_REVIEW e' collegata alla CITY che ha 
	 * anch'essa un campo country, e stesso ovviemnte per COUNTRY 
	/**********************************************/

	public function getCountry(){
		return $this->_country;
	}

	public function setCountry($country){
		$this->_country = $country;
	}
	
	
	/**********************************************/
	/* Questo metodo ad oggi e' usato solo nella ReviewDO
	 * Tuttavia il placeId e' comune ed in futuro, se dovesse 
	 * servire ad un City/CountryReviewDO e' gia' predisposto
	/**********************************************/
	public function getPlaceId(){
		return $this->_placeId;
	}

	public function setPlaceId($placeId){
		$this->_placeId = $placeId;
	}
	
	/**********************************************/
	/* Metodi degli oggetti relazionati da StarDO 
	 * Questi metodi sono comuni perché sia REVIEW che 
	 * CITY_REVIEW che COUNTRY_REVIEW hanno una relazione
	 * verso la tabella REVIEW_/CITY_REV_/COUNTRY_REV_ STAR                 
	/**********************************************/
	
	public function setCntStar($cntStar){
		$this->_cntStar = $cntStar;
	}

	public function getCntStar(){
		return $this->_cntStar;
	}

	public function setCntSee($cntSee){
		$this->_cntSee = $cntSee;
	}

	public function getCntSee(){
		return $this->_cntSee;
	}

	public function setCntPost($cntPost){
		$this->_cntPost = $cntPost;
	}

	public function getCntPost(){
		return $this->_cntPost;
	}

}
?>
