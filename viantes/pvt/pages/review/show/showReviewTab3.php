<?php 
//Rimuovo dalla sessione il vettore che serve al viewer altrimenti fa push degli stessi elementi che comparirebbero ripetuti
unset($_SESSION['showRevMovResult']);

$ids = $reviewDAO->getAttachIdListByReviewIdAndType($reviewDO->getId(), MOV );

//fix problem in mamcached, if array is empty => memcached not work :(
if (count($ids) == 1 &&  $ids[0] == null) $ids = array();
?>

<p class="mrg-top-24"><b> <?php echo (count($ids) == 0) ? $X_langArray['SHOW_REVIEW_NO_MOV_MESSAGE'] : 
								  	   $X_langArray['SHOW_REVIEW_SHOW_MOV_MESSAGE'] ?></b></p>

<?php 
$i = 0;
foreach ($ids as $k => $id) {
	if ($i>0) { ?>
		<hr class="commonMediaRenderHR">
<?php } ?>
	<img id="loadRevImg_<?php echo $id ?>" class="loadImgDoc" src="/viantes/pvt/img/animate/ld_32_ffffff.gif"/>
	
	<div class="reviewMovBox"
		onmouseover="$('#expandAttachRevT3<?php echo $id ?>').show();
					 $('#playMov<?php echo $id ?>').show();"
		onmouseout=" $('#expandAttachRevT3<?php echo $id ?>').hide();
				     $('#playMov<?php echo $id ?>').hide();">
		<span style="position: absolute;">
			<img id="playMov<?php echo $id ?>" src="/viantes/pvt/img/review/play_32.png"
				 style="z-index:40; display:none; margin-left:190px;"
				 onclick="mngMov(<?php echo $id ?>);"/>
		</span>		 
		<span style="position: absolute;" id="spanMov<?php echo $id ?>">
			<img id="expandAttachRevT3<?php echo $id ?>" src="/viantes/pvt/img/review/expand_16.png"
				 style="margin-left: 370px; z-index:40; display:none"
				 onclick="window.open('/viantes/pub/pages/media/viewer.php?revId=<?php echo X_code($reviewDO->getId())?>&revTp=<?php echo $reviewType ?>&revAttachId=<?php echo X_code($id) ?>','_blank')" href="#">
		</span>
	</div>
	<div class="commentMovDiv">
		<textarea id="commentMov_<?php echo $id ?>" class="commentDsbldTxtArea commentMovTxtArea" disabled="true"></textarea>
	</div>
<?php $i++; } ?>

<script>
	jQuery(document).ready(
		<?php 
		for ($i = 0; $i < count($ids); $i++) { 
			$id = $ids[$i];
			if ($i > 0) { echo ","; } ?>
			
			doMovTagAsyncGet('/viantes/pvt/pages/review/show/showReviewTab3Asy.php', '<?php echo $id; ?>', '<?php echo $reviewType; ?>')
		<?php } ?>
	)
</script>
