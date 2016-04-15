<?php 
$X_root = "../viantes/"; //root che referenzia risorse sotto /vaintes
$MX_root = "./viantes/"; //root che referenzia risorse sotto /m/vaintes
$X_page = "index";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
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
</head>

<body>
	<!-- Mobile Header -->
	<?php require_once $MX_root."pvt/pages/common/header.html"; ?>
	
	<!-- Mobile Menu -->
	<?php include $MX_root."pvt/pages/common/menu.html"; ?>	
	
	<!-- Mobile Login Page -->
	<?php 
	include $MX_root."pvt/pages/common/login-signin.html"; 
	//e' il not dello show del div login-singup
	$mainDivShow = isset($_GET['showOverlayLgSg']) ? "display : none;" : "display: block;";
	?>
	<input type="hidden" id="ovrly-initial-src-page" value="/index.php" />
	<input type="hidden" id="ovrly-initial-login-dst-page" value="/index.php" />
	<input type="hidden" id="ovrly-initial-sign-dst-page" value="/index.php" />
	
	<div id="main-div" class="main-welcome-div" style="<?php echo $mainDivShow ?>" >
		
		<?php include $X_root."pvt/pages/common/globalTopMsg.php"; ?>
		
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
									'<?php echo $txt1 ?>', '<?php echo $txt2 ?>', '<?php echo $usrId ?>', true);
					setInterval("doReviewItemBox('<?php  echo Conf::getInstance()->get('maxReviewItem'); ?>', 'PUSH', '<?php echo $txt1 ?>', '<?php echo $txt2 ?>', '<?php echo $usrId ?>', true)", 
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
	
	<?php include $MX_root."pvt/pages/common/footer.html"; ?>
	
</body>
</html>
