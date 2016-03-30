<?php
/**
 * Questa classe mappa una riga della tabella CATEGORY_REVIEW 
 * Non contiene FK verso altre tabelle.
 */
 
class CategoryReviewDO {

	private $_id;
	private $_idCategoryMain;
	private $_category;
			
    public function __construct($id, $idCategoryMain, $category){
		$this->_id = $id;
		$this->_idCategoryMain = $idCategoryMain;
		$this->_category = $category;
    }
	
	public function getId(){
		return $this->_id;
	}
	
	public function getIdCategoryMain(){
		return $this->_idCategoryMain;
	}	
	
	public function getCategoryName(){
		return $this->_category;
	}
}
?>
