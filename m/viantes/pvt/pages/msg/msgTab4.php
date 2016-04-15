<?php 
require_once $X_root."pvt/pages/msg/msgDAO.php";
require_once $X_root."pvt/pages/msg/msgDO.php";

$msgDAO = New msgDAO();
$msgDOArray = $msgDAO->getDeletedMessages($userDO->getId(), $pattern);?>

<div class="mrg-top-12">
	<h3 style=""><?php echo $X_langArray['MESSAGE_TRASH_H3']?></h3>
</div>

<?php
if (count($msgDOArray) == 0) { ?>
	<div class="msgContentDiv">
		<?php echo $X_langArray['MESSAGE_NO_TRASH_MSG']; ?>
	</div>	
<?php 
} else if (count($msgDOArray) > 0) { 
?>
	<div class="msgContentDiv">
		<table class="msgTable">
			<tr class="msgHeaderRow">
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_FROM']?></th>
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_TO']?></th>
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_SBJ']?></th>
				<th class="msgHeaderCell"></th>
			</tr>
			<?php 
			$i = 1;
			foreach ($msgDOArray as $key => $msgDO) { ?>
				<tr class="msgBodyRow <?php if ($i % 2 == 0)  { echo 'msgBodyRowBG'; } $i++; ?>">
					<td class="msgBodyCell">
						<img class="msgCoverBodyCell" <?php echo IMG_25_25 ?> src="<?php echo $msgDO->getFromUsrCoverFileName() ?>" />
						<?php echo ($msgDO->getRecipientStatus() == -1) ? "<b>".$msgDO->getFromUsrName()."</b>" : $msgDO->getFromUsrName() ?>
					</td>
					<td class="msgBodyCell">
						<img class="msgCoverBodyCell" <?php echo IMG_25_25 ?> src="<?php echo $msgDO->getToUsrCoverFileName() ?>" />
						<?php echo ($msgDO->getRecipientStatus() == -1) ? "<b>".$msgDO->getToUsrName()."</b>" : $msgDO->getToUsrName() ?>
					</td>
					<td class="msgBodyCell">
						<?php 
						$sbjct = substr($msgDO->getSubject(), 0, 14); 
						echo ($msgDO->getRecipientStatus() == -1) ?  "<b>".$sbjct."</b>" :  $sbjct;
						if ( strlen($msgDO->getSubject()) > 14 ) echo "..." ?>
					</td>
					<td class="msgBodyCell">
						<input class="msgP5" type="checkbox" name="delMsgTab4" value="<?php echo X_code($msgDO->getId()) ?>" > 
					</td>
				</tr>	
			<?php } ?>
		</table>
		
		<a class="delMsg personalButton" href="#" onclick="confirmDelMsg(4); $('#isRrestore').val(0);">
			<?php echo $X_langArray['MESSAGE_OVERLAY_DEL']?>
		</a>
		<a class="delMsg personalButton" href="#" onclick="$('#isRrestore').val(1); $('#overlay-del-msg-question1').hide(); $('#overlay-del-msg-question2').show(); confirmDelMsg(4);">
			<?php echo $X_langArray['MESSAGE_OVERLAY_RESTORE']?>
		</a>
		<input type="hidden" id="isRrestore" value=""/>	
		
	</div>
<?php } ?>
