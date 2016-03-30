<?php
/* Oggetto di Front-End che contiene tutte le informazioni specifiche 
 * della creazione di una recensione di citta'. Queste informazioni sono lette 
 * dalla user-interface e passate ai metodi di CRUD.
 */
 
require_once $X_root."pvt/pages/review/commonReviewBean.php";

class CityReviewBean extends CommonReviewBean {

	private $_interest = array();

    public function __construct(){ }

	public function setInterest($interest){
		$this->_interest = $interest;
	}
	
	public function getInterest(){
		return $this->_interest;
	}
}
?>