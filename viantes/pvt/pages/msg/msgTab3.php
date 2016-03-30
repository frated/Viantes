<?php 
require_once $X_root."pvt/pages/msg/msgDAO.php";
require_once $X_root."pvt/pages/msg/msgDO.php";

$msgDAO = New msgDAO();
$msgDOArray = $msgDAO->getDraftMessages($userDO->getId(), $pattern);
?>

<div class="mrg-top-12">
	<h3 style=""><?php echo $X_langArray['MESSAGE_DRAFT_H3']?></h3>
</div>

<?php
if (count($msgDOArray) == 0) { ?>
	<div class="msgContentDiv">
		<?php echo $X_langArray['MESSAGE_NO_DRAFT_MSG']; ?>
	</div>	
<?php 
} else if (count($msgDOArray) > 0) { 
?>
	<div class="msgContentDiv">
		<table class="msgTable">
			<tr class="msgHeaderRow">
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_DT']?></th>
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_TO']?></th>
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_SBJ']?></th>
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_MSG']?></th>
				<th class="msgHeaderCell"></th>
			</tr>
			<?php 
			$i = 1;
			foreach ($msgDOArray as $key => $msgDO) { ?>
				<tr class="msgBodyRow crs-pnt <?php if ($i % 2 == 0)  { echo 'msgBodyRowBG'; } $i++; ?>" 
					onclick="$('#autoComplMsgTo').val('<?php echo $msgDO->getToUsrName() ?>');
							 $('#ovrMsgSbjjt').val('<?php echo $msgDO->getSubject() ?>');
							 $('#ovlMsgMsg').val('<?php echo str_replace("<br>", "\r\n", $msgDO->getMessage()) ?>');
							 $('#tabactive').val('3'); showMsgOverlay('/viantes/pub/pages/profile/message.php');">
					<td class="msgBodyCell">
						<?php echo $msgDO->getDtIns() ?>
					</td>
					<td class="msgBodyCell">
							<img class="msgCoverBodyCell" <?php echo IMG_25_25 ?> src="<?php echo $msgDO->getToUsrCoverFileName() ?>" />
							<?php echo $msgDO->getToUsrName() ?>
					</td>
					<td class="msgBodyCell">
							<?php echo substr($msgDO->getSubject(), 0, 14); 
							if ( strlen($msgDO->getSubject()) > 14 ) echo "..." ?>
					</td>
					<td class="msgBodyCell">
							<?php $lunghOriginale = strlen ( str_replace('<br />',' ', str_replace('<br>',' ', $msgDO->getMessage() )) ); 
							$msg = str_replace('<br />',' ', str_replace('<br>',' ', substr($msgDO->getMessage(), 0, 28))); 
							echo $msg;
							if ( $lunghOriginale > 28 ) echo "..." ?>
					</td>
					<td class="msgBodyCell">
						<input class="msgP5" type="checkbox" name="delMsgTab3" value="<?php echo X_code($msgDO->getId()) ?>" > 
					</td>
				</tr>	
			<?php } ?>
		</table>
		
		<a href="#" onclick="confirmDelMsg(3)">
			<div class="delMsg personalButton">
				<?php echo $X_langArray['MESSAGE_OVERLAY_DEL']?>
			</div>
		</a>
	</div>
	
<?php }  ?>
