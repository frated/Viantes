<?php
class AttachDO {

	private $_id;
	private $_reviewId;
	private $_usrId;
	private $_filePath;
	private $_fileName;
	private $_dt_ins;
	private $_typ;
	private $_xDim;
	private $_yDim;
	private $_comment;
		
    public function __construct($id, $reviewId, $usrId, $filePath, $fileName, $xDim, $yDim, $typ, $comment) {
		$this->_id = $id;
		$this->_reviewId = $reviewId;
		$this->_usrId = $usrId;
		$this->_filePath = $filePath;
		$this->_fileName = $fileName;
		$this->_typ = $typ;
		$this->_xDim = $xDim;
		$this->_yDim = $yDim;
		$this->_comment= $comment;
    }
	
	public function getId(){
		return $this->_id;
	}
	
	public function getReviewId(){
		return $this->_reviewId;
	}
	
	public function getDataFile(){
		return $this->_dataFile;
	}

	public function getFilePath(){
		return $this->_filePath;
	}
	
	public function getFileName(){
		return $this->_fileName;
	}
	
	public function getDtIns(){
		return $this->_dt_ins;
	}
	
	public function getType(){
		return $this->_typ;
	}

	public function getXDim(){
		return $this->_xDim;
	}
	
	public function getYDim(){
		return $this->_yDim;
	}
	
	public function getComment(){
		return $this->_comment;
	}
}
?>