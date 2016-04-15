<?php 
require_once $X_root."pvt/pages/msg/msgDAO.php";
require_once $X_root."pvt/pages/msg/msgDO.php";

$msgDAO = New msgDAO();
$msgDOArray = $msgDAO->getReceivedMessages($userDO->getId(), $pattern);?>

<div class="mrg-top-12">
	<h3 style=""><?php echo $X_langArray['MESSAGE_IN_H3']?></h3>
</div>

<?php
if (count($msgDOArray) == 0) { ?>
	<div class="msgContentDiv">
		<?php echo $X_langArray['MESSAGE_NO_RECEV_MSG']; ?>
	</div>	
<?php 
} else if (count($msgDOArray) > 0) { 
?>
	<div class="msgContentDiv">
		<table class="msgTable">
			<tr class="msgHeaderRow">
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_FROM']?></th>
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_SBJ']?></th>
				<th class="msgHeaderCell"></th>
			</tr>
			<?php 
			$i = 1;
			foreach ($msgDOArray as $key => $msgDO) { ?>
				<tr class="msgBodyRow <?php if ($i % 2 == 0)  { echo 'msgBodyRowBG'; } $i++; ?>">
					<td class="msgBodyCell">
						<a class="bodyColor" href="/m/viantes/pub/pages/profile/showMsg.php?msgId=<?php echo X_code($msgDO->getId()) ?>&tabactive=1">
							<img class="msgCoverBodyCell" <?php echo IMG_25_25 ?> src="<?php echo $msgDO->getFromUsrCoverFileName() ?>" />
							<?php echo ($msgDO->getRecipientStatus() == 0) ? "<b>".$msgDO->getFromUsrName()."</b>" : $msgDO->getFromUsrName() ?>
						</a>
					</td>
					<td class="msgBodyCell">
						<a class="bodyColor" href="/m/viantes/pub/pages/profile/showMsg.php?msgId=<?php echo X_code($msgDO->getId()) ?>&tabactive=1">
							<?php $sbjct = substr($msgDO->getSubject(), 0, 10);
							echo ($msgDO->getRecipientStatus() == 0) ?  "<b>".$sbjct."</b>" :  $sbjct;
							if ( strlen($msgDO->getSubject()) > 14 ) echo "..." ?>
						</a>
					</td>
					<td class="msgBodyCell"> 
						<input class="msgP5" type="checkbox" name="delMsgTab1" value="<?php echo X_code($msgDO->getId()) ?>"> 
					</td>
				</tr>	
			<?php } ?>
		</table>
		
		<a href="#" class="" onclick="confirmDelMsg(1)">
			<div class="delMsg personalButton">
				<?php echo $X_langArray['MESSAGE_OVERLAY_DEL']?>
			</div>
		</a>
	</div>

<?php }  ?>
