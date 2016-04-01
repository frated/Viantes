<?php
$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());
$langCode = $settingDO->getLangCode();

//===================== GEO ===========================
//setto il nome del sito (prima quello inserito dell'utente, poi quello di Google)
$X_GEO_country = null; 
if ( isset($_GET['country']) && $_GET['country'] != '')
	$X_GEO_country = $_GET['country'];	
else if ( isset($_GET['countryName']) && $_GET['countryName'] != '')
	$X_GEO_country = $_GET['countryName'];	


$X_GEO_zoom = 5; //zoom
$X_GEO_mapType = 'roadmap'; //tipo di mappa 
$X_GEO_disableUI = false; //disabilita la UI sulla mappa

//indica se all'avvio devo caricare la mappa 
$errorGeo = (isset($_GET['countryErrMsg']) && $_GET['countryErrMsg'] != '') ? true : false;
$X_GEO_loadMap =  ($X_GEO_country != null && !$errorGeo ) ? true : false;

//Messaggio di errore localizzato quando l'indirizzo non e' trovato
$X_GEO_ERR_MSG = $X_langArray['CREATE_COUNTRY_ADDRS_NOT_FOUND'];

require_once $X_root."pvt/pages/geo/countryMap.html";
//====================================================
?>

<form action="/viantes/pvt/pages/review/create/createCountryRev.php" enctype="multipart/form-data" id="writeRevTab1Frm" method="post" >
	
	<input type="hidden" name="langCode"       value="<?php echo $langCode ?>"/>
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
	
	<!-- SECTION 1 -->
	<div id="review_top_div"> 
		<h3>
			<?php echo $X_langArray['CREATE_COUNTRY_REV_TITLE_ESSENTIAL'] ?>
		</h3>
	</div>
	
	<div class="commonDIVLeft">	
		
		<!-- COUNTRY -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_COUNTRY'] ?></b>&nbsp;
				</span>
				<input id="country" type="text" name="country" style="width: 52%" class="commonRowTxt_col2 
					   <?php if (isset($_GET['countryErrMsg']) && urldecode($_GET['countryErrMsg']) != "") { echo " errorInput";} ?>"
					   value="<?php if (isset($_GET['country'])) echo urldecode($_GET['country']);?>" 
					   onfocus="showCartoon('country');"
					   onkeyup="doAutocomplete('/viantes/pvt/pages/review/create/createCountryRev.php?langCode=<?php echo $langCode; ?>','country', 3);"
					   onblur="hideCartoon('country');
					   if($('#countryIgnoreBlur').val() === 'true') { return; }
					   doFieldAsyncGet('/viantes/pvt/pages/review/create/createCountryRev.php?langCode=<?php echo $langCode; ?>','country');"
				/>
				<span class="commonRowMandatory">*</span>
				<img id="findMap"	  	 style="width: 3%; cursor:pointer" 	src="/viantes/pvt/img/common/find_G.png">
				<img id="countryFldLD"   class="commonRowImg"   			src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="countryFldOK"   class="commonRowImg"   			src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="countryFrcCart" class="frecciaCartoon" 			src="/viantes/pvt/img/review/fumettoFreccia.png">
				<input type="hidden"     id="countryIgnoreBlur" 			value="" />
				
				<!-- Seconda riga - Msg Err -->
				<div id="countryDIV" class="commonRowInnerDivError">
					<p class="p-error">
						<?php if ( isset($_GET['countryErrMsg']) ) { echo urldecode($_GET['countryErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- THE MAP -->
		<!-- Le prime due (id="map" e id="loadMap" ) sono alternative alla terza (id="fkMap"). A loro volte le prime due sono 
			 sono gestite dal javascript che all'inizio mostra la id="loadMap" poi la  id="map" -->
		<div id="map"     class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>"></div>
		<img id="fkMap"   class="<?php if ($X_GEO_loadMap)  echo " hidden ";?>" style="width: 90%; height: 300px;" src="/viantes/pvt/img/common/iniMap.png">
		<input type="hidden" id="countryName" name="countryName" value=""/><!-- Nome letto dalle api di google maps -->
		
		
		<!-- DESCRIPTION -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_DSCR'] ?></b>&nbsp;
				</span>
				<textarea id="descr" name="descr" class="commonRowArea_col2_1000
						  <?php if (isset($_GET['descrErrMsg']) && urldecode($_GET['descrErrMsg']) != "" ) { echo " errorInput";} ?>"
					      onfocus="showCartoon('descr');"
						  onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createCountryRev.php','descr', 50);"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCountryRev.php','descr');  hideCartoon('descr');"><?php if (isset($_GET['descr'])) echo urldecode($_GET['descr']);?></textarea>
				<span class="commonRowMandatory">*</span>
				<img id="descrFldLD"   class="commonRowImg"   src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="descrFldOK"   class="commonRowImg"   src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="descrFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="descrDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['descrErrMsg']) ) { echo urldecode($_GET['descrErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>

		<!-- COVER -->
		<div class="commonRowDiv">
			<label>
				<?php 
				$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;
				if ( $bean == null || $bean->getCoverFileName() == null || $bean->getCoverFileName() == '' ) {?>
					<span class="commonRowSpan_col1"><b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_COVER'] ?></b></span>
					<div class="commonRowCoverDiv">
						<!-- visible on all browser -->
						<img class="hideInIE" width="24" src="/viantes/pvt/img/review/articleCoverButton_32.png" onclick="$('#reviewCoverId').click();">
						<!-- visible on ie :( -->
						<input class="hideInputButtonFile" id="reviewCoverId" name="userfile" type="file" onchange="$('#loadCov').show(); $('#submitCov').click();"/>
						<span class="commonRowMandatory">*</span>
					</div>					
				<?php } else { ?>
					<span class="commonRowSpan_col1_txtArea"><b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_COVER'] ?></b></span>
				<?php	
					include $X_root."pvt/pages/review/common/renderCover.php";
				}
				?>
				<!-- Seconda riga - Msg Err -->
				<?php if ( isset($_GET['loadCovImgErrMsg']) ) { ?>
					<div id="descrDIV" class="commonRowInnerDivError">
						<p class="p-error">
						<!--p class="articleLoadImgError"-->
							<?php echo urldecode($_GET['loadCovImgErrMsg']) ?>
						</p>
					</div>
				<?php } ?>	 	
			</label>	
		</div>
	</div>
	
	<div class="commonDIVRight">
		<div class="commonRowDiv" style="margin-top: 29px">
			<span id="countryCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_COUNTRY_CARTOON_COUNTRY'] ?>
			</span>
		</div>	
		<div class="commonRowDiv" style="margin-top: 430px">	
			<span id="descrCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_COUNTRY_CARTOON_REVIEW'] ?>
			</span>
		</div>
	</div>
	
	<hr class="commonRowHR">
	
	<!-- SECTION 2 -->
	<a name="createRevSection2"></a>
	<div id="review_top_div"> 
		<h3>
			<?php echo $X_langArray['CREATE_COUNTRY_WHAT_TO_SEE'] ?>
		</h3>
	</div>
	
	<div class="commonDIVLeft">	
		<!-- ADD INTEREST -->
		<div id="interestRowDiv" class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_COUNTRY_WHAT_TO_SEE_ADD'] ?></b>&nbsp;
				</span>
				<a href="#" class="commonRowTxt_col2" > 
					<img src="/viantes/pvt/img/review/addInterest_24_22.png" onclick="$('html,body').scrollTop(0); $('#overlayAddInterest').show();"
						 onmouseover="showCartoon('addInterest');" onmouseout="hideCartoon('addInterest');"> 
				</a>
				<span class="commonRowMandatory">*</span>
				<img id="addInterestFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="addInterestDIV" class="commonRowInnerDivError">
					<p class="p-error">
						<?php if ( isset($_GET['interErrMsg']) ) { echo urldecode($_GET['interErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		<?php
		if ( $bean != null && $bean->getCityInterest() != null ) { ?>	
			<div> 
			<?php $i = 0;
			foreach ( $bean->getCityInterest() as $inter) {
				$arr = explode(attributeDelim, $inter); ?>
				<div class="innerInterestDiv mrg-bot-24">
					<a onclick="window.open('/viantes/pub/pages/review/showReview.php?revId=<?php echo X_code($arr[0]) ?>&reviewType=2&noCleanSess','_blank')" href="#">
						<img width="64px" src="<?php echo $arr[1]?>" >
						<b><p><?php echo $arr[2]?></p></b>
					</a>	
				</div>
			<?php $i++; } ?>
			</div>
		<?php } ?>
	</div>	
	
	<div class="commonDIVRight">	
		<div class="commonRowDiv" style="margin-top: 8px">
			<span id="addInterestCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_COUNTRY_CARTOON_INTEREST'] ?>
			</span>
		</div>
	</div>	
	
	<hr class="commonRowHR">
	
	<!-- SECTION 3 -->
	<div id="review_top_div"> 
		<h3>
			<?php echo $X_langArray['CREATE_COUNTRY_REV_TITLE_OTHER'] ?>
		</h3>
	</div>	
		
	<div class="commonDIVLeft">	
		
		<!-- WARNING -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_WARN'] ?></b>&nbsp;
				</span>
				<textarea id="warn" name="warn" class="commonRowArea_col2_250 
					   <?php if (isset($_GET['warnErrMsg']) && urldecode($_GET['warnErrMsg']) != "" ) { echo " errorInput";} ?>"
					   onfocus="showCartoon('warn');"
					   onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createCountryRev.php','warn', 10);"
					   onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCountryRev.php','warn'); hideCartoon('warn');"><?php if (isset($_GET['warn'])) echo urldecode($_GET['warn']);?></textarea>
				<img id="warnFldLD"   class="commonRowImg"   src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="warnFldOK"   class="commonRowImg"   src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="warnFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="warnDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['warnErrMsg']) ) { echo urldecode($_GET['warnErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- HOW TO Arrive From/To Airport -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_HOW_TO_ARR'] ?></b>&nbsp;
				</span>
				<textarea id="arrive" name="arrive" class="commonRowArea_col2_1000 
						  <?php if (isset($_GET['arriveErrMsg']) && urldecode($_GET['arriveErrMsg']) != "" ) { echo " errorInput";} ?>"
					      onfocus="showCartoon('arrive');"
						  onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createCountryRev.php','arrive', 50);"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCountryRev.php','arrive'); hideCartoon('arrive');"><?php if (isset($_GET['arrive'])) echo urldecode($_GET['arrive']);?></textarea>
				<img id="arriveFldLD"   class="commonRowImg"   src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="arriveFldOK"   class="commonRowImg"   src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="arriveFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="arriveDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['arriveErrMsg']) ) { echo urldecode($_GET['arriveErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- COOKING -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_COOK'] ?></b>&nbsp;
				</span>
				<textarea id="cook" name="cook" class="commonRowArea_col2_500 
						  <?php if (isset($_GET['cookErrMsg']) && urldecode($_GET['cookErrMsg']) != "" ) { echo " errorInput";} ?>"
					      onfocus="showCartoon('cook');"
						  onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createCountryRev.php','cook', 25);"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCountryRev.php','cook'); hideCartoon('cook');"><?php if (isset($_GET['cook'])) echo urldecode($_GET['cook']);?></textarea>
				<img id="cookFldLD"   class="commonRowImg"   src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="cookFldOK"   class="commonRowImg"   src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="cookFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="cookDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['cookErrMsg']) ) { echo urldecode($_GET['cookErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- MYTH -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_MYTH'] ?></b>&nbsp;
				</span>
				<textarea id="myth" name="myth" class="commonRowArea_col2_250 
					   <?php if (isset($_GET['mythErrMsg']) && urldecode($_GET['mythErrMsg']) != "") { echo " errorInput";} ?>"
					   onfocus="showCartoon('myth');"
					   onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createCountryRev.php','myth', 10);"
					   onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCountryRev.php','myth'); hideCartoon('myth');"><?php if (isset($_GET['myth'])) echo urldecode($_GET['myth']);?></textarea>
				<img id="mythFldLD"   class="commonRowImg"   src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="mythFldOK"   class="commonRowImg"   src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="mythFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="mythDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['mythErrMsg']) ) { echo urldecode($_GET['mythErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- VOTE -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="flt-l-ie7 commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_VOTE'] ?></b>&nbsp;
				</span>
				
				<?php include  $X_root."pvt/pages/review/common/vote.html"; ?>
				
				<img id="voteFrcCart" class="frecciaCartoon marginTop0" src="/viantes/pvt/img/review/fumettoFreccia.png">
			</label>
		</div>
		
		<!-- SUBMIT -->
		<div class="commonRowDiv">
			<span>
				<input type="hidden" name="tabactive" value="1"/>
				<input id="submit" name="submit" class="hgt-30" type="submit" value="<?php echo $X_langArray['CREATE_COUNTRY_REV_SUBMIT_VAL'] ?>"/>
			</span>
		</div>
	</div>
	
	<div class="commonDIVRight">	
		<div class="commonRowDiv" style="margin-top: 40px">
			<span id="warnCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_COUNTRY_CARTOON_WARN'] ?>
			</span>
		</div>
		<div class="commonRowDiv" style="margin-top: 140px">
			<span id="arriveCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_COUNTRY_CARTOON_ARRIVE'] ?>
			</span>
		</div>
		<div class="commonRowDiv" style="margin-top: 390px">
			<span id="cookCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_COOK'] ?>
			</span>
		</div>	
		<div class="commonRowDiv" style="margin-top: 530px;">
			<span id="mythCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_COUNTRY_CARTOON_MYTH'] ?>
			</span>	
		</div>	
		<div class="commonRowDiv" style="margin-top: 50px;">
			<span id="voteCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_COUNTRY_CARTOON_VOTE'] ?>
			</span>	
		</div>	
	</div>
	
	<!-- UPLOAD -->
	<input class="hidden"id="submitCov" name="submit" type="submit" value="GO"/>

	<input type="hidden" name="MAX_FILE_SIZE" value="20000" />
	<input type="hidden" name="tabactive" value="1" id="tabactive"/>
	<input type="hidden" name="backUrl" value="/viantes/pub/pages/review/createCountryReview.php" />
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
	<input type="hidden" name="coverType" value="CRT_CNT_REV" />
</form>
