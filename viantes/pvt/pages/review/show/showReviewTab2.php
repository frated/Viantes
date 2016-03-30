<?php 

$ids = $reviewDAO->getAttachIdListByReviewIdAndType($reviewDO->getId(), IMG );

//fix problem in mamcached, if array is empty => memcached not work :(
if (count($ids) == 1 &&  $ids[0] == null) $ids = array();
?>

<p class="mrg-top-24"><b> <?php echo (count($ids) == 0) ? $X_langArray['SHOW_REVIEW_NO_IMG_MESSAGE'] : 
								  	   $X_langArray['SHOW_REVIEW_SHOW_IMG_MESSAGE'] ?> </b></p>

<?php
$nameArray = '';
$i = 0;
foreach ($ids as $k => $id) {
	if ( $i > 0) { ?>
		<hr class="commonMediaRenderHR">
<?php } ?>	
	<div id="reviewImgBox_<?php echo $id ?>" class="reviewImgBox">
		<img id="loadRevImg_<?php echo $id ?>" class="loadImgDoc" src="/viantes/pvt/img/animate/ld_32_ffffff.gif"/>
		<a onclick="window.open('/viantes/pub/pages/media/viewer.php?revId=<?php echo X_code($reviewDO->getId())?>&revTp=<?php echo $reviewType ?>&revAttachId=<?php echo X_code($id) ?>','_blank')" href="#">
			<img id="realArtImg_<?php echo $id ?>" width="100%" />
		</a>
	</div>
	<div class="commentImgDiv">
		<textarea id="commentImg_<?php echo $id ?>" class="commentDsbldTxtArea" disabled="true"></textarea>
	</div>	
<?php $i++; } ?>

<script>
	jQuery(document).ready(
		<?php
		for ($i = 0; $i < count($ids); $i++) { 
			$id = $ids[$i];
			if ($i > 0) { echo ","; } ?>
			
			doImgTagAsyncGet('/viantes/pvt/pages/review/show/showReviewTab2Asy.php', '<?php echo $id; ?>', '<?php echo $reviewType; ?>')
		<?php } ?>
	)
</script>
