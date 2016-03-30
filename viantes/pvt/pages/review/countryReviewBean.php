<?php
/* Oggetto di Front-End che contiene tutte le informazioni specifiche 
 * della creazione di una recensione di nazione. Queste informazioni sono lette 
 * dalla user-interface e passate ai metodi di CRUD.
 */
 
require_once $X_root."pvt/pages/review/commonReviewBean.php";

class CountryReviewBean extends CommonReviewBean {

	private $_interest = array();

    public function __construct(){ }

	public function setCityInterest($interest){
		$this->_interest = $interest;
	}
	
	public function getCityInterest(){
		return $this->_interest;
	}
	
	/* per generalizzare il metodo con quello del CityReviewBean */
	public function getInterest(){
		return $this->_interest;
	}
}
?>
