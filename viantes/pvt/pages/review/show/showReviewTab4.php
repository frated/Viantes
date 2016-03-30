<?php 
$ids = $reviewDAO->getAttachIdListByReviewIdAndType($reviewDO->getId(), DOC );

//fix problem in mamcached, if array is empty => memcached not work :(
if (count($ids) == 1 &&  $ids[0] == null) $ids = array();
?>

<p class="mrg-top-24"><b> <?php echo (count($ids) == 0) ? $X_langArray['SHOW_REVIEW_NO_DOC_MESSAGE'] : 
									   $X_langArray['SHOW_REVIEW_SHOW_DOC_MESSAGE'] ?> </b></p>

<?php 
$i = 0;
foreach ($ids as $k => $id) { 
	if ( $i > 0 ) { ?>
		<hr class="commonMediaRenderHR">
<?php } ?>
	<div id="docs_<?php echo $id ?>" class="reviewDocBox">
		<img id="loadRevImg_<?php echo $id ?>" class="loadImgDoc" src="/viantes/pvt/img/animate/ld_32_ffffff.gif"/>
		<a id="docAnchor_<?php echo $id ?>" target="_blank" >
			<img id="docIco_<?php echo $id ?>" <?php echo IMG_100_126 ?> src="/viantes/pvt/img/review/pdfFileIcon_144_189.png"/>
		</a>
	</div>
	<div class="commentDocDiv">
		<textarea id="commentDoc_<?php echo $id ?>" class="commentDsbldTxtArea commentDocTxtArea" disabled="true"></textarea>
	</div>
<?php $i++; } ?>

<script>
	jQuery(document).ready(
		<?php 
		for ($i = 0; $i < count($ids); $i++) { 
			$id = $ids[$i];
			if ($i > 0) { echo ","; } ?>
			
			doDocTagAsyncGet('/viantes/pvt/pages/review/show/showReviewTab4Asy.php', '<?php echo $id; ?>', '<?php echo $reviewType; ?>')
		<?php } ?>
	)
</script>
