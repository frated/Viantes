<?php 
$X_root = "../../";
$X_page = "mission";
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
				'La Mission'?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay-login-signin -->
<?php require_once $X_root."pvt/pages/common/overlay-login-signin.html"; ?>
<input type="hidden" id="ovrly-initial-src-page" value="/viantes/pub/pages/mission.php" />
<input type="hidden" id="ovrly-initial-login-dst-page" value="/viantes/pub/pages/mission.php" />
<input type="hidden" id="ovrly-initial-sign-dst-page" value="/viantes/pub/pages/mission.php" />


<body>
	<?php require_once $X_root."pvt/pages/common/header.html";?>
	
	<div id="main-div" class="main-div">
	
		<div class="body-div">			
			<div>
				<div class="top-header">
					<h1><?php echo //$X_langArray['TERMS_H1']
									'La Mission'?></h1>
				</div>
				
				<div>
					<p class="info-general"><?php echo //$X_langArray['TERMS_DISCL']
					'Viantes &egrave; un\'applicazione Web, un progetto, una filosofia basata sull\'idea che ha per presupposto la condivisone della conoscenza.<br><br>'.
					//Fa pa parte di un progetto amcora piu\' ampio denominato ShaKno il cui acronino deriva dalle due parole Share e Know rispettivamente condividere conoscenza.<br>
					'Condividere la conoscenza significa rendere le informazioni accessibili alla maggior parte di utenti possibili (idealmente tutti), in modo semplice e veloce, organizzate cio&egrave; in modo da reperire le informazioni nel minor tempo possibile.<br><br>'.
					'Per far ci&ograve; &egrave; necessario anche il vostro impegno, affinch&eacute; i vostri contenuti siano pi&ugrave; esaustivi e dettagliati possibile, ed allo stesso tempo concisi poich&eacute; crediamo che l\'abbondanza di informazione, se superflua sia solo una difficolt&agrave; aggiuntiva per chi le informazioni le cerca.'?></p>
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
