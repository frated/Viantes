<?php
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/common/commonDAO.php";
require_once $X_root."pvt/pages/msg/msgDO.php";

class MsgDAO extends CommonDAO {

    public function __construct(){
    }

    /** 
 	 * Ritorna tutti i messaggi inviati dall'utente
	 */
	public function getSentMessages($usrId,  $pattern){
		$sql = sprintf("SELECT M.ID, M.FROMUSRID, M.TOUSRID, DATE_FORMAT(M.DTINS, '%s'), M.SUBJECT, M.MESSAGE, M.SENDERSTATUS, M.RECIPIENTSTATUS,
							   U1.NAME, U1.COVERFILENAME, U2.NAME, U2.COVERFILENAME
						FROM MESSAGE M JOIN USER U1 on M.FROMUSRID = U1.ID JOIN USER U2 on M.TOUSRID = U2.ID
						WHERE M.FROMUSRID = %d
						AND SENDERSTATUS > 0
						ORDER BY M.DTINS DESC", $pattern, $usrId);
						
		return $this->doQuery($sql);
    }	
	
	/** 
	 * Ritorna tutti i messaggi in bozza dall'utente
	 */
	public function getDraftMessages($usrId,  $pattern){
		$sql = sprintf("SELECT M.ID, M.FROMUSRID, M.TOUSRID, DATE_FORMAT(M.DTINS, '%s'), M.SUBJECT, M.MESSAGE, M.SENDERSTATUS, M.RECIPIENTSTATUS,
							   U1.NAME, U1.COVERFILENAME, U2.NAME, U2.COVERFILENAME
						FROM MESSAGE M JOIN USER U1 on M.FROMUSRID = U1.ID JOIN USER U2 on M.TOUSRID = U2.ID
						WHERE M.FROMUSRID = %d
						AND SENDERSTATUS = 0
						ORDER BY M.DTINS DESC", $pattern, $usrId);
		
		return $this->doQuery($sql);
    }	

	/** 
	 * Ritorna tutti i messaggi ricevuti dall'utente
	 */
	public function getReceivedMessages($usrId,  $pattern){
		$sql = sprintf("SELECT M.ID, M.FROMUSRID, M.TOUSRID, DATE_FORMAT(M.DTINS, '%s'), M.SUBJECT, M.MESSAGE, M.SENDERSTATUS, M.RECIPIENTSTATUS,
							   U1.NAME, U1.COVERFILENAME, U2.NAME, U2.COVERFILENAME
						FROM MESSAGE M JOIN USER U1 on M.FROMUSRID = U1.ID JOIN USER U2 on M.TOUSRID = U2.ID
						WHERE M.TOUSRID = %d
						AND RECIPIENTSTATUS >= 0
						AND SENDERSTATUS = 1
						ORDER BY M.DTINS DESC", $pattern, $usrId);
		
		return $this->doQuery($sql);
    }		
	
	/** 
	 * Ritorna tutti i messaggi ricevuti dall'utente
	 */
	public function getDeletedMessages($usrId,  $pattern){
		$sql = sprintf("SELECT M.ID, M.FROMUSRID, M.TOUSRID, DATE_FORMAT(M.DTINS, '%s'), M.SUBJECT, M.MESSAGE, M.SENDERSTATUS, M.RECIPIENTSTATUS,
							   U1.NAME, U1.COVERFILENAME, U2.NAME, U2.COVERFILENAME
						FROM MESSAGE M JOIN USER U1 on M.FROMUSRID = U1.ID JOIN USER U2 on M.TOUSRID = U2.ID
						WHERE ( M.TOUSRID = %d	AND RECIPIENTSTATUS < 0 AND RECIPIENTSTATUS >-3 )
						OR    ( M.FROMUSRID= %d	AND SENDERSTATUS < 0 AND SENDERSTATUS >-3 )
						ORDER BY M.DTINS DESC", $pattern, $usrId, $usrId);
		
		return $this->doQuery($sql);
    }
	
	/** 
	 * Ritorna tutti i messaggi ricevuti dall'utente
	 */
	public function getMsgById($id,  $pattern){
		$sql = sprintf("SELECT M.ID, M.FROMUSRID, M.TOUSRID, DATE_FORMAT(M.DTINS, '%s'), M.SUBJECT, M.MESSAGE, M.SENDERSTATUS, M.RECIPIENTSTATUS,
							   U1.NAME, U1.COVERFILENAME, U2.NAME, U2.COVERFILENAME
						FROM MESSAGE M JOIN USER U1 on M.FROMUSRID = U1.ID JOIN USER U2 on M.TOUSRID = U2.ID
						WHERE M.ID = %d
						ORDER BY M.DTINS DESC", $pattern, $id);
		
		$msgDOArray = $this->doQuery($sql);
		if (count($msgDOArray) >0) return $msgDOArray[0];
		return false;
    }
	
	/** 
	 * Esegue la parte comune di ogni metodo del DAO
	 */
	private function doQuery($sql){
		$msgDOArray = array();
		
		$mysqli = $this->getConn();
		Logger::log("MsgDAO :: doQuery :: query: ".$sql, 3);
	
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $fromUsrId, $toUsrId, $dtIns, $subject, $message, $senderStatus, $recipientStatus,
							   $name1, $coverFileName1, $name2, $coverFileName2);
			while($stmt->fetch()) {
				$message = str_replace("\r\n",'<br>', $message);
				
				//Costruttore
				$msgDO = New MsgDO($id, $fromUsrId, $toUsrId, $dtIns, $subject, $message, $senderStatus, $recipientStatus);
				
				//Setto i campi in relazione
				$msgDO->setFromUsrName($name1);
				$msgDO->setFromUsrCoverFileName($coverFileName1);
				$msgDO->setToUsrName($name2);
				$msgDO->setToUsrCoverFileName($coverFileName2);
				
				//push elemento
				array_push($msgDOArray, $msgDO);
			}
			$stmt->free_result();
		}
		//close
		$mysqli->close();
		return $msgDOArray;
    }	
	
	/** 
	 * Invia o salva in bozze un messaggio 
	 */
	public function sendMsg($fromUsrId, $toUsrId, $sbjt, $message, $status){
		$mysqli = $this->getConn();
		
		$sql = sprintf("INSERT INTO MESSAGE (FROMUSRID, TOUSRID, SUBJECT, MESSAGE, SENDERSTATUS) VALUES ( %d, %d, '%s', '%s', %d )",
						$fromUsrId, $toUsrId, $sbjt, $message, $status);
		Logger::log("MsgDAO :: sendMsg :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		
		//close
		$mysqli->close();
		return TRUE;
    }	
	
	/** 
	 * Invia o salva in bozze un messaggio che era in bozze
	 */
	public function sendMsgInDraft($id, $fromUsrId, $toUsrId, $sbjt, $message, $status){
		$mysqli = $this->getConn();
		
		$sql = sprintf("UPDATE MESSAGE SET FROMUSRID =  %d, TOUSRID = %d, SUBJECT = '%s', MESSAGE ='%s', SENDERSTATUS = %d, DTINS = SYSDATE() 
						WHERE ID = %d ", $fromUsrId, $toUsrId, $sbjt, $message, $status, $id);
		Logger::log("MsgDAO :: sendMsgInDraft :: query: ".$sql, 3);
		
		$mysqli->query($sql);
		
		//close
		$mysqli->close();
		return TRUE;
    }	
	
	/** 
	 * Cancella una lista di messaggi dai messaggi inviati
	 */
	public function delSentMsg($ids){
		$mysqli = $this->getConn();
		
		foreach( $ids as $key => $val) {
			$sql = sprintf("UPDATE MESSAGE SET SENDERSTATUS = -2 WHERE ID = %d", $val);
			Logger::log("MsgDAO :: delSentMsg :: query: ".$sql, 3);
			$mysqli->query($sql);
		}
		
		//close
		$mysqli->close();
		return TRUE;
    }
	
	/** 
	 * Cancella una lista di messaggi dalle bozze
	 */
	public function delDraftMsg($ids){
		$mysqli = $this->getConn();
		
		foreach( $ids as $key => $val) {
			$sql = sprintf("UPDATE MESSAGE SET SENDERSTATUS = -1 WHERE ID = %d", $val);
			Logger::log("MsgDAO :: delDraftMsg :: query: ".$sql, 3);
			$mysqli->query($sql);
		}
		
		//close
		$mysqli->close();
		return TRUE;
    }
	
	/** 
	 * Cancella una lista di messaggi dai messaggi in arrivo
	 */
	public function delInMsg($ids){
		$mysqli = $this->getConn();
		
		foreach( $ids as $key => $val) {
			//one of these will not update
			$sql = sprintf("UPDATE MESSAGE SET RECIPIENTSTATUS = -2 WHERE ID = %d AND RECIPIENTSTATUS =1", $val);
			Logger::log("MsgDAO :: delInMsg :: query: ".$sql, 3);
			$mysqli->query($sql);
			
			$sql = sprintf("UPDATE MESSAGE SET RECIPIENTSTATUS = -1 WHERE ID = %d AND RECIPIENTSTATUS =0", $val);
			Logger::log("MsgDAO :: delInMsg :: query: ".$sql, 3);
			$mysqli->query($sql);
		}
		
		//close
		$mysqli->close();
		return TRUE;
    }	
	
	/** 
	 * Cancella definitivamente dal cestino una lista di messaggi
	 */
	public function delTrashMsg($ids, $usrId){
		$mysqli = $this->getConn();
		
		foreach( $ids as $key => $val) {
			//one of these will not update
			$sql = sprintf("UPDATE MESSAGE SET SENDERSTATUS = -3 WHERE ID = %d AND FROMUSRID=%d", $val, $usrId);
			$mysqli->query($sql);
			Logger::log("MsgDAO :: delTrashMsg :: query: ".$sql, 3);
			
			$sql = sprintf("UPDATE MESSAGE SET RECIPIENTSTATUS = -3 WHERE ID = %d AND TOUSRID=%d", $val, $usrId);
			$mysqli->query($sql);
			Logger::log("MsgDAO :: delTrashMsg :: query: ".$sql, 3);
		}
		
		//close
		$mysqli->close();
		return TRUE;
    }	
	
	/** 
	 * Porta lo sato del messaggio a letto
	 */
	public function readMesasge($msgId, $toUsrId){
		$mysqli = $this->getConn();
		
		$sql = sprintf("UPDATE MESSAGE SET RECIPIENTSTATUS = 1 WHERE ID = %d AND TOUSRID=%d", $msgId, $toUsrId);
		$mysqli->query($sql);
		Logger::log("MsgDAO :: readMesasge :: query: ".$sql, 3);	
			
		//close
		$mysqli->close();
		return TRUE;
    }
	
	/** 
	 * Ripristina i messaggi cancellati
	 */
	public function restoreDelMsg($ids, $usrId){
		$v = "a ";
		$mysqli = $this->getConn();
		foreach( $ids as $key => $val) {
			//3 of these will not update
			$sql = sprintf("UPDATE MESSAGE SET SENDERSTATUS = 1 WHERE ID = %d AND FROMUSRID=%d AND SENDERSTATUS=-2", $val, $usrId);
			$mysqli->query($sql);
			Logger::log("MsgDAO :: delTrashMsg :: query: ".$sql, 3);

			$sql = sprintf("UPDATE MESSAGE SET SENDERSTATUS = 0 WHERE ID = %d AND FROMUSRID=%d AND SENDERSTATUS=-1", $val, $usrId);
			$mysqli->query($sql);
			Logger::log("MsgDAO :: delTrashMsg :: query: ".$sql, 3);
			
			$sql = sprintf("UPDATE MESSAGE SET RECIPIENTSTATUS = 1 WHERE ID = %d AND TOUSRID=%d AND RECIPIENTSTATUS = -2", $val, $usrId);
			$mysqli->query($sql);
			Logger::log("MsgDAO :: delTrashMsg :: query: ".$sql, 3);
			
			$sql = sprintf("UPDATE MESSAGE SET RECIPIENTSTATUS = 0 WHERE ID = %d AND TOUSRID=%d AND RECIPIENTSTATUS = -1", $val, $usrId);
			$mysqli->query($sql);
			Logger::log("MsgDAO :: delTrashMsg :: query: ".$sql, 3);
		}
		
		//close
		$mysqli->close();
		return TRUE;
    }	
	
	
    /** 
	 * Ritorna il numero di messaggi da leggere
	 */
	public function getToBeReadMsgNum($usrId){
		$sql = sprintf("SELECT COUNT(*)
						FROM MESSAGE M JOIN USER U1 on M.FROMUSRID = U1.ID JOIN USER U2 on M.TOUSRID = U2.ID
						WHERE M.TOUSRID = %d
						AND RECIPIENTSTATUS = 0
						AND SENDERSTATUS = 1", $usrId);
		
		$mysqli = $this->getConn();
		Logger::log("MsgDAO :: getToBeReadMsgNum :: query: ".$sql, 3);
	
		$result = 0;
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($num);
			if ($stmt->fetch()) {
				$result = $num;
			}
			$stmt->free_result();
		}
		//close
		$mysqli->close();
		return $result;
    }
}
?>