<?php
$X_root = "../../../../viantes/";
$X_page = "infoCookie";
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
						'Informativa sui cookie'?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay-login-signin -->
<?php require_once $X_root."pvt/pages/common/overlay-login-signin.html"; ?>
<input type="hidden" id="ovrly-initial-src-page" value="/viantes/pub/pages/infoCookie.php" />
<input type="hidden" id="ovrly-initial-login-dst-page" value="/viantes/pub/pages/infoCookie.php" />
<input type="hidden" id="ovrly-initial-sign-dst-page" value="/viantes/pub/pages/infoCookie.php" />


<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div id="main-div" class="main-div">
		<div>
			<div class="top-header">
				<h1>Cosa sono e perch&eacute;</h1>
			</div>
			<div>
				<p class="info-general">I cookie sono dei piccoli file di testo che hanno il compito di salvare delle informazioni durante la navigazione dell'utente. <br>
				   Durante la navigazione, il sito visitato o i suoi fornitori di servizi autorizzati, possono usare questi file ed altre tecnologie simili per diversi scopi.</p>
			</div>
			<div class="second-header">
				<h1>Scopi</h1>
			</div>
			<div>
				<p class="info-general">I cookie possono sono classificati in base al loro scopo. Di seguito sono elencati gli scopi per ogni tipologia di cookie.</p>
				<br>
				<p class="info-general"><b>Tecnici:</b></p>
				<p class="info-general">Sono cookie gestiti internamente dal sito ed hanno finalit&agrave; strettamente connesse al corretto funzionamento del sito stesso. Sono usati in questo sito.</p>
				<br>
				<p class="info-general"><b>Analitici:</b></p>
				<p class="info-general">Sono utilizzati per raccogliere informazioni sull'uso del sito (fare statistiche, monitorare gli accessi eccetera). Non usati in questo sito.</p>
				<br>
				<p class="info-general"><b>Analitici terze parti I:</b></p>
				<p class="info-general">Sono siti analitici per i quali, come riportato dal garante della privacy, "sono adottati strumenti che riducono il potere identificativo dei cookie e la terza parte non incrocia le informazioni raccolte con altre di cui già dispone". Non sono usati in questo sito.</p>
				<br>
				<p class="info-general"><b>Analitici terze parti II:</b></p>
				<p class="info-general">Sono siti analitici per i quali, come riportato dal garante della privacy, "<b>NON</b> sono adottati strumenti che riducono il potere identificativo dei cookie e la terza parte non incrocia le informazioni raccolte con altre di cui già dispone". Usati in questo sito.</p>
				<br>
				<p class="info-general"><b>Di profilazione prima parte:</b></p>
				<p class="info-general">Citando il garante della privacy "sono volti a creare profili relativi all'utente e vengono utilizzati al fine di inviare messaggi pubblicitari in linea con le preferenze manifestate dallo stesso nell'ambito della navigazione in rete". Essendo di prima parte sono trasmessi dal gestore del sito visitato. Non usati in questo sito.</p>
				<br>
				<p class="info-general"><b>Di profilazione terze parti della sessione:</b></p> 
				<p class="info-general">Vale la stessa definizione dei precedenti ma sono trasmessi da societ&agrave; terze presenti all'interno del sito visitato. Usati in questo sito.</p>
				<br>
			</div>
		</div>
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
			
</body>
</html>
