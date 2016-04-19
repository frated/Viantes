<script>
	function openDraftMsg(to, sbjt, msg) {
		$('#autoComplMsgTo').val(to);
		$('#ovrMsgSbjjt').val(sbjt);
		//sostituisco agli eventuali <br>, < br\>
		$('#ovlMsgMsg').val(msg.replace(/<br\s*[\/]?>/gi, "\n"));
		$('#tabactive').val('3'); 
		showNewMsg('/viantes/pub/pages/profile/message.php');
	}
</script>	
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
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_TO']?></th>
				<th class="msgHeaderCell"><?php echo $X_langArray['MESSAGE_COMMON_SBJ']?></th>
				<th class="msgHeaderCell"></th>
			</tr>
			<?php 
			$i = 1;
			foreach ($msgDOArray as $key => $msgDO) { ?>
				<tr class="msgBodyRow crs-pnt <?php if ($i % 2 == 0)  { echo 'msgBodyRowBG'; } $i++; ?>">
					<td class="msgBodyCell"
						onclick="openDraftMsg('<?php echo $msgDO->getToUsrName() ?>', '<?php echo addslashes($msgDO->getSubject())?>', '<?php echo addslashes($msgDO->getMessage()) ?>')">
						<img class="msgCoverBodyCell" <?php echo IMG_25_25 ?> src="<?php echo $msgDO->getToUsrCoverFileName() ?>" />
						<?php echo $msgDO->getToUsrName() ?>
					</td>
					<td class="msgBodyCell"
						onclick="openDraftMsg('<?php echo $msgDO->getToUsrName() ?>', '<?php echo addslashes($msgDO->getSubject())?>', '<?php echo addslashes($msgDO->getMessage()) ?>')">
						<?php echo substr($msgDO->getSubject(), 0, 14); 
						if ( strlen($msgDO->getSubject()) > 14 ) echo "..." ?>
					</td>
					<td class="msgBodyCell" onclick="">
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