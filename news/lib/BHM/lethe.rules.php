<?php 
 /*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 12.03.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */

$rule_categories = $LETHE_BOUNCE_TYPES;

$bmh_newline = '<br>';

function bmhBodyRules($body,$structure='',$debug_mode=0){
	
  $result = array(
     'email'       => ''
    ,'bounce_type' => false
    ,'remove'      => 0
    ,'rule_cat'    => 'unrecognized'
    ,'rule_no'     => '0000'
  );
  
  if (false) {
  }
  
  /*
   * rule: mailbox unknown;
   * sample:
   * xxxxx@yourdomain.com
   * no such address here
   */
  elseif (preg_match ("/(\S+@\S+\w).*\n?.*no such address here/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0237';
    $result['email']       = $match[1];
  }

  /*
   * <xxxxx@yourdomain.com>:
   * 111.111.111.111 does not like recipient.
   * Remote host said: 550 User unknown
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*\n?.*user unknown/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0236';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknown;
   * sample:
   * <xxxxx@yourdomain.com>:
   * Sorry, no mailbox here by that name. vpopmail (#5.1.1)
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*no mailbox/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0157';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknown;
   * sample:
   * xxxxx@yourdomain.com<br>
   * local: Sorry, can't find user's mailbox. (#5.1.1)<br>
   */
  elseif (preg_match ("/(\S+@\S+\w)<br>.*\n?.*\n?.*can't find.*mailbox/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0164';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknown;
   * sample:
   *     ##########################################################
   *     #  This is an automated response from a mail delivery    #
   *     #  program.  Your message could not be delivered to      #
   *     #  the following address:                                #
   *     #                                                        #
   *     #      "|/usr/local/bin/mailfilt -u #dkms"               #
   *     #        (reason: Can't create output)                   #
   *     #        (expanded from: <xxxxx@yourdomain.com>)         #
   *     #                                                        #
   */
  elseif (preg_match ("/Can't create output.*\n?.*<(\S+@\S+\w)>/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0169';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknown;
   * sample:
   * ????????????????:
   * xxxxx@yourdomain.com : ????, ?????.
   */
  elseif (preg_match ("/(\S+@\S+\w).*=D5=CA=BA=C5=B2=BB=B4=E6=D4=DA/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0174';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknown;
   * sample:
   * xxxxx@yourdomain.com
   * Unrouteable address
   */
  elseif (preg_match ("/(\S+@\S+\w).*\n?.*Unrouteable address/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0179';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknow;
   * sample:
   * Delivery to the following recipients failed.
   * xxxxx@yourdomain.com
   */
  elseif (preg_match ("/delivery[^\n\r]+failed\S*\s+(\S+@\S+\w)\s/is",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0013';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknow;
   * sample:
   * A message that you sent could not be delivered to one or more of its^M
   * recipients. This is a permanent error. The following address(es) failed:^M
   * ^M
   * xxxxx@yourdomain.com^M
   * unknown local-part "xxxxx" in domain "yourdomain.com"^M
   */
  elseif (preg_match ("/(\S+@\S+\w).*\n?.*unknown local-part/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0232';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknow;
   * sample:
   * <xxxxx@yourdomain.com>:^M
   * 111.111.111.11 does not like recipient.^M
   * Remote host said: 550 Invalid recipient: <xxxxx@yourdomain.com>^M
   */
  elseif (preg_match ("/Invalid.*(?:alias|account|recipient|address|email|mailbox|user).*<(\S+@\S+\w)>/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0233';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknow;
   * sample:
   * Sent >>> RCPT TO: <xxxxx@yourdomain.com>^M
   * Received <<< 550 xxxxx@yourdomain.com... No such user^M
   * ^M
   * Could not deliver mail to this user.^M
   * xxxxx@yourdomain.com^M
   * *****************     End of message     ***************^M
   */
  elseif (preg_match ("/\s(\S+@\S+\w).*No such.*(?:alias|account|recipient|address|email|mailbox|user)>/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0234';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox unknow;
   * sample:
   * <xxxxx@yourdomain.com>:^M
   * This address no longer accepts mail.
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*(?:alias|account|recipient|address|email|mailbox|user).*no.*accept.*mail>/i",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0235';
    $result['email']       = $match[1];
  }

  /*
   * rule: full
   * sample 1:
   * <xxxxx@yourdomain.com>:
   * This account is over quota and unable to receive mail.
   * sample 2:
   * <xxxxx@yourdomain.com>:
   * Warning: undefined mail delivery mode: normal (ignored).
   * The users mailfolder is over the allowed quota (size). (#5.2.2)
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*\n?.*over.*quota/i",$body,$match)) {
    $result['rule_cat']    = 'full';
    $result['rule_no']     = '0182';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox full;
   * sample:
   *   ----- Transcript of session follows -----
   * mail.local: /var/mail/2b/10/kellen.lee: Disc quota exceeded
   * 554 <xxxxx@yourdomain.com>... Service unavailable
   */
  elseif (preg_match ("/quota exceeded.*\n?.*<(\S+@\S+\w)>/i",$body,$match)) {
    $result['rule_cat']    = 'full';
    $result['rule_no']     = '0126';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox full;
   * sample:
   * Hi. This is the qmail-send program at 263.domain.com.
   * <xxxxx@yourdomain.com>:
   * - User disk quota exceeded. (#4.3.0)
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*quota exceeded/i",$body,$match)) {
    $result['rule_cat']    = 'full';
    $result['rule_no']     = '0158';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox full;
   * sample:
   * xxxxx@yourdomain.com
   * mailbox is full (MTA-imposed quota exceeded while writing to file /mbx201/mbx011/A100/09/35/A1000935772/mail/.inbox):
   */
  elseif (preg_match ("/\s(\S+@\S+\w)\s.*\n?.*mailbox.*full/i",$body,$match)) {
    $result['rule_cat']    = 'full';
    $result['rule_no']     = '0166';
    $result['email']       = $match[1];
  }

  /*
   * rule: mailbox full;
   * sample:
   * The message to xxxxx@yourdomain.com is bounced because : Quota exceed the hard limit
   */
  elseif (preg_match ("/The message to (\S+@\S+\w)\s.*bounce.*Quota exceed/i",$body,$match)) {
    $result['rule_cat']    = 'full';
    $result['rule_no']     = '0168';
    $result['email']       = $match[1];
  }

  /*
   * rule: inactive
   * sample:
   * xxxxx@yourdomain.com<br>
   * 553 user is inactive (eyou mta)
   */
  elseif (preg_match ("/(\S+@\S+\w)<br>.*\n?.*\n?.*user is inactive/i",$body,$match)) {
    $result['rule_cat']    = 'inactive';
    $result['rule_no']     = '0171';
    $result['email']       = $match[1];
  }

  /*
   * rule: inactive
   * sample:
   * xxxxx@yourdomain.com [Inactive account]
   */
  elseif (preg_match ("/(\S+@\S+\w).*inactive account/i",$body,$match)) {
    $result['rule_cat']    = 'inactive';
    $result['rule_no']     = '0181';
    $result['email']       = $match[1];
  }

  /*
   * rule: internal_error
   * sample:
   * <xxxxx@yourdomain.com>:
   * Unable to switch to /var/vpopmail/domains/domain.com: input/output error. (#4.3.0)
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*input\/output error/i",$body,$match)) {
    $result['rule_cat']    = 'internal_error';
    $result['rule_no']     = '0172';
    $result['bounce_type'] = 'hard';
    $result['remove']      = 1;
    $result['email']       = $match[1];
  }

  /*
   * rule: internal_error
   * sample:
   * <xxxxx@yourdomain.com>:
   * can not open new email file errno=13 file=/home/vpopmail/domains/fromc.com/0/domain/Maildir/tmp/1155254417.28358.mx05,S=212350
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*can not open new email file/i",$body,$match)) {
    $result['rule_cat']    = 'internal_error';
    $result['rule_no']     = '0173';
    $result['bounce_type'] = 'hard';
    $result['remove']      = 1;
    $result['email']       = $match[1];
  }

  /*
   * rule: defer
   * sample:
   * <xxxxx@yourdomain.com>:
   * 111.111.111.111 failed after I sent the message.
   * Remote host said: 451 mta283.mail.scd.yahoo.com Resources temporarily unavailable. Please try again later [#4.16.5].
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*\n?.*Resources temporarily unavailable/i",$body,$match)) {
    $result['rule_cat']    = 'defer';
    $result['rule_no']     = '0163';
    $result['email']       = $match[1];
  }

  /*
   * rule: autoreply
   * sample:
   * AutoReply message from xxxxx@yourdomain.com
   */
  elseif (preg_match ("/^AutoReply message from (\S+@\S+\w)/i",$body,$match)) {
    $result['rule_cat']    = 'autoreply';
    $result['rule_no']     = '0167';
    $result['email']       = $match[1];
  }

  /*
   * rule: western chars only
   * sample:
   * <xxxxx@yourdomain.com>:
   * The user does not accept email in non-Western (non-Latin) character sets.
   */
  elseif (preg_match ("/<(\S+@\S+\w)>.*\n?.*does not accept[^\r\n]*non-Western/i",$body,$match)) {
    $result['rule_cat']    = 'latin_only';
    $result['rule_no']     = '0043';
    $result['email']       = $match[1];
  }
  
  /*
   * rule: Unknow body
   * sample:
   * /var/mail/nobody:
   * Unknow body.
   */
  elseif (preg_match ("/^\/var\/mail\/nobody/im",$body,$match)) {
    $result['rule_cat']    = 'unknown';
    $result['rule_no']     = '0043';
    $result['email']       = $match[0];
  }
  
  /*
   * rule: Message Delivery Failure
   * sample:
   * Message Delivery Failure - E-Mail Verification:
   * Unknow body.
   */
  elseif (preg_match ("/^message delivery failure/im",$body,$match)) {
    $result['rule_cat']    = 'dns_unknown';
    $result['rule_no']     = '0047';
    $result['email']       = $match[0];
  }
  
  /*
   * rule: 550 5.7.1
   * sample:
   * Message Delivery Failure - E-Mail Verification:
   * Unknow body.
   */
  elseif (preg_match ("/^550 5\.7\.1/im",$body,$match)) {
    $result['rule_cat']    = 'dns_unknown';
    $result['rule_no']     = '0047';
    $result['email']       = $match[0];
  }
  
  /*
   * rule: %G%#%l%/%H%j+$+$^$;!#
   * sample:
   * Message Delivery Failure - E-Mail Verification:
   * Unknow body.
   */
  elseif (preg_match ("/%G%\#%l%\/%H%j\+\$\+\$\^\$;!\#/imu",$body,$match)) {
    $result['rule_cat']    = 'dns_unknown';
    $result['rule_no']     = '0047';
    $result['email']       = $match[0];
  }
  
  /*
   * rule: 550 5.7.1
   * sample:
   * 550 5.7.1
   * Unknow body.
   */
  elseif (preg_match ("/550 5.7.1/im",$body,$match)) {
    $result['rule_cat']    = 'dns_unknown';
    $result['rule_no']     = '0047';
    $result['email']       = $match[0];
  }
  
  /*
   * rule: 550 access denied
   * sample:
   * 550 access denied
   * Unknow body.
   */
  elseif (preg_match ("/550 access denied/im",$body,$match)) {
    $result['rule_cat']    = 'dns_unknown';
    $result['rule_no']     = '0047';
    $result['email']       = $match[0];
  }

  global $rule_categories, $bmh_newline;
  if ($result['rule_no'] == '0000') {
    if ($debug_mode) {
      echo 'Body:' . $bmh_newline . $body . $bmh_newline;
      echo $bmh_newline;
    }
  } else {
    if ($result['bounce_type'] === false) {
      $result['bounce_type'] = $rule_categories[$result['rule_cat']]['bounce_type'];
      $result['remove']      = $rule_categories[$result['rule_cat']]['remove'];
    }
  }
  return $result;
	
}
?>