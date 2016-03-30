<?php 
$X_root = "../../";
$X_page = "recoverPwd";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$userDAO = New UserDAO();
$validLink = FALSE;

$errorMessage = "";

$fwdCode = isset($_GET['fwdCode']) ? urldecode($_GET['fwdCode']) : "";
$email = isset($_GET['email']) ? $_GET['email'] : "";

$userDO = $userDAO->checkRecoverPwd($email, $fwdCode);
if (is_object($userDO)) {
	$validLink = TRUE;
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
	<title><?php echo $X_langArray['RECOVER_PWD_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
	
	<script type="text/javascript" src="<?php echo $X_root.'pvt/js/jPass.js'?>"></script>
</head>

<!-- Overlay-login-signin -->
<?php include $X_root."pvt/pages/common/overlay-login-signin.html"; ?>
<input type="hidden" id="ovrly-initial-src-page" value="/index.php" />
<input type="hidden" id="ovrly-initial-login-dst-page" value="/index.php" />
<input type="hidden" id="ovrly-initial-sign-dst-page" value="/index.php" />

<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div id="main-div" class="main-div">
	
		<div class="body-div">			
			
			<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
			
			<div class="top-header">
				<h1 class="entry-title"><?php echo ($validLink ? $X_langArray['RECOVER_PWD_H3_OK'] : $X_langArray['RECOVER_PWD_H3']) ?></h1>
			</div>	
			
			<div>
				<form action="/viantes/pvt/pages/auth/completeRecover.php" method="post" id="commentform" class="comment-form">
					<p><?php echo $X_langArray['RECOVER_PWD_FORM_TITLE'] ?></p>
					<br><br>
					
					<?php if ($validLink) {?>
					<div class="recoverPwdRow">
						<label>
							<span class="ovl-lg-sg-span_col1">
								<b><?php echo $X_langArray['RECOVER_PWD_PWD'] ?>:</b>
							</span>
							<input id="pwd" type="password" name="pwd" 
								class="overlay-lg-sg-input-col22"
						   		value="<?php if (isset($_GET['pwd'])) echo urldecode($_GET['pwd']);?>"
							/>
							<span class="commonRowMandatory">*</span>
						</label>
					</div>
					
					<div class="recoverPwdRow">
						<label>
							<span class="ovl-lg-sg-span_col1">
								<b><?php echo $X_langArray['RECOVER_PWD_CNFRM_PWD'] ?>:</b>
							</span>
							<input id="pwd2" type="password" name="pwd2" 
								class="overlay-lg-sg-input-col22
								<?php echo (isset($_GET['pwd2ErrMsg']) ? " errorInput" : "" ) ?>"
						   		value="<?php if (isset($_GET['pwd2'])) echo urldecode($_GET['pwd2']);?>" 
							/>
							<span class="commonRowMandatory">*</span>

							<!-- Seconda riga - Msg Err -->
							<div id="emailDIV" class="overlay-lg-sg-ErrorDiv">
								<p class="p-error">
									<?php if ( isset($_GET['pwd2ErrMsg']) ) { echo urldecode($_GET['pwd2ErrMsg']);} ?>
								</p>
							</div>
						</label>
					</div>
					
					<!-- RECOVER -->
					<div class="mrg-top-24 mrg-bot-16 recoverPwdRow">
						<!-- Prima riga - Campi -->
						<label>
							<span class="ovl-lg-sg-span_col1"></span>
						</label>
						<input type="submit" class="overlay-lg-sg-submit-col2" name="invia" value="<?php echo $X_langArray['RECOVER_PWD_SUBMIT_COMPLETE'] ?>"/>
						<input type="hidden" name="fwdCode" value="<?php echo $fwdCode != "" ? $fwdCode : $postFwdCode ?>"/>
						<input type="hidden" name="email" value="<?php echo $email != "" ? $email : $postEmail ?>"/>
					</div>
					
					<?php } else {?>
						<h4><?php echo $X_langArray['RECOVER_PWD_PAGE_KO']; ?></h4>
						<br><br>
						<p><?php echo $X_langArray['RECOVER_PWD_PAGE_NON_AVLB_TIT'] ?></p><br>
						<ul style="list-style-type: circle;">
							<li class="errorListText"><p><?php echo $X_langArray['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_1'] ?></p></li>
							<li class="errorListText"><p><?php echo $X_langArray['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_2'] ?></p></li>
							<li class="errorListText"><p><?php echo $X_langArray['RECOVER_PWD_PAGE_NON_AVLB_CAUSE_3'] ?></p></li>
						</ul>
					<?php } ?>
				</form>
			</div>
		</div>	
		
		<?php require_once $X_root."pvt/pages/common/right_section.html"; ?>
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
			
</body>
</html>
