<?php 
$X_root = "../../";
$X_page = "terms";
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
	<title><?php echo $X_langArray['TERMS_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay-login-signin -->
<?php require_once $X_root."pvt/pages/common/overlay-login-signin.html"; ?>
<input type="hidden" id="ovrly-initial-src-page" value="/viantes/pub/pages/terms.php" />
<input type="hidden" id="ovrly-initial-login-dst-page" value="/viantes/pub/pages/terms.php" />
<input type="hidden" id="ovrly-initial-sign-dst-page" value="/viantes/pub/pages/terms.php" />


<body>
	<?php require_once $X_root."pvt/pages/common/header.html";?>
	
	<div id="main-div" class="main-div">
	
		<div class="body-div">			
			<div>
				<div class="top-header">
					<h1><?php echo $X_langArray['TERMS_H1']?></h1>
				</div>
				<div class="second-header-font14">
					<h1><?php echo $X_langArray['TERMS_DISCL']?></h1>
				</div>
				
				<div>
					<p class="info-general">La presente dichiarazione definisce i diritti e le responsabilit&agrave; delle parti. In particolare, tra l&rsquo;ideatore (d&rsquo;ora in poi detto Ideatore) del portale web (d&rsquo;ora in poi Portale), e l&rsquo;utente registrato nel portale (d&rsquo;ora in poi detto Utente), si stabilisce quanto segue:</p>
					<br>
					<p class="info-general" style="font-size: 14px;"><b>Responsabilit&agrave; generali:</b></p>
					<p class="info-general">L&rsquo;Utente &egrave; il solo responsabile dei contenuti caricati sul portale. Il Portale non eseguir&agrave; alcun controllo sui contenuti caricati dall&rsquo;Utente e quest&rsquo;ultimo &egrave; consapevole di tutte le responsabilit&agrave; connesse ai contenuti caricati; a titolo di esempio, senza pretesa di completezza, l&rsquo;Utente &egrave; responsabile di eventuali contenuti errati (in parte o totali), contenuti moralmente od eticamente offensivi, contenuti razzisti, contenuti omofobi, eccetera.</p>
					<p class="info-general">L&rsquo;Ideatore metter&agrave; sempre e comunque a disposizione l&rsquo;infrastruttura tecnologica per rimuovere tutti i contenuti ritenuti offensivi o errati nei tempi tecnici necessario dal momento in cui arriva qualsiasi tipo di segnalazione.</p>
					<br>
					<p class="info-general" style="font-size: 14px;"><b>Propriet&agrave;:</b></p>
					<p class="info-general">Distinguiamo due tipi di contenuti: le informazioni caricate dall&rsquo;Utente durante la scrittura di una recensione (recensione, foto e documenti allegate, etc...) definiti <b>Contenuti Recensione</b>, e le informazioni personali relative all&rsquo;account dall&rsquo;Utente (immagine del profilo, dati anagrafici etc...) definiti <b>Contenuti Profilo Utente</b>.</p>
					<p class="info-general">Tutti i Contenuti Recensione presenti sul portale sono da definirsi liberi e visibili ad ogni Utente sia esso registrato o no sul Portale.</p>
					<p class="info-general">Non &egrave; possibile in nessuna misura ed in nessun caso attribuire una propriet&agrave; ai Contenuti Recensione.</p>
					<p class="info-general">I Contenuti Recensione possono essere, copiati e/o divulgati purch&eacute; si menzioni sempre e comunque <b>Viantes</b> come fonte del contenuto copiato o divulgato.</p>
					<p class="info-general">Non &egrave; permesso l&rsquo;uso dei Contenuti Recensione - pur citandone la fonte - per scopi commerciali, o per qualsiasi altra finalit&agrave; che non sia la <i>libera condivisione dell&rsquo;informazione</i>.</p>
					<p class="info-general">La <i>libera condivisione dell&rsquo;informazione</i> si basa su 3 principi: 
					<p class="info-general" style="ine-height: 22px">1) L&rsquo;informazione deve essere accessibile a tutti gratuitamente e liberamente.</p>
					<p class="info-general" style="ine-height: 22px">2) Qualsiasi uso dell&rsquo;informazione, che non sia quello <i>personale</i> - fruito cio&egrave; da una persona fisica che acquisisce queste informazioni e le usa successivamente per trarne un vantaggio o un beneficio personale - deve riportare sempre la fonte iniziale <b>Viantes</b>.</p>
					<p class="info-general" style="ine-height: 22px">3) Anche se si cita la fonte, non &egrave; mai consentito l&rsquo;uso dell&rsquo;informazione per trarne un profitto o un beneficio che non sia quello <i>personale</i>. A titolo di esempio non esaustivo, sapere cosa vedere in una data citt&agrave; rappresenta un vantaggio per colui che &ldquo;acquisisce&rdquo; questa informazione in quanto pu&ograve;  pianificare meglio il proprio viaggio. Qualsiasi altro vantaggio, beneficio che se ne trae all&rsquo;uso o alla conoscenza di certe informazioni &egrave vietato.</p>
					<p class="info-general">L&rsquo;Utente pu&ograve; decidere liberamente se rendere <b>pubblici</b> (visibili a tutti gli Utenti registrati sul Portale ma comunque non visibili ai visitatori del Portale che non siano registrati) o <b>privati</b> (non visibili agli Utenti ed ai visitatori non registrati) i propri Contenuti Profilo Utente.</p><br/>
					<br>
					<p class="info-general" style="font-size: 14px;"><b>Privacy:</b></p>
					<p class="info-general">L&rsquo;Ideatore si impegna a non diffondere in nessun modo ed in nessun caso i Contenuti Profilo Utente.</p>
					<p class="info-general">Sia l&rsquo;Ideatore &egrave; che il Portale stesso, sono sollevati da qualsiasi responsabilit&agrave; diretta o indiretta sulla possibile diffusione dei Contenuti Profilo anche se questi siano stati impostati come privati.</p>
					<p class="info-general">L&rsquo;Utente si impegna a non diffondere in nessun modo ed in nessun caso i Contenuti Profilo Utente.</p>
					<br>
					<p class="info-general" style="font-size: 14px;"><b>Sicurezza:</b></p>
					<p class="info-general">L&rsquo;Ideatore si impegna a garantire nel miglior modo possibile la sicurezza del Portale. Tuttavia, in caso di accesso illecito o forzoso a qualsiasi tipo di Contenuto del Portale, l&rsquo;Ideatore ed il Portale sono sollevati da qualsiasi responsabilit&agrave; diretta o indiretta.</p>
					<p class="info-general">A titolo di esempio, senza pretesa di completezza, in caso di falle di sicurezza e conseguente intrusione e diffusione, modifica o cancellazione di qualsiasi contenuto del Portale, l&rsquo;Ideatore ed il Portale sono sollevati da qualsiasi responsabilit&agrave; diretta o indiretta.</p>
					<br>
					<p class="info-general" style="font-size: 14px;"><b>Messaggistica:</b></p>
					<p class="info-general">Sia l&rsquo;Ideatore &egrave; che il Portale sono sollevati da qualsiasi responsabilit&agrave; diretta o indiretta per i messaggi ricevuti dall&rsquo;Utente. A titolo di esempio, senza pretesa di completezza, l&rsquo;Utente non pu&ograve; rivalersi in nessun caso ed in nessuna misura nei confronti dell&rsquo;Ideatore né nei confronti del Portale nel caso ricevi messaggi offensivi, volgari, pubblicitari, spam o quant&rsquo;altro; l&rsquo;Utente ha sempre possibilit&agrave; di bloccare il mittente del messaggio non desiderato.</p>
					<br>
					<p class="info-general">Ultimo aggiornamento 30/03/2016.</p>
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
