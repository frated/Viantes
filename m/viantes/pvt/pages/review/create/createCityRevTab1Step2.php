<?php
$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());
$langCode = $settingDO->getLangCode();

//===================== GEO ===========================
$X_GEO_placeId = '';

$X_GEO_city = isset($_GET['cityName']) ? $_GET['cityName'] : $_GET['city'];

$X_GEO_loadMap = true;
$X_GEO_zoom = 11;
$X_GEO_mapType = 'roadmap'; 
$X_GEO_disableUI = true;

//Messaggio di errore localizzato quando l'indirizzo non e' trovato
$X_GEO_ERR_MSG = $X_langArray['CREATE_CITY_ADDRS_NOT_FOUND'];

require_once $X_root."pvt/pages/geo/cityMap.html";
//====================================================
?>

<form action="/viantes/pvt/pages/review/create/createCityRev.php" id="writeRevTab1Frm" method="post">
	
	<input type="hidden" name="langCode"       value="<?php echo $langCode ?>"/>
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
	
	<div>	
		<!-- CITY NAME -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_CITY_NAME'] ?></b>&nbsp;
				</span>
				<?php 
					echo '<p id="cityNameP" class="commonRowTxt_col2_fff dspl-inln-blk"></p>';
				?>	
			</label>
		</div>
		<!-- Nome digitato dall'utente -->
		<input type="hidden" name="city" value="<?php echo $_GET['city'] ?>"/>
				
		<!-- THE MAP -->
		<!-- Le prime due (id="map" e id="loadMap" ) sono alternative alla terza (id="fkMap"). A loro volte le prime due sono 
			 sono gestite dal javascript che all'inizio mostra la id="loadMap" poi la  id="map" -->
		<div id="map"     class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>"></div>
		<div id="loadMap" class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>">
			<img id="loadMapImg" width="48" src="/viantes/pvt/img/animate/ld_32_ffffff.gif" />
		</div>	
		<img id="fkMap"   class="<?php if ($X_GEO_loadMap)  echo " hidden ";?>" src="/viantes/pvt/img/common/iniMap.png">
		
		<input type="hidden" id="cityName"  name="cityName"  value=""/><!-- Nome letto dalle api di google maps -->
		<input type="hidden" id="country"   name="country" 	 value=""/><!-- Nome letto dalle api di google maps -->
		
		
		<!-- DESCRIPTION -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_CITY_REV_S2_REVIEW'] ?></b>&nbsp;
				</span>
				
				<?php 
					echo '<pre class="preCol2Step2">' . urldecode($_GET['descr']).'</pre>';
				?>	
			</label>
		</div>
		<textarea class="hidden" name="descr"><?php echo $_GET['descr'] ?></textarea>
		
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
		<textarea class="hidden" name="arrive"><?php echo $_GET['arrive'] ?></textarea>
		
		<!-- COVER -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1_txtArea">
					<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_COVER'] ?></b>&nbsp;
				</span>
				<?php $bean = unserialize($_SESSION[$beanSessionKey]);
				$fileName = $bean->getCoverFileName();
				echo '<img class="reviewCoverImgBoxStep2" src="'.$fileName.'"/>';?>
			</label>
		</div>
		
		<!-- WHAT TO SEE -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_CITY_WHAT_TO_SEE'] ?></b>&nbsp;
				</span>
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
		
		<!-- WARNING -->
		<?php if (isset($_GET['warn']) && $_GET['warn'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_WARN'] ?></b>&nbsp;
					</span>
					<?php 
						echo '<pre class="preCol2Step2">' . urldecode($_GET['warn']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<input type="hidden" name="warn" value="<?php echo $_GET['warn'] ?>" />
		
		<!-- WHERE_TO_EAT -->
		<?php if (isset($_GET['whEat']) && $_GET['whEat'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_WHEAT'] ?></b>&nbsp;
					</span>
					<?php
						echo '<pre class="preCol2Step2">' . urldecode($_GET['whEat']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<input type="hidden" name="whEat" value="<?php echo $_GET['whEat'] ?>" />
		
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
						<b><?php echo $X_langArray['CREATE_CITY_REV_FIELD_MYTH'] ?></b>&nbsp;
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
				<input id="submit" name="submit" class="hgt-30" type="submit" value="<?php echo $X_langArray['CREATE_CITY_REV_CHANGE_VAL'] ?>"/>
			</span>
			<span class="commonRowTxt_col2">
				<input id="submit" name="submit" class="hgt-30 flt-r" type="submit" value="<?php echo $X_langArray['CREATE_CITY_REV_COMPLETE_VAL'] ?>" style="margin-right: 10%;"/>
			</span>
		</div>
	</div>
	
	<input name="tabactive" value="1" type="hidden">
</form>
