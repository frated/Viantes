<?php 
$X_root = "../../../../../viantes/";
$X_page = "myReview";
session_start();
require_once $X_root."pvt/pages/const.php";
//prima di verificare la sessione salvo la richeesta
require_once $X_root."pvt/pages/globalFunction.php";
savePageRequest("/viantes/pub/pages/review/myReview.php");
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$userDO = unserialize($_SESSION["USER_LOGGED"]);
$X_userId = $userDO->getId();

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());

$X_pattern = getDatePatternByLangCode($settingDO->getLangCode());
Logger::log("myProfile :: pattern per data rilevato :: ".$X_pattern, 3);
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
	<title><?php echo $X_langArray['MY_REV_PAGE_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html";?>
	
	<div class="main-div">

		<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
		
		<!-- MY REVIEW - Header -->
		<div class="myRevHeaderDiv">
			<div class="top-header">
				<h1><?php echo $X_langArray['MY_REV_PAGE_H3'] ?></h1>
			</div>	
			<div class="myRevDisclaimerDiv">
				<p><?php echo $X_langArray['MY_REV_PAGE_DISCL'] ?></p>
			</div>
			<div  class="myRevCreateRevLink personalButton">
				<a href="/viantes/pub/pages/review/createReview.php">
					<!--img class="myRevCreareRevImg" src="/viantes/pvt/img/review/review_18.png" -->
					<?php echo $X_langArray['MY_REV_CREATE_REV']?>
				</a>
			</div>
		</div>
	
		<div id="setting_top_div">
			<h3><?php echo $X_langArray['MYPROFILE_MY_REVIEW'] ?></h3>
		</div>					
		<?php include $X_root."pvt/pages/review/common/userReview.php" ?>
		
	</div>	
		
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>		
	
</body>
</html>
