<?php
ini_set('display_errors',1);

// Pear Mail Library
require_once "Mail.php";
require_once $X_root."pvt/pages/cfg/conf.php";

$from = ''; //CHANGE ME
$mailUsr = Conf::getInstance()->get('mailUsr');
$mailPwd = Conf::getInstance()->get('mailPwd');

function sendMail($to, $fwdCode, $name, $X_langArray) { 
	$head = '<head><title>'.$X_langArray['SEND_MAIL_SIGNIN_SBJ'].$name.'</title></head>';
	$subject = $X_langArray['SEND_MAIL_SIGNIN_SBJ'].$name;
	$link = '<a href="https://www.viantes.com/viantes/pub/pages/confirmSignUp.php?email='. $to .'&fwdCode='. urlencode($fwdCode) .'">qui</a>';
	$body = '<html>'.$head.
				'<body style="background-color: rgb(247, 247, 247);">
					<div style="margin: 0px auto; max-width: 550px">
						<div style="background-color: rgb(255, 170, 0); height: 28px; color: rgb(255, 255, 255);
									box-shadow: 3px 6px 9px #A2A2A2;">
							<a href="https://www.viantes.com" style="text-decoration: none;">
								<img src="https://www.viantes.com/viantes/pvt/img/logo_19_30.png"    style="height: 28px;">
								<img src="https://www.viantes.com/viantes/pvt/img/viantes_19_77.png" style="margin-bottom: 4px;">
							</a>
						</div>
						<h3 style="margin-left: 4px;">'.
							$X_langArray['SEND_MAIL_SIGNIN_PWD_TITLE1'].$name.$X_langArray['SEND_MAIL_SIGNIN_PWD_TITLE2'].
						'</h3>
						<p style="margin-left: 4px;">'.$X_langArray['SEND_MAIL_SIGNIN_PWD_P1']. $link . '</p>
						<p style="margin-left: 4px;">'.$X_langArray['SEND_MAIL_SIGNIN_PWD_P2']. '  <br> 
							https://www.viantes.com/viantes/pub/pages/confirmSignUp.php?email='. $to .'&fwdCode='. urlencode($fwdCode).'
						</p>
						<footer style="border-top: 1px solid rgb(255, 170, 0); margin-top: 16px; 
									   background-color: rgb(188, 188, 188); font-size: 12px;
									   padding-bottom: 4px;">
							<div>
								<p style="margin-left: 4px;">' .$X_langArray['COMM_FOOTER_TXT']. '</p>
							</div>
						</footer>
					</div>
				</body>
			</html>';

	//if ( Conf::getInstance()->get('doMail') != 1) 
		return $body;
	
	
	$this->send($from, $to, $subject, $body);
}

function sendMailForRecover($to, $fwdCode, $X_langArray) {
	$subject = $X_langArray['SEND_MAIL_RECOVER_PWD_SBJ'];
	$link = '<a href="https://www.viantes.com/viantes/pub/pages/recoverPwd.php?email='. $to .'&fwdCode='. urlencode($fwdCode) .'">qui</a>';
	$body = '<html>
				<head><title>' .$X_langArray['SEND_MAIL_RECOVER_PWD_TITLE']. '</title></head>
				<body style="background-color: rgb(247, 247, 247);">
					<div style="margin: 0px auto; max-width: 550px">
						<div style="background-color: rgb(255, 170, 0); height: 28px; color: rgb(255, 255, 255);
									box-shadow: 3px 6px 9px #A2A2A2;">
							<a href="https://www.viantes.com" style="text-decoration: none;">
								<img src="https://www.viantes.com/viantes/pvt/img/logo_19_30.png"    style="height: 28px;">
								<img src="https://www.viantes.com/viantes/pvt/img/viantes_19_77.png" style="margin-bottom: 4px;">
							</a>
						</div>
						<h3 style="margin-left: 4px;">
							' .$X_langArray['SEND_MAIL_RECOVER_PWD_TITLE']. '
						</h3>
						<p style="margin-left: 4px;">'.
							$X_langArray['SEND_MAIL_RECOVER_PWD_P1'].$link.
						'</p>
						<p style="margin-left: 4px;">'.
							$X_langArray['SEND_MAIL_RECOVER_PWD_P2'].
						'</p>
						<footer style="border-top: 1px solid rgb(255, 170, 0); margin-top: 16px; 
									   background-color: rgb(188, 188, 188); font-size: 12px;
									   padding-bottom: 4px;">
							<div class="footerDiv">
								<p style="margin-left: 4px;">' .$X_langArray['COMM_FOOTER_TXT']. '</p>
							</div>
						</footer>
					</div>
				</body>
			</html>';

	//if ( Conf::getInstance()->get('doMail') != 1) 
		return $body;
	
	$this->send($from, $to, $subject, $body);
}

function sendMailForComment($name, $mail, $comment) { 
	$subject = "Contatto da ".$name;
	$body = 'E\' arrivata una mail da '.$name.' email=['.$mail.'] e dice:<br>'.$comment;

	//if ( Conf::getInstance()->get('doMail') != 1) 
		return $body;
	
	$this->send($from, $from, $subject, $body);
}

/* Send mail using php Mail */
function send($from, $to, $subject, $body) { 
	
	$headers = array(
	    'From' => $from,
	    'To' => $to,
	    'Subject' => $subject,
	    'MIME-Version' => '1.0',
	    'Content-Type' => 'text/html; charset=ISO-8859-1' 
	);

	$smtp = Mail::factory('smtp', array(
		'host' => 'ssl://smtp.gmail.com',
		'port' => '465',
		'auth' => true,
		'username' => $mailUsr,
		'password' => $mailPwd
	));

	$mail = $smtp->send($to, $headers, $body);

	if (PEAR::isError($mail)) {
	    return false;
	} 
	return true;
}
?>