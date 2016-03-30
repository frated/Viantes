<?php
/**
 * Questa classe mappa una riga della tabella SETTING_USER 
 * Contiene inoltre le informazione della tabella relazionata LANG
 */

class SettingDO {

    private $_langCode;
	private $_profileType;
	
    public function __construct($langCode, $profileType){
		$this->_langCode = $langCode;
		$this->_profileType = $profileType;
    }
	
	public function getLangCode(){
		return $this->_langCode;
	}
	
	public function getProfileType(){
		return $this->_profileType;
	}
}
?>