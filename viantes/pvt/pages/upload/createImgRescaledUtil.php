<?php

/* Crea e riscala un'immagine per la pagina di index */
function createAndRescaleForIndex($fullFileName) {
	
	list($_width, $_height, $type, $attr) = getimagesize($fullFileName);
	
	//dimensioni della nuova immagine (altezza 128px)
	$rescaledHeight = 128;
	$rescaledWidth  =  ratioImagDimensionFixHeight($_width, $_height, $rescaledHeight);
	
	//immagine originale
    $resurceImgOrig = imagecreatefromjpeg($fullFileName);
	
	//dimensioni originali
	list($widthIn, $heightIn) = getimagesize($fullFileName);
	
	//creao la nuova immagine 
	$scaledImg = imagecreatetruecolor($rescaledWidth, $rescaledHeight);
	//copio, in proporzione, l'orignale su quella scalata
	imagecopyresampled ($scaledImg , $resurceImgOrig , 0 , 0,  0, 0, $rescaledWidth, $rescaledHeight, $_width, $_height);
	
	//salvo con estensione RSZD_FOR_IND
	imagejpeg($scaledImg, $fullFileName.RSZD_FOR_IND, 100);
}

/* Crea e riscala un'immagine per le pagina di recensioni */
function createAndRescaleForReview($fullFileName) {
	
	list($_width, $_height, $type, $attr) = getimagesize($fullFileName);
	
	$rescaledWidth  = 320;
	$rescaledHeight = ratioImagDimensionFixWidth($_width, $_height, $rescaledWidth);

	//immagine originale
	$resurceImgOrig = imagecreatefromjpeg($fullFileName);

	//dimensioni originali
	list($widthIn, $heightIn) = getimagesize($fullFileName);

	//creao la nuova immagine 
	$scaledImg = imagecreatetruecolor($rescaledWidth, $rescaledHeight);
	//copio, in proporzione, l'orignale su quella scalata
	imagecopyresampled ($scaledImg , $resurceImgOrig , 0 , 0,  0, 0, $rescaledWidth, $rescaledHeight, $_width, $_height);

	//salvo con estensione RSZD_FOR_RVW
	imagejpeg($scaledImg, $fullFileName.RSZD_FOR_RVW, 100);
}

?>