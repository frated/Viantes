<?php 
$X_root = "../../../../../viantes/"; //root che referenzia risorse sotto /vaintes
$MX_root = "../../../";              //root che referenzia risorse sotto /m/vaintes
$X_page = "message";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
//prima di verificare la sessione salvo la richeesta
savePageRequest("/viantes/pub/pages/profile/message.php");
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/msg/msgDAO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$activeTabIdx = ( isset($_GET['tabactive']) && 
				  ($_GET['tabactive'] == '1' || $_GET['tabactive'] == '2' || $_GET['tabactive'] == '3' || $_GET['tabactive'] == '4') 
				) ?	$_GET['tabactive'] : 1;

$userDO = unserialize($_SESSION["USER_LOGGED"]);

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());

$pattern = getDatePatternByLangCode($settingDO->getLangCode());

//Aggiorno i messaggi da leggere
$msgDAO = New MsgDAO();
$toBeRead = $msgDAO->getToBeReadMsgNum($userDO->getId());
if ($toBeRead > 0) {
	$userDO->setInBoxMsgNum($toBeRead);	
	$_SESSION['USER_LOGGED'] = serialize($userDO);
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
	<title><?php echo $X_langArray['MESSAGE_PAGE_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<body>
	<!-- Mobile Header -->
	<?php require_once $MX_root."pvt/pages/common/header.html";?>
	
	<!-- Mobile Menu -->
	<?php include $MX_root."pvt/pages/common/menu.html"; ?>	
	
	<!-- Messaggi -->
	<?php require_once $MX_root."pvt/pages/common/send-msg.html"; ?>
	<?php require_once $MX_root."pvt/pages/common/del-msg.html"; ?>
	<?php require_once $MX_root."pvt/pages/common/del-msg-no-selected.html"; ?>
	<?php require_once $MX_root."pvt/pages/common/loading.html"; ?>

	<div id="main-div" class="main-div">
		<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
		
		<div>
			<div class="top-header">
				<h1><?php echo $X_langArray['MESSAGE_PAGE_H3'] ?></h1>
			</div>
			<div>
				<p><?php echo $X_langArray['MESSAGE_PAGE_DISCL'] ?></p>
			</div>
			<div  class="msgNewMsgLink personalButton">
				<a href="#" onclick="showNewMsg('/viantes/pub/pages/profile/message.php');">
					<?php echo $X_langArray['MESSAGE_NEW']?>
				</a>
			</div>
		</div>
		
		<div id="createReview" class="mrg-top-36">
			<div class="tabs">
				<input type="hidden" name="tabactive" value="<?php echo $activeTabIdx ?>" id="tabactive"/>
				<input type="hidden" id="del-element-name-param" value=""/>
				<input type="hidden" id="del-element-type-param" value=""/>
				<input type="hidden" id="del-element-pstn-param" value=""/>
				
				<ul class="tab-links">
					<li <?php echo ($activeTabIdx == 1) ? 'class="active"' : '' ?>>
						<a href="#tab1" onclick="$('#tabactive').val(1)">
							<?php echo $X_langArray['MESSAGE_TAB_IN'] ?>
						</a>
					</li>
					<li <?php echo ($activeTabIdx == 2) ? 'class="active"' : '' ?>>
						<a href="#tab2" onclick="$('#tabactive').val(2)">
							<?php echo $X_langArray['MESSAGE_TAB_SENT'] ?>
						</a>
					</li>
					<li <?php echo ($activeTabIdx == 3) ? 'class="active"' : '' ?>>
						<a href="#tab3" onclick="$('#tabactive').val(3)">
							<?php echo $X_langArray['MESSAGE_TAB_DRAFT'] ?>
						</a>
					</li>
					<li <?php echo ($activeTabIdx == 4) ? 'class="active"' : '' ?>>
						<a href="#tab4" onclick="$('#tabactive').val(4)">
							<?php echo $X_langArray['MESSAGE_TAB_TRASH'] ?>
						</a>
					</li>
				</ul>
				<div class="tab-content" >
					<?php $backUrl='/m/viantes/pub/pages/profile/message.php'; ?>
					<div id="tab1" <?php echo ($activeTabIdx == 1) ? 'class="tab active"' : 'class="tab"' ?> >
						<?php require_once $MX_root."pvt/pages/msg/msgTab1.php"; ?>
					</div>
					
					<div id="tab2" <?php echo ($activeTabIdx == 2) ? 'class="tab active"' : 'class="tab"' ?> >
						<?php require_once $MX_root."pvt/pages/msg/msgTab2.php"; ?>
					</div>
			 
					<div id="tab3"  <?php echo ($activeTabIdx == 3) ? 'class="tab active"' : 'class="tab"' ?> >
						<?php require_once $MX_root."pvt/pages/msg/msgTab3.php"; ?>
					</div>
					
					<div id="tab4"  <?php echo ($activeTabIdx == 4) ? 'class="tab active"' : 'class="tab"' ?> >
						<?php require_once $MX_root."pvt/pages/msg/msgTab4.php"; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php require_once $MX_root."pvt/pages/common/footer.html"; ?>
	
</body>
</html>
