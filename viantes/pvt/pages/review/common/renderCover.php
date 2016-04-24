<?php
$fileName = $bean->getCoverFileName();

//display div tag
echo '<div class="reviewCoverImgBox" 
		   onmouseover="$(\'#delAttachRevT1\').show();"
		   onmouseout="$(\'#delAttachRevT1\').hide();">
		<span style="position: relative;">
			<img id="delAttachRevT1"
				 src="/viantes/pvt/img/review/close_16.png"         
				 class="deleteAttachReview" 
				 onclick="$(\'#tabactive\').val(1); delFromSess(\''.$beanSessionKey.'\', \'CVR\', 0)" />
			<img width="320" src="'.$fileName.RSZD_FOR_RVW.'"/>
		</span>
	</div>';
?>
