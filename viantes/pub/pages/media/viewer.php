<?php
$X_root = "../../../";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/checkSession4Pub.php";
require_once $X_root."pvt/pages/review/reviewBean.php";
require_once $X_root."pvt/pages/review/cityReviewBean.php";
require_once $X_root."pvt/pages/review/countryReviewBean.php";
require_once $X_root."pvt/pages/review/commonReviewDAO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

cleanSesison($X_page);

$dao = New CommonReviewDAO();

$revId       = $_GET['revId'];
$revTp       = $_GET['revTp'];
//id del file allegato, se e' una cover vale -1 (per la trovare la cover usare l'id della review)
$revAttachId = $_GET['revAttachId'] != -1 ? X_deco($_GET['revAttachId']) : -1;

/* Array di array con la form [ {[id][name][type][xdim][ydim][revtp]}, { [name][type]} ... ] */
$mediaArray = $dao->getAllMedia( X_deco($revId), $revTp);

/************ Calcolo della posizione corrente  ***************/
//cerco la posizione dell'immagine/video cliccata all'interno del vettore dei media
$currentIndex = 0;
//echo "Input revAttachId=".$revAttachId."  revTp=".$revTp." <br>";
for ($i = 0; $i <= count($mediaArray); $i++) {

	$mediaId   = $mediaArray[$i]['id'];
	$mediaType = $mediaArray[$i]['type'];
	$mediaRevTp = $mediaArray[$i]['revtp'];

	//echo "mediaId=".$mediaId. "  mediaType=".$mediaType."  mediaRevTp=".$mediaRevTp."<br>";
	//se trovo l'id tutto ok 
	if ( $revAttachId == $mediaId && $revTp == $mediaRevTp && $mediaType != 'CVR') {
		$currentIndex = $i;
		break;
	}
	
	//se l'id dell'attached review e' -1 (<=> significa che e' una cover) 
	//=> non devo confrontare l'id dell'attached review ma l'id della recensione
	if ($revAttachId == -1 && X_deco($revId) == $mediaId && $revTp == $mediaRevTp && $mediaType == 'CVR') {
		$currentIndex = $i;
		break;
	}
}

//immagine/video corrente
$current = $mediaArray[$currentIndex];

//calcolo l'altezza del media corrente
$height = round(800 / $current['xdim'] * $current['ydim']);

//definisce l'altezza in px dell'immagine play/pause/next/prev (al centro dell'altezza dell media corrente)
$imgHeight = round($height/2); 

/************ Barra dei Media  ***************/
//definisce il massimo num. di elementi nella barra dei media
$mediaBarElemNumber = 10; 

//definisce la pagina corrente della barra dei media
// Nota che se vale
// 0 significa che visualizzero gli elemnti da $mediaArray[0] a $mediaArray[$mediaBarElemNumber]
// 1 significa che visualizzero gli elemnti da $mediaArray[mediaBarElemNumber+1] a $mediaArray[2*$mediaBarElemNumber] ecosi' via...
$mediaBarCurrentPageIndex = round($currentIndex / $mediaBarElemNumber);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html style="background-color: #060606">

<head>
	<title><?php echo $X_langArray['GEN_GALLERY']?></title>
	<?php include $X_root."pvt/pages/common/meta-link-script.html"; ?>
	
	<script>
		var currentIndex = <?php echo $currentIndex?>;
		var size = <?php echo count($mediaArray)?>;

		var mediaBarElemNumber = <?php echo $mediaBarElemNumber; ?>;
		var mediaBarCurrentPageIndex = <?php echo $mediaBarCurrentPageIndex; ?>;

	   /** 
	     * Questa funziona estende quella di jQuery ($.browser.msie) che NON funziona su ie11 
		 * Per quest'ultimo utilizzo (!!navigator.userAgent.match(/Trident\/7\./) 
		 * http://stackoverflow.com/questions/18684099/jquery-fail-to-detect-ie-11
		 */
		function isIE(){
			return $.browser.msie || (!!navigator.userAgent.match(/Trident\/7\./));
		}
		
	   /** 
	    * Mostra l'elemento di posizione index in primo piano 
		* index rappresenta la posizione dell'elemento selezionato nella barra dei media in alto a sinistra
		* convenzione: 
		* identifico con currentImg e currentMov rispettivamente l'immagine o il video in primo piano 
		* (sono esclusivi visto che ho in primo piano un solo tag); 
		* identifico con elementImg_i e elementMov_i rispettivamente l'iesima immagine/video  sulla barra in alto a sinistra.
		* Inoltre currentObj e elementObj sono analoghi a currentMov e elementMov ma servono per InternetExlorer!!
		* N.B.: se esiste elementMov_3 => non esiste elementImg_3
		*/
		function goToElement(index){
	
			var xdim = 800;
			var ydim = xdim  / $('#xdim_' + index).val() * $('#ydim_' + index).val();
			
				
			//se l'elemento corrente e' un video => lo nascondo
			if ($('#elementMov_'+currentIndex).length) {
				$('#elementMov_'+currentIndex).css('opacity', '1'); //opacizzo l'elemento
				$('#elementObj_'+currentIndex).css('opacity', '1'); //opacizzo l'elemento
				
				//Stoppo il video
				$('#currentMov').attr('play', 'false');
			}
			
			//se l'elemento corrente e' un'immagine => la nascondo
			if ($('#elementImg_'+currentIndex).length) {
				$('#elementImg_'+currentIndex).css('opacity', '1');//deopacizzo l'elemento
			}			
			
			$('#currentObj').remove();
			$('#currentMov').remove();
			$('#currentImg').remove();
			
			//se il nuovo elemento e' un video
			if ($('#elementMov_'+index).length) {
				//il nome del video nuovo
				var video =  $('#elementMov_' + index).val();
				
				$('#currentObj').remove();
				$('#currentImg').remove();
				
				$('#viewerPauseId').after( 
				"<object id=\"currentObj\" height=\"" + ydim + "\" width=\"800\" >" + 
					"<param name=\"movie\" value=\"" + video+ "\">" +
					"<param name=\"play\" value=\"false\" />" +
					"<param name=\"wmode\" value=\"transparent\" />" +
					"<embed id=\"currentMov\" src=\"" + video+ "\" "  +
						   "type=\"application/x-shockwave-flash\" volume=\"100\" " +
						   "wmode=\"transparent\" allowscriptaccess=\"always\" allowfullscreen=\"true\" play=\"false\" " +
						   "height=\"" + ydim + "\" width=\"800\">" +
					"</embed>" + 
				"</object>"
				);
				
				$('#elementMov_'+index).css('opacity', '0.40');
				
				//immagine play
				$('#viewerPlayId').css('margin-top',  ydim/2 +'px');
				$('#viewerPauseId').css('margin-top', ydim/2 +'px');
				$('#viewerPlayId').show();
			}
			
			//se il nuovo elemento e' un'immagine
			if ($('#elementImg_'+index).length) {
					
				var img = $('#elementImg_' + index).attr('src');
				$('#viewerPauseId').after(
					"<img id=\"currentImg\" style=\"width: 100%;\" src=\"" + img + "\">"
				);
				
				//cambio immagine
				$('#elementImg_'+index).css('opacity', '0.40');
				
				//nascondo immagine play
				$('#viewerPlayId').hide();
			}
			
			//posizione le due immagini (next e prev) al centro
			$('#nxt').css('margin-top', ydim/2 +'px');
			$('#prv').css('margin-top', ydim/2 +'px');
			
			$('#riquadroDIV').css('height', (Math.round(ydim)+20)+'px');

			currentIndex = index;
		}
		
		/* Mostra l'elemento successivo */
		function goNext(){
			var idx = currentIndex == size-1 ? 0 : parseInt(currentIndex) +1;
			goToElement(idx);
		}
		
		/* Mostra l'elemento successivo */
		function goPrev(){
			var idx = currentIndex == 0 ? size-1 : parseInt(currentIndex)-1;
			goToElement(idx);
		}
		
		/** Gestisce l'evento di onMouseover sull'immagine/video correnti */
		function doOnMsOver() {
			//mostro le 3 immagini che ci sono sempre
			$('#nxt').show(); 
			$('#prv').show(); 
			
			//se e' un video
			if ($('#elementMov_'+currentIndex).length) { 
				//se non e' in play =>mostro il play
				if ($('#currentMov').attr('play') == 'false') {
					//mostro play e nascondo pausa
					$('#viewerPlayId').show();
					$('#viewerPauseId').hide();
				//viceversa
				} else {
					//mostro pausa e nascondo play
					$('#viewerPlayId').hide();
					$('#viewerPauseId').show();
					//nascondo anche le due freccine e la X
					$('#nxt').hide(); 
					$('#prv').hide(); 
				}
			}
			$('#mediaBar').show();
		}
		
		/** Gestisce l'evento di onMouseover sull'immagine/video corrente */
		function doOnMsOut(){ 
			//nascondo tutto
			$('#nxt').hide(); 
			$('#prv').hide(); 
			$('#viewerPlayId').hide(); 
			$('#viewerPauseId').hide();
			$('#mediaBar').hide();
		}
		
		/** Simula un cambio di pagina in avanti nella barra dei media*/
		function mediaBarNext(){
			//vado avanti
			mediaBarPage('avanti');
			//mostro di sicuro il pulsante INDIETRO
			$('#mediaBarPrevId').show();
			//se esiste almeno un elemento successo mostro il pulsante AVANTI
			var idxSuccessivo = (mediaBarCurrentPageIndex + 1) * mediaBarElemNumber;
			if (!$('#elementImg_'+idxSuccessivo).length && !$('#elementObj_'+idxSuccessivo).length ) 
				$('#mediaBarNextId').hide();
		}

		/** Simula un cambio di pagina in dietro nella barra dei media */
		function mediaBarPrev(){
			//vado in dietro
			mediaBarPage('dietro');
			//mostro di sicuro il pulsante AVANTI
			$('#mediaBarNextId').show();
			//se non sono all'index 0 mostro il pulsante INDIETRO
			if (mediaBarCurrentPageIndex == 0) 
				$('#mediaBarPrevId').hide();
		}

		/** Simula un cambio di pagina in dietro nella barra dei media 
		  * Nota che 
		  * se mediaBarCurrentPageIndex = 0 => si devono vedere gli elementi da 0 a 11
		  * se mediaBarCurrentPageIndex = 1 => si devono vedere gli elementi da 12 a 23
		  * in generale da mediaBarCurrentPageIndex * 12 a  mediaBarCurrentPageIndex * 12 + (12-1) */
		function mediaBarPage(verso){

			//nascondo gli elementi correnti
			var i1 = mediaBarCurrentPageIndex * mediaBarElemNumber;
			var i2 = mediaBarCurrentPageIndex * mediaBarElemNumber + (mediaBarElemNumber-1);
			for (var i = i1; i <= i2; i++) {
				//alert("nascondo " + i);
				if ($('#elementImg_'+i).length)
					$('#elementImg_' + i ).hide();
				if ($('#elementObj_'+i).length)
					$('#elementObj_' + i ).hide();
			}
			
			//stabilisco il verso (incrementanto o decrementando la pagina corrente)
			if (verso == 'avanti') mediaBarCurrentPageIndex++;
			else mediaBarCurrentPageIndex--;

			//mostro gli elementi successivi/precedenti in base al verso
			var i1 = mediaBarCurrentPageIndex * mediaBarElemNumber;
			var i2 = mediaBarCurrentPageIndex * mediaBarElemNumber + (mediaBarElemNumber-1);
			for (var i = i1; i <= i2; i++) {
				//alert("mostro " + i);
				if ($('#elementImg_'+i).length)
					$('#elementImg_' + i ).show();
				if ($('#elementObj_'+i).length)
					$('#elementObj_' + i ).show();
			}
		}
	</script>
</head>

<body  style="background-color: #060606">

	<!-- MEDIA CORRENTE -->
	<div style="width: 100%; margin-top: 11px;">

			<div style="width: 820px; height: <?php echo round($height)+20 ?>px; 
						display: table; margin: 0px auto;  margin-top: 10px; 
						background-color: #333;"
				 id="riquadroDIV"
				 onmouseover="doOnMsOver();" onmouseout="doOnMsOut();" >

				<!-- IMG Close -->
				<img id="close" src="/viantes/pvt/img/common/close_w.png" width="42"
					 onclick="window.close()"
					 style="position: absolute; margin: -10px 0px 0px 820px; cursor:pointer">

				<div style="width: 800px; display: table; margin: 10px">
					<!-- Contenitore dell'immagine/video correnti -->
					<div>
						<!-- IMG Next -->
						<img id="nxt" src="/viantes/pvt/img/common/next.png" width="42"
							 onClick="goNext();"
							 style="position: absolute; margin:<?php echo $imgHeight?>px 0px 0px  760px; display: none; cursor:pointer">
						
						<!-- IMG Prec -->
						<img id="prv" src="/viantes/pvt/img/common/back.png" width="42"
							 onClick="goPrev();" 
							 style="position: absolute; margin:<?php echo $imgHeight?>px 0px 0px 0px; display: none; cursor:pointer">

						<!-- IMG Play -->
						<img id="viewerPlayId" src="/viantes/pvt/img/review/play_32.png"  
							 style="position: absolute; margin:<?php echo $imgHeight?>px 0px 0px 390px; display: none; cursor:pointer" 
							 onclick="mngMov4Viewer();">

						<!-- IMG Pause -->
						<img id="viewerPauseId" src="/viantes/pvt/img/review/pause_32.png" 
							 style="position: absolute; margin:<?php echo $imgHeight?>px 0px 0px 390px; display: none; cursor:pointer"
							 onclick="mngMov4Viewer();">	 
						
						<!-- IMMAGINE -->
						<?php if ( $current['type'] == IMG || $current['type'] == 'CVR') { ?>
							<img id="currentImg" style="width: 100%;" src="<?php echo $current['name'] ?>">
						<!-- VIDEO -->
						<?php } else if ($current['type'] == MOV) { ?>
							<object id="currentObj" height="<?php echo $height ?>" width="800">
								<param name="movie" value="<?php echo $current['name'] ?>">
								<param name="play"  value="false">
								<param name="wmode" value="transparent">
								<embed id="currentMov" src="<?php echo $current['name'] ?>" 
									   type="application/x-shockwave-flash" volume="100" wmode="transparent" allowscriptaccess="always" 
									   allowfullscreen="true" play="false" height="<?php echo $height ?>" width="800">
							</object>
						<?php }?>
						<!-- serve per IE - non ho modo di settare lo stato play/stop -->
						<input type="hidden" id="videoStartStopForIE" value="false">
					</div>
				</div>

				<!-- BARRA DEI MEDIA -->
				<div id="mediaBar" style="background-color: #333; height: 40px; display: none">
					<div style="width: 100%; display: table; margin: 6px auto; ">
						<span>
							<img id="mediaBarPrevId" src="/viantes/pvt/img/common/mediaBarPrev.png" 
								 style="margin-bottom:10px; cursor:pointer; display:none" 
								 onclick="mediaBarPrev();">
							<?php	$i = 0;
							foreach ( $mediaArray as $k => $v) {
								if ( $v['type'] == IMG || $v['type'] == 'CVR') { ?>
									<img id="elementImg_<?php echo $i?>" height="36" src="<?php echo $v['name']?>" 
										 style="cursor:pointer; border: 1px solid #fa0;
										<?php //se e' il corrente deve essere ingrigito
										if ($i==$currentIndex) echo "opacity: 0.40;";
										//se e' oltre il numero massimo => deve essere nascosto
										if ($i > $mediaBarElemNumber -1 ) echo "display:none;"; ?>" 
										onclick="goToElement('<?php echo $i?>')"
									/> <?php

									//dato che le immagini sono sprovviste di xDim e yDim (valgono zero!!!!!!) le calcolo ora
									list($_width, $_height) = getimagesize(HT_ROOT.$v['name']); ?>
									<input type="hidden" id="xdim_<?php echo $i?>" value="<?php echo $_width ?>">
									<input type="hidden" id="ydim_<?php echo $i?>" value="<?php echo $_height ?>">
								<?php
								}
								if ( $v['type'] == MOV ) { 
									$width = round(46 / $v['ydim'] * $v['xdim']); ?>
									<img id="elementObj_<?php echo $i?>" height="36" src="/viantes/pvt/img/review/play_32.png" 
										 style="cursor:pointer; border: 1px solid #fa0;
										<?php //se e' il corrente deve essere ingrigito
										if ($i==$currentIndex) echo "opacity: 0.40;";
										//se e' oltre il numero massimo => deve essere nascosto
										if ($i > $mediaBarElemNumber -1 ) echo "display:none;"; ?>" 
										onclick="goToElement('<?php echo $i?>')"
									/> 
									<input type="hidden" id="xdim_<?php echo $i?>" value="<?php echo $v['xdim'] ?>">
									<input type="hidden" id="ydim_<?php echo $i?>" value="<?php echo $v['ydim'] ?>">
									<input type="hidden" id="elementMov_<?php echo $i?>" value="<?php echo $v['name'] ?>">
								<?php
								}
								$i++;
							} ?>
							<img id="mediaBarNextId" src="/viantes/pvt/img/common/mediaBarNext.png" 
								 style="margin-bottom:10px; cursor:pointer; <?php  if ( $mediaBarElemNumber >= $i) echo "display:none;"; ?>" 
								 onclick="mediaBarNext();">
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>
 ) echo "display:none;"; ?>" 
											onclick="goToElement('<?php //echo $i?>')" >
										<param name="movie" value="<?php //echo $v['name'] ?>">
										<param name="play" value="false">
										<embed id="elementMov_<?php //echo $i?>" src="<?php //echo $v['name'] ?>"
											  type="application/x-shockwave-flash" volume="100" wmode="transparent" allowscriptaccess="always" 
											  allowfullscreen="false" play="false" height="36" width="<?php //echo $width?>">
									</object-->
								<?php
								}
								$i++;
							} ?>
							<img id="mediaBarNextId" src="/viantes/pvt/img/common/mediaBarNext.png" 
								 style="margin-bottom:10px; cursor:pointer; <?php  if ( $mediaBarElemNumber >= $i) echo "display:none;"; ?>" 
								 onclick="mediaBarNext();">
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>

											  type="application/x-shockwave-flash" volume="100" wmode="transparent" allowscriptaccess="always" 
											  allowfullscreen="false" play="false" height="36" width="<?php //echo $width?>">
									</object-->
								<?php
								}
								$i++;
							} ?>
							<img id="mediaBarNextId" src="/viantes/pvt/img/common/mediaBarNext.png" 
								 style="margin-bottom:10px; cursor:pointer; <?php  if ( $mediaBarElemNumber >= $i) echo "display:none;"; ?>" 
								 onclick="mediaBarNext();">
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>

</body>
</html>
tml>
