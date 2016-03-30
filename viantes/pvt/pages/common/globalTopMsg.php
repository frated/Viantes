<?php 
if (isset($_SESSION[GLOBAL_TOP_MSG_SUCCESS]) && $_SESSION[GLOBAL_TOP_MSG_SUCCESS] != '') { ?>
	<div id="top-msg-div-id-ok" class="top-msg top-msg-ok">
		<div>
			<a class="close" href="#" onclick="$('#top-msg-div-id-ok').hide()">×</a>
		</div>
		<div class="top-msg-container-div">
			<img src="/viantes/pvt/img/common/global_ok_32_22.png">
			<h4 class="top-msg-h4">
				<?PHP echo $_SESSION[GLOBAL_TOP_MSG_SUCCESS];?>
			</h4>
		</div>
	</div>
<?php 
	unset($_SESSION[GLOBAL_TOP_MSG_SUCCESS]);
} 
if (isset($_SESSION[GLOBAL_TOP_MSG_ERROR]) && $_SESSION[GLOBAL_TOP_MSG_ERROR] != '') { ?>
	<div id="top-msg-div-id-ko" class="top-msg top-msg-ko">
		<div>
			<a class="close" href="#" onclick="$('#top-msg-div-id-ko').hide()">×</a>
		</div>
		<div class="top-msg-container-div">
			<img src="/viantes/pvt/img/common/warn_24.png">
			<h4 class="top-msg-h4">
				<?PHP echo $_SESSION[GLOBAL_TOP_MSG_ERROR];?>
			</h4>
		</div>
	</div>
<?php 
	unset($_SESSION[GLOBAL_TOP_MSG_ERROR]);
}
?>