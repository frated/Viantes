<?php
/**
 * Questa classe mappa una riga della tabella MESSAGE 
 * Contiene inoltre le informazione della tabella relazionata USER
 */
 
class MsgDO {

	private $_id;
	private $_fromUsrId;
	private $_toUsrId;
	private $_dtIns;
	private $_subject;
	private $_message;
	private $_senderStatus;
	private $_recipientStatus;
	
	/* Oggetti Relazionati della tabella User */
	private $_fromUsrName;
	private $_fromUsrCoverFileName;
	
	private $_toUsrName;
	private $_toUsrCoverFileName;
	
	
    public function __construct($id, $fromUsrId, $toUsrId, $dtIns, $subject, $message, $senderStatus, $recipientStatus){
		$this->_id = $id;
		$this->_fromUsrId = $fromUsrId;
		$this->_toUsrId = $toUsrId;
		$this->_dtIns = $dtIns;
		$this->_subject = $subject;
		$this->_message = $message;
		$this->_senderStatus = $senderStatus;
		$this->_recipientStatus = $recipientStatus;
    }
	
	
	/**********/
	/* Getter */
	/**********/
	
	public function getId(){
		return $this->_id;
	}
	
	public function getFromUsrId(){
		return $this->_fromUsrId;
	}
	
	public function getToUsrId(){
		return $this->_toUsrId;
	}
	
	public function getDtIns(){
		return $this->_dtIns;
	}
	
	public function getSubject(){
		return $this->_subject;
	}
	
	public function getMessage(){
		return $this->_message;
	}
	
	public function getSenderStatus(){
		return $this->_senderStatus;
	}
	
	public function getRecipientStatus(){
		return $this->_recipientStatus;
	}
	
	
	/**********************************************/
	/* Metodi degli oggetti relazionati da UserDO */
	/**********************************************/
	
	public function setFromUsrName($fromUsrName){
		$this->_fromUsrName = $fromUsrName;
	}
	
	public function getFromUsrName(){
		return $this->_fromUsrName;
	}
	
	public function setFromUsrCoverFileName($fromUsrCoverFileName){
		$this->_fromUsrCoverFileName = $fromUsrCoverFileName;
	}
	
	public function getFromUsrCoverFileName(){
		return $this->_fromUsrCoverFileName;
	}
	
	public function setToUsrName($toUsrName){
		$this->_toUsrName = $toUsrName;
	}
	
	public function getToUsrName(){
		return $this->_toUsrName;
	}
	
	public function setToUsrCoverFileName($toUsrCoverFileName){
		$this->_toUsrCoverFileName = $toUsrCoverFileName;
	}
	
	public function getToUsrCoverFileName(){
		return $this->_toUsrCoverFileName;
	}
	
}
?>	