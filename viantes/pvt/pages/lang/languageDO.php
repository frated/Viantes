<?php
/**
 * Questa classe mappa una riga della tabella LANG 
 * Non contiene FK verso altre tabelle.
 */
class LanguageDO {

    private $_langCode;
	private $_langDesc;
		
    public function __construct($langCode, $langDesc){
		$this->_langCode = $langCode;
		$this->_langDesc = $langDesc;
    }
		
	public function getLangCode(){
		return $this->_langCode;
	}
	
	public function getLangDesc(){
		return $this->_langDesc;
	}
}
?>
