<?php 
$X_root = "../../../../../viantes/";
$X_page = "showMsg";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
//prima di verificare la sessione salvo la richeesta
$msgId = isset($_GET['msgId']) ? X_deco($_GET['msgId']) : -1;
savePageRequest("/viantes/pub/pages/profile/showMsg.php?msgId=".$_GET['msgId']);
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/msg/msgDAO.php";
require_once $X_root."pvt/pages/msg/msgDO.php";
require_once $X_root."pvt/pages/setting/settingDAO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$userDO = unserialize($_SESSION["USER_LOGGED"]);

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());

$pattern = getDatePatternByLangCode($settingDO->getLangCode());

$msgDAO = New MsgDAO();
$msgDO = $msgDAO->getMsgById($msgId, $pattern);

//se il messaggio e' da leggere ed e' testinato all'utente corrente => lo leggo e decremento inBoxMsgNum */
if ($msgDO->getRecipientStatus() == 0 && $msgDO->getToUsrId() == $userDO->getId()) {
	$msgDAO->readMesasge($msgId, $userDO->getId());
	
	//decremento il numero di messaggi da leggere
	$inBoxMsgNum = $userDO->getInBoxMsgNum();
	if ($inBoxMsgNum > 0 ) $inBoxMsgNum--;
	$userDO->setInBoxMsgNum($inBoxMsgNum);
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
	<title><?php echo $X_langArray['SHOW_REVIEW_PAGE_TITLE_NOT_FOUND'];	?></title>
	<?php require_once $X_root."pvt/pages/common/meta-link-script.html"; ?>
</head>

<!-- Overlay -->
<?php require_once $X_root."pvt/pages/common/overlay-send-msg.html"; ?>
<?php require_once $X_root."pvt/pages/common/overlay-del-msg.html"; ?>
<?php require_once $X_root."pvt/pages/common/overlay-loading.html"; ?>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html"; ?>
	
	<div class="main-div">
		
		<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
		
		<div class="mrg-top-24">
			<a href="/viantes/pub/pages/profile/message.php?tabactive=<?php echo $_GET['tabactive']?>">
				<?php echo $X_langArray['MESSAGE_BACK_LINK'] ?>
			</a>
		</div>
	
		<div class="mrg-top-36 showMsgContainerDiv">
			<div class="mrg-top-12 showMsgRowDiv showMsgRowDivPadding">
				<label>
					<span class="show-msg-pan_col1">
						<b><?php echo $X_langArray['MESSAGE_COMMON_FROM'] ?>:</b>
					</span>
					<span class="show-msg-col2">
						<a href="/viantes/pub/pages/profile/showProfile.php?usrId=<?php echo X_code($msgDO->getFromUsrId())?>" >
							<?php echo $msgDO->getFromUsrName(); ?>
							<img class="msgCoverBodyCell" <?php echo IMG_25_25 ?> src="<?php echo $msgDO->getFromUsrCoverFileName() ?>" />
						</a>
					</span>
				</label>	
			</div>
			
			<div class="mrg-top-12 showMsgRowDiv showMsgRowDivPadding">
				<label>
					<span class="show-msg-pan_col1">
						<b><?php echo $X_langArray['MESSAGE_COMMON_TO'] ?>:</b>
					</span>
					<span class="show-msg-col2">
						<a href="/viantes/pub/pages/profile/showProfile.php?usrId=<?php echo X_code($msgDO->getToUsrId())?>" >
							<?php echo $msgDO->getToUsrName(); ?>
							<img class="msgCoverBodyCell" <?php echo IMG_25_25 ?> src="<?php echo $msgDO->getToUsrCoverFileName() ?>" />
						</a>
					</span>
				</label>	
			</div>
			
			<div class="mrg-top-12 showMsgRowDiv showMsgRowDivPadding">
				<label>
					<span class="show-msg-pan_col1">
						<b><?php echo $X_langArray['MESSAGE_COMMON_DT'] ?>:</b>
					</span>
					<span class="show-msg-col2">
						<?php echo $msgDO->getDtIns(); ?>
					</span>
				</label>	
			</div>
			
			<div class="mrg-top-12 showMsgRowDiv showMsgRowDivPadding">
				<label>
					<span class="show-msg-pan_col1">
						<b><?php echo $X_langArray['MESSAGE_COMMON_SBJ'] ?>:</b>
					</span>
					<span class="show-msg-col2">
						<?php echo $msgDO->getSubject(); ?>
					</span>
				</label>	
			</div>
			
			<div class="mrg-top-12 showMsgRowDivPadding">
				<div>
					<span class="show-msg-pan_col1">
						<b><?php echo $X_langArray['MESSAGE_COMMON_MSG'] ?>:</b>
					</span>
				</div>
				<div class="showMsg-Msg">
					<?php echo $msgDO->getMessage(); ?>
				</div>
			</div>
		</div>
		
		<div class="showMsgButtonsDiv">
			<a href="#" class="" onclick="$('#overlay-del-msg').show();">
				<div class="delMsg personalButton"><?php echo $X_langArray['MESSAGE_OVERLAY_DEL']?></div>
			</a>
			<?php if ( $_GET['tabactive'] == 1 ) { ?>
				<a href="#" onclick="$('#autoComplMsgTo').val('<?php echo $msgDO->getFromUsrName() ?>');
									 $('#ovrMsgSbjjt').val('<?php echo 'RE:'.$msgDO->getSubject() ?>');
									 $('#msgId').val('<?php echo $_GET['msgId'] ?>');
									 showMsgOverlay('/viantes/pub/pages/profile/showMsg.php')">
					<div class="delMsg personalButton"><?php echo $X_langArray['MESSAGE_OVERLAY_REPLY']?></div>
				</a>
			<?php } if ( $_GET['tabactive'] == 4 ){ ?>
			<a href="#" class="" onclick="$('#isRrestore').val(1); $('#overlay-del-msg-question1').hide(); $('#overlay-del-msg-question2').show(); $('#overlay-del-msg').show();">
				<div class="delMsg personalButton"><?php echo $X_langArray['MESSAGE_OVERLAY_RESTORE']?></div>
			</a>
			<?php } ?>	
		</div>
		
		<!-- Questi campi simulano lo scenario come quello che avviene nei file msgTabx 
			In questo modo posso riusare i metodi confirmDelMsg(), 
		-->
		<input type="hidden" id="delMsgShoMsg" value="<?php echo $_GET['msgId'] ?>" > 
		<input type="hidden" id="isRrestore" value="0"/>	
		<input type="hidden" id="tabactive" value="<?php echo$_GET['tabactive'] ?>"/>	
	</div>
	
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>
			
</body>
</html>
