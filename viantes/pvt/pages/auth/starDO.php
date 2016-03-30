<?php
class StarDO {
	
	private $_palceId;
	private $_placeName;
	private $_author;
	private $_star;
	private $_see;
	private $_post;
	
	//altre info sull'utente
	private $_usrId;
	private $_usrCover;
	
    public function __construct($palceId, $placeName, $author, $star, $see, $post){
		$this->_palceId = $palceId;
		$this->_placeName = $placeName;
		$this->_author = $author;
		$this->_star = $star;
		$this->_see = $see;
		$this->_post = $post;
    }
	
	public function getPpalceId(){
		return $this->_palceId;
	}
	
	public function getPplaceName(){
		return $this->_placeName;
	}
	
	public function getAuthor(){
		return $this->_author;
	}

	public function getStar(){
		return $this->_star;
	}

	public function getSee(){
		return $this->_see;
	}

	public function getPost(){
		return $this->_post;
	}
	
	public function setUsrId($usrId){
		return $this->_usrId = $usrId;
	}
	
	public function getUsrId(){
		return $this->_usrId;
	}

	public function setUsrCover($usrCover){
		return $this->_usrCover = $usrCover;
	}
	
	public function getUsrCover(){
		return $this->_usrCover;
	}
	
	/**
	 * Ritorna l'oggetto come array. Usato nel json_encode
	 */
	public function toArray(){
		$a = array();
		
		$a['palceId'] = $this->_palceId;
		$a['author'] = $this->_author;
		$a['star'] = $this->_star;
		$a['see'] = $this->_see;
		$a['post'] = $this->_post;
		if(isset($this->_usrId)) $a['usrId'] = $this->_usrId;
		if(isset($this->_usrCover))	$a['usrCover'] = $this->_usrCover;
		
		return $a;	
	}
}
?>
