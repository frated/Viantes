<?php
/* Oggetto di Front-End che contiene tutte le informazioni comuni 
 * della creazione di una recensione. Queste informazioni sono lette 
 * dalla user-interface e passate ai metodi di CRUD. 
 */
class CommonReviewBean {

	private $_coverFileName;
	private $_coverWidth;
	private $_coverHeight;

	private $_imgFileNameArray = array();
	private $_imgRelativeFilePathArray = array();
	private $_imgCommentArray = array();
	private $_imgWidthArray = array();
	private $_imgHeightArray = array();
	
	private $_movFileNameArray = array();
	private $_movRelativeFilePathArray = array();
	private $_movCommentArray = array();
	private $_movWidthArray = array();
	private $_movHeightArray = array();

	private $_docFileNameArray = array();
	private $_docRelativeFilePathArray = array();
	private $_docCommentArray = array();
	
    public function __construct(){ }
	
	
	/* Cover */
	
	public function setCoverFileName($coverFileName){
		$this->_coverFileName = $coverFileName;
	}
	
	public function getCoverFileName(){
		return $this->_coverFileName;
	}

	public function setCoverWidth($coverWidth){
		$this->_coverWidth = $coverWidth;
	}

	public function getCoverWidth(){
		return $this->_coverWidth;
	}

	public function setCoverHeight($coverHeight){
		$this->_coverHeight = $coverHeight;
	}

	public function getCoverHeight(){
		return $this->_coverHeight;
	}
	
	
	/* IMG */
	
	public function setImgFileNameArray($imgFileNameArray){
		$this->_imgFileNameArray = $imgFileNameArray;
	}
	
	public function getImgFileNameArray(){
		return $this->_imgFileNameArray;
	}
	
	public function setImgRelativeFilePathArray($imgRelativeFilePathArray){
		$this->_imgRelativeFilePathArray = $imgRelativeFilePathArray;
	}
	
	public function getImgRelativeFilePathArray(){
		return $this->_imgRelativeFilePathArray;
	}
	
	public function setImgCommentArray($imgCommentArray){
		$this->_imgCommentArray = $imgCommentArray;
	}
	
	public function getImgCommentArray(){
		return $this->_imgCommentArray;
	}
	
	public function setImgWidthArray($widthArray){
		$this->_imgWidthArray = $widthArray;
	}
	
	public function getImgWidthArray(){
		return $this->_imgWidthArray;
	}
	
	public function setImgHeightArray($heightArray){
		$this->_imgHeightArray = $heightArray;
	}
	
	public function getImgHeightArray(){
		return $this->_imgHeightArray;
	}

	public function deleteImg($position) {
		unset($this->_imgFileNameArray[$position]);
		$this->_imgFileNameArray = array_values($this->_imgFileNameArray);
		
		unset($this->_imgRelativeFilePathArray[$position]);
		$this->_imgRelativeFilePathArray = array_values($this->_imgRelativeFilePathArray);
		
		unset($this->_imgCommentArray[$position]);
		$this->_imgCommentArray = array_values($this->_imgCommentArray);

		unset($this->_imgWidthArray[$position]);
		$this->_imgWidthArray = array_values($this->_imgWidthArray);
		
		unset($this->_imgHeightArray[$position]);
		$this->_imgHeightArray = array_values($this->_imgHeightArray);
	}
	
	/* MOV */
	
	public function setMovFileNameArray($movFileNameArray){
		$this->_movFileNameArray = $movFileNameArray;
	}
	
	public function getMovFileNameArray(){
		return $this->_movFileNameArray;
	}
	
	public function setMovRelativeFilePathArray($movRelativeFilePathArray){
		$this->_movRelativeFilePathArray = $movRelativeFilePathArray;
	}
	
	public function getMovRelativeFilePathArray(){
		return $this->_movRelativeFilePathArray;
	}
	
	public function setMovCommentArray($movCommentArray){
		$this->_movCommentArray = $movCommentArray;
	}
	
	public function getMovCommentArray(){
		return $this->_movCommentArray;
	}
	
	public function setMovWidthArray($widthArray){
		$this->_movWidthArray = $widthArray;
	}
	
	public function getMovWidthArray(){
		return $this->_movWidthArray;
	}
	
	public function setMovHeightArray($heightArray){
		$this->_movHeightArray = $heightArray;
	}
	
	public function getMovHeightArray(){
		return $this->_movHeightArray;
	}
	
	public function deleteMov($position) {
		unset($this->_movFileNameArray[$position]);
		$this->_movFileNameArray = array_values($this->_movFileNameArray);
		
		unset($this->_movRelativeFilePathArray[$position]);
		$this->_movRelativeFilePathArray = array_values($this->_movRelativeFilePathArray);
		
		unset($this->_movCommentArray[$position]);
		$this->_movCommentArray = array_values($this->_movCommentArray);
		
		unset($this->_movWidthArray[$position]);
		$this->_movWidthArray = array_values($this->_movWidthArray);
		
		unset($this->_movHeightArray[$position]);
		$this->_movHeightArray = array_values($this->_movHeightArray);
	}
	
	
	/* DOC */
	
	public function setDocFileNameArray($docFileNameArray){
		$this->_docFileNameArray = $docFileNameArray;
	}
	
	public function getDocFileNameArray(){
		return $this->_docFileNameArray;
	}
	
	public function setDocRelativeFilePathArray($docRelativeFilePathArray){
		$this->_docRelativeFilePathArray = $docRelativeFilePathArray;
	}
	
	public function getDocRelativeFilePathArray(){
		return $this->_docRelativeFilePathArray;
	}
	
	public function setDocCommentArray($docCommentArray){
		$this->_docCommentArray = $docCommentArray;
	}
	
	public function getDocCommentArray(){
		return $this->_docCommentArray;
	}
	
	public function deleteDoc($position) {
		unset($this->_docFileNameArray[$position]);
		$this->_docFileNameArray = array_values($this->_docFileNameArray);
		
		unset($this->_docRelativeFilePathArray[$position]);
		$this->_docRelativeFilePathArray = array_values($this->_docRelativeFilePathArray);
		
		unset($this->_docCommentArray[$position]);
		$this->_docCommentArray = array_values($this->_docCommentArray);
	}
}
?>