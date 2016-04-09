<?php 
$X_root = $_SERVER['DOCUMENT_ROOT']."/viantes/";
$X_page = "error";
session_start();
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/const.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/globalFunction.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/common/checkMobile.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/cfg/conf.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/checkSession4Pub.php";
require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);
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
	<title><?php echo $X_langArray['ERROR_TITLE'] ?></title>
	<?php include $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay-login-signin -->
<?php include $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/common/overlay-login-signin.html"; ?>
<input type="hidden" id="ovrly-initial-src-page" value="/index.php" />
<input type="hidden" id="ovrly-initial-login-dst-page" value="/index.php" />
<input type="hidden" id="ovrly-initial-sign-dst-page" value="/index.php" />

<body>
	<?php require_once $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/common/header.html"; ?>
	
	<div id="main-div" class="main-div">
	
		<div class="body-div">			
			<div>
				<div class="top-header">
					<h1><?php echo $X_langArray['ERROR_H1'] ?></h1>
				</div>
				<div>
					<!-- Descrizione errore -->
					<?php if (isset($_GET['reason']) && $_GET['reason'] != "" ) { ?>
						<p class="info-general"><?php echo urldecode($_GET['reason']) ?></p>
					<?php } else{ ?>
						<p class="info-general"><?php echo $X_langArray['ERROR_REASON_UNEXPECTED'] ?></p>
					<?php } ?>
					<br>
					
					<!-- Link close o link home -->
					<?php if (isset($_GET['closeble']) && $_GET['closeble'] != "" ) { ?>
						<a href="#" onclick="window.close()"><?php echo $X_langArray['ERROR_CLOSE'] ?></a>
					<?php } else{ ?>
						<p class="info-general"><?php echo $X_langArray['ERROR_BACK_TO_HOME'] ?></p>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	
	<?php include $_SERVER['DOCUMENT_ROOT']."/viantes/pvt/pages/common/footer.html"; ?>
			
</body>
</html>
