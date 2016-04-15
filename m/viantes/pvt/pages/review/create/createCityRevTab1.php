<?php
$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());
$langCode = $settingDO->getLangCode();

//===================== GEO ===========================
//setto il nome del sito (prima quello inserito dell'utente, poi quello di Google)
$X_GEO_city = null; 
if ( isset($_GET['city']) && $_GET['city'] != '') {
	$X_GEO_city = $_GET['city'];	
} else if ( isset($_GET['cityName']) && $_GET['cityName'] != '') {
	$X_GEO_city = $_GET['cityName'];
}

$X_GEO_zoom = 11; //zoom
$X_GEO_mapType = 'roadmap'; //tipo di mappa 
$X_GEO_disableUI = false; //disabilita la UI sulla mappa

//la citta' inserita non e' corretta
$errorGeo = (isset($_GET['cityErrMsg']) && $_GET['cityErrMsg'] != '') ? true : false;

//non carico la mappa se c'e' un errore o e' la prima volta che entro
$X_GEO_loadMap =  ($errorGeo || $X_GEO_city == null) ? false : true;

//Messaggio di errore localizzato quando l'indirizzo non e' trovato
$X_GEO_ERR_MSG = $X_langArray['CREATE_CITY_ADDRS_NOT_FOUND'];

require_once $X_root."pvt/pages/geo/cityMap.html";
//====================================================
?>

<form action="/viantes/pvt/pages/review/create/createCityRev.php" method="post" >
	
	<input type="hidden" name="langCode"       value="<?php echo $langCode ?>"/>
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
	
	<!-- SECTION 1 -->
	<div id="review_top_div"> 
		<h3>
			<?php echo $X_langArray['CREATE_CITY_REV_TITLE_ESSENTIAL'] ?>
		</h3>
	</div>
	
	<div class="commonDIVLeft">	
			
		<!-- CITY NAME -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_CITY_NAME'] ?></b>&nbsp;
				</span>
				<input id="city" type="text" name="city" style="width: 52%" class="commonRowTxt_col2 
					   <?php if (isset($_GET['cityErrMsg']) && urldecode($_GET['cityErrMsg']) != "") { echo " errorInput";} ?>"
					   value="<?php if (isset($_GET['city'])) echo urldecode($_GET['city']);?>" 
					   onfocus="showCartoon('city');"
					   onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCityRev.php','city');"
				/>
				<span class="commonRowMandatory">*</span>
				<img id="findMap"	  style="width: 3%; cursor:pointer"	src="/viantes/pvt/img/common/find_G.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="cityDIV" class="commonRowInnerDivError">
					<p class="p-error">
						<?php if ( isset($_GET['cityErrMsg']) ) { echo urldecode($_GET['cityErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- THE MAP -->
		<!-- Le prime due (id="map" e id="loadMap" ) sono alternative alla terza (id="fkMap"). A loro volte le prime due sono 
			 sono gestite dal javascript che all'inizio mostra la id="loadMap" poi la  id="map" -->
		<div id="map"     class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>" style="width: 90%; height: 300px; position:fixed"></div>	
		<img id="fkMap"   class="<?php if ($X_GEO_loadMap)  echo " hidden ";?>" style="width: 90%; height: 300px;" src="/viantes/pvt/img/common/iniMap.png">
		
		<input type="hidden" id="cityName"    	name="cityName" 	 value=""/><!-- Nome letto dalle api di google maps -->
		<input type="hidden" id="country"    	name="country" 		 value=""/><!-- Nome letto dalle api di google maps -->
		
		
		<!-- DESCRIPTION -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_DSCR'] ?></b>&nbsp;
				</span>
				<textarea id="descr" name="descr" class="commonRowArea_col2_1000
						  <?php if (isset($_GET['descrErrMsg']) && urldecode($_GET['descrErrMsg']) != "" ) { echo " errorInput";} ?>"
						  onfocus="showCartoon('descr');"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCityRev.php','descr'); hideCartoon('descr');"><?php if (isset($_GET['descr'])) echo urldecode($_GET['descr']);?></textarea>
				<span class="commonRowMandatory">*</span>
				
				<!-- Seconda riga - Msg Err -->
				<div id="descrDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['descrErrMsg']) ) { echo urldecode($_GET['descrErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>

		<!-- HOW TO Arrive From/To Airport -->
		<div class="commonRowDiv">
			<!-- Prima riga - Campi -->
			<span class="commonRowSpan_col1_txtArea">
				<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_HOW_TO_ARR'] ?></b>&nbsp;
			</span>
			<textarea id="arrive" name="arrive" class="commonRowArea_col2_1000 
					  <?php if (isset($_GET['arriveErrMsg']) && urldecode($_GET['arriveErrMsg']) != "" ) { echo " errorInput";} ?>"
					  onfocus="showCartoon('arrive');"
					  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCityRev.php','arrive'); hideCartoon('arrive');"><?php if (isset($_GET['arrive'])) echo urldecode($_GET['arrive']);?></textarea>
			<span class="commonRowMandatory">*</span>
			
			<!-- Seconda riga - Msg Err -->
			<div id="arriveDIV" class="commonTextAreaDivError">
				<p class="p-error">
					<?php if ( isset($_GET['arriveErrMsg']) ) { echo urldecode($_GET['arriveErrMsg']);} ?>
				</p>
			</div>
		</div>
		
		<!-- COVER -->
		<div class="commonRowDiv">
			<?php
			$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;	
			if ( $bean == null || $bean->getCoverFileName() == null || $bean->getCoverFileName() == '' ) {?>
				<span class="commonRowSpan_col1"><b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_COVER'] ?></b></span>
				<div class="commonRowCoverDiv">
					<!-- visible on all browser -->
					<img class="hideInIE crs-pnt"  width="24" src="/viantes/pvt/img/review/articleCoverButton_32.png" onclick="$('#reviewCoverId').click();">
					<!-- visible on ie :( -->
					<input class="hideInputButtonFile" id="reviewCoverId" name="userfile" type="file"  
						   onchange="$('#submitCov').click(); showOverlayForLoad('<?php echo $X_langArray['CREATE_REVIEW_IMG_WAIT_LOAD'] ?>','submitCov')"/>
					<span class="commonRowMandatory">*</span>
				</div>
			<?php } else { ?>
				<span class="commonRowSpan_col1_txtArea"><b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_COVER'] ?></b></span>
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
		</div>
		
	</div>
	
	<hr class="commonRowHR">
	
	<!-- SECTION 2 -->
	<a name="createRevSection2"></a>
	<div id="review_top_div">
		<h3>
			<?php echo $X_langArray['CREATE_CITY_WHAT_TO_SEE'] ?>
		</h3>
	</div>
	
	<div class="commonDIVLeft">	
		<!-- ADD INTEREST -->
		<div id="interestRowDiv" class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_CITY_WHAT_TO_SEE_ADD'] ?></b>&nbsp;
				</span>
				<a href="#" class="commonRowTxt_col2"> 
					<img src="/viantes/pvt/img/review/addInterest_24_22.png" onclick="$('html,body').scrollTop(0); $('#overlayAddInterest').show();" 
						 onmouseover="showCartoon('addInterest');" onmouseout="hideCartoon('addInterest');"> 
				</a>
				<span class="commonRowMandatory">*</span>
				
				<!-- Seconda riga - Msg Err -->
				<div id="addInterestDIV" class="commonRowInnerDivError">
					<p class="p-error">
						<?php if ( isset($_GET['interErrMsg']) ) { echo urldecode($_GET['interErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		<?php $i = 0;
		if ( $bean != null && $bean->getInterest() != null ) { ?>	
			<div>	
			<?php
			foreach ( $bean->getInterest() as $inter) {
				$arr = explode(attributeDelim, $inter); ?>
				<div class="innerInterestDiv mrg-bot-24">
					<a onclick="window.open('/viantes/pub/pages/review/showReview.php?revId=<?php echo X_code($arr[0]) ?>&reviewType=1&noCleanSess','_blank')" href="#">
						<img width="64px" src="<?php echo $arr[1]?>" >
						<b><p><?php echo $arr[2]?></p></b>
					</a>
				</div>
			<?php  $i++; } ?>
			</div>
		<?php } ?>
	</div>
	
	<hr class="commonRowHR">
	
	<!-- SECTION 3 -->
	<div id="review_top_div"> 
		<h3>
			<?php echo $X_langArray['CREATE_CITY_REV_TITLE_OTHER'] ?>
		</h3>
	</div>	
		
	<div class="commonDIVLeft">	
		
		<!-- WARNING -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_WARN'] ?></b>&nbsp;
				</span>
				<textarea id="warn" name="warn" class="commonRowArea_col2_250 
					   <?php if (isset($_GET['warnErrMsg']) && urldecode($_GET['warnErrMsg']) != "" ) { echo " errorInput";} ?>"
					   onfocus="showCartoon('warn');"
					   onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createCityRev.php','warn', 10);"
					   onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCityRev.php','warn'); hideCartoon('warn');"><?php if (isset($_GET['warn'])) echo urldecode($_GET['warn']);?></textarea>
				
				<!-- Seconda riga - Msg Err -->
				<div id="warnDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['warnErrMsg']) ) { echo urldecode($_GET['warnErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- WHERE_TO_EAT -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_WHEAT'] ?></b>&nbsp;
				</span>
				<textarea id="whEat" name="whEat" class="commonRowArea_col2_500 
						  <?php if (isset($_GET['whEatErrMsg']) && urldecode($_GET['whEatErrMsg']) != "" ) { echo " errorInput";} ?>"
					      onfocus="showCartoon('whEat');"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCityRev.php','whEat'); hideCartoon('whEat');"><?php if (isset($_GET['whEat'])) echo urldecode($_GET['whEat']);?></textarea>
				
				<!-- Seconda riga - Msg Err -->
				<div id="whEatDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['whEatErrMsg']) ) { echo urldecode($_GET['whEatErrMsg']);} ?>
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
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCityRev.php','cook'); hideCartoon('cook');"><?php if (isset($_GET['cook'])) echo urldecode($_GET['cook']);?></textarea>
				
				<!-- Seconda riga - Msg Err -->
				<div id="cookDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['cookErrMsg']) ) { echo urldecode($_GET['cookErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- WHERE_TO_SLEEP -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_WHSTAY'] ?></b>&nbsp;
				</span>
				<textarea id="whStay" name="whStay" class="commonRowArea_col2_500 
						  <?php if (isset($_GET['whStayErrMsg']) && urldecode($_GET['whStayErrMsg']) != "" ) { echo " errorInput";} ?>"
					      onfocus="showCartoon('whStay');"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCityRev.php','whStay'); hideCartoon('whStay');"><?php if (isset($_GET['whStay'])) echo urldecode($_GET['whStay']);?></textarea>
				
				<!-- Seconda riga - Msg Err -->
				<div id="whStayDIV" class="commonTextAreaDivError">
					<p class="p-error">
						<?php if ( isset($_GET['whStayErrMsg']) ) { echo urldecode($_GET['whStayErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- MYTH -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_MYTH'] ?></b>&nbsp;
				</span>
				<textarea id="myth" name="myth" class="commonRowArea_col2_250 
					   <?php if (isset($_GET['mythErrMsg']) && urldecode($_GET['mythErrMsg']) != "") { echo " errorInput";} ?>"
					   onfocus="showCartoon('myth');"
					   onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createCityRev.php','myth'); hideCartoon('myth');"><?php if (isset($_GET['myth'])) echo urldecode($_GET['myth']);?></textarea>
				
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
			</label>
		</div>
		
		<!-- SUBMIT -->
		<div class="commonRowDiv">
			<span>
				<input type="hidden" name="tabactive" value="1"/>
				<input id="submit" name="submit" class="hgt-30" type="submit" value="<?php echo $X_langArray['CREATE_CITY_REV_SUBMIT_VAL'] ?>"/>
			</span>
		</div>
	</div>

	<!-- UPLOAD -->
	<input class="hidden" id="submitCov" name="submit" type="submit" value="GO"/>
	
	<input type="hidden" name="MAX_FILE_SIZE" value="20000" />
	<input type="hidden" name="tabactive" value="1" id="tabactive"/>
	<input type="hidden" name="backUrl" value="/viantes/pub/pages/review/createCityReview.php" />
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
	<input type="hidden" name="coverType" value="CRT_CTY_REV" />
</form>
