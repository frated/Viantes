<?php
$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;

if ( $bean != null && count($bean->getDocFileNameArray()) > 0 ) {
	
	$filePath = $bean->getDocRelativeFilePathArray();
	$fileNameArray = $bean->getDocFileNameArray();
	$commentArray = $bean->getDocCommentArray();
	
	for( $i = 0; $i < count($fileNameArray); $i++ ) {
		
		//replace blank space 
		$fn = str_replace(" ", "_",$fileNameArray[$i]);
		
		//format to max leng
		$fnFormatted = strlen($fn) < 12 ? $fn : substr($fn, 0, 5).'...'.substr($fn, -6);
		
		if ( $i > 0 ) echo '<hr class="commonMediaRenderHR">';
		
		$commentVal = isset($commentArray) && isset($commentArray[$i]) 
					  && $commentArray[$i] != "" ? 
						$commentArray[$i] : 
						$X_langArray['CREATE_REVIEW_DOC_TELL_US'];
						
		$commentStl = isset($commentArray) && isset($commentArray[$i]) 
					  && $commentArray[$i] != "" ? 
						' style="color: #454545" ' : '';
						
		//display div tag
		if (!isset($_GET['finish'])) {
			echo	'<div id ="reviewDocBoxId'.$i.'" class="reviewDocBox"
						  onmouseover="$(\'#delAttachRevT4'.$i.'\').show();"
						  onmouseout=" $(\'#delAttachRevT4'.$i.'\').hide();">
						<span style="position: relative;">
							<img id="delAttachRevT4'.$i.'"
								 src="/viantes/pvt/img/review/close_16.png"
								 class="deleteAttachReview" 
								 onclick="deleteImg(\''.$beanSessionKey.'\', \''.$i.'\', \'DOC\', 4)"/>
							<img ' .IMG_100_126. 'src="/viantes/pvt/img/review/pdfFileIcon_144_189.png"/>
							<a  target="_blank" href="'.$filePath[$i].$fileNameArray[$i].'">' . $fnFormatted . '</a>
						</span>
					</div>
					<div class="commentDocDiv">
						<textarea id="commentDoc'.$i.'" name="commentDoc'.$i.'" class="commentDocTxtArea" '.$commentStl.' maxlength="250"
								  onfocus="resetTellUsTextArea(\'Doc'.$i.'\', \''. $X_langArray['CREATE_REVIEW_DOC_TELL_US']. '\')"
								  onblur="restoreTellUsTextArea(\''.$beanSessionKey.'\', \'Doc'.$i.'\', \''. $X_langArray['CREATE_REVIEW_DOC_TELL_US']. '\')"
						>'. $commentVal . '</textarea>
				  </div>';
		} else {
			$commentVal = ($commentVal == $X_langArray['CREATE_REVIEW_DOC_TELL_US']) ? "" :$commentVal;
			
			echo	'<div id ="reviewDocBoxId'.$i.'" class="reviewDocBox">
						<span style="position: relative;">
							<img ' .IMG_100_126. 'src="/viantes/pvt/img/review/pdfFileIcon_144_189.png"/>
						</span>
					</div>
					<div class="commentDocDiv">
						<textarea id="commentDoc'.$i.'" name="commentDoc'.$i.'" disabled="disabled" class="commentDsbldTxtArea">'. $commentVal . '</textarea>
				  </div>';
		}
	}
}

?>
