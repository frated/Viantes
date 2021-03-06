<?php 
$X_root = "../../../../viantes/";//root che referenzia risorse sotto /vaintes
$MX_root = "../../"; //root che referenzia risorse sotto /m/vaintes
$X_page = "contacts";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);
?>

<!DOCTYPE html>
<html>

<head>
	<title><?php echo $X_langArray['CONTACT_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
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
	<input type="hidden" id="ovrly-initial-src-page" value="/viantes/pub/pages/contacts.php" />
	<input type="hidden" id="ovrly-initial-login-dst-page" value="/viantes/pub/pages/contacts.php" />
	<input type="hidden" id="ovrly-initial-sign-dst-page" value="/viantes/pub/pages/contacts.php" />

	<div id="main-div" class="main-div">
		<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
		<div>
			<div class="top-header">
				<h1><?php echo $X_langArray['CONTACT_TITLE'] ?></h1>
			</div>
			
			<div class="second-header-font14">	
				<h1 style="display: inline-block">
					<?php echo $X_langArray['CONTACT_DISCL'] ?>
				</h1>
			</div>

			<form action="/viantes/pvt/pages/contact/contact.php" method="post" >

				<!-- NAME -->
				<div class="mrg-bot-6">
					<b><?php echo $X_langArray['CONTACT_NAME'] ?></b>
				</div>
				<div>
					<input id="name" type="text" name="name" style="background-color: #f1f1f1" class="commonRowTxt_col2 
							   <?php if (isset($_GET['nameErrMsg']) && urldecode($_GET['nameErrMsg']) != "") { echo " errorInput";} ?>"
							   value="<?php if (isset($_GET['name'])) echo urldecode($_GET['name']);?>"
						/>
					<span class="commonRowMandatory">*</span>

					<div class="mrg-top-3">
						<p class="p-error">
							<?php if ( isset($_GET['nameErrMsg']) ) { echo urldecode($_GET['nameErrMsg']);} ?>
						</p>
					</div>
				</div>

				<!-- MAIL -->
				<div class="mrg-bot-6">
					<b><?php echo $X_langArray['CONTACT_MAIL'] ?></b>
				</div>
				<div>
					<input id="email" type="text" name="email" style="background-color: #f1f1f1;" class="commonRowTxt_col2 
							   <?php if (isset($_GET['emailErrMsg']) && urldecode($_GET['emailErrMsg']) != "") { echo " errorInput";} ?>"
							   value="<?php if (isset($_GET['email'])) echo urldecode($_GET['email']);?>"
						/>
					<span class="commonRowMandatory">*</span>

					<div class="mrg-top-3">
						<p class="p-error">
							<?php if ( isset($_GET['emailErrMsg']) ) { echo urldecode($_GET['emailErrMsg']);} ?>
						</p>
					</div>
				</div>

				<!-- DESCRIPTION -->
				<div class="mrg-bot-6">
					<b><?php echo $X_langArray['CONTACT_COMMENT'] ?></b>
				</div>
				<div>
					<textarea id="descr" name="descr" style="background-color: #f1f1f1; width: 77%;" class="commonRowArea_col2_1000
							  <?php if (isset($_GET['descrErrMsg']) && urldecode($_GET['descrErrMsg']) != "" ) { echo " errorInput";} ?>"
					><?php if (isset($_GET['descr'])) echo urldecode($_GET['descr']);?></textarea>
					<span class="commonRowMandatory">*</span>

					<div class="mrg-top-3">
						<p class="p-error">
							<?php if ( isset($_GET['descrErrMsg']) ) { echo urldecode($_GET['descrErrMsg']);} ?>
						</p>
					</div>
				</div>

				<!-- CAPTCHA -->
				<div class="mrg-bot-6">
					<b><?php echo $X_langArray['CONTACT_SECURE'] ?></b>
				</div>
				<div>
					<input type="text" size="6" maxlength="6" name="captcha_code" style="background-color: #f1f1f1; width: 20%;" class="overlay-lg-sg-input-col22 
							<?php if (isset($_GET['cptchErrMsg']) && urldecode($_GET['cptchErrMsg']) != "") { echo " errorInput";} ?>">
					<span class="commonRowMandatory">*</span>

					<img id="captcha_img" src="/viantes/pvt/pages/captcha/captcha.php?reqType=<?php echo CONTACT ?>" style="margin: -3px 0px 0px 26px; position: absolute;">
					<a href="#" onclick="refreshCaptcha(<?php echo CONTACT ?>);" style="position: absolute; margin-left: 153px; margin-top: 5px;" >
						<?php echo $X_langArray['OVERLAY_LOG_SIGN_REFRESH_CAPTCHA'] ?>
					</a>

					<div class="mrg-top-3">
						<p class="p-error">
							<?php if ( isset($_GET['cptchErrMsg']) ) { echo urldecode($_GET['cptchErrMsg']);} ?>
						</p>
					</div>
				</div>
				
				<!-- SUBMIT -->
				<div class="mrg-bot-6">
					<input id="submit" name="submit" class="hgt-30 wdth-60" type="submit" value="<?php echo $X_langArray['CONTACT_SEND'] ?>"/>
				</div>

			</form>

		</div>
	</div>	
	
	<?php require_once $MX_root."pvt/pages/common/footer.html"; ?>
			
</body>
</html>
