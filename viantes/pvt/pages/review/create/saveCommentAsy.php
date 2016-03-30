<?php
/**
 * Questo script salva i commenti di un'immagine/video/documento in un CommonReviewBean. 
 * A run time si stabilisce la specializzazione ReviewBean, CityReviewBean o CountryReviewBean
 * @param id: valorizzato nel modo seguente:'Xxxj' con Xxx in {Img, Mov o Doc} e j 
 * intero >= 0 che indica la posizione a cui si riferisce quel commento.
 * @param val: contiene il valore del commento
 * @param name: contiene la chiave di sessione del bean a runtime
 * @see renderImgFromSession.php, renderMovFromSession.php, renderDocFromSession.php
 */
$X_root = '../../../../';
session_start();
require_once $X_root."pvt/pages/review/cityReviewBean.php";
require_once $X_root."pvt/pages/review/countryReviewBean.php";
require_once $X_root."pvt/pages/review/reviewBean.php";

if (isset($_GET['id']) && isset($_GET['val'])) {
	$commentId = $_GET['id']; //formato Mov5
	$type      = substr($commentId, 0,3);
	$position  = substr($commentId, 3);
	
	$val = strlen($_GET['val']) > 250 ? substr($_GET['val'], 0, 250) : $_GET['val'];
	
	$bean = unserialize($_SESSION[$_GET['name']]);
	if ($type == 'Img') {
		$arr = $bean->getImgCommentArray();
		$arr[$position] = $val;
		$bean->setImgCommentArray($arr);
	}
	
	if ($type == 'Mov') {
		$arr = $bean->getMovCommentArray();
		$arr[$position] = $val;
		$bean->setMovCommentArray($arr);
	}
	if ($type == 'Doc') {
		$arr = $bean->getDocCommentArray();
		$arr[$position] = $val;
		$bean->setDocCommentArray($arr);
	}
	//print_r($bean);
	$_SESSION[$_GET['name']] = serialize($bean);
}
?>
