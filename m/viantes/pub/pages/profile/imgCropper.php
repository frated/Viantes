<?php 
$X_root = "../../../../../viantes/";
session_start();
require_once $X_root."pvt/pages/const.php";
require_once $X_root."pvt/pages/globalFunction.php";
//prima di verificare la sessione salvo la richeesta
savePageRequest("/viantes/pub/pages/profile/imgCropper.php?fn=".$_GET['fn']."^dir".$_GET['dir']."^uid".$_GET['uid']);
require_once $X_root."pvt/pages/checkSession.php";
require_once $X_root."pvt/pages/lang/initLang.html";
require_once $X_root."pvt/pages/auth/userDO.php";
require_once $X_root."pvt/pages/auth/userRegistryDAO.php";
require_once $X_root."pvt/pages/auth/userRegistryDO.php";
require_once $X_root."pvt/pages/infoCookie.php"; //setta la variabile globale $X_show_cookie usata nell'header

$fn = $_GET['fn']; //file name
$directoryName = $_GET['dir']; //la dir giornaliera del file 
$fullFileName = UPLOAD_PATH.SLASH.$directoryName.SLASH.$fn;

//dimensione reale in input dell'immagine
list($widthIn, $heightIn) = getimagesize($fullFileName);

$lato = 400; //il cropper che e' un quadrato ha dimensioni 400x400
$scale = 1;  //fattore di scala e' 1 per immagini 400x400

$width = 640; $height = 640; //dimensioni del div contanitore del cropper (immagine esterna)

//se entrambe le dimensioni sono inferiori alla dimensione voluta (640x640)
if ($widthIn < $width && $heightIn < $height) {
	$height = $heightIn;
	$width = $widthIn;
} else {
	if ($widthIn > $heightIn) {
		$height = ratioImagDimensionFixWidth($widthIn, $heightIn, $width);
		$scale  = round($widthIn / $width, 2);
	} else {
		$width = ratioImagDimensionFixHeight($widthIn, $heightIn, $height);	
		$scale  = round($heightIn / $height, 2);
	}
} 
//se, dopo il ridimensionamento, una dimensione lato e' < del tao del cropper
if ( $width < $lato || $height < $lato )
	$lato = $width > $height ? $height : $width;

$imgCropperStyle = ' width: '.$lato.'px; height: '.$lato.'px;';
$x0 = ($width  - $lato)/2;
$y0 = ($height - $lato)/2;
$x1 = $lato + $x0;
$y1 = $lato + $y0;
//echo $x1; exit;

//------------  Riscalo l'immagine ---------------------
$img = imagecreatefromjpeg($fullFileName);

// Create a new temporary image.
$tmpimg = imagecreatetruecolor( $widthIn, $heightIn );

// Copy and resize old image into new image.
imagecopyresampled( $tmpimg, $img, 0, 0, 0, 0, $width, $height, $widthIn, $heightIn );

// Save temporary image into a file.
$croppedFileName = UPLOAD_PATH.SLASH.$directoryName.SLASH.TMP_CROPPED_FILE_NAME;
imagejpeg( $tmpimg, $croppedFileName, 100);
if (isUnix()) {
	chmod($croppedFileName, 0777); 
}

//Il file relativo per i tag <img> sara ora:
$fileName = REL_UP_PATH.$directoryName.SLASH.TMP_CROPPED_FILE_NAME;

// release the memory
imagedestroy($tmpimg);
imagedestroy($img);
//------------------------------------------------------
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
	<title></title>
	<?php include $X_root."pvt/pages/common/meta-link-script.html"; ?>

	<script type="text/javascript" src="<?php echo $X_root.'pvt/js/jCropper.js'?>"></script>

</head>

<body>
	<?php require_once $X_root."pvt/pages/common/header.html";?>
	
	<div class="main-div">

		<?php include $X_root."pvt/pages/common/globalTopMsg.php"; ?>
		
		<style type="text/css">
		.imgOriginalOverlay {
			color: #f00;
			margin: 10px;
			width: <?php echo $width ?>px;
			height:<?php echo $height?>px;
			position: relative;
			top: 0px; left: 0px;
			/* for IE9 and below : NB Insert to the top of css */
			background: url('<?php echo $fileName ?>') no-repeat;
			/* Standard */
			background-image: linear-gradient(to bottom, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.4)), url("<?php echo $fileName ?>");
			/* Firefox */
			background-image: -moz-linear-gradient(top, rgba(255, 255, 255, 0.4), rgba(256, 255, 255, 0.4)), url("<?php echo $fileName ?>");
			/* Opera 11.10+ */
			background-image: -o-linear-gradient(top, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.4)), url("<?php echo $fileName ?>");
			/* IE10+ */
			background-image: -ms-linear-gradient(top, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0.4)), url("<?php echo $fileName ?>");
			/* Chrome10+,Safari5.1+ */
			background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(255, 255, 255, 0.4)), to(rgba(255, 255, 255, 0.4))), url("<?php echo $fileName ?>"); 
		}
		.imgOver {
			position: relative; <?php echo $imgCropperStyle ?> overflow: hidden; border: 1px #fa0 solid; top: <?php echo $y0?>px; left: <?php echo $x0?>px;
		}
		</style>

		<div>
			<form id="attachCoverFrm" enctype="multipart/form-data" action="/viantes/pvt/pages/upload/crop.php" method="POST">
				<div class="imgOriginalOverlay">
					<div id="divToBeMoved" class="imgOver" >
						<img id="imgToBeMoved" src="<?php echo $fileName ?>" 
							style="position: relative; top: <?php echo ($y0*-1) ?>px; left: <?php echo ($x0*-1) ?>px;"/>
					</div>						
				</div>

				<div style="width: <?php echo $width ?>px;" class="mrg-top-36 mrg-bot-36">
					<?php 
					//il minimo e' il valore del lato (400) il masismo e' la massima dimensione x o Y
					$shift = 20; $minX1 = $lato; $minY1 = $lato; $maxX1 = $width; $maxY1 = $height;
					?>
					<img src="/viantes/pvt/img/cropper/left.png"  width="24px" onclick="toLeft( <?php echo $shift. ",". $minX1?>);"/>
					<img src="/viantes/pvt/img/cropper/right.png" width="24px" onclick="toRight(<?php echo $shift. ",". $maxX1?>);"/>
					<img src="/viantes/pvt/img/cropper/up.png"    width="24px" onclick="toTop(  <?php echo $shift. ",". $minY1?>);"/>
					<img src="/viantes/pvt/img/cropper/down.png"  width="24px" onclick="toDown( <?php echo $shift. ",". $maxY1?>);"/>
					<input style="float: right; height: 26px;" type="submit" value="OK"/>
				</div>
				
				<!-- Id utente -->
				<input type="hidden" name="usrId" value="<?php echo $_GET['uid']?>"/>
				
				<!-- coordinate del vertice alto a sinistra e basso destra-->
				<input id="x0" name="x0" type="hidden" value="<?php echo $x0?>"/>
				<input id="y0" name="y0" type="hidden" value="<?php echo $y0?>"/>
				<input id="x1" name="x1" type="hidden" value="<?php echo $x1?>"/>
				<input id="y1" name="y1" type="hidden" value="<?php echo $y1?>"/>
				
				<!-- Nome del file -->
				<input name="fn"  type="hidden" value="<?php echo $fn?>"/>
				<input name="directoryName" type="hidden" value="<?php echo $directoryName?>"/>
				<!-- Fattore di scala -->
				<input id="dn" name="scala" type="hidden" value="<?php echo $scale?>"/>

				<!-- Cover Type -->
				<input name="coverType" type="hidden" value="<?php echo COVER_TYPE_PROFILE ?>"/>
			</form>	
		</div>
	</div>
		
	<?php include $X_root."pvt/pages/common/footer.html"; ?>

</body>
</html>
