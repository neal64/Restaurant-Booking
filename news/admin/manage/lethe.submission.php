<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 01.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
if(!isset($pgnt)){die('You are not authorized to view this page!');}
/* Requests */
if(!isset($_GET['ID']) || !is_numeric($_GET['ID'])){$ID=0;}else{$ID=intval($_GET['ID']);}

/* Demo Check */
if(!isDemo('addAccount,editAccount')){$errText = errMod(letheglobal_demo_mode_active,'danger');}

if(isset($_POST['addAccount'])){ # Add Submission Account

	$myLethe = new lethe();
	$myLethe->auth_mode = 2;
	$myLethe->addSubAccount();
	$errText = $myLethe->errPrint;

}

if(isset($_POST['editAccount'])){ # Edit Submission Account

	$myLethe = new lethe();
	$myLethe->ID = (int)$ID;
	$myLethe->editSubAccount();
	$errText = $myLethe->errPrint;

}
?>

	<?php if($page_sub2=='add'){
		echo($errText);?>
	<!-- Add Submission Account Start -->
		<form action="" method="POST">
			<div role="tabpanel">

			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo(letheglobal_general_settings);?></a></li>
				<li role="presentation"><a href="#sending" aria-controls="sending" role="tab" data-toggle="tab"><?php echo(settings_sending);?></a></li>
				<li role="presentation"><a href="#connection" aria-controls="connection" role="tab" data-toggle="tab"><?php echo(settings_connection);?></a></li>
				<li role="presentation"><a href="#dkim" aria-controls="dkim" role="tab" data-toggle="tab">DKIM</a></li>
				<li role="presentation"><a href="#bounce" aria-controls="bounce" role="tab" data-toggle="tab">Bounce</a></li>
				<li role="presentation"><a href="#save" aria-controls="save" role="tab" data-toggle="tab"><?php echo(letheglobal_save);?></a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="general">
					<!-- General -->
					&nbsp;
					<div class="form-group">
						<label for="acc_title"><?php echo(sh('lzBaNskLnE').settings_account_title);?></label>
						<input type="text" name="acc_title" id="acc_title" value="<?php echo(((isset($_POST['acc_title'])) ? showIn($_POST['acc_title'],'input'):''));?>" size="40" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="spec_limit_range"><?php echo(sh('AzHt5OeYZK').settings_limit_range);?></label>
						<select name="spec_limit_range" id="spec_limit_range" class="form-control autoWidth">
							<option value="1"<?php echo(formSelector(((isset($_POST['spec_limit_range'])) ? $_POST['spec_limit_range']:''),1,0));?>><?php echo(settings_per_minute);?></option>
							<option value="60"<?php echo(formSelector(((isset($_POST['spec_limit_range'])) ? $_POST['spec_limit_range']:''),60,0));?>><?php echo(settings_per_hour);?></option>
							<option value="1440"<?php echo(formSelector(((isset($_POST['spec_limit_range'])) ? $_POST['spec_limit_range']:''),1440,0));?>><?php echo(settings_per_day);?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="daily_limit"><?php echo(sh('OIuOGGeyfP').letheglobal_limits);?></label>
						<div class="input-group">
							<input onkeydown="validateNumber(event);" type="number" name="daily_limit" id="daily_limit" value="<?php echo(((isset($_POST['daily_limit'])) ? showIn($_POST['daily_limit'],'input'):''));?>" class="form-control">
							<span class="input-group-addon autoWidth">0</span>
						</div>
					</div>
					<div class="form-group">
						<label for="send_per_conn"><?php echo(sh('f4kSQ3MNm4').settings_send_per_connection);?></label>
						<input type="number" onkeydown="validateNumber(event);" name="send_per_conn" id="send_per_conn" value="<?php echo(((isset($_POST['send_per_conn'])) ? showIn($_POST['send_per_conn'],'input'):''));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="standby_time"><?php echo(sh('HOTGIRH0eF').settings_standby_time.' ('. letheglobal_seconds .')');?></label>
						<input type="number" onkeydown="validateNumber(event);" name="standby_time" id="standby_time" value="<?php echo(((isset($_POST['standby_time'])) ? showIn($_POST['standby_time'],'input'):''));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="systemAcc"><?php echo(sh('tiC4ApBdWQ').settings_system_account);?></label>
						<div>
						<input type="checkbox" name="systemAcc" id="systemAcc" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(((isset($_POST['systemAcc'])) ? ' checked':''));?>>
						</div>
					</div>
					<div class="form-group">
						<label for="debug"><?php echo(sh('W1us3D6GOb').settings_debug_mode);?></label>
						<div>
						<input type="checkbox" name="debug" id="debug" data-on-label="ON" data-off-label="OFF" value="YES" class="letheSwitch"<?php echo(((isset($_POST['debug'])) ? ' checked':''));?>>
						</div>
					</div>
					<div class="form-group">
						<label for="active"><?php echo(sh('8Ldfvb0tGm').letheglobal_active);?></label>
						<div>
						<input type="checkbox" name="active" id="active" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(((isset($_POST['active'])) ? ' checked':''));?>>
						</div>
					</div>
					<!-- General -->
				</div>
				<div role="tabpanel" class="tab-pane fade" id="sending">
					<!-- Sending -->
					&nbsp;
					<div class="form-group">
						<label for="from_title"><?php echo(sh('kfMTnyvW8x').settings_sender_title);?></label>
						<input type="text" name="from_title" id="from_title" value="<?php echo(((isset($_POST['from_title'])) ? showIn($_POST['from_title'],'input'):''));?>" size="40" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="from_mail"><?php echo(sh('Mz5HgMq918').settings_sender_e_mail);?></label>
						<input type="email" name="from_mail" id="from_mail" value="<?php echo(((isset($_POST['from_mail'])) ? showIn($_POST['from_mail'],'input'):''));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="reply_mail"><?php echo(sh('zIo5YkkltJ').settings_reply_e_mail);?></label>
						<input type="email" name="reply_mail" id="reply_mail" value="<?php echo(((isset($_POST['reply_mail'])) ? showIn($_POST['reply_mail'],'input'):''));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="test_mail"><?php echo(sh('bcWtR8fOlU').settings_test_e_mail);?></label>
						<input type="email" name="test_mail" id="test_mail" value="<?php echo(((isset($_POST['test_mail'])) ? showIn($_POST['test_mail'],'input'):''));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="mail_type"><?php echo(sh('DwFhJWa1df').settings_e_mail_content_type);?></label>
						<select name="mail_type" id="mail_type" class="form-control autoWidth">
							<?php foreach($LETHE_MAIL_TYPE as $k=>$v){echo('<option value="'. $k .'"'. ((isset($_POST['mail_type'])) ? formSelector($_POST['mail_type'],$k,0):'') .'>'. $v .'</option>');}?>
						</select>
					</div>
					<div class="form-group">
						<label for="mail_engine"><?php echo(sh('qFyVBuUeVq').settings_e_mail_engine);?></label>
						<select name="mail_engine" id="mail_engine" class="form-control autoWidth">
							<?php foreach($LETHE_MAIL_ENGINE as $k=>$v){echo('<option value="'. $k .'"'. ((isset($_POST['mail_engine'])) ? formSelector($_POST['mail_engine'],$k,0):'') .'>'. $v['title'] .'</option>');}?>
						</select>
					</div>
					<!-- Sending -->
				</div>
				<div role="tabpanel" class="tab-pane fade" id="connection">
					<!-- Connection -->
					&nbsp;
					<div class="form-group">
						<label for="send_method"><?php echo(sh('SZhJ4IPHO1').settings_sending_method);?></label>
						<select name="send_method" id="send_method" class="form-control autoWidth">
							<?php foreach($LETHE_MAIL_METHOD as $k=>$v){echo('<option value="'. $k .'"'. ((isset($_POST['send_method'])) ? formSelector($_POST['send_method'],$k,0):'') .'>'. $v .'</option>');}?>
						</select>
					</div>
					<div class="row">
						<div class="col-md-4 mailMethod0 mailMethods">
							<h4 class="text-warning">SMTP</h4><hr>
							<div class="form-group">
								<label for="smtp_host"><?php echo(sh('0cdC8eZbXa'));?>SMTP <?php echo(settings_server);?></label>
								<input type="text" name="smtp_host" id="smtp_host" onblur="textCopier('#smtp_host','#pop3_host,#imap_host');" value="<?php echo(((isset($_POST['smtp_host'])) ? showIn($_POST['smtp_host'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="smtp_port"><?php echo(sh('rZnbloJUA7'));?>SMTP <?php echo(settings_port);?></label>
								<input type="text" onkeydown="validateNumber(event);" name="smtp_port" id="smtp_port" value="<?php echo(((isset($_POST['smtp_port'])) ? showIn($_POST['smtp_port'],'input'):''));?>" class="form-control autoWidth" placeholder="587">
							</div>
							<div class="form-group">
								<label for="smtp_user"><?php echo(sh('cwM012UEPl'));?>SMTP <?php echo(settings_username);?></label>
								<input type="text" name="smtp_user" id="smtp_user" onblur="textCopier('#smtp_user','#pop3_user,#imap_user');" value="<?php echo(((isset($_POST['smtp_user'])) ? showIn($_POST['smtp_user'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="smtp_pass"><?php echo(sh('FG4ntQOrHw'));?>SMTP <?php echo(settings_password);?></label>
								<input type="password" name="smtp_pass" id="smtp_pass" onblur="textCopier('#smtp_pass','#pop3_pass,#imap_pass');" value="<?php echo(((isset($_POST['smtp_pass'])) ? showIn($_POST['smtp_pass'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="smtp_secure"><?php echo(sh('QRIqfHX81i'));?>SMTP <?php echo(settings_encryption);?></label>
								<select name="smtp_secure" id="smtp_secure" class="form-control autoWidth">
									<?php foreach($LETHE_MAIL_SECURE as $k=>$v){echo('<option value="'. $k .'"'. ((isset($_POST['smtp_secure'])) ? formSelector($_POST['smtp_secure'],$k,0):'') .'>'. $v .'</option>');}?>
								</select>
							</div>
							<div class="form-group">
								<label for="smtp_auth"><?php echo(sh('4tQ6wgJuGC'));?>SMTP <?php echo(settings_auth);?></label>
								<div>
								<input type="checkbox" data-on-label="On" data-off-label="Off" name="smtp_auth" id="smtp_auth" value="YES" class="letheSwitch"<?php echo(((isset($_POST['smtp_auth'])) ? formSelector($_POST['smtp_auth'],'YES',1):''));?>>
								</div>
							</div>
						</div>
						<div class="mailMethod1 mailMethods"><!-- PHP MAIL --></div>
						<div class="col-md-4 mailMethod2 mailMethods">
							<h4 class="text-warning">Amazon SES</h4><hr>
							<div class="form-group">
								<label for="aws_acc_key"><?php echo(sh('1RCXxlcvFc'));?>AWS <?php echo(settings_access_key);?></label>
								<input type="text" name="aws_acc_key" id="aws_acc_key" value="<?php echo(((isset($_POST['aws_acc_key'])) ? showIn($_POST['aws_acc_key'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="aws_sec_key"><?php echo(sh('dkvLq9XY7n'));?>AWS <?php echo(settings_secret_key);?></label>
								<input type="text" name="aws_sec_key" id="aws_sec_key" value="<?php echo(((isset($_POST['aws_sec_key'])) ? showIn($_POST['aws_sec_key'],'input'):''));?>" class="form-control autoWidth">
							</div>
						</div>	
						<div class="col-md-4 mailMethod3 mailMethods">
							<h4 class="text-warning">Mandrill</h4><hr>
							<div class="form-group">
								<label for="mandrill_user"><?php echo(sh('EyvMNZKMd9'));?>Mandrill <?php echo(settings_username);?></label>
								<input type="text" name="mandrill_user" id="mandrill_user" value="<?php echo(((isset($_POST['mandrill_user'])) ? showIn($_POST['mandrill_user'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="mandrill_key"><?php echo(sh('WlVgweKMR0'));?>Mandrill <?php echo(organizations_api_key);?></label>
								<input type="text" name="mandrill_key" id="mandrill_key" value="<?php echo(((isset($_POST['mandrill_key'])) ? showIn($_POST['mandrill_key'],'input'):''));?>" class="form-control autoWidth">
							</div>
						</div>
						<div class="col-md-4 mailMethod4 mailMethods">
							<h4 class="text-warning">SendGrid</h4><hr>
							<div class="form-group">
								<label for="sendgrid_user"><?php echo(sh('JxL85Yj8Wm'));?>SendGrid <?php echo(settings_username);?></label>
								<input type="text" name="sendgrid_user" id="sendgrid_user" value="<?php echo(((isset($_POST['sendgrid_user'])) ? showIn($_POST['sendgrid_user'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="sendgrid_pass"><?php echo(sh('xVYMGvzroO'));?>SendGrid <?php echo(settings_password);?></label>
								<input type="password" name="sendgrid_pass" id="sendgrid_pass" value="" class="form-control autoWidth" autocomplete="off">
							</div>
						</div>
						<div class="col-md-4">
							<h4 class="text-warning">POP3</h4><hr>
							<div class="form-group">
								<label for="pop3_host"><?php echo(sh('TtWPeCE72I'));?>POP3 <?php echo(settings_server);?></label>
								<input type="text" name="pop3_host" id="pop3_host" value="<?php echo(((isset($_POST['pop3_host'])) ? showIn($_POST['pop3_host'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="pop3_port"><?php echo(sh('KqgpHLxzep'));?>POP3 <?php echo(settings_port);?></label>
								<input type="text" onkeydown="validateNumber(event);" name="pop3_port" id="pop3_port" value="<?php echo(((isset($_POST['pop3_port'])) ? showIn($_POST['pop3_port'],'input'):''));?>" class="form-control autoWidth" placeholder="110">
							</div>
							<div class="form-group">
								<label for="pop3_user"><?php echo(sh('6DFDWa8juT'));?>POP3 <?php echo(settings_username);?></label>
								<input type="text" name="pop3_user" id="pop3_user" value="<?php echo(((isset($_POST['pop3_user'])) ? showIn($_POST['pop3_user'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="pop3_pass"><?php echo(sh('iWy1Oy3Mme'));?>POP3 <?php echo(settings_password);?></label>
								<input type="password" name="pop3_pass" id="pop3_pass" value="<?php echo(((isset($_POST['pop3_pass'])) ? showIn($_POST['pop3_pass'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="pop3_secure"><?php echo(sh('2l1eXfRktt'));?>POP3 <?php echo(settings_encryption);?></label>
								<select name="pop3_secure" id="pop3_secure" class="form-control autoWidth">
									<?php foreach($LETHE_MAIL_SECURE as $k=>$v){echo('<option value="'. $k .'"'. ((isset($_POST['pop3_secure'])) ? formSelector($_POST['pop3_secure'],$k,0):'') .'>'. $v .'</option>');}?>
								</select>
							</div>
							<div class="form-group">
								<span><?php echo(sh('JWrihS9A0Y'));?></span><label for="bounce_acc0">Bounce <?php echo(settings_account);?></label>
								<input type="radio" name="bounce_acc" id="bounce_acc0" value="0" class="ionc"<?php echo(((isset($_POST['bounce_acc'])) ? formSelector($_POST['bounce_acc'],0,1):''));?>>
							</div>
						</div>
						<div class="col-md-4">
							<h4 class="text-warning">IMAP</h4><hr>
							<div class="form-group">
								<label for="imap_host"><?php echo(sh('xAPd9jscX0'));?>IMAP <?php echo(settings_server);?></label>
								<input type="text" name="imap_host" id="imap_host" value="<?php echo(((isset($_POST['imap_host'])) ? showIn($_POST['imap_host'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="imap_port"><?php echo(sh('o7a2mtcV9X'));?>IMAP <?php echo(settings_port);?></label>
								<input type="text" onkeydown="validateNumber(event);" name="imap_port" id="imap_port" value="<?php echo(((isset($_POST['imap_port'])) ? showIn($_POST['imap_port'],'input'):''));?>" class="form-control autoWidth" placeholder="143">
							</div>
							<div class="form-group">
								<label for="imap_user"><?php echo(sh('ZU8R27nFPB'));?>IMAP <?php echo(settings_username);?></label>
								<input type="text" name="imap_user" id="imap_user" value="<?php echo(((isset($_POST['imap_user'])) ? showIn($_POST['imap_user'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="imap_pass"><?php echo(sh('BnrEuVhsHM'));?>IMAP <?php echo(settings_password);?></label>
								<input type="password" name="imap_pass" id="imap_pass" value="<?php echo(((isset($_POST['imap_pass'])) ? showIn($_POST['imap_pass'],'input'):''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="imap_secure"><?php echo(sh('hD8up4EX8N'));?>IMAP <?php echo(settings_encryption);?></label>
								<select name="imap_secure" id="imap_secure" class="form-control autoWidth">
									<?php foreach($LETHE_MAIL_SECURE as $k=>$v){echo('<option value="'. $k .'"'. ((isset($_POST['imap_secure'])) ? formSelector($_POST['imap_secure'],$k,0):'') .'>'. $v .'</option>');}?>
								</select>
							</div>
							<div class="form-group">
								<span><?php echo(sh('JWrihS9A0Y'));?></span><label for="bounce_acc1">Bounce <?php echo(settings_account);?></label>
								<input type="radio" name="bounce_acc" id="bounce_acc1" value="1" class="ionc"<?php echo(((isset($_POST['bounce_acc'])) ? formSelector($_POST['bounce_acc'],1,1):''));?>>
							</div>
						</div>
					</div>
					<!-- Connection -->
				</div>
				<div role="tabpanel" class="tab-pane fade" id="dkim">
					&nbsp;
					<div class="form-group">
						<label for="dkimactive"><?php echo(sh('vqaWZq3msj'));?>DKIM <?php echo(letheglobal_active);?></label>
						<div>
						<input type="checkbox" name="dkimactive" id="dkimactive" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(((isset($_POST['dkimactive'])) ? ' checked':''));?>>
						</div>
					</div>
					
					<div id="dkiminfos"<?php if(!isset($_POST['dkimactive']) || $_POST['dkimactive']!='YES'){echo(' class="sHide"');}?>>
					<hr>
					
						<div class="form-group">
							<label for="dkimdomain"><?php echo(sh('YAJKSDyPku').settings_domain);?></label>
							<input type="text" class="form-control autoWidth" id="dkimdomain" name="dkimdomain" value="<?php echo(((isset($_POST['dkimdomain']) && !empty($_POST['dkimdomain'])) ? showIn($_POST['dkimdomain'],'input'):''));?>" placeholder="mydomain.com">
						</div>
						
						<div class="form-group">
							<label for="dkimprivate"><?php echo(sh('AU28Kbt2d5').settings_private_key);?></label>
							<input type="text" class="form-control autoWidth" id="dkimprivate" name="dkimprivate" value="<?php echo(((isset($_POST['dkimprivate']) && !empty($_POST['dkimprivate'])) ? showIn($_POST['dkimprivate'],'input'):''));?>">
						</div>
						
						<div class="form-group">
							<label for="dkimselector"><?php echo(sh('mnkwCznfVG').settings_selector);?></label>
							<input type="text" class="form-control autoWidth" id="dkimselector" name="dkimselector" value="<?php echo(((isset($_POST['dkimselector']) && !empty($_POST['dkimselector'])) ? showIn($_POST['dkimselector'],'input'):''));?>" placeholder="default">
						</div>
						
						<div class="form-group">
							<label for="dkimpassphrase"><?php echo(sh('P7Tk5NiINR').settings_passphrase);?></label>
							<input type="text" class="form-control autoWidth" id="dkimpassphrase" name="dkimpassphrase" value="<?php echo(((isset($_POST['dkimpassphrase']) && !empty($_POST['dkimpassphrase'])) ? showIn($_POST['dkimpassphrase'],'input'):''));?>">
						</div>
					
					</div>
					
					<script>
						$(document).ready(function(){
							$("#dkimactive").bind('change',function(){
								if($("#dkimactive").is(':checked')){
									$("#dkiminfos").removeClass("sHide");
								}else{
									$("#dkiminfos").addClass("sHide");
								}
							});
						});
					</script>
				
				</div>
				<div role="tabpanel" class="tab-pane fade" id="bounce">
					&nbsp;
					<?php 			
					foreach($LETHE_BOUNCE_TYPES as $k=>$v){
						$frmAct = ((isset($_POST['bounces_'.$k]) && is_numeric($_POST['bounces_'.$k])) ? $_POST['bounces_'.$k]:0);
						?>
					<div class="form-group">
						<label for="bounces_<?php echo($k);?>"><?php echo(sh('pRP9MnRKZo').$v['name']);?></label>
						<select name="bounces_<?php echo($k);?>" id="bounces_<?php echo($k);?>" class="form-control autoWidth">
							<?php foreach($LETHE_BOUNCE_ACTIONS as $ak=>$av){
								echo('<option value="'. $ak .'"'. formSelector($frmAct,$ak,0) .'>'. $av .'</option>');
							}?>
						</select>
					</div>
					<?php }?>
				
				</div>
				<div role="tabpanel" class="tab-pane fade" id="save">
					&nbsp;
					<div class="form-group">
						<button type="submit" name="addAccount" class="btn btn-success"><?php echo(letheglobal_save);?></button>
					</div>
				</div>
			  </div>

			</div>
		</form>
	<!-- Add Submission Account End -->
	<?php }else if($page_sub2=='edit'){
		echo($errText);?>
	<!-- Edit Submission Account Start -->

	<?php $opAcc = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE ID=". $ID ."") or die(mysqli_error($myconn));
	if(mysqli_num_rows($opAcc)==0){echo(errMod(letheglobal_record_not_found,'danger'));}else{
	$opAccRs = $opAcc->fetch_assoc();
	?>
	
		<form action="" method="POST">
			<div role="tabpanel">

			  <!-- Nav tabs -->
			  <ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo(letheglobal_general_settings);?></a></li>
				<li role="presentation"><a href="#sending" aria-controls="sending" role="tab" data-toggle="tab"><?php echo(settings_sending);?></a></li>
				<li role="presentation"><a href="#connection" aria-controls="connection" role="tab" data-toggle="tab"><?php echo(settings_connection);?></a></li>
				<li role="presentation"><a href="#dkim" aria-controls="dkim" role="tab" data-toggle="tab">DKIM</a></li>
				<li role="presentation"><a href="#bounce" aria-controls="bounce" role="tab" data-toggle="tab">Bounce</a></li>
				<li role="presentation"><a href="#save" aria-controls="save" role="tab" data-toggle="tab"><?php echo(letheglobal_save);?></a></li>
			  </ul>

			  <!-- Tab panes -->
			  <div class="tab-content">
				<div role="tabpanel" class="tab-pane fade in active" id="general">
					<!-- General -->
					&nbsp;
					<div class="form-group">
						<label for="acc_title"><?php echo(sh('lzBaNskLnE').settings_account_title);?></label>
						<input type="text" name="acc_title" id="acc_title" value="<?php echo(showIn($opAccRs['acc_title'],'input'));?>" size="40" class="form-control autoWidth">
					</div>				
					<div class="form-group">
						<label for="spec_limit_range"><?php echo(sh('AzHt5OeYZK').settings_limit_range);?></label>
						<select name="spec_limit_range" id="spec_limit_range" class="form-control autoWidth">
							<option value="1"<?php echo(formSelector($opAccRs['limit_range'],1,0))?>><?php echo(settings_per_minute);?></option>
							<option value="60"<?php echo(formSelector($opAccRs['limit_range'],60,0))?>><?php echo(settings_per_hour);?></option>
							<option value="1440"<?php echo(formSelector($opAccRs['limit_range'],1440,0))?>><?php echo(settings_per_day);?></option>
						</select>
					</div>
					<div class="form-group">
						<label for="daily_limit"><?php echo(sh('OIuOGGeyfP').letheglobal_limits);?></label>
						<div class="input-group">
							<input type="number" onkeydown="validateNumber(event);" name="daily_limit" id="daily_limit" value="<?php echo(showIn($opAccRs['daily_limit'],'input'));?>" class="form-control">
							<span class="input-group-addon autoWidth"><?php echo(showIn($opAccRs['daily_sent'],'input'));?></span>
						</div>
					</div>
					
					<div class="form-group">
						<label for="send_per_conn"><?php echo(sh('f4kSQ3MNm4').settings_send_per_connection);?></label>
						<input type="number" onkeydown="validateNumber(event);" name="send_per_conn" id="send_per_conn" value="<?php echo(showIn($opAccRs['send_per_conn'],'input'));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="standby_time"><?php echo(sh('HOTGIRH0eF').settings_standby_time.' ('. letheglobal_seconds .')');?></label>
						<input type="number" onkeydown="validateNumber(event);" name="standby_time" id="standby_time" value="<?php echo(showIn($opAccRs['standby_time'],'input'));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="systemAcc"><?php echo(sh('tiC4ApBdWQ').settings_system_account);?></label>
						<div>
						<input type="checkbox" name="systemAcc" id="systemAcc" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(formSelector($opAccRs['systemAcc'],1,1));?>>
						</div>
					</div>
					<div class="form-group">
						<label for="debug"><?php echo(sh('W1us3D6GOb').settings_debug_mode);?></label>
						<div>
						<input type="checkbox" name="debug" id="debug" data-on-label="ON" data-off-label="OFF" value="YES" class="letheSwitch"<?php echo(formSelector($opAccRs['isDebug'],1,1));?>>
						</div>
					</div>
					<div class="form-group">
						<label for="active"><?php echo(sh('8Ldfvb0tGm').letheglobal_active);?></label>
						<div>
						<input type="checkbox" name="active" id="active" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(formSelector($opAccRs['isActive'],1,1));?>>
						</div>
					</div>
					<!-- General -->
				</div>
				<div role="tabpanel" class="tab-pane fade" id="sending">
					<!-- Sending -->
					&nbsp;
					<div class="form-group">
						<label for="from_title"><?php echo(sh('kfMTnyvW8x').settings_sender_title);?></label>
						<input type="text" name="from_title" id="from_title" value="<?php echo(showIn($opAccRs['from_title'],'input'));?>" size="40" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="from_mail"><?php echo(sh('Mz5HgMq918').settings_sender_e_mail);?></label>
						<input type="email" name="from_mail" id="from_mail" value="<?php echo(showIn($opAccRs['from_mail'],'input'));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="reply_mail"><?php echo(sh('zIo5YkkltJ').settings_reply_e_mail);?></label>
						<input type="email" name="reply_mail" id="reply_mail" value="<?php echo(showIn($opAccRs['reply_mail'],'input'));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="test_mail"><?php echo(sh('bcWtR8fOlU').settings_test_e_mail);?></label>
						<input type="email" name="test_mail" id="test_mail" value="<?php echo(showIn($opAccRs['test_mail'],'input'));?>" class="form-control autoWidth">
					</div>
					<div class="form-group">
						<label for="mail_type"><?php echo(sh('DwFhJWa1df').settings_e_mail_content_type);?></label>
						<select name="mail_type" id="mail_type" class="form-control autoWidth">
							<?php foreach($LETHE_MAIL_TYPE as $k=>$v){echo('<option value="'. $k .'"'. formSelector($opAccRs['mail_type'],$k,0) .'>'. $v .'</option>');}?>
						</select>
					</div>
					<div class="form-group">
						<label for="mail_engine"><?php echo(sh('qFyVBuUeVq').settings_e_mail_engine);?></label>
						<select name="mail_engine" id="mail_engine" class="form-control autoWidth">
							<?php foreach($LETHE_MAIL_ENGINE as $k=>$v){echo('<option value="'. $k .'"'. formSelector($opAccRs['mail_engine'],$k,0) .'>'. $v['title'] .'</option>');}?>
						</select>
					</div>
					<!-- Sending -->
				</div>
				<div role="tabpanel" class="tab-pane fade" id="connection">
					<!-- Connection -->
					&nbsp;
					<div class="form-group">
						<label for="send_method"><?php echo(sh('SZhJ4IPHO1').settings_sending_method);?></label>
						<select name="send_method" id="send_method" class="form-control autoWidth">
							<?php foreach($LETHE_MAIL_METHOD as $k=>$v){echo('<option value="'. $k .'"'. formSelector($opAccRs['send_method'],$k,0) .'>'. $v .'</option>');}?>
						</select>
					</div>
					<div class="row">
						<div class="col-md-4 mailMethod0 mailMethods">
							<h4 class="text-warning">SMTP</h4><hr>
							<div class="form-group">
								<label for="smtp_host"><?php echo(sh('0cdC8eZbXa'));?>SMTP <?php echo(settings_server);?></label>
								<input type="text" name="smtp_host" id="smtp_host" onblur="textCopier('#smtp_host','#pop3_host,#imap_host');" value="<?php echo(showIn($opAccRs['smtp_host'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="smtp_port"><?php echo(sh('rZnbloJUA7'));?>SMTP <?php echo(settings_port);?></label>
								<input type="text" onkeydown="validateNumber(event);" name="smtp_port" id="smtp_port" value="<?php echo(showIn($opAccRs['smtp_port'],'input'));?>" class="form-control autoWidth" placeholder="587">
							</div>
							<div class="form-group">
								<label for="smtp_user"><?php echo(sh('cwM012UEPl'));?>SMTP <?php echo(settings_username);?></label>
								<input type="text" name="smtp_user" id="smtp_user" onblur="textCopier('#smtp_user','#pop3_user,#imap_user');" value="<?php echo(showIn($opAccRs['smtp_user'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="smtp_pass"><?php echo(sh('FG4ntQOrHw'));?>SMTP <?php echo(settings_password);?></label>
								<input type="password" name="smtp_pass" id="smtp_pass" onblur="textCopier('#smtp_pass','#pop3_pass,#imap_pass');" value="" class="form-control autoWidth" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="smtp_secure"><?php echo(sh('QRIqfHX81i'));?>SMTP <?php echo(settings_encryption);?></label>
								<select name="smtp_secure" id="smtp_secure" class="form-control autoWidth">
									<?php foreach($LETHE_MAIL_SECURE as $k=>$v){echo('<option value="'. $k .'"'. formSelector($opAccRs['smtp_secure'],$k,0) .'>'. $v .'</option>');}?>
								</select>
							</div>
							<div class="form-group">
								<label for="smtp_auth"><?php echo(sh('4tQ6wgJuGC'));?>SMTP <?php echo(settings_auth);?></label>
								<div>
								<input type="checkbox" data-on-label="On" data-off-label="Off" name="smtp_auth" id="smtp_auth" value="YES" class="letheSwitch"<?php echo(formSelector($opAccRs['smtp_auth'],1,1));?>>
								</div>
							</div>
						</div>
						<div class="mailMethod1 mailMethods"></div>
						<div class="col-md-4 mailMethod2 mailMethods">
							<h4 class="text-warning">Amazon SES</h4><hr>
							<div class="form-group">
								<label for="aws_acc_key"><?php echo(sh('1RCXxlcvFc'));?>AWS <?php echo(settings_access_key);?></label>
								<input type="text" name="aws_acc_key" id="aws_acc_key" value="<?php echo(showIn($opAccRs['aws_access_key'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="aws_sec_key"><?php echo(sh('dkvLq9XY7n'));?>AWS <?php echo(settings_secret_key);?></label>
								<input type="text" name="aws_sec_key" id="aws_sec_key" value="<?php echo(showIn($opAccRs['aws_secret_key'],'input'));?>" class="form-control autoWidth">
							</div>
						</div>
						<div class="col-md-4 mailMethod3 mailMethods">
							<h4 class="text-warning">Mandrill</h4><hr>
							<div class="form-group">
								<label for="mandrill_user"><?php echo(sh('EyvMNZKMd9'));?>Mandrill <?php echo(settings_username);?></label>
								<input type="text" name="mandrill_user" id="mandrill_user" value="<?php echo(showIn($opAccRs['mandrill_user'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="mandrill_key"><?php echo(sh('WlVgweKMR0'));?>Mandrill <?php echo(organizations_api_key);?></label>
								<input type="text" name="mandrill_key" id="mandrill_key" value="<?php echo(showIn($opAccRs['mandrill_key'],'input'));?>" class="form-control autoWidth">
							</div>
						</div>
						<div class="col-md-4 mailMethod4 mailMethods">
							<h4 class="text-warning">SendGrid</h4><hr>
							<div class="form-group">
								<label for="sendgrid_user"><?php echo(sh('JxL85Yj8Wm'));?>SendGrid <?php echo(settings_username);?></label>
								<input type="text" name="sendgrid_user" id="sendgrid_user" value="<?php echo(showIn($opAccRs['sendgrid_user'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="sendgrid_pass"><?php echo(sh('xVYMGvzroO'));?>SendGrid <?php echo(settings_password);?></label>
								<input type="password" name="sendgrid_pass" id="sendgrid_pass" value="" class="form-control autoWidth" autocomplete="off">
							</div>
						</div>
						<div class="col-md-4">
							<h4 class="text-warning">POP3</h4><hr>
							<div class="form-group">
								<label for="pop3_host"><?php echo(sh('TtWPeCE72I'));?>POP3 <?php echo(settings_server);?></label>
								<input type="text" name="pop3_host" id="pop3_host" value="<?php echo(showIn($opAccRs['pop3_host'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="pop3_port"><?php echo(sh('KqgpHLxzep'));?>POP3 <?php echo(settings_port);?></label>
								<input type="text" onkeydown="validateNumber(event);" name="pop3_port" id="pop3_port" value="<?php echo(showIn($opAccRs['pop3_port'],'input'));?>" class="form-control autoWidth" placeholder="110">
							</div>
							<div class="form-group">
								<label for="pop3_user"><?php echo(sh('6DFDWa8juT'));?>POP3 <?php echo(settings_username);?></label>
								<input type="text" name="pop3_user" id="pop3_user" value="<?php echo(showIn($opAccRs['pop3_user'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="pop3_pass"><?php echo(sh('iWy1Oy3Mme'));?>POP3 <?php echo(settings_password);?></label>
								<input type="password" name="pop3_pass" id="pop3_pass" value="" class="form-control autoWidth" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="pop3_secure"><?php echo(sh('2l1eXfRktt'));?>POP3 <?php echo(settings_encryption);?></label>
								<select name="pop3_secure" id="pop3_secure" class="form-control autoWidth">
									<?php foreach($LETHE_MAIL_SECURE as $k=>$v){echo('<option value="'. $k .'"'. formSelector($opAccRs['pop3_secure'],$k,0) .'>'. $v .'</option>');}?>
								</select>
							</div>
							<div class="form-group">
								<span><?php echo(sh('JWrihS9A0Y'));?></span><label for="bounce_acc0">Bounce <?php echo(settings_account);?></label>
								<input type="radio" name="bounce_acc" id="bounce_acc0" value="0" class="ionc"<?php echo(formSelector($opAccRs['bounce_acc'],0,1));?>>
							</div>
						</div>
						<div class="col-md-4">
							<h4 class="text-warning">IMAP</h4><hr>
							<div class="form-group">
								<label for="imap_host"><?php echo(sh('xAPd9jscX0'));?>IMAP <?php echo(settings_server);?></label>
								<input type="text" name="imap_host" id="imap_host" value="<?php echo(showIn($opAccRs['imap_host'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="imap_port"><?php echo(sh('o7a2mtcV9X'));?>IMAP <?php echo(settings_port);?></label>
								<input type="text" onkeydown="validateNumber(event);" name="imap_port" id="imap_port" value="<?php echo(showIn($opAccRs['imap_port'],'input'));?>" class="form-control autoWidth" placeholder="143">
							</div>
							<div class="form-group">
								<label for="imap_user"><?php echo(sh('ZU8R27nFPB'));?>IMAP <?php echo(settings_username);?></label>
								<input type="text" name="imap_user" id="imap_user" value="<?php echo(showIn($opAccRs['imap_user'],'input'));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="imap_pass"><?php echo(sh('BnrEuVhsHM'));?>IMAP <?php echo(settings_password);?></label>
								<input type="password" name="imap_pass" id="imap_pass" value="" class="form-control autoWidth" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="imap_secure"><?php echo(sh('hD8up4EX8N'));?>IMAP <?php echo(settings_encryption);?></label>
								<select name="imap_secure" id="imap_secure" class="form-control autoWidth">
									<?php foreach($LETHE_MAIL_SECURE as $k=>$v){echo('<option value="'. $k .'"'. formSelector($opAccRs['imap_secure'],$k,0) .'>'. $v .'</option>');}?>
								</select>
							</div>
							<div class="form-group">
								<span><?php echo(sh('JWrihS9A0Y'));?></span><label for="bounce_acc1">Bounce <?php echo(settings_account);?></label>
								<input type="radio" name="bounce_acc" id="bounce_acc1" value="1" class="ionc"<?php echo(formSelector($opAccRs['bounce_acc'],1,1));?>>
							</div>
						</div>
					</div>
					<!-- Connection -->
				</div>
				<div role="tabpanel" class="tab-pane fade" id="dkim">
					&nbsp;
					<div class="form-group">
						<label for="dkimactive"><?php echo(sh('vqaWZq3msj'));?>DKIM <?php echo(letheglobal_active);?></label>
						<div>
						<input type="checkbox" name="dkimactive" id="dkimactive" data-on-label="<?php echo(letheglobal_yes);?>" data-off-label="<?php echo(letheglobal_no);?>" value="YES" class="letheSwitch"<?php echo(((isset($opAccRs['dkim_active']) && $opAccRs['dkim_active']==1) ? ' checked':''));?>>
						</div>
					</div>
					
					<div id="dkiminfos"<?php if(!isset($_POST['dkimactive']) || $_POST['dkimactive']!='YES'){echo(' class="sHide"');}?>>
					<hr>
					
						<div class="form-group">
							<label for="dkimdomain"><?php echo(sh('YAJKSDyPku').settings_domain);?></label>
							<input type="text" class="form-control autoWidth" id="dkimdomain" name="dkimdomain" value="<?php echo(((isset($opAccRs['dkim_domain']) && $opAccRs['dkim_domain']!='') ? showIn($opAccRs['dkim_domain'],'input'):''));?>" placeholder="mydomain.com">
						</div>
						
						<div class="form-group">
							<label for="dkimprivate"><?php echo(sh('AU28Kbt2d5').settings_private_key);?></label>
							<input type="text" class="form-control autoWidth" id="dkimprivate" name="dkimprivate" value="<?php echo(((isset($opAccRs['dkim_private']) && $opAccRs['dkim_private']!='') ? showIn($opAccRs['dkim_private'],'input'):''));?>">
						</div>
						
						<div class="form-group">
							<label for="dkimselector"><?php echo(sh('mnkwCznfVG').settings_selector);?></label>
							<input type="text" class="form-control autoWidth" id="dkimselector" name="dkimselector" value="<?php echo(((isset($opAccRs['dkim_selector']) && $opAccRs['dkim_selector']!='') ? showIn($opAccRs['dkim_selector'],'input'):''));?>" placeholder="default">
						</div>
						
						<div class="form-group">
							<label for="dkimpassphrase"><?php echo(sh('P7Tk5NiINR').settings_passphrase);?></label>
							<input type="text" class="form-control autoWidth" id="dkimpassphrase" name="dkimpassphrase" value="<?php echo(((isset($opAccRs['dkim_passphrase']) && $opAccRs['dkim_passphrase']!='') ? showIn($opAccRs['dkim_passphrase'],'input'):''));?>">
						</div>
					
					</div>
					
					<script>
						$(document).ready(function(){
							$("#dkimactive").bind('change',function(){
								if($("#dkimactive").is(':checked')){
									$("#dkiminfos").removeClass("sHide");
								}else{
									$("#dkiminfos").addClass("sHide");
								}
							});
						});
					</script>
				
				</div>
				<div role="tabpanel" class="tab-pane fade" id="bounce">
					&nbsp;
					<?php
					$bounceActList = json_decode($opAccRs['bounce_actions'],true);
					foreach($LETHE_BOUNCE_TYPES as $k=>$v){
						$frmAct = ((array_key_exists($k,$bounceActList)) ? $bounceActList[$k]:0);
						?>
					<div class="form-group">
						<label for="bounces_<?php echo($k);?>"><?php echo(sh('pRP9MnRKZo').$v['name']);?></label>
						<select name="bounces_<?php echo($k);?>" id="bounces_<?php echo($k);?>" class="form-control autoWidth">
							<?php foreach($LETHE_BOUNCE_ACTIONS as $ak=>$av){
								echo('<option value="'. $ak .'"'. formSelector($frmAct,$ak,0) .'>'. $av .'</option>');
							}?>
						</select>
					</div>
					<?php }?>
				
				</div>
				<div role="tabpanel" class="tab-pane fade" id="save">
					&nbsp;
					<div class="form-group">
						<label for="del"><?php echo(letheglobal_delete);?></label>
						<input type="checkbox" data-alert-dialog-text="<?php echo(letheglobal_are_you_sure_to_delete);?>?" name="del" id="del" value="YES" class="ionc">
					</div>					
					<div class="form-group">
						<button type="submit" name="editAccount" class="btn btn-success"><?php echo(letheglobal_save);?></button>
					</div>
				</div>
			  </div>

			</div>
		</form>
		<?php } $opAcc->free();?>
	<!-- Edit Submission Account End -->
	<?php }else{?>
	<!-- List Submission Account Start -->
		<table class="footable table">
			<thead>
				<tr>
					<th><?php echo(settings_account);?></th>
					<th width="200"><?php echo(settings_daily_limit);?></th>
					<th data-hide="phone"><?php echo(settings_e_mail_engine);?></th>
					<th data-hide="phone,tablet"><?php echo(settings_sending_method);?></th>
					<th data-hide="phone"><?php echo(letheglobal_active);?></th>
					<th data-hide="phone"><?php echo(letheglobal_primary);?></th>
				</tr>
			</thead>
			<tbody>
			<?php 
			(isMob() ? $limit = 10 : $limit = 20);
			((!isset($_GET["pgGo"]) || !is_numeric($_GET["pgGo"])) ? $pgGo = 1 : $pgGo = intval($_GET["pgGo"]));
			 $count		 = mysqli_num_rows($myconn->query("SELECT ID FROM ". db_table_pref ."submission_accounts"));
			 $total_page	 = ceil($count / $limit);
			 $dtStart	 = ($pgGo-1)*$limit;
			$opAccs = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts ORDER BY systemAcc DESC,acc_title ASC LIMIT $dtStart,$limit") or die(mysqli_error($myconn));
			while($opAccsRs = $opAccs->fetch_assoc()){
			?>
				<tr>
					<td><a href="?p=settings/submission/edit&amp;ID=<?php echo($opAccsRs['ID']);?>"><?php echo(showIn($opAccsRs['acc_title'],'page'));?></a></td>
					<td data-value="<?php echo($opAccsRs['daily_limit']);?>">
						<?php echo(getMyLimits($opAccsRs['daily_sent'],$opAccsRs['daily_limit']));?>
						<span class="text-mute"><small><?php echo(letheglobal_reset);?>: <?php echo(setMyDate($opAccsRs['daily_reset'],2));?></small></span>
					</td>
					<td><?php echo($LETHE_MAIL_ENGINE[$opAccsRs['mail_engine']]['title']);?></td>
					<td><?php echo($LETHE_MAIL_METHOD[$opAccsRs['send_method']]);?></td>
					<td data-value="<?php echo($opAccsRs['isActive']);?>"><?php echo(getBullets($opAccsRs['isActive']));?></td>
					<td data-value="<?php echo($opAccsRs['systemAcc']);?>"><?php echo(getBullets($opAccsRs['systemAcc']));?></td>
				</tr>
			<?php } $opAccs->free();?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="6">
					<?php $pgVar='?p='. $p;include_once("inc/inc_pagination.php");?>
				</td>
			</tr>
			</tfoot>
		</table>

		<script type="text/javascript">
			$(document).ready(function(){
				$('.footable').footable();
			});
		</script>
	<!-- List Submission Account End -->
	<?php } # Submission Subs End?>