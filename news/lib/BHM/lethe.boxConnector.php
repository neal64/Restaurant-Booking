<?php 
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 11.03.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
# ** Mailbox Controllers
	# Mailbox Connector
		function pop3_login($host,$port,$user,$pass,$folder="INBOX",$ssl='') 
		{ 
			// $ssl=($ssl==false)?"/novalidate-cert":""; 
			return (imap_open("{"."$host:$port/pop3". $ssl .""."}$folder",$user,$pass,OP_SILENT)); 
		} 
	# Mailbox Statistic
		function pop3_stat($connection)        
		{ 
			$check = @imap_mailboxmsginfo($connection); 
			return ((array)$check); 
		} 
	# Mailbox Post List
		function pop3_list($connection,$message="") 
		{ 
			 if(!isset($result)){$result=array();}
			if ($message) 
			{ 
				$range=$message; 
			} else { 
				$MC = @imap_check($connection); 
				if($MC){
					$range = "1:".$MC->Nmsgs;
				}else{
					$range = "1:0";
				}
			} 
			$response = @imap_fetch_overview($connection,$range);
			if($response){
				$result = array();
				foreach ($response as $msg)$result[$msg->msgno]=(array)$msg;
			}else{
				$result = array();
			}
				return $result; 
		} 
	# Mailbox Post Header Fetch
		function pop3_retr($connection,$message) 
		{ 
			return(imap_fetchheader($connection,$message,FT_PREFETCHTEXT)); 
		} 
	# Mailbox Post Remover
		function pop3_dele($connection,$message) 
		{ 
			return(imap_delete($connection,$message) or false); 
		} 
	# Message Structure With Rule
		function bodyDecoding($body,$etype){
			if($etype=='base64'){
				return base64_decode($body);
			}
			else if($etype=='quoted-printable'){
				return quoted_printable_decode($body);
			}else{
				return $body;
			}
		}
?>