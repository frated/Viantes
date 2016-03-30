<?php
ini_set('display_errors', 1);
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
require_once $X_root."pvt/pages/review/categoryReviewDAO.php";

$dao = New CategoryReviewDAO();

/*
 * Test me to /viantes/tst/testCategoryReviewDAO.php?method=...
 */
 
$lang='it';

// Method= 1 - Test getLazyUserDO
if ($_GET['method']==1){
	$dao->retrieveCategoriesByLangCode($lang); //va sulla base dati
	$dao->retrieveCategoriesByLangCode($lang); //usa memcache
}
?>
