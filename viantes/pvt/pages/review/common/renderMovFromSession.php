<?php
$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;

if ( $bean != null && count($bean->getMovFileNameArray()) > 0 ) {
		
	$uri = getURI();
	$filePath = $bean->getMovRelativeFilePathArray();
	$fileNameArray = $bean->getMovFileNameArray();
	$commentArray = $bean->getMovCommentArray();
	$widthArray = $bean->getMovWidthArray();
	$heightArray = $bean->getMovHeightArray();

	for( $i = 0; $i < count($fileNameArray); $i++ ) {
		if ( $i > 0 ) echo '<hr class="commonMediaRenderHR">';
		
		$commentVal = isset($commentArray) && isset($commentArray[$i]) 
					  && $commentArray[$i] != "" ? 
						$commentArray[$i] : 
						$X_langArray['CREATE_REVIEW_MOV_TELL_US'];
						
		$commentStl = isset($commentArray) && isset($commentArray[$i]) 
					  && $commentArray[$i] != "" ? 
						' style="color: #454545" ' : '';
		
		$height = 400 / $widthArray[$i] * $heightArray[$i];

		if (!isset($_GET['finish'])) {
			echo	'<div class="reviewMovBox"
						  onmouseover="$(\'#delAttachRevT3'.$i.'\').show();
									   $(\'#playMov'.$i.'\').show();"
						  onmouseout=" $(\'#delAttachRevT3'.$i.'\').hide();
									   $(\'#playMov'.$i.'\').hide();">	   
						<span style="position: absolute;">
							<img id="playMov'.$i.'" src="/viantes/pvt/img/review/play_32.png"
								 onclick="mngMov('.$i.');" 
								 style="z-index:40; margin-top:'. round($height/2).'px; margin-left:190px; display:none"/>
						</span>	
						<span style="position: absolute;">
							<img id="delAttachRevT3'.$i.'" src="/viantes/pvt/img/review/close_16.png"         
								 style="margin-left: 370px; z-index:40; display:none; cursor:pointer"
								 onclick="deleteImg(\''.$beanSessionKey.'\', \''.$i.'\', \'MOV\', 3)" />
						</span>	
						<object height="'.$height.'" width="400">
							<param name="movie" value="'.$uri.$filePath[$i].$fileNameArray[$i].'">
							<param name="play" value="false" />
							<embed id="crtReviewMov'.$i.'" src="'.$uri.$filePath[$i].$fileNameArray[$i].'" 
								   type="application/x-shockwave-flash"  volume="100"
								   wmode="transparent" allowscriptaccess="always" allowfullscreen="true" play="false"
								   height="'.$height.'" width="400">
							</embed>
						</object>
					</div>
					<input type="hidden" id="videoStartStopForIE_'.$i.'" value="false">
					<div class="commentMovDiv">
						<textarea id="commentMov'.$i.'" name="commentMov'.$i.'" class="commentMovTxtArea" '.$commentStl.'  maxlength="250"
								  onfocus="resetTellUsTextArea(\'Mov'.$i.'\', \''. $X_langArray['CREATE_REVIEW_MOV_TELL_US']. '\')"
								  onblur="restoreTellUsTextArea(\''.$beanSessionKey.'\', \'Mov'.$i.'\', \''. $X_langArray['CREATE_REVIEW_MOV_TELL_US']. '\')"
						>'. $commentVal. '</textarea>
				  </div>';
		} else {
			$commentVal = ($commentVal == $X_langArray['CREATE_REVIEW_MOV_TELL_US']) ? "" :$commentVal;
			
			echo	'<div class="reviewMovBox">
						<object height="'.$height.'" width="400">
							<param name="movie" value="'.$uri.$filePath[$i].$fileNameArray[$i].'">
							<param name="play" value="false" />
							<embed id="crtReviewMov'.$i.'" src="'.$uri.$filePath[$i].$fileNameArray[$i].'" 
								   type="application/x-shockwave-flash"  volume="100"
								   wmode="transparent" allowscriptaccess="always" allowfullscreen="true" play="false"
								   height="'.$height.'" width="400">
							</embed>
						</object>
					</div>
					<div class="commentMovDiv">
						<textarea id="commentMov'.$i.'" name="commentMov'.$i.'" disabled="disabled" class="commentDsbldTxtArea commentMovTxtArea">'. $commentVal. '</textarea>
				  </div>';			
		}
	}
}
?>
