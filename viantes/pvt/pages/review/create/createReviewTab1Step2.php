<?php
$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());
$langCode = $settingDO->getLangCode();

$categoryReviewDAO = new CategoryReviewDAO();
$reviewCategoryArray = $categoryReviewDAO->retrieveCategoriesByLangCode($langCode);

//===================== GEO ===========================
$X_GEO_site     = isset($_GET['siteName']) ? $_GET['siteName']  : $_GET['site']; 
$X_GEO_locality = isset($_GET['frmtdAdrs'])? $_GET['frmtdAdrs'] : $_GET['locality'];

$X_GEO_zoom = 17; //zoom
$X_GEO_mapType = 'roadmap'; //tipo di mappa 
$X_GEO_disableUI = true;  //disabilita la UI sulla mappa

//indica se all'avvio devo caricare la mappa 
$X_GEO_loadMap = true;//

//Messaggio di errore localizzato quando l'indirizzo non e' trovato
$X_GEO_ERR_MSG = $X_langArray['CREATE_REVIEW_ADDRS_NOT_VALID'];

require_once $X_root."pvt/pages/geo/siteMap.html";
//====================================================
?>

<form action="/viantes/pvt/pages/review/create/createReview.php" id="writeRevTab1Frm" method="post">
	
	<input type="hidden" name="langCode"       value="<?php echo $langCode ?>"/>
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
	
	<div>	
		<!-- SITE NAME -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_SITE_NAME'] ?></b>&nbsp;
				</span>
				<?php 
					echo '<p id="siteNameP" class="commonRowTxt_col2_fff dspl-inln-blk"></p>';
				?>	
			</label>
		</div>
		<!-- Nome digitato dall'utente -->
		<input type="hidden" name="site" value="<?php echo $_GET['site'] ?>"/>
		
		<!-- LOCALITY -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_LOCALITY'] ?></b>&nbsp;
				</span>
				<?php 
					echo '<p id="frmtdAdrsP"  class="commonRowTxt_col2_fff dspl-inln-blk"></p>';
				?>	
			</label>
		</div>
		<!-- Nome digitato dall'utente -->
		<input type="hidden" name="locality" value="<?php echo $_GET['locality'] ?>"/>
		
		<!-- THE MAP -->
		<!-- Le prime due (id="map" e id="loadMap" ) sono alternative alla terza (id="fkMap"). A loro volte le prime due sono 
			 sono gestite dal javascript che all'inizio mostra la id="loadMap" poi la  id="map" -->
		<div id="map"     class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>"></div>
		<div id="loadMap" class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>">
			<img id="loadMapImg" width="48" src="/viantes/pvt/img/animate/ld_32_ffffff.gif" />
		</div>	
		<img id="fkMap"   class="<?php if ($X_GEO_loadMap)  echo " hidden ";?>" style="width: 90%; height: 300px;" src="/viantes/pvt/img/common/iniMap.png">
		
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
					<b><?php echo $X_langArray['CREATE_REVIEW_S2_FIELD_CATEG'] ?></b>&nbsp;
				</span>
				<?php 
					foreach ($reviewCategoryArray as $category) {
						if (isset($_GET['catRev']) && $category->getId() == $_GET['catRev'] ) {
							echo '<p class="commonRowTxt_col2_fff dspl-inln-blk">'.$category->getCategoryName().'</p>';
							echo '<input type="hidden" name="catRev" value="'.$category->getId().'" />';
						}
					}
				?>
			</label>
		</div>
		
		<!-- DESCRIPTION -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_REVIEW_S2_REVIEW'] ?></b>&nbsp;
				</span>
				
				<?php 
					echo '<pre class="preCol2Step2">' . urldecode($_GET['descr']).'</pre>';
				?>	
			</label>
		</div>
		<textarea class="hidden" name="descr"><?php echo $_GET['descr'] ?></textarea>
		
		<!-- COVER -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_COVER'] ?></b>&nbsp;
				</span>
				<?php $bean = unserialize($_SESSION[$beanSessionKey]);
				$fileName = $bean->getCoverFileName();
				echo '<img class="reviewCoverImgBoxStep2" src="'.$fileName.'"/>';?>
			</label>
		</div>
		
		<!-- HOW TO Arrive -->
		<?php if (isset($_GET['arrive']) && $_GET['arrive'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_ARRIVE'] ?></b>&nbsp;
					</span>
					<?php 
						echo '<pre class="preCol2Step2">' . urldecode($_GET['arrive']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<textarea class="hidden" name="arrive"><?php echo $_GET['arrive'] ?></textarea>
		
		<!-- WARNING -->
		<?php if (isset($_GET['warn']) && $_GET['warn'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_WARN'] ?></b>&nbsp;
					</span>
					<?php 
						echo '<pre class="preCol2Step2">' . urldecode($_GET['warn']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<textarea class="hidden" name="warn"><?php echo $_GET['warn'] ?></textarea>
		
		<!-- WHERE_TO_EAT -->
		<?php if (isset($_GET['whEat']) && $_GET['whEat'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_WHEAT'] ?></b>&nbsp;
					</span>
					<?php
						echo '<pre class="preCol2Step2">' . urldecode($_GET['whEat']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<textarea class="hidden" name="whEat"><?php echo $_GET['whEat'] ?></textarea>
		
		<!-- COOKING -->
		<?php if (isset($_GET['cook']) && $_GET['cook'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_COOK'] ?></b>&nbsp;
					</span>
					<?php
						echo '<pre class="preCol2Step2">' . urldecode($_GET['cook']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<textarea class="hidden" name="cook"><?php echo $_GET['cook'] ?></textarea>
		
		<!-- WHERE_TO_SLEEP -->
		<?php if (isset($_GET['whStay']) && $_GET['whStay'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_WHSTAY'] ?></b>&nbsp;
					</span>
					<?php 
						echo '<pre class="preCol2Step2">' . urldecode($_GET['whStay']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<textarea class="hidden" name="whStay"><?php echo $_GET['whStay'] ?></textarea>
		
		<!-- MYTH -->
		<?php if (isset($_GET['myth']) && $_GET['myth'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_MYTH'] ?></b>&nbsp;
					</span>
					
					<?php
						echo '<pre class="preCol2Step2">' . urldecode($_GET['myth']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<textarea class="hidden" name="myth"><?php echo $_GET['myth'] ?></textarea>
		
		<!-- VOTE -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_REVIEW_FIELD_VOTE'] ?></b>&nbsp;
				</span>
				<?php include  $X_root."pvt/pages/review/common/vote.html"; ?>
			</label>
		</div>
		
		<!-- CREATE/FINISH -->
		<div class="commonRowDiv">
			<span class="commonRowSpan_col1">
				<input id="submit" name="submit" class="hgt-30" type="submit" value="<?php echo $X_langArray['CREATE_REVIEW_CHANGE_VAL'] ?>"/>
			</span>
			<span class="commonRowTxt_col2">
				<input id="submit" name="submit" class="hgt-30 flt-r" type="submit" value="<?php echo $X_langArray['CREATE_REVIEW_COMPLETE_VAL'] ?>" style="margin-right: 10%;"/>
			</span>
		</div>
	</div>
	
	<input name="tabactive" value="1" type="hidden">
</form>
