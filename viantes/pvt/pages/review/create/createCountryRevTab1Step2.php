<?php
$settingDAO = New SettingDAO();
$settingDO = $settingDAO->getSetting($userDO->getId());
$langCode = $settingDO->getLangCode();

//===================== GEO ===========================
$X_GEO_country = $_GET['country'];

$X_GEO_loadMap = true;
$X_GEO_zoom = 5;
$X_GEO_mapType = 'roadmap'; 
$X_GEO_disableUI = true;

//Messaggio di errore localizzato quando l'indirizzo non e' trovato
$X_GEO_ERR_MSG = $X_langArray['CREATE_COUNTRY_ADDRS_NOT_FOUND'];

require_once $X_root."pvt/pages/geo/countryMap.html";
//====================================================
?>

<form action="/viantes/pvt/pages/review/create/createCountryRev.php" id="writeRevTab1Frm" method="post">
	
	<input type="hidden" name="langCode"       value="<?php echo $langCode ?>"/>
	<input type="hidden" name="beanSessionKey" value="<?php echo $beanSessionKey?>" />
	
	<div>	
		<!-- COUNTRY -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_COUNTRY'] ?></b>&nbsp;
				</span>
				<?php 
					echo '<p id="countryP" class="commonRowTxt_col2_fff dspl-inln-blk"></p>';
				?>	
			</label>
		</div>
		<!-- Nome digitato dall'utente -->
		<input type="hidden" name="country" value="<?php echo $_GET['country'] ?>"/>
		
		<!-- THE MAP -->
		<!-- Le prime due (id="map" e id="loadMap" ) sono alternative alla terza (id="fkMap"). A loro volte le prime due sono 
			 sono gestite dal javascript che all'inizio mostra la id="loadMap" poi la  id="map" -->
		<div id="map"     class="<?php if (!$X_GEO_loadMap) echo " hidden ";?>"></div>
		
		<input type="hidden" id="countryName" name="countryName" value=""/><!-- Nome letto dalle api di google maps -->
		
		
		<!-- DESCRIPTION -->
		<div class="commonRowDiv">
			<label>
				<!-- Prima riga - Campi -->
				<span class="commonRowSpan_col1">
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_S2_REVIEW'] ?></b>&nbsp;
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
					<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_COVER'] ?></b>&nbsp;
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
					<b><?php echo $X_langArray['CREATE_COUNTRY_WHAT_TO_SEE'] ?></b>&nbsp;
				</span>
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
		
		<!-- WARNING -->
		<?php if (isset($_GET['warn']) && $_GET['warn'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_WARN'] ?></b>&nbsp;
					</span>
					<?php 
						echo '<pre class="preCol2Step2">' . urldecode($_GET['warn']).'</pre>';
					?>	
				</label>
			</div>
		<?php } ?>
		<textarea class="hidden" name="warn"><?php echo $_GET['warn'] ?></textarea>
		
		<!-- ARRIVE -->
		<?php if (isset($_GET['arrive']) && $_GET['arrive'] != '') { ?>
			<div class="commonRowDiv">
				<label>
					<!-- Prima riga - Campi -->
					<span class="commonRowSpan_col1">
						<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_HOW_TO_ARR'] ?></b>&nbsp;
					</span>
					<?php 
						echo '<pre class="preCol2Step2">' . urldecode($_GET['arrive']).'</pre>';
					?>
				</label>
			</div>
		<?php } ?>
		<textarea class="hidden" name="arrive"><?php echo $_GET['arrive'] ?></textarea>
		
		
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
						<b><?php echo $X_langArray['CREATE_COUNTRY_REV_FIELD_MYTH'] ?></b>&nbsp;
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
				<input id="submit" name="submit" class="hgt-30" type="submit" value="<?php echo $X_langArray['CREATE_CITY_REV_COMPLETE_VAL'] ?>" class="flt-r" style="margin-right: 10%;"/>
			</span>
		</div>
	</div>
	
	<input name="tabactive" value="1" type="hidden">
</form>
