<?php 
$X_root = "../../../../viantes/";
$X_page = "createReview";
session_start();			
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
//prima di verificare la sessione salvo la richeesta
savePageRequest("/viantes/pub/pages/review/createReview.php");
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/review/categoryReviewDO.php";
require_once $X_root."pvt/pages/review/categoryReviewDAO.php";
require_once $X_root."pvt/pages/review/reviewBean.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$activeTabIdx = ( isset($_GET['tabactive']) && 
				  ($_GET['tabactive'] == '1' || $_GET['tabactive'] == '2' || $_GET['tabactive'] == '3' || $_GET['tabactive'] == '4') 
				) ?	$_GET['tabactive'] : 1;

$userDO = unserialize($_SESSION["USER_LOGGED"]);

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());

$beanSessionKey = "REVIEWN_BEAN";

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
		<?php echo isset($_GET['finish']) ?  $X_langArray['CREATE_REVIEW_S2_PAGE_TITLE']: 
											 $X_langArray['CREATE_REVIEW_PAGE_TITLE'] ?>
	</title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay delete item  -->
<?php require_once $X_root."pvt/pages/common/overlay-del-item.html"; ?>
<?php require_once $X_root."pvt/pages/common/overlay-loading.html"; ?>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html";?>
	
	<div id="main-div" class="main-div">
	
		<div class="body-div">
			<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
			
			<div>
				<div class="top-header">
					<h1><?php echo isset($_GET['finish']) ? $X_langArray['CREATE_REVIEW_S2_PAGE_H3'] : 
															$X_langArray['CREATE_REVIEW_PAGE_H3'] ?></h1>
				</div>
				<div>
					<p><?php echo isset($_GET['finish']) ?  $X_langArray['CREATE_REVIEW_S2_PAGE_DISCL']:
															$X_langArray['CREATE_REVIEW_PAGE_DISCL'] ?></p>
				</div>
			</div>
			
			<br><br>
			<div id="createReview">
				<div class="tabs">
					<ul class="tab-links">
						<li <?php echo ($activeTabIdx == 1) ? 'class="active"' : '' ?>>
							<a href="#tab1" onclick="$('#tabactive').val(1)">
								<?php echo $X_langArray['CREATE_REVIEW_TAB_REV_TITLE'] ?>
							</a>
						</li>
						<li <?php echo ($activeTabIdx == 2) ? 'class="active"' : '' ?>>
							<a href="#tab2"  onclick="$('#tabactive').val(2)">
								<?php echo $X_langArray['CREATE_REVIEW_TAB_PIC_TITLE'] ?>
							</a>
						</li>
						<li <?php echo ($activeTabIdx == 3) ? 'class="active"' : '' ?>>
							<a href="#tab3" onclick="$('#tabactive').val(3)">
								<?php echo $X_langArray['CREATE_REVIEW_TAB_VID_TITLE'] ?>
							</a>
						</li>
						<li <?php echo ($activeTabIdx == 4) ? 'class="active"' : '' ?>>
							<a href="#tab4" onclick="$('#tabactive').val(4)">
								<?php echo $X_langArray['CREATE_REVIEW_TAB_DOC_TITLE'] ?>
							</a>
						</li>
					</ul>
					<div class="tab-content" >
						<?php $backUrl='/viantes/pub/pages/review/createReview.php'; ?>
						
						<div id="tab1" <?php echo ($activeTabIdx == 1) ? 'class="tab active"' : 'class="tab"' ?> >
							
							<?php if (isset($_GET['finish'])) {
								require_once $X_root."pvt/pages/review/create/createReviewTab1Step2.php"; 
							}
							else{
								require_once $X_root."pvt/pages/review/create/createReviewTab1.php"; 
							} ?>
						</div>
						
						<div id="tab2" <?php echo ($activeTabIdx == 2) ? 'class="tab active"' : 'class="tab"' ?> >
							<?php require_once $X_root."pvt/pages/review/create/createReviewTab2.php"; ?>
						</div>
				 
						<div id="tab3"  <?php echo ($activeTabIdx == 3) ? 'class="tab active"' : 'class="tab"' ?> >
							<?php require_once $X_root."pvt/pages/review/create/createReviewTab3.php"; ?>
						</div>
						
						<div id="tab4"  <?php echo ($activeTabIdx == 4) ? 'class="tab active"' : 'class="tab"' ?> >
							<?php require_once $X_root."pvt/pages/review/create/createReviewTab4.php"; ?>
						</div>
					</div>

					<input type="hidden" name="tabactive" value="<?php echo $activeTabIdx ?>" id="tabactive"/>
					<input type="hidden" id="del-element-name-param" value=""/>
					<input type="hidden" id="del-element-type-param" value=""/>
					<input type="hidden" id="del-element-pstn-param" value=""/>
					
				</div>
			</div>
		</div>
		
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
	
</body>
</html>
