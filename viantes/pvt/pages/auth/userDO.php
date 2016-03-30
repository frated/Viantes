<?php
/**
 * Questa classe mappa una riga della tabella USER 
 * Non contiene FK verso altre tabelle.
 */

class UserDO {

	private $_id;
	private $_email;
	private $_name;
	private $_coverFileName;
	private $_bckCoverFileName;
	private $_inBoxMsgNum;
	private $_starArray;
		
    public function __construct($id, $email, $name){
		$this->_id = $id;
		$this->_email = $email;
		$this->_name = $name;
    }
	
	public function getId(){
		return $this->_id;
	}
	
	public function getName(){
		return $this->_name;
	}
	
	public function getEmail(){
		return $this->_email;
	}

	public function getCoverFileName(){
		return $this->_coverFileName;
	}

	public function setCoverFileName($coverFileName){
		$this->_coverFileName = $coverFileName;
	}	

	public function getBckCoverFileName(){
		return $this->_bckCoverFileName;
	}

	public function setBckCoverFileName($bckCoverFileName){
		$this->_bckCoverFileName = $bckCoverFileName;
	}
	
	public function getInBoxMsgNum(){
		return $this->_inBoxMsgNum;
	}
	
	public function setInBoxMsgNum($inBoxMsgNum){
		$this->_inBoxMsgNum = $inBoxMsgNum;
	}
	
	public function getStar(){
		return $this->_starArray;
	}
	
	public function setStar($starArray){
		$this->_starArray = $starArray;
	}
}
?>