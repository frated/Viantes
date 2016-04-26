<?php
$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());
$langCode = $settingDO->getLangCode();

$categoryReviewDAO = new CategoryReviewDAO();
$reviewCategoryArray = $categoryReviewDAO->retrieveCategoriesByLangCode($langCode);

//===================== GEO ===========================
$X_GEO_placeId = '';

//setto il nome del sito (prima quello inserito dell'utente, poi quello di Google)
$X_GEO_site = null; 
if ( isset($_GET['site']) && $_GET['site'] != '')
	$X_GEO_site = $_GET['site'];	
else if ( isset($_GET['siteName']) && $_GET['siteName'] != '')
	$X_GEO_site = $_GET['siteName'];	

//setto la localita' (prima quella inserita dall'utente, poi quello di Google)
$X_GEO_locality = null;
if ( isset($_GET['locality']) && $_GET['locality'] != '')
	$X_GEO_locality = $_GET['locality'];	
else if ( isset($_GET['frmtdAdrs']) && $_GET['frmtdAdrs'] != '')
	$X_GEO_locality = $_GET['frmtdAdrs'];	


$X_GEO_zoom = 17; //zoom
$X_GEO_mapType = 'roadmap'; //tipo di mappa 
$X_GEO_disableUI = false; //disabilita la UI sulla mappa

//indica se all'avvio devo caricare la mappa 
$errorGeo = (isset($_GET['localityErrMsg']) && $_GET['localityErrMsg'] != '') ? true : false;

//non carico la mappa se c'e' un errore o nessun campo di geolocalizzazione e' valorizzato
$X_GEO_loadMap =  ($errorGeo || ($X_GEO_site == null && $X_GEO_locality == null) ) ? false : true;

//Messaggio di errore localizzato quando l'indirizzo non e' trovato
$X_GEO_ERR_MSG = $X_langArray['CREATE_REVIEW_ADDRS_NOT_VALID'];

require_once $X_root."pvt/pages/geo/siteMap.html";
//====================================================
?>
<form action="/viantes/pvt/pages/review/create/createReview.php" enctype="multipart/form-data"  id="writeRevTab1Frm" method="post" >
	
	<input type="hidden" name="langCode"       value="<?php echo $langCode ?>"/>
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
		
	<!-- TITLE 1 -->
	<div id="review_top_div"> 
		<h3>
			<?php echo $X_langArray['CREATE_REVIEW_TITLE_ESSENTIAL'] ?>
		</h3>
	</div>
	
	<div class="commonDIVLeft">	
		
		<!-- SITE NAME -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_SITE_NAME'] ?></b>&nbsp;
				</span>
				<input id="site" type="text" name="site" class="commonRowTxt_col2 
					   <?php if (isset($_GET['siteErrMsg']) && urldecode($_GET['siteErrMsg']) != "") { echo " errorInput";} ?>"
					   value="<?php  if (isset($_GET['site'])) echo urldecode($_GET['site']);?>" 
					   onfocus="showCartoon('site');"
					   onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','site', 3);"
					   onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','site'); hideCartoon('site');"
				/>
				<span class="commonRowMandatory">*</span>
				<img id="siteFldOK"   class="commonRowImg"   src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="siteFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="siteDIV" class="commonRowInnerDivError">
					<p class="p-error">
						<?php if ( isset($_GET['siteErrMsg']) ) { echo urldecode($_GET['siteErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		

		<!-- LOCALITY -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_LOCALITY'] ?></b>&nbsp;
				</span>
				<input id="locality" type="text" name="locality" style="width: 52%" class="commonRowTxt_col2 
					   <?php if (isset($_GET['localityErrMsg']) && urldecode($_GET['localityErrMsg']) != "") { echo " errorInput";} ?>"
					   value="<?php if (isset($_GET['locality'])) echo urldecode($_GET['locality']);?>" 
					   onfocus="showCartoon('locality');"
					   onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','locality', 3);"
					   onblur=" hideCartoon('locality'); 
					   if($('#localityIgnoreBlur').val() === 'true') { $('#localityIgnoreBlur').val(''); return; }
					   doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','locality');"
				/>
				<span class="commonRowMandatory">*</span>
				<img id="findMap"	  	  style="width: 3%; cursor:pointer" src="/viantes/pvt/img/common/find_G.png">
				<img id="localityFldLD"   class="commonRowImg"   			src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="localityFldOK"   class="commonRowImg"   			src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="localityFrcCart" class="frecciaCartoon" 			src="/viantes/pvt/img/review/fumettoFreccia.png">
				<input type="hidden" 	  id="localityIgnoreBlur" 			value="" />
				
				<!-- Seconda riga - Msg Err -->
				<div id="localityDIV" class="commonRowInnerDivError">
					<p class="p-error">
						<?php if ( isset($_GET['localityErrMsg']) ) { echo urldecode($_GET['localityErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- THE MAP -->
		<!-- Le prime due (id="map" e id="loadMap" ) sono alternative alla terza (id="fkMap"). A loro volte le prime due sono 
			 sono gestite dal javascript che all'inizio mostra la id="loadMap" poi la  id="map" -->
		<div id="map"   class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>" style="width: 95%"></div>
		<img id="fkMap" class="<?php if ($X_GEO_loadMap)  echo " hidden ";?>" src="/viantes/pvt/img/common/iniMap.png">
		
		<input type="hidden" id="siteName"    	name="siteName" 	 value=""/><!-- Nome letto dalle api di google maps -->
		<input type="hidden" id="frmtdAdrs"    	name="frmtdAdrs" 	 value=""/><!-- Nome letto dalle api di google maps -->
		<input type="hidden" id="country"    	name="country" 		 value=""/><!-- Nome letto dalle api di google maps -->
		<input type="hidden" id="lat"    		name="lat" 			 value=""/><!-- Nome letto dalle api di google maps -->
		<input type="hidden" id="lng"    		name="lng" 			 value=""/><!-- Nome letto dalle api di google maps -->
		<input type="hidden" id="placeId" 		name="placeId" 		 value=""/><!-- Nome letto dalle api di google maps -->
		
		
		<!-- CATEGORY -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_CATEG'] ?></b>&nbsp;
				</span>
				
				<select id="catRev" name="catRev"
					    onfocus="showCartoon('catRev');"
						onchange="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','catRev'); hideCartoon('catRev');"
						onblur="hideCartoon('catRev');"
						class="commonRowSelect_col2 createRevSelect 
						<?php if (isset($_GET['catRevErrMsg']) && urldecode($_GET['catRevErrMsg']) != "" ) { echo " errorInput";} ?>">
					<?php 
					$opt = 0;
					foreach ($reviewCategoryArray as $category) { 
						$selected = "";
						if (isset($_GET['catRev']) && $category->getId() == $_GET['catRev'] ) {
							$selected = " selected ";
						}
						if ($category->getId() == -1 &&  $opt == 0) {
							$option = "<optgroup value=\"". $category->getId()."\" label=\"" .$category->getCategoryName(). "\" >";
						}						
						if ($category->getId() == -1 &&  $opt > 0) {
							$option = "</optgroup></optgroup><optgroup value=\"". $category->getId()."\" label=\"" .$category->getCategoryName(). "\" >";
						}
						if ($category->getId() != -1 ) {
							$option = "<option value=\"". $category->getId()."\"" . $selected . " >" .$category->getCategoryName(). "</option>";
						}
						$opt ++;
						echo $option;
					} 
					echo "</optgroup>";
					?>
				</select>
				<span class="commonRowMandatory">*</span>
				<img id="catRevFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
				<!-- Seconda riga - Msg Err -->
				<div id="catRevDIV" class="commonRowInnerDivError">
					<p class="p-error">
						<?php if ( isset($_GET['catRevErrMsg']) ) { echo urldecode($_GET['catRevErrMsg']);} ?>
					</p>
				</div>
			</label>
		</div>
		
		<!-- DESCRIPTION -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_DSCR'] ?></b>&nbsp;
				</span>
				<textarea id="descr" name="descr" class="commonRowArea_col2_1000
						  <?php if (isset($_GET['descrErrMsg']) && urldecode($_GET['descrErrMsg']) != "" ) { echo " errorInput";} ?>"
					      onfocus="showCartoon('descr');"
						  onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','descr', 50);"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','descr'); hideCartoon('descr');"><?php if (isset($_GET['descr'])) echo urldecode($_GET['descr']);?></textarea>
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
			<?php 
			$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;
			if ( $bean == null || $bean->getCoverFileName() == null || $bean->getCoverFileName() == '' ) {?>
				<span class="commonRowSpan_col1"><b><?php echo $X_langArray['CREATE_REVIEW_FIELD_COVER'] ?></b></span>
				<div class="commonRowCoverDiv">
					<!-- visible on all browser -->
					<img class="hideInIE" width="24" src="/viantes/pvt/img/review/articleCoverButton_32.png" onclick="$('#reviewCoverId').click();">
					<!-- visible on ie :( -->
					<input class="hideInputButtonFile" id="reviewCoverId" name="userfile" type="file" 
						   onchange="$('#submitCov').click(); showOverlayForLoad('<?php echo $X_langArray['CREATE_REVIEW_IMG_WAIT_LOAD'] ?>','submitCov')"/>
					<input class="hidden" id="submitCov" name="submit" type="submit" value="GO"/>
					
					<span class="commonRowMandatory">*</span>
				</div>
			<?php } else { ?>
				<span class="commonRowSpan_col1_txtArea"><b><?php echo $X_langArray['CREATE_REVIEW_FIELD_COVER'] ?></b></span>
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
	
	<div class="commonDIVRight">
		<div class="commonRowDiv" style="margin-top: 8px;">
			<span id="siteCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_SITE'] ?>
			</span>
		</div><div class="commonRowDiv" style="margin-top: 68px">
			<span id="localityCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_LOCALITY'] ?>
			</span>
		</div>
		<div class="commonRowDiv" style="margin-top: 485px">
			<span id="catRevCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_CATEGORY'] ?>
			</span>	
		</div>	
		<div class="commonRowDiv" style="margin-top: 560px">	
			<span id="descrCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_REVIEW'] ?>
			</span>
		</div>	
	</div>
	
	<hr class="commonRowHR">
	
	<!-- TITLE 2 -->
	<div id="review_top_div">
		<h3>
			<?php echo $X_langArray['CREATE_REVIEW_TITLE_OTHER'] ?>
		</h3>
	</div>	
		
	<div class="commonDIVLeft">			
		<!-- HOW TO Arrive -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_ARRIVE'] ?></b>&nbsp;
				</span>
				<textarea id="arrive" name="arrive" class="commonRowArea_col2_500 
						  <?php if (isset($_GET['arriveErrMsg']) && urldecode($_GET['arriveErrMsg']) != "" ) { echo " errorInput";} ?>"
					      onfocus="showCartoon('arrive');"
						  onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','arrive', 25);"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','arrive'); hideCartoon('arrive');"><?php if (isset($_GET['arrive'])) echo urldecode($_GET['arrive']);?></textarea>
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
		
		<!-- WARNING -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_WARN'] ?></b>&nbsp;
				</span>
				<textarea id="warn" name="warn" class="commonRowArea_col2_250 
					   <?php if (isset($_GET['warnErrMsg']) && urldecode($_GET['warnErrMsg']) != "" ) { echo " errorInput";} ?>"
					   onfocus="showCartoon('warn');"
					   onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','warn', 10);"
					   onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','warn'); hideCartoon('warn');"><?php if (isset($_GET['warn'])) echo urldecode($_GET['warn']);?></textarea>
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
		
		<!-- WHERE_TO_EAT -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_WHEAT'] ?></b>&nbsp;
				</span>
				<textarea id="whEat" name="whEat" class="commonRowArea_col2_500 
						  <?php if (isset($_GET['whEatErrMsg']) && urldecode($_GET['whEatErrMsg']) != "" ) { echo " errorInput";} ?>"
					      onfocus="showCartoon('whEat');"
						  onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','whEat', 25);"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','whEat'); hideCartoon('whEat');"><?php if (isset($_GET['whEat'])) echo urldecode($_GET['whEat']);?></textarea>
				<img id="whEatFldLD"   class="commonRowImg"   src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="whEatFldOK"   class="commonRowImg"   src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="whEatFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
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
						  onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','cook', 25);"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','cook'); hideCartoon('cook');"><?php if (isset($_GET['cook'])) echo urldecode($_GET['cook']);?></textarea>
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
						  onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','whStay', 25);"
						  onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','whStay'); hideCartoon('whStay');"><?php if (isset($_GET['whStay'])) echo urldecode($_GET['whStay']);?></textarea>
				<img id="whStayFldLD"   class="commonRowImg"   src="/viantes/pvt/img/animate/ld_16_ffffff.gif">
				<img id="whStayFldOK"   class="commonRowImg"   src="/viantes/pvt/img/common/ok_32_22.png">
				<img id="whStayFrcCart" class="frecciaCartoon" src="/viantes/pvt/img/review/fumettoFreccia.png">
				
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
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_MYTH'] ?></b>&nbsp;
				</span>
				<textarea id="myth" name="myth" class="commonRowArea_col2_250 
					   <?php if (isset($_GET['mythErrMsg']) && urldecode($_GET['mythErrMsg']) != "") { echo " errorInput";} ?>"
					   onfocus="showCartoon('myth');"
					   onkeyup="doFieldAsyncGetMinLen('/viantes/pvt/pages/review/create/createReview.php','myth', 10);"
					   onblur="doFieldAsyncGet('/viantes/pvt/pages/review/create/createReview.php','myth'); hideCartoon('myth');"><?php if (isset($_GET['myth'])) echo urldecode($_GET['myth']);?></textarea>
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
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_VOTE'] ?></b>&nbsp;
				</span>
				
				<?php include  $X_root."pvt/pages/review/common/vote.html"; ?>
				
				<img id="voteFrcCart" class="frecciaCartoon marginTop0" src="/viantes/pvt/img/review/fumettoFreccia.png">
			</label>
		</div>
		
		<!-- SUBMIT -->
		<div class="commonRowDiv">
			<span>
				<input type="hidden" name="tabactive" value="1"/>
				<input id="submit" name="submit" class="hgt-30" type="submit" value="<?php echo $X_langArray['CREATE_REVIEW_SUBMIT_VAL'] ?>"/>
			</span>
		</div>
	</div>
	
	<div class="commonDIVRight">	
		<div class="commonRowDiv" style="margin-top: 12px">
			<span id="arriveCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_ARRIVE'] ?>
			</span>
		</div>
		<div class="commonRowDiv" style="margin-top: 190px">
			<span id="warnCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_WARN'] ?>
			</span>
		</div>	
		<div class="commonRowDiv" style="margin-top: 290px">
			<span id="whEatCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_EAT'] ?>
			</span>
		</div>	
		<div class="commonRowDiv" style="margin-top: 460px">
			<span id="cookCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_COOK'] ?>
			</span>
		</div>	
		<div class="commonRowDiv" style="margin-top: 610px">
			<span id="whStayCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_STAY'] ?>
			</span>
		</div>	
		<div class="commonRowDiv" style="margin-top: 760px;">
			<span id="mythCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_MYTH'] ?>
			</span>	
		</div>	
		<div class="commonRowDiv" style="margin-top: 725px;">
			<span id="voteCartoon" class="inputcartoon">
				<?php echo $X_langArray['CREATE_REVIEW_CARTOON_VOTE'] ?>
			</span>	
		</div>	
	</div>

	<!-- UPLOAD -->
	<input type="hidden" name="MAX_FILE_SIZE" value="20000" />
	<input type="hidden" name="tabactive" value="1" id="tabactive"/>
	<input type="hidden" name="backUrl" value="<?php echo $backUrl?>" />
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
	<input type="hidden" name="coverType" value="CRT_REV" />
	
</form>
