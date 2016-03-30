<?php 
$X_root = "../../../";
$X_page = "searchReview";
session_start();
require_once $X_root."pvt/pages/const.php";
//prima di verificare la sessione salvo la richeesta
require_once $X_root."pvt/pages/globalFunction.php";
savePageRequest("/viantes/pub/pages/review/searchReview.php");
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/reviewDO.php";
require_once $X_root."pvt/pages/lang/languageDAO.php";
require_once $X_root."pvt/pages/setting/settingDAO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$userDO = unserialize($_SESSION["USER_LOGGED"]);

$languageDAO = New LanguageDAO();
$languageDOArray = $languageDAO->getLanguages();

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());
$defaultLangCod = "not. def.";

$searchCriteria = isset($_SESSION["SEARCH_REVIEW_SEARCH_CRITERIA"]) ? $_SESSION["SEARCH_REVIEW_SEARCH_CRITERIA"] : null;
?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-US">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-US">
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html><!--<![endif]-->

<head>
	<title><?php echo $X_langArray['SEARCH_REVIEW_PAGE_TOP_MSG'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay delete item  -->
<?php require_once $X_root."pvt/pages/common/overlay-del-item.html"; ?>
					
<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div id="main-div" class="main-div">
		
		<div class="body-div">
			<form action="/viantes/pvt/pages/review/search/searchReviewFromAdv.php" method="post">
				
				<!-- Top Msg -->
				<div>
					<div class="top-header">
						<h1><?php echo  $X_langArray['SEARCH_REVIEW_PAGE_TOP_MSG']?></h1>
					</div>
				</div>
				
				<!-- Text -->
				<div class="mrg-top-24">
					<p> <?php echo  $X_langArray['SEARCH_REVIEW_SEARCH_SITE_NAME']?> </p>
				</div>
				
				<!-- Input field -->
				<div class="mrg-top-12">
					<input type="text" name="kwrds" class="searchRevInput" 
						   value="<?php echo isset($_GET['kwrds']) ? urldecode($_GET['kwrds']) : ( $searchCriteria != null ? $searchCriteria['kwrds'] : "" ); ?>"/>
				</div>
				
				<!-- Error Msg -->
				<?php if ( isset($_GET['kwrdsErrMsg']) ) { ?>
					<div class="mrg-top-6">
						<p class="p-error"><?php echo urldecode($_GET['kwrdsErrMsg']); ?></p>
					</div>
				<?php } ?>
				
				<div class="mrg-top-24">
					<?php 
					/* ----------------------------------------------------------------------------
					 * La variabile $_GET serve oer rivalorizzare in caso di errore
					 * La variabile $searchCriteria serve per rivalorizzare in caso di ritorna indietro 
					 * Le due sono mutuamente esclusuve perche nella serachReview.php e' la variabile
					 * $searchCriteria e' settata solo se non ci sono errori, mentre la $_GET solo se ce ne sono
					 */
					if (!isset($_GET['reviewType']) && $searchCriteria == null) {
						$sel1 = "checked";
						$sel2 = "";
						$sel3 = "";
					}
					else if( isset($_GET['reviewType']) ) {
						$sel1 = "1" == $_GET['reviewType'] ? "checked" : "";
						$sel2 = "2" == $_GET['reviewType'] ? "checked" : "";
						$sel3 = "3" == $_GET['reviewType'] ? "checked" : "";
					}
					else if ( $searchCriteria != null ) {
						$sel1 = "1" == $searchCriteria['type'] ? "checked" : "";
						$sel2 = "2" == $searchCriteria['type'] ? "checked" : "";
						$sel3 = "3" == $searchCriteria['type'] ? "checked" : "";
					}	
					?>
					
					<input type="radio" name="reviewType" value="1" <?php echo $sel1?>/>
					<p class="searchRevRadio"><?php echo  $X_langArray['SEARCH_REVIEW_SEARCH_TYPE_1']?></p>
					<br>
					
					<input type="radio" name="reviewType" value="2" <?php echo $sel2?>/>
					<p class="searchRevRadio"><?php echo  $X_langArray['SEARCH_REVIEW_SEARCH_TYPE_2']?></p>
					<br>
					
					<input type="radio" name="reviewType" value="3" <?php echo $sel3?>/>
					<p class="searchRevRadio"><?php echo  $X_langArray['SEARCH_REVIEW_SEARCH_TYPE_3']?></p>
				</div>
				
				
				<div id="searchReviewAdv" class="mrg-bot-24">
					<hr class="searchRevHR">
					
					<div class="second-header-font14">	</div>
					
					<div class="mrg-top-12">
						<?php
						$selChk1 =  (isset($_GET['onlyImg']) && $_GET['onlyImg'] == 1) || ($searchCriteria != null && $searchCriteria['onlyImg'] == 1) ? "checked" : "";
						$selChk2 =  (isset($_GET['onlyMov']) && $_GET['onlyMov'] == 1) || ( $searchCriteria != null && $searchCriteria['onlyMov'] == 1) ? "checked" : "";
						$selChk3 =  (isset($_GET['onlyDoc']) && $_GET['onlyDoc'] == 1) || ( $searchCriteria != null && $searchCriteria['onlyDoc'] == 1) ? "checked" : "";
						?>
						<div>
							<input type="checkbox" name="onlyImg" <?php echo $selChk1; ?> >
							<p class="searchRevRadio">
								<?php echo  $X_langArray['SEARCH_REVIEW_ADV_ONLY_IMG']?>
							</p>
						</div>
						<div>
							<input type="checkbox" name="onlyMov" <?php echo $selChk2; ?> >
							<p class="searchRevRadio">
								<?php echo  $X_langArray['SEARCH_REVIEW_ADV_ONLY_MOV']?>
							</p>
						</div>
						<div>
							<input type="checkbox" name="onlyDoc" <?php echo $selChk3; ?> >
							<p class="searchRevRadio">
								<?php echo  $X_langArray['SEARCH_REVIEW_ADV_ONLY_DOC']?>
							</p>
						</div>
						<div class="mrg-top-12 mrg-bot-6"> 
							<?php echo  $X_langArray['SEARCH_REVIEW_ADV_LANG']?>
							<select name="langCode" class="searchRevSelect">
								<?php  foreach ($languageDOArray as $language) { 
									$langCode = $language->getLangCode();
									$s = "";
									//se e' settato il langCode della GET (in caso di errore) ripropongo quel valore
									if (isset($_GET['langCode']) && $langCode == $_GET['langCode']) {
										$s = " selected " ; 
										$defaultLangCod = $langCode; 
									}
									if (!isset($_GET['langCode']) && $searchCriteria != null && $langCode == $searchCriteria['langCode']) {
										$s = " selected " ; 
										$defaultLangCod = $langCode; 
									}
									else if (!isset($_GET['langCode'])  && $searchCriteria == null && $langCode == $settingDO->getLangCode() ) { 
										$s = " selected " ; 
										$defaultLangCod = $langCode; 
									} ?>
									<option <?php echo $s ?> value="<?php echo $langCode ?>"><?php echo $X_langArray[$langCode] ?></option>
								<?php } ?>		
							</select>
						</div>
					</div>
				</div>
				
				<div class="mrg-top-24 mrg-bot-24" >
					<input class="advSrchSub" type="submit" value="<?php echo  $X_langArray['SEARCH_REVIEW_SEARCH_BUTTON']?> " />
				</div>
				
				<!-- Tipo di ordinamento -->
				<input type="hidden" name="orderType" value="0" />
			</form>
		</div>
		
		<?php require_once $X_root."pvt/pages/common/right_section.html"; ?>
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
			
</body>
</html>
