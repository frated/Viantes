<?php
/**
 * Cancella un elemento specifico da un oggetto che estende la CommonReviewBean.
 * @name: specifica la chiave di sessione del bean che estende la CommonReviewBean.
 * @type: tipo di elemento da eliminare {CVR=Immagine di coperina, IMG=Immagine,..}
 * @index:la posizione dell'elemento da eliminare se esso e' un array
 */
$X_root = '../../../../';
session_start();
require_once $X_root."pvt/pages/checkSession4Script.php";
require_once $X_root."pvt/pages/review/cityReviewBean.php";
require_once $X_root."pvt/pages/review/countryReviewBean.php";
require_once $X_root."pvt/pages/review/reviewBean.php";

if (isset($_POST['name'])) {	
	$bean = unserialize($_SESSION[$_POST['name']]);
	if ($_POST['type'] == 'CVR') {
		$bean->setCoverFileName(null);
	}
	else if ($_POST['type'] == 'IMG') {
		$ojb = $bean->deleteImg($_POST['index']);
	}
	else if ($_POST['type'] == 'MOV') {
		$ojb = $bean->deleteMov($_POST['index']);
	}
	else if ($_POST['type'] == 'DOC') {
		$ojb = $bean->deleteDoc($_POST['index']);
	}
	$_SESSION[$_POST['name']] = serialize($bean);
}
?>
