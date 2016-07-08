<?php 
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 23.01.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */

include_once('class.phpmailer.php');
include_once('class.smtp.php');
include_once('PHPMailerAutoload.php');

if($this->sub_send_method==0){ # SMTP
	
	$mail = new PHPMailer();
	$mail->IsSMTP();
	
	$mail->Host     = $this->sub_smtp_host;
	$mail->SMTPAuth = $this->sub_smtp_auth;
	$mail->SMTPDebug = $this->sub_isDebug;
	$mail->Debugoutput = 'html';
	$mail->SMTPKeepAlive = true;
	if($this->sub_smtp_secure==1){# SSL
		$mail->SMTPSecure = 'ssl';
		}else if($this->sub_smtp_secure==2){# TLS
			$mail->SMTPSecure = 'tls';
			}
	$mail->Username = $this->sub_smtp_user;
	$mail->Password = $this->sub_smtp_pass;
	$mail->Port = $this->sub_smtp_port;
	
	if($this->sub_dkim_active){
		$mail->DKIM_selector = $this->sub_dkim_selector;
		//$mail->DKIM_identity = 'default';
		$mail->DKIM_passphrase = $this->sub_dkim_passphrase;
		$mail->DKIM_domain = $this->sub_dkim_domain;
		$mail->DKIM_private = LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private;
	}
	
	$mail->SetLanguage("tr", 'language/');
	$mail->Priority = 3;
	$mail->Encoding = 'base64';
	$mail->CharSet = "utf-8";
	if($this->sub_mail_type==0){$mail->IsHTML(true);$mail->ContentType = "text/html";}else{$mail->ContentType = "text/plain";}
	$mail->SetFrom($this->sub_from_mail, $name = $this->sub_from_title);
	$mail->AddReplyTo($this->sub_reply_mail, $this->sub_from_title);
	if($this->sub_mail_attach!=''){
		$attData = curl_get_result($this->sub_mail_attach);
		if($attData===false){
			# File Error
		}else{
			$mail->AddStringAttachment($attData,basename($this->sub_mail_attach),$encoding = 'base64',$type = 'application/octet-stream');
		}
	}
	
	# ** Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		# *************************************************************************
		/* Clear Mails */
		$mail->clearAddresses();
		$mail->clearCustomHeaders();
		$mail->clearAllRecipients();
		$mail->AddAddress($key, $value['name']);
		$mail->addCustomHeader("X-Lethe-Receiver: " . $key);
		$mail->addCustomHeader("X-Lethe-ID: " . $this->sub_mail_id);
		$mail->addCustomHeader("X-Mailer: Lethe Newsletter v" . LETHE_VERSION . ' http://www.newslether.com/');
		$mail->addCustomHeader("X-Mailer: Powered by Artlantis Design Studio http://www.artlantis.net/");
		$mail->Subject  =  $value['subject'];
		$mail->AltBody = $value['altbody'];
		$mail->MsgHTML($value['body']);
		
		/* Send Error */
		if(!$mail->Send()){
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = true;
		}
		# *************************************************************************
	}
	# **
	
}
# ********************************************************************************************************************************
else if($this->sub_send_method==1){ # PHPMail

	$mail = new PHPMailer(true);
	$mail->Debugoutput = 'html';
	
	if($this->sub_dkim_active){
		$mail->DKIM_selector = $this->sub_dkim_selector;
		//$mail->DKIM_identity = 'default';
		$mail->DKIM_passphrase = $this->sub_dkim_passphrase;
		$mail->DKIM_domain = $this->sub_dkim_domain;
		$mail->DKIM_private = LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private;
	}
	
	$mail->SetLanguage("tr", 'language/');
	$mail->Priority = 3;
	$mail->Encoding = 'base64';
	$mail->CharSet = "utf-8";
	if($this->sub_mail_type==0){$mail->IsHTML(true);$mail->ContentType = "text/html";}else{$mail->ContentType = "text/plain";}
	$mail->SetFrom($this->sub_from_mail, $name = $this->sub_from_title);
	$mail->AddReplyTo($this->sub_reply_mail, $this->sub_from_title);
	if($this->sub_mail_attach!=''){
		$attData = curl_get_result($this->sub_mail_attach);
		if($attData===false){
			# File Error
		}else{
			$mail->AddStringAttachment($attData,basename($this->sub_mail_attach),$encoding = 'base64',$type = 'application/octet-stream');
		}
	}
	
	# ** Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		# *************************************************************************
		/* Clear Mails */
		$mail->clearAddresses();
		$mail->clearCustomHeaders();
		$mail->clearAllRecipients();
		$mail->AddAddress($key, $value['name']);
		$mail->addCustomHeader("X-Lethe-Receiver: " . $key);
		$mail->addCustomHeader("X-Lethe-ID: " . $this->sub_mail_id);
		$mail->addCustomHeader("X-Mailer: Lethe Newsletter v" . LETHE_VERSION . ' http://www.newslether.com/');
		$mail->addCustomHeader("X-Mailer: Powered by Artlantis Design Studio http://www.artlantis.net/");
		$mail->Subject  =  $value['subject'];
		$mail->AltBody = $value['altbody'];
		$mail->MsgHTML($value['body']);
		
		/* Send Error */
		if(!$mail->Send()){
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = true;
		}

		# *************************************************************************
	}
	# **

}
# ********************************************************************************************************************************
else if($this->sub_send_method==2){ # Amazon SES


	$mail = new PHPMailer();
	$mail->IsSMTP();
	
	$mail->Host     = LETHE_AWS_HOST;
	$mail->SMTPAuth = $this->sub_smtp_auth;
	$mail->SMTPDebug = $this->sub_isDebug;
	$mail->Debugoutput = 'html';
	$mail->SMTPKeepAlive = true;
	$mail->SMTPSecure = 'tls';
	$mail->Username = $this->sub_aws_access_key;
	$mail->Password = $this->sub_aws_secret_key;
	$mail->Port = 465;
	
	if($this->sub_dkim_active){
		$mail->DKIM_selector = $this->sub_dkim_selector;
		//$mail->DKIM_identity = 'default';
		$mail->DKIM_passphrase = $this->sub_dkim_passphrase;
		$mail->DKIM_domain = $this->sub_dkim_domain;
		$mail->DKIM_private = LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private;
	}
	
	$mail->SetLanguage("tr", 'language/');
	$mail->Priority = 3;
	$mail->Encoding = 'base64';
	$mail->CharSet = "utf-8";
	if($this->sub_mail_type==0){$mail->IsHTML(true);$mail->ContentType = "text/html";}else{$mail->ContentType = "text/plain";}
	$mail->SetFrom($this->sub_from_mail, $name = $this->sub_from_title);
	$mail->AddReplyTo($this->sub_reply_mail, $this->sub_from_title);
	if($this->sub_mail_attach!=''){
		$attData = curl_get_result($this->sub_mail_attach);
		if($attData===false){
			# File Error
		}else{
			$mail->AddStringAttachment($attData,basename($this->sub_mail_attach),$encoding = 'base64',$type = 'application/octet-stream');
		}
	}
	
	# ** Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		# *************************************************************************
		/* Clear Mails */
		$mail->clearAddresses();
		$mail->clearCustomHeaders();
		$mail->clearAllRecipients();
		$mail->AddAddress($key, $value['name']);
		$mail->addCustomHeader("X-Lethe-Receiver: " . $key);
		$mail->addCustomHeader("X-Lethe-ID: " . $this->sub_mail_id);
		$mail->addCustomHeader("X-Mailer: Lethe Newsletter v" . LETHE_VERSION . ' http://www.newslether.com/');
		$mail->addCustomHeader("X-Mailer: Powered by Artlantis Design Studio http://www.artlantis.net/");
		$mail->Subject  =  $value['subject'];
		$mail->AltBody = $value['altbody'];
		$mail->MsgHTML($value['body']);
		
		/* Send Error */
		if(!$mail->Send()){
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = true;
		}

		# *************************************************************************
	}
	# **

}
# ********************************************************************************************************************************
else if($this->sub_send_method==3){ # Mandrill


	$mail = new PHPMailer();
	$mail->IsSMTP();
	
	$mail->Host     = 'smtp.mandrillapp.com';
	$mail->SMTPAuth = true;
	$mail->SMTPDebug = $this->sub_isDebug;
	$mail->Debugoutput = 'html';
	$mail->SMTPKeepAlive = true;
	$mail->SMTPSecure = 'tls';
	$mail->Username = $this->sub_mandrill_user;
	$mail->Password = $this->sub_mandrill_key;
	$mail->Port = 587;
	
	if($this->sub_dkim_active){
		$mail->DKIM_selector = $this->sub_dkim_selector;
		//$mail->DKIM_identity = 'default';
		$mail->DKIM_passphrase = $this->sub_dkim_passphrase;
		$mail->DKIM_domain = $this->sub_dkim_domain;
		$mail->DKIM_private = LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private;
	}
	
	$mail->SetLanguage("tr", 'language/');
	$mail->Priority = 3;
	$mail->Encoding = 'base64';
	$mail->CharSet = "utf-8";
	if($this->sub_mail_type==0){$mail->IsHTML(true);$mail->ContentType = "text/html";}else{$mail->ContentType = "text/plain";}
	$mail->SetFrom($this->sub_from_mail, $name = $this->sub_from_title);
	$mail->AddReplyTo($this->sub_reply_mail, $this->sub_from_title);
	if($this->sub_mail_attach!=''){
		$attData = curl_get_result($this->sub_mail_attach);
		if($attData===false){
			# File Error
		}else{
			$mail->AddStringAttachment($attData,basename($this->sub_mail_attach),$encoding = 'base64',$type = 'application/octet-stream');
		}
	}
	
	# ** Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		# *************************************************************************
		/* Clear Mails */
		$mail->clearAddresses();
		$mail->clearCustomHeaders();
		$mail->clearAllRecipients();
		$mail->AddAddress($key, $value['name']);
		$mail->addCustomHeader("X-Lethe-Receiver: " . $key);
		$mail->addCustomHeader("X-Lethe-ID: " . $this->sub_mail_id);
		$mail->addCustomHeader("X-Mailer: Lethe Newsletter v" . LETHE_VERSION . ' http://www.newslether.com/');
		$mail->addCustomHeader("X-Mailer: Powered by Artlantis Design Studio http://www.artlantis.net/");
		$mail->Subject  =  $value['subject'];
		$mail->AltBody = $value['altbody'];
		$mail->MsgHTML($value['body']);
		
		/* Send Error */
		if(!$mail->Send()){
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = true;
		}
		
		# *************************************************************************
	}
	# **

}
# ********************************************************************************************************************************
else if($this->sub_send_method==4){ # SendGrid
	$mail = new PHPMailer();
	$mail->IsSMTP();
	
	$mail->Host     = 'smtp.sendgrid.net';
	$mail->SMTPAuth = true;
	$mail->SMTPDebug = $this->sub_isDebug;
	$mail->Debugoutput = 'html';
	$mail->SMTPKeepAlive = true;
	$mail->SMTPSecure = 'tls';
	$mail->Username = $this->sub_sendgrid_user;
	$mail->Password = $this->sub_sendgrid_pass;
	$mail->Port = 587;
	
	if($this->sub_dkim_active){
		$mail->DKIM_selector = $this->sub_dkim_selector;
		//$mail->DKIM_identity = 'default';
		$mail->DKIM_passphrase = $this->sub_dkim_passphrase;
		$mail->DKIM_domain = $this->sub_dkim_domain;
		$mail->DKIM_private = LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private;
	}
	
	$mail->SetLanguage("tr", 'language/');
	$mail->Priority = 3;
	$mail->Encoding = 'base64';
	$mail->CharSet = "utf-8";
	if($this->sub_mail_type==0){$mail->IsHTML(true);$mail->ContentType = "text/html";}else{$mail->ContentType = "text/plain";}
	$mail->SetFrom($this->sub_from_mail, $name = $this->sub_from_title);
	$mail->AddReplyTo($this->sub_reply_mail, $this->sub_from_title);
	if($this->sub_mail_attach!=''){
		$attData = curl_get_result($this->sub_mail_attach);
		if($attData===false){
			# File Error
		}else{
			$mail->AddStringAttachment($attData,basename($this->sub_mail_attach),$encoding = 'base64',$type = 'application/octet-stream');
		}
	}

	# ** Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		# *************************************************************************
		/* Clear Mails */
		$mail->clearAddresses();
		$mail->clearCustomHeaders();
		$mail->clearAllRecipients();
		$mail->AddAddress($key, $value['name']);
		$mail->addCustomHeader("X-Lethe-Receiver: " . $key);
		$mail->addCustomHeader("X-Lethe-ID: " . $this->sub_mail_id);
		$mail->addCustomHeader("X-Mailer: Lethe Newsletter v" . LETHE_VERSION . ' http://www.newslether.com/');
		$mail->addCustomHeader("X-Mailer: Powered by Artlantis Design Studio http://www.artlantis.net/");
		$mail->Subject  =  $value['subject'];
		$mail->AltBody = $value['altbody'];
		$mail->MsgHTML($value['body']);
		
		/* Send Error */
		if(!$mail->Send()){
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = $mail->ErrorInfo;
			$this->sendPos = true;
		}
		
		# *************************************************************************
	}
	# **
}
?>