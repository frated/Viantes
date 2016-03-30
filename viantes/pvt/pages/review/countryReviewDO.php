<?php
/**
 * Questa classe mappa una riga della tabella CITY_REVIEW 
 * Contiene inoltre le informazione delle tabelle relazionate USER e CITY
 */
$root = "../../../";
require_once $X_root."pvt/pages/review/commonReviewDO.php";

class CountryReviewDO extends CommonReviewDO {

	private $_countryId;
	
    public function __construct($id, $usrId, $countryId, $langCode, $descr, $howToArrive, $warning, 
								$cooking, $myth, $dtIns, $vote, $coverFileName,
								$xdim=0, $ydim=0){

		parent::__construct($id, $usrId, $langCode, $descr, $howToArrive, $warning, $cooking, 
							$myth, $dtIns, $vote, $coverFileName, $xdim, $ydim);

		$this->_countryId = $countryId;
    }
	
	
	/**********/
	/* Getter */
	/**********/
	
	public function getCountryId(){
		return $this->_countryId;
	}

}
?>