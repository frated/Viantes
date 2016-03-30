<?php
require_once $X_root."pvt/pages/cfg/conf.php";

class Logger {

    const DEB = 3;
    const INF = 2;
    const ERR = 1;
	const FAT = 0;
	
    private static $instance;

    private function __construct() {
    }

    private static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    private function writeToFile($message) {
		$date = date('Y-m-d');
		
		$logDir = Conf::getInstance()->get('logDir');
		$logName = Conf::getInstance()->get('logName');
		
		if (!file_exists($logDir)) {
			mkdir($logDir, 0604, false);
		}
		$fileName = $date.'_'.$logName;
        file_put_contents($logDir.$fileName, "$message\n", FILE_APPEND);
    }

    public static function log($message, $level = 3) {
		$logger = self::getInstance();
		
        $date = date('Y-m-d H:i:s');
		if ( $logger->isEnabledLevel($level) ) {
			$message = "$date ".$logger->getLevel($level)." :: ".$message;
			$logger->writeToFile($message);
		}
    }
	
    private function isEnabledLevel($level) {
		$logLevel = Conf::getInstance()->get('logLevel');
		return ($level <= $logLevel);
	}
	
	private function getLevel($level) {
		if ($level == 0) return "[FATAL] ";
		if ($level == 1) return "[ERROR] ";
		if ($level == 2) return "[INFO]  ";
		if ($level == 3) return "[DEBBUG]";
		return false;
	}
}
?>