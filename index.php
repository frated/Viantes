<?php 
$X_root = "./viantes/";
$X_page = "index";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/common/checkMobile.php";
require_once $X_root."pvt/pages/cfg/conf.php";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$usrId = -1;
if (isset($_SESSION["USER_LOGGED"]) && isset($_SESSION["USER_LOGGED"])) {
	$userDO = unserialize($_SESSION["USER_LOGGED"]);
	$usrId = X_code($userDO->getId());
}
?>

<!DOCTYPE html>
<html>

<head>
	<title><?php echo $X_langArray['WELCOME_PAGE_TITLE'] ?></title>
	<?php include $X_root."pvt/pages/common/meta-link-script.html"; ?>
	
    <!-- load jQuery and the plugin -->
	<!--script src="viantes/pvt/js/responsiveslides.js"></script-->
    <script src="viantes/pvt/js/jQuerySlider-1.3.js"></script>
</head>

<!-- Overlay-login-signin -->
<?php include $X_root."pvt/pages/common/overlay-login-signin.html"; ?>
<input type="hidden" id="ovrly-initial-src-page" value="/index.php" />
<input type="hidden" id="ovrly-initial-login-dst-page" value="/index.php" />
<input type="hidden" id="ovrly-initial-sign-dst-page" value="/index.php" />

<?php include $X_root."pvt/pages/common/overlay-loading.html"; ?>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div id="main-banner-fade-id" class="main-banner-fade-div">
		<div id="banner-fade">
			<ul class="bjqs">
				<li><img src="/viantes/pvt/img/slider/index-back-1.jpg" class="bjqs-ie7"></li>
				<li><img src="/viantes/pvt/img/slider/index-back-2.jpg" class="bjqs-ie7"></li>
				<li><img src="/viantes/pvt/img/slider/index-back-3.jpg" class="bjqs-ie7"></li>
				<li><img src="/viantes/pvt/img/slider/index-back-4.jpg" class="bjqs-ie7"></li>
				<li><img src="/viantes/pvt/img/slider/index-back-5.jpg" class="bjqs-ie7"></li>
				<!--li><img src="/viantes/pvt/img/slider/index-back-6.jpg" class="bjqs-ie7"></li-->
			</ul>
		</div>	
	</div>	
	<script>
		//slide-bar
		jQuery(document).ready(function($) {
			$('#banner-fade').bjqs({
				animtype	: 'fade',
				responsive  : true
			});
		});
		/*$(function() {
			$(".rslides").responsiveSlides();
		});*/
	</script>
	
	<div id="main-div" class="main-welcome-div">
		
		<div class="body-div">
		
			<?php include $X_root."pvt/pages/common/globalTopMsg.php"; ?>
			
			<br><br>
			<div id="reviewItemBoxId" class="reviewItemsBox">
				<input type="hidden" id="topReviewId" value="-1" />
				<input type="hidden" id="topCityReviewId" value="-1" />
				<input type="hidden" id="topCountryReviewId" value="-1" />
				<input type="hidden" id="bottomReviewId" value="-1" />
				<input type="hidden" id="bottomCityReviewId" value="-1" />
				<input type="hidden" id="bottomCountryReviewId" value="-1" />
				<input type="hidden" id="exec" value="0" />
				<script>
					<?php 
					$txt1 = $X_langArray['WELCOME_HAS_PUB'];
					$txt2 = $X_langArray['WELCOME_KEEP_READING'] ;
					?>
					//On-Load
					$(document).ready(function() {
						doReviewItemBox('<?php  echo Conf::getInstance()->get('maxReviewItem'); ?>', 'PUSH', 
										'<?php echo $txt1 ?>', '<?php echo $txt2 ?>', '<?php echo $usrId ?>', false);
						setInterval("doReviewItemBox('<?php  echo Conf::getInstance()->get('maxReviewItem'); ?>', 'PUSH', '<?php echo $txt1 ?>', '<?php echo $txt2 ?>', '<?php echo $usrId ?>', false)", 
									<?php  echo Conf::getInstance()->get('reloadItemEvery'); ?>);
					});
					
					//SCROLL FUNCTION
					$(window).scroll(function() {  
						// $(window).scrollTop() Indica quanti pixel hai scrollato dal TOP della pagina
						// $(window).height()    Indica l'altezza della finestra ed e' costante per una data risoluzione
						if($(window).scrollTop() + $(window).height() == $(document).height()) {
							doReviewItemBox('<?php  echo Conf::getInstance()->get('maxReviewItem'); ?>', 'APPEND', '<?php echo $txt1 ?>', '<?php echo $txt2 ?>', '<?php echo $usrId ?>');
						}
					});
				</script>
			</div>
		</div>
		
		<?php include $X_root."pvt/pages/common/right_section.html"; ?>		
		
	</div>
	
	<?php include $X_root."pvt/pages/common/footer.html"; ?>
	
</body>
</html>
