<?php 
$X_root = "../../../viantes/";
$X_page = "confirmSignUp";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDAO.php";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/log/log.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$userDAO = New UserDAO();
$isAuth = FALSE;

$postConfPwd = isset($_POST['confPwd']) ? $_POST['confPwd'] : "";
$postEmail = isset($_POST['email']) ? $_POST['email'] : "";
$postFwdCode = isset($_POST['fwdCode']) ? $_POST['fwdCode'] : "";

$errorMessage = "";
//ho cliccato conferma ma la pwd e' vuota
if ( isset($_POST['ispost']) && $postConfPwd == "" ) {
	$errorMessage = $X_langArray['CONF_SIGN_EMPTY_PWD_ERR'];
}

//ho cliccato conferma con pwd valorizzata
if ( isset($_POST['ispost']) && $postConfPwd != "" ) {
	$userDO = $userDAO->checkConfirmSingIn($postEmail, sha1($postConfPwd), $postFwdCode);
	if (is_object($userDO)) {
		$isAuth = TRUE;
		
		//set logged in session and last activity
		$_SESSION['USER_LOGGED'] = serialize($userDO);
		$_SESSION['LAST_ACTIVITY'] = time();
		$_SESSION[GLOBAL_TOP_MSG_SUCCESS] = $X_langArray['CONF_SIGN_PAGE_OK'];
		
		//set cookie
		setcookie("LOGGED_IN", "Loggato", time() + 1200, "/"); //1200 second = 30 min.
		
		//update status 
		$updated = $userDAO->updateUserStatus($postEmail, sha1($postConfPwd), $postFwdCode);
	}
	else {
		$errorMessage = $X_langArray['CONF_SIGN_PWD_ERR'];
	}
}

$fwdCode = isset($_GET['fwdCode']) ? $_GET['fwdCode'] : "";
$email = isset($_GET['email']) ? $_GET['email'] : "";

//check url param is ok
$isCheckedURL = $userDAO->checkEmailAndFwdCodeIsValid($email, $fwdCode);
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
	<title><?php echo $X_langArray['CONF_SIGN_TITLE'] ?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay-login-signin -->
<?php include $X_root."pvt/pages/common/overlay-login-signin.html"; ?>
<input type="hidden" id="ovrly-initial-src-page" value="/index.php" />
<input type="hidden" id="ovrly-initial-login-dst-page" value="/index.php" />
<input type="hidden" id="ovrly-initial-sign-dst-page" value="/index.php" />

<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div  id="main-div" class="main-div">
	
		<div class="body-div">			
			
			<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
			
			<div class="top-header">
				<h1 class="entry-title"><?php echo ($isAuth ? $X_langArray['CONF_SIGN_H3_OK'] : $X_langArray['CONF_SIGN_H3']) ?></h1>
			</div>
			
			<div>
				<?php 
				//echo 'isCheckedURL:'.$isCheckedURL.'<br>'.$_POST['ispost'].'<br> isAuth:'.$isAuth;
				
				// vengo dalla GET (invio mail) e l'URL non e' verificata
				if (!$isCheckedURL && !isset($_POST['ispost'])) { 
				?>
					<h4><?php echo $X_langArray['CONF_SIGN_PAGE_KO']; ?></h4>
					<br><br>
					<p><?php echo $X_langArray['CONF_SIGN_PAGE_NON_AVLB_TIT'] ?></p><br>
					<ul style="list-style-type: circle;">
						<li class="errorListText"><p><?php echo $X_langArray['CONF_SIGN_PAGE_NON_AVLB_CAUSE_1'] ?></p></li>
						<li class="errorListText"><p><?php echo $X_langArray['CONF_SIGN_PAGE_NON_AVLB_CAUSE_2'] ?></p></li>
						<li class="errorListText"><p><?php echo $X_langArray['CONF_SIGN_PAGE_NON_AVLB_CAUSE_3'] ?></p></li>
					</ul>
				<?php
				}
				// vengo dalla GET (con URL verificata) oppure dalla POST
				else {
					//vengo dalla POST e la password e' corretta (FINE del giro)
					if ($isAuth) {
				?>
						<!-- <p><?php //echo $X_langArray['CONF_SIGN_PAGE_OK'] ?> </p><br -->
						<p>
							<?php echo $X_langArray['CONF_SIGN_PAGE_OK_NOW'] ?> <a href="/viantes/pub/pages/review/createReview.php">
							<?php echo $X_langArray['CONF_SIGN_PAGE_OK_HR'] ?></a>
						</p> 
						<br>
						<p>
							<?php echo $X_langArray['CONF_SIGN_PAGE_OK_NOW_HOME'] ?> <a href="/index.php">
							<?php echo $X_langArray['CONF_SIGN_PAGE_OK_HR'] ?></a>
						</p>
				<?php
					// vengo dalla GET (con URL verificata) oppure dalla POST
					} else {
				?>
						<br>
						<form action="/viantes/pub/pages/confirmSignUp.php" method="post" id="commentform" class="comment-form">
							<p><?php echo $X_langArray['CONF_SIGN_FORM_TITLE'] ?></p>
							<br><br>
							<table>
								<tr>
									<td><?php echo $X_langArray['CONF_SIGN_PWD'] ?> :</td>
									<?php if ( $errorMessage != "" ) { ?>
										<td><input type="password" name="confPwd" class="errorInput" /></td>
									<?php } else { ?>
										<td><input type="password" name="confPwd"/></td>
									<?php } ?>
										<td>
											<input name="submit" type="submit" id="submit" value="<?php echo $X_langArray['CONF_SIGN_DONE'] ?>!" />
										</td>
								</tr>
								<?php if ( $errorMessage != "" ) { ?>
								<tr class="errorInput">
									<td></td>
									<td colspan="2"><p> <?php echo $errorMessage; ?></p></td>
								</tr>
								<?php } ?>
							</table>
							<input type="hidden" name="fwdCode" value="<?php echo $fwdCode != "" ? $fwdCode : $postFwdCode ?>"/>
							<input type="hidden" name="email" value="<?php echo $email != "" ? $email : $postEmail ?>"/>
							<input type="hidden" name="ispost" value="ispost"/>
						</form>
				<?php
					}
				}
				?>
			</div>
		</div>	
		
		<?php require_once $X_root."pvt/pages/common/right_section.html"; ?>
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
			
</body>
</html>
