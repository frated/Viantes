<script>
	function deleteImg(beanSessionKey, position, type, tabIndex){
		$('html,body').scrollTop(0);
		$('#del-element-name-param').attr('value', beanSessionKey);
		$('#del-element-type-param').attr('value', type);
		$('#del-element-pstn-param').attr('value', position);
		$('#overlay-del-item').show();
		$('#tabactive').val(tabIndex);
	}
</script>

<?php
$bean = isset($_SESSION[$beanSessionKey]) ? unserialize($_SESSION[$beanSessionKey]) : null;

if ( $bean != null && count($bean->getImgFileNameArray()) > 0 ) {

	$filePath = $bean->getImgRelativeFilePathArray();
	$fileNameArray = $bean->getImgFileNameArray();
	$commentArray = $bean->getImgCommentArray();
	
	for( $i = 0; $i < count($fileNameArray); $i++ ) {
		
		if ( $i > 0 ) echo '<hr class="commonMediaRenderHR">';
		
		$commentVal = isset($commentArray) && isset($commentArray[$i]) 
					  && $commentArray[$i] != "" ? 
						$commentArray[$i] : 
						$X_langArray['CREATE_REVIEW_IMG_TELL_US'];
						
		$commentStl = isset($commentArray) && isset($commentArray[$i]) 
					  && $commentArray[$i] != "" ?  
						' style="color: #454545" ' : '';
		
		//display div tag
		if (!isset($_GET['finish'])) {
			echo '<div class="reviewImgBox"
					   onmouseover="$(\'#delAttachRevT2'.$i.'\').show();"
					   onmouseout=" $(\'#delAttachRevT2'.$i.'\').hide();">
					<span style="position: relative;">
						<img id="delAttachRevT2'.$i.'"
							 src="/viantes/pvt/img/review/close_16.png"         
							 class="deleteAttachReview"
							 onclick="deleteImg(\''.$beanSessionKey.'\', \''.$i.'\', \'IMG\', 2)"/>
						<img width="100%" src="'.$filePath[$i].$fileNameArray[$i].RSZD_FOR_RVW.'"/>
					</span>
				  </div>
				  <div class="commentImgDiv">
					<textarea id="commentImg'.$i.'" name="commentImg'.$i.'" class="commentTxtArea" '.$commentStl.' maxlength="250"
							  onfocus="resetTellUsTextArea(\'Img'.$i.'\', \''. $X_langArray['CREATE_REVIEW_IMG_TELL_US']. '\')"
							  onblur="restoreTellUsTextArea(\''.$beanSessionKey.'\', \'Img'.$i.'\', \''. $X_langArray['CREATE_REVIEW_IMG_TELL_US']. '\')"
					>'. $commentVal . '</textarea>
				  </div>';
		} else {
			$commentVal = ($commentVal == $X_langArray['CREATE_REVIEW_IMG_TELL_US']) ? "" :$commentVal;
			
			echo '<div class="reviewImgBox">
					  <img width="100%" src="'.$filePath[$i].$fileNameArray[$i].'"/>
				  </div>
				  <div class="commentImgDiv">
					  <textarea id="commentImg'.$i.'" name="commentImg'.$i.'" disabled="disabled" class="commentDsbldTxtArea">'. $commentVal . '</textarea>
				  </div>';
		}
	} 
}
?>
