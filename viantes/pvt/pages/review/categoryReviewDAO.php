<?php
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/review/categoryReviewDO.php";
include_once $X_root."pvt/pages/common/commonDAO.php";

class CategoryReviewDAO extends CommonDAO {

	public function __construct(){
    }

	/* Ritorna la lista delle categorie principali e delle categorie sottostanti*/
	public function retrieveCategoriesByLangCode($langCode){
		
		$key = $this->getRetrieveCategoriesByLangCodeKey($langCode);
		$result = $this->getCached($key);
		if ($result) return $result;

		$catRevDOArray = array();
	
		$mysqli = $this->getConn();
		
		$sql = sprintf("SELECT T1.id, T1.idCategoryMain, T1.category FROM (
							SELECT -1 as id, id as idCategoryMain, categoryMain as category
							FROM CATEGORY_REVIEW_MAIN
							WHERE langCode = '%s'
							UNION
							SELECT id as id, idCategoryMain as idCategoryMain, category as category
							FROM CATEGORY_REVIEW
							WHERE langCode = '%s'
						) As T1
						ORDER BY T1.idCategoryMain , T1.category asc", $langCode, $langCode);
		
		Logger::log("CategoryReviewDAO :: retrieveCategoriesByLangCode :: query: ".$sql, 3);
		
		if ($stmt = $mysqli->prepare($sql)) {
			$stmt->execute();
			$stmt->bind_result($id, $idCategoryMain, $category);
			while ($stmt->fetch()) {
				$catRevDO = New CategoryReviewDO($id, $idCategoryMain, $category);
				array_push($catRevDOArray, $catRevDO);
			}
			
			//free result
			$stmt->free_result();
			
			//close
			$mysqli->close();
			$this->setCached($key, $catRevDOArray);
			return $catRevDOArray;			
		}
		
		Logger::log("CategoryReviewDAO :: retrieveCategoriesByLangCode :: Attenzione nessuna categoria trovata ", 1);
		
		//close
		$mysqli->close();
		return FALSE;
    }
    
    /* return getRetrieveCategoriesByLangCode key */
	private function getRetrieveCategoriesByLangCodeKey($langCode){
		return "CategoryReviewDAO" . "getRetrieveCategoriesByLangCode" . $langCode;
	}
}
?>
