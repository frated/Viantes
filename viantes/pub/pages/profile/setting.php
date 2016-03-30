<?php 
$X_root = "../../../";
$X_page = "setting";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
//prima di verificare la sessione salvo la richeesta
savePageRequest("/viantes/pub/pages/profile/setting.php");
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/lang/languageDAO.php";
require_once $X_root."pvt/pages/setting/settingDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);
	
$userDO = unserialize($_SESSION["USER_LOGGED"]);

$languageDAO = New LanguageDAO();
$languageDOArray = $languageDAO->getLanguages();

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());
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
	<title><?php echo $X_langArray['SETTING_PAGE_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
	
	<script type="text/javascript" src="<?php echo $X_root.'pvt/js/jPass.js'?>"></script>
</head>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div class="main-div">

		<div class="body-div">		
			<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
			
			<!-- ARTICLE COVER IMAGE -->
			<div style="margin-bottom: 25px">
				<div class="top-header">
					<h1><?php echo $X_langArray['SETTING_PAGE_H3'] ?></h1>
				</div>	
				<div>
					<p><?php echo $X_langArray['SETTING_PAGE_DISCL'] ?></p>
				</div>
			</div>
			
			<!-- INFO -->
			<a id="setting_inf_div_anchor"></a>
			<div id="setting_top_div">
				<h3><?php echo $X_langArray['SETTING_ACCOUNT'] ?></h3>
				<?php if ( isset($_GET['mod']) && "m" == $_GET['mod'] && isset($_GET['t']) && $_GET['t'] == "inf" ) { ?>
					<!-- Cancel link -->
					<a class="setting-a1" href="/viantes/pub/pages/profile/setting.php#setting_inf_div_anchor">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_CANCEL'] ?>
					</a>
					<!-- Save link -->
					<a class="setting-a2" href="javascript:$('#setting_inf_frm').submit();">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_SAVE'] ?>
					</a>
				<?php } else { ?>
					<!-- Modify link -->
					<a class="setting-a3" href="/viantes/pub/pages/profile/setting.php?mod=m&t=inf#setting_inf_div_anchor">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_MODIFY'] ?>
					</a>
				<?php } ?>
			</div>
			<div class="setting_body_div">
				<form id="setting_inf_frm" action="/viantes/pvt/pages/setting/settingModify.php" method="post">
					<input type="hidden" name="type" value="inf"/>
					<input type="hidden" name="usrId" value="<?php echo $userDO->getId() ?>" />
					<div class="setting_row_div">
						<p class="flt-l"><?php echo $X_langArray['SETTING_ACCOUNT_NAME'] ?></p>
						<p class="setting_row-p-right"><?php echo $userDO->getName();?></p>
					</div>
					<div class="setting_row_div_alterned">
						<p class="flt-l"><?php echo $X_langArray['SETTING_ACCOUNT_EMAIL'] ?></p>
						<p class="setting_row-p-right"><?php echo $userDO->getEmail();?></p>
					</div>
					<div class="setting_row_div">
						<p class="flt-l"><?php echo $X_langArray['SETTING_ACCOUNT_LANG'] ?></p>
						<?php if ( isset($_GET['mod']) && "m" == $_GET['mod'] && isset($_GET['t']) && $_GET['t'] == "inf" ) { ?>
							<select class="selectSettAndProfile" name="lang">
								<?php 
								foreach ($languageDOArray as $language) { 
									$langCode = $language->getLangCode();
									$s = "";
									if ( $langCode == $settingDO->getLangCode() ) { $s = " selected " ; }
								?>
									<option <?php echo $s ?> value="<?php echo $langCode ?>"><?php echo $X_langArray[$langCode] ?></option>
								<?php } ?>		
							</select>	
						<?php } else { ?>
							<p class="setting_row-p-right"><?php echo $X_langArray[$settingDO->getLangCode()] ?></p>
						<?php } ?>
					</div>
				</form>	
			</div>
			
			<!-- PASSWORD -->
			<a id="setting_pwd_div_anchor"></a>
			<div id="setting_top_div">
				<h3><?php echo $X_langArray['SETTING_PASSWORD'] ?> </h3>
				<?php $isModPwd = isset($_GET['mod']) && "m" == $_GET['mod'] && isset($_GET['t']) && $_GET['t'] == "pwd";?>
				
				<?php if ($isModPwd) { ?>
					<!-- Cancel link -->
					<a class="setting-a1" href="/viantes/pub/pages/profile/setting.php#setting_pwd_div_anchor">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_CANCEL'] ?>
					</a>
					<!-- Save link -->
					<a class="setting-a2" href="javascript:$('#setting_pwd_frm').submit();">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_SAVE'] ?>
					</a>
				<?php } else { ?>
					<!-- Modify link -->
					<a class="setting-a3" href="/viantes/pub/pages/profile/setting.php?mod=m&t=pwd#setting_pwd_div_anchor">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_MODIFY'] ?>
					</a>
				<?php } ?>
			</div>
			<div class="setting_body_div">
				<form id="setting_pwd_frm" action="/viantes/pvt/pages/setting/settingModify.php" method="post">
					<input type="hidden" name="type" value="pwd"/>
					<?php if ($isModPwd) { ?>
						<div class="setting_row_div">
							<p class="flt-l"><?php echo $X_langArray['SETTING_PASSWORD_CHANGE'] ?></p>
							<input name="password" type="password" value="" class="inputSettAndProfile <?php if (isset($_GET['pwdErrMsg'])) { echo "errorInput";} ?>" />
						</div>
						<?php if ( isset($_GET['pwdErrMsg']) ) { ?>
							<div class="setting_row_error_div">
								<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['pwdErrMsg']) ?><p>
							</div>
						<?php } ?>
						<div class="setting_row_div_alterned">
							<p class="flt-l"><?php echo $X_langArray['SETTING_PASSWORD_NEW'] ?></p>
							<input class="inputSettAndProfile <?php if (isset($_GET['pwd2ErrMsg'])) { echo "errorInput";} ?>" name="newPwd" type="password" value="" />
						</div>
						<?php if ( isset($_GET['pwd2ErrMsg']) ) { ?>
							<div class="setting_row_error_div_alterned">
								<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['pwd2ErrMsg']) ?><p>
							</div>
						<?php } ?>
						<div class="setting_row_div">
							<p class="flt-l"><?php echo $X_langArray['SETTING_PASSWORD_REPEAT'] ?> </p>
							<input class="inputSettAndProfile <?php if (isset($_GET['pwd2ErrMsg'])) { echo "errorInput";} ?>" name="repetPwd" id="pwd2" type="password" value="" />
						</div>
						<?php if ( isset($_GET['pwd2ErrMsg']) ) { ?>
							<div class="setting_row_error_div">
								<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['pwd2ErrMsg']) ?><p>
							</div>
						<?php } ?>
					<?php } else { ?>
						<div class="setting_row_div">
							<p class="flt-l"><?php echo $X_langArray['SETTING_PASSWORD'] ?> :</p>
							<p class="setting_row-p-right">********</p>
						</div>
					<?php } ?>	
				</form>	
			</div>
			
			<!-- PRIVACY -->
			<a id="setting_priv_div_anchor"></a>
			<div id="setting_top_div">
				<h3><?php echo $X_langArray['SETTING_PRIVACY'] ?></h3>
				<?php if ( isset($_GET['mod']) && "m" == $_GET['mod'] && isset($_GET['t']) && $_GET['t'] == "priv" ) { ?>
					<!-- Cancel link -->
					<a class="setting-a1" href="/viantes/pub/pages/profile/setting.php#setting_priv_div_anchor">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_CANCEL'] ?>
					</a>
					<!-- Save link -->
					<a class="setting-a2" href="javascript:$('#setting_priv_frm').submit();">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_SAVE'] ?>
					</a>
				<?php } else { ?>
					<!-- Modify link -->
					<a class="setting-a3" href="/viantes/pub/pages/profile/setting.php?mod=m&t=priv#setting_priv_div_anchor">
						<?php echo $X_langArray['MYPROFILE_MY_INFO_MODIFY'] ?>
					</a>
				<?php } ?>
			</div>
			<div class="setting_body_div">
				<form id="setting_priv_frm" action="/viantes/pvt/pages/setting/settingModify.php" method="post">
					<input type="hidden" name="type" value="priv"/>
					<input type="hidden" name="usrId" value="<?php echo $userDO->getId() ?>" />
					<div class="setting_row_div">
						<p class="flt-l"><?php echo $X_langArray['SETTING_PROFILE_TYPE'] ?></p>
						<?php  
						$tp = $settingDO->getProfileType(); 
						if ( isset($_GET['mod']) && "m" == $_GET['mod'] && isset($_GET['t']) && $_GET['t'] == "priv" ) { ?>
							<select class="selectSettAndProfile" name="profileType">
								<option <?php if ($tp == 0) echo "selected" ?> value="0"><?php echo $X_langArray['SETTING_PROFILE_TYPE_0'] ?></option>
								<option <?php if ($tp == 1) echo "selected" ?> value="1"><?php echo $X_langArray['SETTING_PROFILE_TYPE_1'] ?></option>
							</select>	
						<?php } else { ?>
							<p class="setting_row-p-right">
								<?php 
								if ($tp == 0) echo $X_langArray['SETTING_PROFILE_TYPE_0'];
								else if ($tp == 1) echo $X_langArray['SETTING_PROFILE_TYPE_1'];
								?>
							</p>
						<?php } ?>
					</div>
				</form>	
			</div>
			
		</div>
		
		<?php require_once $X_root."pvt/pages/common/right_section.html"; ?>
	</div>
		
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
		
</body>
</html>
