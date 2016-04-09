<?php 
$X_root = "../../../../viantes/";
$X_page = "myProfile";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
//prima di verificare la sessione salvo la richeesta
savePageRequest("/viantes/pub/pages/profile/myProfile.php");
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/auth/userRegistryDAO.php";
require_once $X_root."pvt/pages/auth/userRegistryDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$userDO = unserialize($_SESSION["USER_LOGGED"]);

$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());

$pattern = getDatePatternByLangCode($settingDO->getLangCode());
Logger::log("myProfile :: pattern per data rilevato :: ".$pattern, 3);

$userRegistryDAO = New UserRegistryDAO();
$userRegistryDO = $userRegistryDAO->getUserRegistryByUserId($userDO->getId(), $pattern);
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
	<title><?php echo $X_langArray['MYPROFILE_PAGE_TITLE'] ?></title>
	<?php
	// n.b. se setti questa variabile DEVE essere valorizzata la variabile $settingDO
	$X_showDatepicker   = true;
	require_once $X_root."pvt/pages/common/meta-link-script.html"; 
	?>
</head>
		
<body>
	<?php require_once $X_root."pvt/pages/common/header.html";?>
	
	<div class="main-div">

		<div class="body-div">		
			<?php require_once $X_root."pvt/pages/common/globalTopMsg.php"; ?>
			
			<div>

				<div class="bckGrndProfileCover">
					<form id="attachCoverFrm" enctype="multipart/form-data" action="/viantes/pvt/pages/upload/uploadUserCover.php" method="POST">
						<!-- Visibile sui browser -->
						<img class="hideInIE"  src="<?php echo $userDO->getBckCoverFileName(); ?>" <?php echo IMG_748_290 ?>
								 onclick="$('#attachImgInput2').click();">
						<!-- Visibili in ie7/8 -->
						<img class="showInIE" src="<?php echo $userDO->getBckCoverFileName(); ?>" <?php echo IMG_748_290 ?> >

						<!-- Pulsante type=file nascosto per i browser visibile in ie -->
						<input class="hideInputButtonFile" style="position:absolute; right: 0; width: 70px; height: 26px; margin-top: -26px; z-index:999" 
							   id="attachImgInput2" name="userfile" type="file" onchange="$('#submitCov2').click();" value="cambia"/>

						<!-- Pulsante submit che non si vede mai -->
						<input class="hidden" id="submitCov2" name="submit" type="submit" value="GO" />

						<input type="hidden"  name="backUrl"   value="/viantes/pub/pages/profile/myProfile.php" />
						<input type="hidden"  name="usrId"     value="<?php echo $userDO->getId();?>"/>
						<input id="coverType" name="coverType" value="2" type="hidden"  /><!-- vale 1 => cover profile - 2 => load back cover profile-->
					</form>

					<form id="attachCoverFrm" enctype="multipart/form-data" action="/viantes/pvt/pages/upload/uploadUserCover.php" method="POST">
						<div style="position: relative;">
							<!-- Visibile sui browser -->
							<img class="profileCover hideInIE" src="<?php echo $userDO->getCoverFileName(); ?>" <?php echo IMG_128_128 ?>
								 onclick="$('#attachImgInput1').click();">

							<!-- Visibili in ie -->
							<img class="profileCover showInIE" src="<?php echo $userDO->getCoverFileName(); ?>" <?php echo IMG_128_128 ?> >
							<input id="attachImgInput1" class="profileCover hideInputButtonFile" style="margin:105px 0px 0px 68px; width: 70px; height: 26px;"  
								   name="userfile" type="file" onchange="$('#submitCov1').click();" />
						</div>

						<!-- Pulsante submit che non si vede mai -->
						<input class="hidden" id="submitCov1" name="submit" type="submit" value="GO" />

						<input type="hidden"  name="backUrl"   value="/viantes/pub/pages/profile/myProfile.php" />
						<input type="hidden"  name="usrId"     value="<?php echo $userDO->getId();?>"/>
						<input id="coverType" name="coverType" value="1" type="hidden"  /><!-- vale 1 => cover profile - 2 => load back cover profile-->
					</form>
				</div>

				<div class="second-header">	
					<?php if ( isset($_GET['loadCovImgErrMsg']) ){ ?>
						<p class="p-error" style="font-size: 16px"><?php echo urldecode($_GET['loadCovImgErrMsg']) ?><p>
					<?php } ?>
					<h1>
						<?php echo $userDO->getName();?>
					</h1>
				</div>
				
				<form id="myProfileFrm" action="/viantes/pvt/pages/auth/userRegistryModify.php" method="POST">
					<input type="hidden" name="usrId" value="<?php echo $userDO->getId();?>"/>
					
					<div id="profile_top_div">
						<h3><?php echo $X_langArray['MYPROFILE_MY_INFO'] ?></h3>
						<?php if ( isset($_GET['mod']) && "m" == $_GET['mod'] ) { ?>
							<!-- Cancel and Save link -->
							<a class="setting-a1" href="/viantes/pub/pages/profile/myProfile.php">
								<?php echo $X_langArray['MYPROFILE_MY_INFO_CANCEL'] ?>
							</a>
							<a class="setting-a2" href="javascript:$('#myProfileFrm').submit();">
								<?php echo $X_langArray['MYPROFILE_MY_INFO_SAVE'] ?>
							</a>
						<?php } else { ?>
						<!-- Modify link -->
						<a class="setting-a3" href="/viantes/pub/pages/profile/myProfile.php?mod=m">
							<?php echo $X_langArray['MYPROFILE_MY_INFO_MODIFY'] ?>
						</a>
						<?php } ?>
					</div>
					<?php if ( isset($_GET['mod']) && "m" == $_GET['mod'] ) { ?>
						<div class="setting_body_div">
							<!-- First Name -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_FIRST_NAME'] ?></p></b>
								<input name="firstName" class="inputSettAndProfile <?php if (isset($_GET['firstNameErrMsg'])) echo "errorInput" ?>" type="text" 
									   value="<?php if (isset($_GET['firstName'])) echo urldecode($_GET['firstName']); else echo $userRegistryDO->getFirstName(); ?>" />
							</div>
							<?php if ( isset($_GET['firstNameErrMsg']) ) { ?>
								<div class="setting_row_error_div">
									<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['firstNameErrMsg']) ?><p>
								</div>
							<?php } ?>
							
							<!-- Last Name -->
							<div class="setting_row_div_alterned">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_LAST_NAME'] ?></p></b>
								<input name="lastName" class="inputSettAndProfile <?php  if (isset($_GET['lastNameErrMsg'])) echo "errorInput" ?>" type="text" 
									   value="<?php if (isset($_GET['lastName'])) echo urldecode($_GET['lastName']); else echo $userRegistryDO->getLastName(); ?>" />
							</div>
							<?php if ( isset($_GET['lastNameErrMsg']) ) { ?>
								<div class="setting_row_error_div_alterned">
									<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['lastNameErrMsg']) ?><p>
								</div>
							<?php } ?>
							
							<!-- Email -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_EMAIL'] ?></p></b>
								<input class="inputSettAndProfile" type="text" value="<?php echo $userDO->getEmail();?>" disabled="disabled"/>
							</div>
							
							<!-- Mobile Number -->
							<div class="setting_row_div_alterned">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_MOBILE_NUM'] ?></p></b>
								<input name="mobileNum" class="inputSettAndProfile <?php  if (isset($_GET['mobileNumErrMsg'])) echo "errorInput" ?>" type="text"
									   value="<?php if (isset($_GET['mobileNum'])) echo urldecode($_GET['mobileNum']); else echo $userRegistryDO->getMobileNum(); ?>" />
							</div>
							<?php if ( isset($_GET['mobileNumErrMsg']) ) { ?>
								<div class="setting_row_error_div_alterned">
									<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['mobileNumErrMsg']) ?><p>
								</div>
							<?php } ?>
							
							<!-- Gender -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_GENDER'] ?></p></b>
								<select class="selectSettAndProfile" name="gender">
									<?php  
									$s0=""; $s1=""; $s2="";
									if ($userRegistryDO->getGender() == 1) $s1="selected";
									else if ($userRegistryDO->getGender() == 2) $s2="selected";
									else $s0="selected";
									if ($userRegistryDO->getGender() == 0) { ?>
										<option <?php echo $s0 ?> value="0"><?php echo $X_langArray['MYPROFILE_GENDER_0'] ?></option>
									<?php } ?>	
									<option <?php echo $s1 ?> value="1"><?php echo $X_langArray['MYPROFILE_GENDER_1'] ?></option>
									<option <?php echo $s2 ?> value="2"><?php echo $X_langArray['MYPROFILE_GENDER_2'] ?></option>
								</select>
							</div>
							
							<!--Birth date -->
							<div class="setting_row_div_alterned">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_BIRTH_DATE']?></p></b>
								<input name="dateOfBirth" id="birth-date-id" class="inputSettAndProfile <?php  if (isset($_GET['dateOfBirthErrMsg'])) echo "errorInput" ?>" type="text" 
									   value="<?php if (isset($_GET['dateOfBirth'])) echo urldecode($_GET['dateOfBirth']); else echo $userRegistryDO->getDateOfBirth(); ?>" />
							</div>
							<?php if ( isset($_GET['dateOfBirthErrMsg']) ) { ?>
								<div class="setting_row_error_div_alterned">
									<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['dateOfBirthErrMsg']) ?><p>
								</div>
							<?php } ?>
							
							<!-- City -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_CITY']?></p></b>
								<input name="city" id="city" class="inputSettAndProfile <?php if (isset($_GET['cityErrMsg'])) echo "errorInput" ?>" type="text" 
									   value="<?php if (isset($_GET['city'])) echo urldecode($_GET['city']); else echo $userRegistryDO->getCity(); ?>" />
							</div>
							<?php if ( isset($_GET['cityErrMsg']) ) { ?>
								<div class="setting_row_error_div">
									<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['cityErrMsg']) ?><p>
								</div>
							<?php } ?>
							
							<!-- Posal Code -->
							<div class="setting_row_div_alterned">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_POSTAL_CODE']?></p></b>
								<input name="postcode" id="postcode" class="inputSettAndProfile <?php  if (isset($_GET['postcodeErrMsg'])) echo "errorInput" ?>" type="text" 
									   value="<?php if (isset($_GET['postcode'])) echo urldecode($_GET['postcode']); else echo $userRegistryDO->getPostcode(); ?>" />
							</div>
							<?php if ( isset($_GET['postcodeErrMsg']) ) { ?>
								<div class="setting_row_error_div_alterned">
									<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['postcodeErrMsg']) ?><p>
								</div>
							<?php } ?>
							
							<!-- Country -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_COUNTRY']?></p></b>
								<input name="country" id="country" class="inputSettAndProfile <?php  if (isset($_GET['countryErrMsg'])) echo "errorInput" ?>" type="text" 
									   value="<?php if (isset($_GET['country'])) echo urldecode($_GET['country']); else echo $userRegistryDO->getCountry(); ?>" />
							</div>
							<?php if ( isset($_GET['countryErrMsg']) ) { ?>
								<div class="setting_row_error_div">
									<p class="setting-p-error mrg-top--3"><?php echo urldecode($_GET['countryErrMsg']) ?><p>
								</div>
							<?php } ?>
							
						</div>
					<?php } else { ?>
						<div class="setting_body_div">
							<!-- Name -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_FIRST_NAME'] ?></p></b>
								<p class="setting_row-p-right"><?php echo $userRegistryDO->getFirstName();?></p>
							</div>
							<!-- Last Name -->
							<div class="setting_row_div_alterned">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_LAST_NAME'] ?></p></b>
								<p class="setting_row-p-right"><?php echo $userRegistryDO->getLastName();?></p>
							</div>
							<!-- Email -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_EMAIL'] ?></p></b>
								<p class="setting_row-p-right"><?php echo $userDO->getEmail();?></p>
							</div>
							<!-- Mobile Number -->
							<div class="setting_row_div_alterned">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_MOBILE_NUM'] ?></p></b>
								<p class="setting_row-p-right"><?php echo $userRegistryDO->getMobileNum();?></p>
							</div>
							<!-- Gender -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_GENDER'] ?></p></b>
								<p class="setting_row-p-right">
									<?php
									if ($userRegistryDO->getGender() == 1) echo  $X_langArray['MYPROFILE_GENDER_1'];
									else if ($userRegistryDO->getGender() == 2) echo  $X_langArray['MYPROFILE_GENDER_2'];
									else echo "";
									?>
								</p>
							</div>
							<!--Birth date -->
							<div class="setting_row_div_alterned">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_BIRTH_DATE']?></p></b>
								<p class="setting_row-p-right"><?php echo $userRegistryDO->getDateOfBirth();?></p>
							</div>		
							<!-- City -->
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_CITY']?></p></b>
								<p class="setting_row-p-right"><?php echo $userRegistryDO->getCity();?></p>
							</div>
							<!-- Posal Code -->
							<div class="setting_row_div_alterned">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_POSTAL_CODE']?></p></b>
								<p class="setting_row-p-right"><?php echo $userRegistryDO->getPostcode();?></p>
							</div>
							<!-- Country -->						
							<div class="setting_row_div">
								<b><p class="flt-l"><?php echo $X_langArray['MYPROFILE_COUNTRY']?></p></b>
								<p class="setting_row-p-right"><?php echo $userRegistryDO->getCountry();?></p>
							</div>
						</div>	
					<?php } ?>
				</form>	
				
			</div>
			
			<hr class="commonRowHR">
			
			<!-- My review included -->
			<div id="setting_top_div" class="setting_top_div">  <!-- devo mettere anche il class se no si rompe con ie !!!-->
				<h3><?php echo $X_langArray['MYPROFILE_MY_REVIEW'] ?></h3>
			</div>			
			<?php 
			$X_userId = $userDO->getId();
			$X_pattern = $pattern;
			include $X_root."pvt/pages/review/common/userReview.php" 
			?>
			
		</div>
		
		<?php require_once $X_root."pvt/pages/common/right_section.html"; ?>
	</div>
		
	<?php require_once $X_root."pvt/pages/common/footer.html"; ?>

</body>
</html>
