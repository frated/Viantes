<?php 
$X_root = "../../";
$X_page = "who";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
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
	<title><?php echo //$X_langArray['TERMS_TITLE'] 
				'Chi Siamo'?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay-login-signin -->
<?php require_once $X_root."pvt/pages/common/overlay-login-signin.html"; ?>
<input type="hidden" id="ovrly-initial-src-page" value="/viantes/pub/pages/who.php" />
<input type="hidden" id="ovrly-initial-login-dst-page" value="/viantes/pub/pages/who.php" />
<input type="hidden" id="ovrly-initial-sign-dst-page" value="/viantes/pub/pages/who.php" />


<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div id="main-div" class="main-div">
	
		<div class="body-div">			
			<div>
				<div class="top-header">
					<h1><?php echo //$X_langArray['TERMS_H1']
							'Chi Siamo'?></h1>
				</div>
				
				<div>
					<p class="info-general"><?php echo //$X_langArray['TERMS_DISCL']
					'Viantes &egrave; un progetto realizzato da Francesco Tedesco, un ingegnere informatico esperto in programmazione <i>web-oriented</i>.<br>'.
					'Francesco vuole inserire il progetto all\'interno di un pensiero pi&ugrave; ampio; una filosofia basata sulla <i>libera condivisione dell&rsquo;informazione</i>.<br><br>'.
					'Francesco &egrave; convinto che oggi ci si tanta informazione superflua, e spesso inutile o addirittura fuorviante; quindi la filosofia di condividere informazione non pu&ograve; prescindere dalla necessit&agrave; di organizzarla e renderla accessibile in modo semplice, efficace e completo.<br><br>'.
					'Francesco spera di aver messo a disposizione della comunit&agrave; non solo un mezzo ma anche una nuova idea di pensiero.'?></p>
				</div>
			</div>
			
			<br><br>
			<div>
			
			</div>
		</div>	
		
		<?php require_once $X_root."pvt/pages/common/right_section.html"; ?>
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
			
</body>
</html>
