<?php 
$X_root = "../../../../../viantes/";
$X_page = "showProfile";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
//prima di verificare la sessione salvo la richeesta
$usrId = isset($_GET['usrId']) ? X_deco($_GET['usrId']) : -1 ;
savePageRequest("/viantes/pub/pages/profile/showProfile.php?usrId=".$_GET['usrId']);
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userRegistryDAO.php";
require_once $X_root."pvt/pages/auth/userRegistryDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

//logged user 
$userDO = unserialize($_SESSION["USER_LOGGED"]);
//sono io!
if (X_deco($_GET['usrId']) == $userDO->getId()) {
	header('Location: '.getURI().'/viantes/pub/pages/profile/myProfile.php');
	exit;
}

cleanSesison($X_page);

$userDAO = New UserDAO();
//user that we are show its profile
$currentUserDO = $userDAO->getLazyUserDO($usrId);

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($currentUserDO->getId());

$pattern = getDatePatternByLangCode($settingDO->getLangCode());
Logger::log("showProfile :: pattern per data rilevato :: ".$pattern, 3);

$userRegistryDAO = New UserRegistryDAO();
$userRegistryDO = $userRegistryDAO->getUserRegistryByUserId($currentUserDO->getId(), $pattern);

$blockedUsrList = $userDAO->getBlockedUsrList($userDO->getId());
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
	<title><?php echo $X_langArray['SHOWPROFILE_PAGE_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html";  ?>
</head>

<!-- Overlay delete item  -->
<?php require_once $X_root."pvt/pages/common/overlay-send-msg.html"; ?>
<?php require_once $X_root."pvt/pages/common/overlay-den-usr.html"; ?>
<?php require_once $X_root."pvt/pages/common/overlay-loading.html"; ?>		
		
<body>
	<?php require_once $X_root."pvt/pages/common/header.html";?>
	
	<div id="main-div" class="main-div">

		<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
		
		<div>
			<?php $currentUserDO->getBckCoverFileName();?>
			<div class="bckGrndProfileCover">
				<img src="<?php echo $currentUserDO->getBckCoverFileName(); ?>" <?php echo IMG_748_290 ?> >
				<div style="position: relative;">
					<img class="profileCover" src="<?php echo $currentUserDO->getCoverFileName(); ?>" <?php echo IMG_128_128 ?> >
				</div>
			</div>

			<div class="second-header dspl-inln-blk">	
				<?php if ( isset($_GET['loadCovImgErrMsg']) ){ ?>
					<p class="p-error"><?php echo urldecode($_GET['loadCovImgErrMsg']) ?><p>
				<?php } ?>
				<h1 style="display: inline-block">
					<?php echo $currentUserDO->getName();?>
				</h1>
			</div>
			
			<?php if ( $currentUserDO->getId() != $userDO->getId() ) { ?>
				<!--Blocca Utente-->
				<div class="personalButton denUsrButton">
					<a href="#" onclick="$('#overlay-den-usr').show()">
						<?php $blocked = in_array($currentUserDO->getId(), $blockedUsrList);
							  echo $blocked ? $X_langArray['MYPROFILE_MY_ALLOW_USR'] : $X_langArray['MYPROFILE_MY_DEN_USR']?>
					</a>
					<input type="hidden" id="loggedUsrId" value="<?php echo X_code( $userDO->getId() )?>">
					<input type="hidden" id="denUsrId"    value="<?php echo X_code( $currentUserDO->getId() )?>">
					<input type="hidden" id="denStatus"   value="<?php echo $blocked  ? 0 : 1 ?>">
				</div>
				
				<!--Invia Messaggio-->
				<div class="personalButton sendMsgUsr">
					<a href="#" onclick="showMsgOverlay('/viantes/pub/pages/profile/showProfile.php');
										 $('#autoComplMsgTo').val('<?php echo $currentUserDO->getName() ?>');
										 $('#usrId').val('<?php echo $_GET['usrId'] ?> '); ">
						<?php echo $X_langArray['MYPROFILE_MY_SEND_MSG']?>
					</a>
				</div>
			<?php } ?>
			
			<!--div class="profile-container"-->
			<div id="setting_top_div" style="margin-top: 0px;">
				<h3><?php echo $X_langArray['SHOWPROFILE_MY_INFO'] ?></h3>
			</div>
			
			<div class="setting_body_div">
				<!-- Name -->
				<div class="setting_row_div">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_FIRST_NAME'] ?></p></b>
					<p class="setting_row-p-right"><?php echo $settingDO->getProfileType() != 0 ? '***' : $userRegistryDO->getFirstName();?></p>
				</div>
				<!-- Last Name -->
				<div class="setting_row_div_alterned">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_LAST_NAME'] ?></p></b>
					<p class="setting_row-p-right"><?php echo $settingDO->getProfileType() != 0 ? '***' : $userRegistryDO->getLastName();?></p>
				</div>
				<!-- Email -->
				<div class="setting_row_div">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_EMAIL'] ?></p></b>
					<p class="setting_row-p-right"><?php echo $settingDO->getProfileType() != 0 ? '***' : $currentUserDO->getEmail();?></p>
				</div>
				<!-- Mobile Number -->
				<div class="setting_row_div_alterned">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_MOBILE_NUM'] ?></p></b>
					<p class="setting_row-p-right"><?php echo $settingDO->getProfileType() != 0 ? '***' : $userRegistryDO->getMobileNum();?></p>
				</div>
				<!-- Gender -->
				<div class="setting_row_div">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_GENDER'] ?></p></b>
					<p class="setting_row-p-right">
						<?php 
						if ($settingDO->getProfileType() != 0 ) echo '***';
						else if ($userRegistryDO->getGender() == 1) {
							echo $X_langArray['MYPROFILE_GENDER_1'];
						}
						else if ($userRegistryDO->getGender() == 2) {
							echo $X_langArray['MYPROFILE_GENDER_2'];
						}
						else {
							echo "";
						}
						?>
					</p>
				</div>
				<!--Birth date -->
				<div class="setting_row_div_alterned">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_BIRTH_DATE']?></p></b>
					<p class="setting_row-p-right"><?php echo $settingDO->getProfileType() != 0 ? '***' : $userRegistryDO->getDateOfBirth();?></p>
				</div>		
				<!-- City -->
				<div class="setting_row_div">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_CITY']?></p></b>
					<p class="setting_row-p-right"><?php echo $settingDO->getProfileType() != 0 ? '***' : $userRegistryDO->getCity();?></p>
				</div>
				<!-- Posal Code -->
				<div class="setting_row_div_alterned">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_POSTAL_CODE']?></p></b>
					<p class="setting_row-p-right"><?php echo $settingDO->getProfileType() != 0 ? '***' : $userRegistryDO->getPostcode();?></p>
				</div>
				<!-- Country -->						
				<div class="setting_row_div">
					<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_COUNTRY']?></p></b>
					<p class="setting_row-p-right"><?php echo $settingDO->getProfileType() != 0 ? '***' : $userRegistryDO->getCountry();?></p>
				</div>
			</div>
		</div>
	
		<hr class="commonRowHR">
			
		<!-- My review included -->
		<div id="setting_top_div">
			<h3><?php echo $X_langArray['SHOWPROFILE_USER_REVIEW'] ?></h3>
		</div>
		<?php 
			$X_userId = $currentUserDO->getId(); 
			$X_pattern = $pattern;
			include $X_root."pvt/pages/review/common/userReview.php" 
		?>
		
	</div>
		
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>

</body>
</html>
