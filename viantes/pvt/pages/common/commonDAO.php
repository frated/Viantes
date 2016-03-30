<?php
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/cfg/conf.php";

class CommonDAO {

	/* Ottiene una connessione ad database */
	protected function getConn(){
		$usr = Conf::getInstance()->get('usr');
		$pwd = Conf::getInstance()->get('pwd');
		
		$mysqli = new mysqli("127.0.0.1", $usr, $pwd, "viantes");
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			return FALSE;
		}
		return $mysqli;
	}
	
	/* Memcache Get */
	protected function getCached($key){
		if (Conf::getInstance()->get('useCache') == 1){	
			$memcache = new Memcached;
			$memcache->addServer('127.0.0.1', 11211);
			return $memcache->get($key);
		}
		return false;
	}
	
	/* Memcache Set */
	protected function setCached($key, $val){
		if (Conf::getInstance()->get('useCache') == 1){
			$memcache = new Memcached;
			$memcache->addServer('127.0.0.1', 11211);
			$memcache->set($key, $val);
		}
		return false;
	}
	
	/* Memcache Del */
	protected function delCached($key){
		if (Conf::getInstance()->get('useCache') == 1){
			$memcache = new Memcached;
			$memcache->addServer('127.0.0.1', 11211);
			$memcache->delete($key);
		}
		return false;
	}
	
}
?>
