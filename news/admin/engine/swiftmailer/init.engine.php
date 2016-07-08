<?php
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 17.04.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
@set_time_limit(0);
require_once dirname(__FILE__).'/swift_required.php';

if($this->sub_send_method==0){ # SMTP

	$transport = Swift_SmtpTransport::newInstance();
	$transport->setHost($this->sub_smtp_host);
	$transport->setPort($this->sub_smtp_port);
	$transport->setUsername($this->sub_smtp_user);
	$transport->setPassword($this->sub_smtp_pass);
	if($this->sub_smtp_secure==1){# SSL
		$transport->setEncryption('ssl');
	}else if($this->sub_smtp_secure==2){# TLS
		$transport->setEncryption('tls');
	}
	
	# Create Mailer
	$mailer = Swift_Mailer::newInstance($transport);
	
	# DKIM
	if($this->sub_dkim_active){
		$privateKey = file_get_contents(LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private);
		$domainName = $this->sub_dkim_domain;
		$selector = $this->sub_dkim_selector;
		$signer = new Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
		$message = Swift_SignedMessage::newInstance();
		$message->attachSigner($signer);
	}else{
		$message = Swift_Message::newInstance();
	}
	

	# Create a message
	$message->setEncoder(Swift_Encoding::getBase64Encoding());
	$message->setReplyTo(array($this->sub_reply_mail => $this->sub_from_title));
	$message->setCharset('utf-8');
	$message->setPriority(3);
	$message->setFrom(array($this->sub_from_mail => $this->sub_from_title));
	if($this->sub_mail_attach!=''){
		$message->attach(Swift_Attachment::fromPath($this->sub_mail_attach)->setFilename(basename($this->sub_mail_attach))->setContentType('application/octet-stream'));
	}
	$headers = $message->getHeaders();
	$headers->addTextHeader('X-Mailer','Lethe Newsletter v' . LETHE_VERSION . ' http://www.newslether.com/');
	$headers->addTextHeader('X-Mailer','Powered by Artlantis Design Studio http://www.artlantis.net/');
	$headers->addTextHeader('X-Lethe-ID',$this->sub_mail_id);
	$headers->addTextHeader('X-Lethe-Receiver','');
	
	# Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		$message->setTo(array($key => $value['name']));
		$message->setSubject($value['subject']);
		$message->setBody($value['body'],'text/html');
		$message->addPart($value['altbody'], 'text/plain');
		
		# Change Header
		$recHeader = $headers->get('X-Lethe-Receiver');
		$recHeader->setValue($key);
		
		# Send Message
		if(!$mailer->send($message)){
			$this->sendingErrors = 'Messages could not be sent!';
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = 'Sent successfully';
			$this->sendPos = true;
		}
	}

}
# ********************************************************************************************************************************
else if($this->sub_send_method==1){ # PHPMail

	$transport = Swift_MailTransport::newInstance();
	
	# Create Mailer
	$mailer = Swift_Mailer::newInstance($transport);

	# DKIM
	if($this->sub_dkim_active){
		$privateKey = file_get_contents(LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private);
		$domainName = $this->sub_dkim_domain;
		$selector = $this->sub_dkim_selector;
		$signer = new Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
		$message = Swift_SignedMessage::newInstance();
		$message->attachSigner($signer);
	}else{
		$message = Swift_Message::newInstance();
	}	

	# Create a message
	$message->setEncoder(Swift_Encoding::getBase64Encoding());
	$message->setReplyTo(array($this->sub_reply_mail => $this->sub_from_title));
	$message->setCharset('utf-8');
	$message->setPriority(3);
	$message->setFrom(array($this->sub_from_mail => $this->sub_from_title));
	if($this->sub_mail_attach!=''){
		$message->attach(Swift_Attachment::fromPath($this->sub_mail_attach)->setFilename(basename($this->sub_mail_attach))->setContentType('application/octet-stream'));
	}
	$headers = $message->getHeaders();
	$headers->addTextHeader('X-Mailer','Lethe Newsletter v' . LETHE_VERSION . ' http://www.newslether.com/');
	$headers->addTextHeader('X-Mailer','Powered by Artlantis Design Studio http://www.artlantis.net/');
	$headers->addTextHeader('X-Lethe-ID',$this->sub_mail_id);
	$headers->addTextHeader('X-Lethe-Receiver','');
	
	# Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		$message->setTo(array($key => $value['name']));
		$message->setSubject($value['subject']);
		$message->setBody($value['body'],'text/html');
		$message->addPart($value['altbody'], 'text/plain');
		
		# Change Header
		$recHeader = $headers->get('X-Lethe-Receiver');
		$recHeader->setValue($key);
		
		# Send Message
		if(!$mailer->send($message)){
			$this->sendingErrors = 'Messages could not be sent!';
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = 'Sent successfully';
			$this->sendPos = true;
		}
	}

}
# ********************************************************************************************************************************
else if($this->sub_send_method==2){ # Amazon SES

	$transport = Swift_SmtpTransport::newInstance();
	$transport->setHost(LETHE_AWS_HOST);
	$transport->setPort(465);
	$transport->setUsername($this->sub_aws_access_key);
	$transport->setPassword($this->sub_aws_secret_key);
	$transport->setEncryption('tls');
	
	# Create Mailer
	$mailer = Swift_Mailer::newInstance($transport);
	
	# DKIM
	if($this->sub_dkim_active){
		$privateKey = file_get_contents(LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private);
		$domainName = $this->sub_dkim_domain;
		$selector = $this->sub_dkim_selector;
		$signer = new Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
		$message = Swift_SignedMessage::newInstance();
		$message->attachSigner($signer);
	}else{
		$message = Swift_Message::newInstance();
	}
	

	# Create a message
	$message->setEncoder(Swift_Encoding::getBase64Encoding());
	$message->setReplyTo(array($this->sub_reply_mail => $this->sub_from_title));
	$message->setCharset('utf-8');
	$message->setPriority(3);
	$message->setFrom(array($this->sub_from_mail => $this->sub_from_title));
	if($this->sub_mail_attach!=''){
		$message->attach(Swift_Attachment::fromPath($this->sub_mail_attach)->setFilename(basename($this->sub_mail_attach))->setContentType('application/octet-stream'));
	}
	$headers = $message->getHeaders();
	$headers->addTextHeader('X-Mailer','Lethe Newsletter v' . LETHE_VERSION . ' http://www.newslether.com/');
	$headers->addTextHeader('X-Mailer','Powered by Artlantis Design Studio http://www.artlantis.net/');
	$headers->addTextHeader('X-Lethe-ID',$this->sub_mail_id);
	$headers->addTextHeader('X-Lethe-Receiver','');
	
	# Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		$message->setTo(array($key => $value['name']));
		$message->setSubject($value['subject']);
		$message->setBody($value['body'],'text/html');
		$message->addPart($value['altbody'], 'text/plain');
		
		# Change Header
		$recHeader = $headers->get('X-Lethe-Receiver');
		$recHeader->setValue($key);
		
		# Send Message
		if(!$mailer->send($message)){
			$this->sendingErrors = 'Messages could not be sent!';
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = 'Sent successfully';
			$this->sendPos = true;
		}
	}

}
# ********************************************************************************************************************************
else if($this->sub_send_method==3){ # Mandrill

	$transport = Swift_SmtpTransport::newInstance();
	$transport->setHost('smtp.mandrillapp.com');
	$transport->setPort(587);
	$transport->setUsername($this->sub_mandrill_user);
	$transport->setPassword($this->sub_mandrill_key);
	$transport->setEncryption('tls');
	
	# Create Mailer
	$mailer = Swift_Mailer::newInstance($transport);
	
	# DKIM
	if($this->sub_dkim_active){
		$privateKey = file_get_contents(LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private);
		$domainName = $this->sub_dkim_domain;
		$selector = $this->sub_dkim_selector;
		$signer = new Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
		$message = Swift_SignedMessage::newInstance();
		$message->attachSigner($signer);
	}else{
		$message = Swift_Message::newInstance();
	}
	

	# Create a message
	$message->setEncoder(Swift_Encoding::getBase64Encoding());
	$message->setReplyTo(array($this->sub_reply_mail => $this->sub_from_title));
	$message->setCharset('utf-8');
	$message->setPriority(3);
	$message->setFrom(array($this->sub_from_mail => $this->sub_from_title));
	if($this->sub_mail_attach!=''){
		$message->attach(Swift_Attachment::fromPath($this->sub_mail_attach)->setFilename(basename($this->sub_mail_attach))->setContentType('application/octet-stream'));
	}
	$headers = $message->getHeaders();
	$headers->addTextHeader('X-Mailer','Lethe Newsletter v' . LETHE_VERSION . ' http://www.newslether.com/');
	$headers->addTextHeader('X-Mailer','Powered by Artlantis Design Studio http://www.artlantis.net/');
	$headers->addTextHeader('X-Lethe-ID',$this->sub_mail_id);
	$headers->addTextHeader('X-Lethe-Receiver','');
	
	# Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		$message->setTo(array($key => $value['name']));
		$message->setSubject($value['subject']);
		$message->setBody($value['body'],'text/html');
		$message->addPart($value['altbody'], 'text/plain');
		
		# Change Header
		$recHeader = $headers->get('X-Lethe-Receiver');
		$recHeader->setValue($key);
		
		# Send Message
		if(!$mailer->send($message)){
			$this->sendingErrors = 'Messages could not be sent!';
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = 'Sent successfully';
			$this->sendPos = true;
		}
	}

}

# ********************************************************************************************************************************
else if($this->sub_send_method==4){ # SendGrid

	$transport = Swift_SmtpTransport::newInstance();
	$transport->setHost('smtp.sendgrid.net');
	$transport->setPort(587);
	$transport->setUsername($this->sub_sendgrid_user);
	$transport->setPassword($this->sub_sendgrid_pass);
	$transport->setEncryption('tls');
	
	# Create Mailer
	$mailer = Swift_Mailer::newInstance($transport);
	
	# DKIM
	if($this->sub_dkim_active){
		$privateKey = file_get_contents(LETHE_KEY_STORE.DIRECTORY_SEPARATOR.$this->sub_dkim_private);
		$domainName = $this->sub_dkim_domain;
		$selector = $this->sub_dkim_selector;
		$signer = new Swift_Signers_DKIMSigner($privateKey, $domainName, $selector);
		$message = Swift_SignedMessage::newInstance();
		$message->attachSigner($signer);
	}else{
		$message = Swift_Message::newInstance();
	}
	

	# Create a message
	$message->setEncoder(Swift_Encoding::getBase64Encoding());
	$message->setReplyTo(array($this->sub_reply_mail => $this->sub_from_title));
	$message->setCharset('utf-8');
	$message->setPriority(3);
	$message->setFrom(array($this->sub_from_mail => $this->sub_from_title));
	if($this->sub_mail_attach!=''){
		$message->attach(Swift_Attachment::fromPath($this->sub_mail_attach)->setFilename(basename($this->sub_mail_attach))->setContentType('application/octet-stream'));
	}
	$headers = $message->getHeaders();
	$headers->addTextHeader('X-Mailer','Lethe Newsletter v' . LETHE_VERSION . ' http://www.newslether.com/');
	$headers->addTextHeader('X-Mailer','Powered by Artlantis Design Studio http://www.artlantis.net/');
	$headers->addTextHeader('X-Lethe-ID',$this->sub_mail_id);
	$headers->addTextHeader('X-Lethe-Receiver','');
	
	# Receivers
	foreach($this->sub_mail_receiver as $key => $value){
		$message->setTo(array($key => $value['name']));
		$message->setSubject($value['subject']);
		$message->setBody($value['body'],'text/html');
		$message->addPart($value['altbody'], 'text/plain');
		
		# Change Header
		$recHeader = $headers->get('X-Lethe-Receiver');
		$recHeader->setValue($key);
		
		# Send Message
		if(!$mailer->send($message)){
			$this->sendingErrors = 'Messages could not be sent!';
			$this->sendPos = false;
		}else{
			/* Sent Done */
			$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=daily_sent+1 WHERE ID=". $this->OSMID ."");
			if($this->OID!=0){
				$myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=daily_sent+1 WHERE ID=". $this->OID ."");
			}
			$this->sendingErrors = 'Sent successfully';
			$this->sendPos = true;
		}
	}

}
?>