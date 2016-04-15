<?php 
$X_root = "../../../../../viantes/"; //root che referenzia risorse sotto /vaintes
$MX_root = "../../../";              //root che referenzia risorse sotto /m/vaintes
$X_page = "createCityReview";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
//prima di verificare la sessione salvo la richeesta
savePageRequest("/viantes/pub/pages/review/createCityReview.php");
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/review/reviewDAO.php";
require_once $X_root."pvt/pages/review/cityReviewBean.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$activeTabIdx = ( isset($_GET['tabactive']) && 
				  ($_GET['tabactive'] == '1' || $_GET['tabactive'] == '2' || $_GET['tabactive'] == '3' || $_GET['tabactive'] == '4') 
				) ?	$_GET['tabactive'] : 1;

$userDO = unserialize($_SESSION["USER_LOGGED"]);

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());

//Check se esiste almeno una recensione di citta? => alert 
$reviewDAO = New ReviewDAO();
$pattern = getDatePatternByLangCode($settingDO->getLangCode());
if (count($reviewDAO->getReviewList($userDO->getId(), $pattern)) == 0 ){
	$_SESSION[GLOBAL_TOP_MSG_ERROR] = $X_langArray['CREATE_CITY_REV_PAGE_TOP_MSG_ERROR'];
}

$beanSessionKey = "CITY_REVIEWN_BEAN";

//Gestisco un caso limite, resto nella seconda pagina e scade la sessione
if ( !isset($_SESSION[$beanSessionKey]) && isset($_GET['finish']) ) {
	header('Location: '.getURI().'/viantes/pub/pages/error.php?reason='.urlencode($X_langArray['ERROR_REASON_SESSION_EXPIRED']));
	exit;
}
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
	<title>
		<?php echo isset($_GET['finish']) ?  $X_langArray['CREATE_CITY_REV_S2_PAGE_TITLE']: 
											 $X_langArray['CREATE_CITY_REV_PAGE_TITLE'] ?>
	</title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay delete item  -->
<?php require_once $X_root."pvt/pages/common/overlay-del-item.html"; ?>
<?php require_once $X_root."pvt/pages/common/overlay-loading.html"; ?>
<?php require_once $X_root."pvt/pages/review/create/overlayAddInterest.html"; ?>

<body>
	<!-- Mobile Header -->
	<?php require_once $MX_root."pvt/pages/common/header.html";?>
	
	<!-- Mobile Menu -->
	<?php include $MX_root."pvt/pages/common/menu.html"; ?>	
	
	<div id="main-div" class="main-div">
	
		<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
		
		<div>
			<div class="top-header">
				<h1><?php echo isset($_GET['finish']) ? $X_langArray['CREATE_CITY_REV_S2_PAGE_H3'] : 
														$X_langArray['CREATE_CITY_REV_PAGE_H3'] ?></h1>
			</div>
			<div>
				<p><?php echo isset($_GET['finish']) ?  $X_langArray['CREATE_CITY_REV_S2_PAGE_DISCL']:
														$X_langArray['CREATE_CITY_REV_PAGE_DISCL'] ?></p>
			</div>
		</div>
		
		<br><br>
		<div id="createCityReview">
			<div class="tabs">
				<input type="hidden" name="tabactive" value="<?php echo $activeTabIdx ?>" id="tabactive"/>
				<input type="hidden" id="del-element-name-param" value=""/>
				<input type="hidden" id="del-element-type-param" value=""/>
				<input type="hidden" id="del-element-pstn-param" value=""/>
				
				<ul class="tab-links">
					<li <?php echo ($activeTabIdx == 1) ? 'class="active"' : '' ?>>
						<a href="#tab1" onclick="$('#tabactive').val(1)" >
							<?php echo $X_langArray['CREATE_CITY_REV_TAB_REV_TITLE'] ?>
						</a>
					</li>
					<li <?php echo ($activeTabIdx == 2) ? 'class="active"' : '' ?>>
						<a href="#tab2" onclick="$('#tabactive').val(2)">
							<?php echo $X_langArray['CREATE_CITY_REV_TAB_PIC_TITLE'] ?>
						</a>
					</li>
					<li <?php echo ($activeTabIdx == 3) ? 'class="active"' : '' ?>>
						<a href="#tab3" onclick="$('#tabactive').val(3)">
							<?php echo $X_langArray['CREATE_CITY_REV_TAB_VID_TITLE'] ?>
						</a>
					</li>
					<li <?php echo ($activeTabIdx == 4) ? 'class="active"' : '' ?>>
						<a href="#tab4" onclick="$('#tabactive').val(4)">
							<?php echo $X_langArray['CREATE_CITY_REV_TAB_DOC_TITLE'] ?>
						</a>
					</li>
				</ul>
				<div class="tab-content" >
					<?php $backUrl='/viantes/pub/pages/review/createCityReview.php'; ?>
					
					<div id="tab1" <?php echo ($activeTabIdx == 1) ? 'class="tab active"' : 'class="tab"' ?> >
						<?php 
						if (isset($_GET['finish'])) {
							require_once $MX_root."pvt/pages/review/create/createCityRevTab1Step2.php"; 
						}
						else{
							require_once $MX_root."pvt/pages/review/create/createCityRevTab1.php";
						}?>
					</div>
					
					<div id="tab2" <?php echo ($activeTabIdx == 2) ? 'class="tab active"' : 'class="tab"' ?> >
						<?php require_once $X_root."pvt/pages/review/create/createCityRevTab2.php"; ?>
					</div>
			 
					<div id="tab3"  <?php echo ($activeTabIdx == 3) ? 'class="tab active"' : 'class="tab"' ?> >
						<?php require_once $X_root."pvt/pages/review/create/createCityRevTab3.php"; ?>
					</div>
					
					<div id="tab4"  <?php echo ($activeTabIdx == 4) ? 'class="tab active"' : 'class="tab"' ?> >
						<?php require_once $X_root."pvt/pages/review/create/createCityRevTab4.php"; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php require_once $MX_root."pvt/pages/common/footer.html"; ?>
	
</body>
</html>
