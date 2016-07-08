<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 31.10.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lethe.php');
$sirius->langFiles[] = 'letheglobal_front.php';
$sirius->langFiles[] = 'subscribers_back.php';
$sirius->loadLanguages();
include_once(LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.class.php');

if(isset($_GET['loginPos']) && $_GET['loginPos']=='logout'){

	$letheCookie = new sessionMaster;
	$letheCookie->sesList = "lethe,lethe_login";
	$letheCookie->sessDestroy();
	header('Location:index.php');
	die();

}

$errText = '';
$loginSucc = false;
$pwRem = false;
$pwrm_res = '';
if(isset($_POST['signIn'])){

	if(!isset($_POST['email']) || !mailVal($_POST['email'])){
		$errText = errMod('* '. letheglobal_invalid_e_mail_address .'','danger');
	}else{
	
		if(!isset($_POST['pass']) || empty($_POST['pass'])){
			$errText = errMod('* '. letheglobal_please_enter_password .'','danger');
		}else{
		
			$LTH = $myconn->stmt_init();
			$LTH = $myconn->prepare("SELECT ID,OID,pass,private_key,isActive,mail,last_login FROM ". db_table_pref ."users WHERE mail=? AND isActive=1") or die(mysqli_error($myconn));
			$LTH->bind_param('s',$_POST['email']);
			$LTH->execute();
			$LTH->store_result();
			if($LTH->num_rows==0){
				$errText = errMod('* '. letheglobal_incorrect_login_informations .'','danger');
			}else{
				$sr = new Statement_Result($LTH);
				$LTH->fetch();
				if(encr($_POST['pass']) != $sr->Get('pass')){
					$errText = errMod('* '. letheglobal_incorrect_login_informations .'','danger');
				}else{
					/* Create New Token */
					$logToken = encr($sr->Get('ID').$sr->Get('private_key').$sr->Get('OID').time().uniqid());
					if(DEMO_MODE){$logToken=encr('lethe_demo_mode');}
					$sessionTime=time()+(11800);
					if(isset($_POST['remember']) && $_POST['remember']=='YES'){
						$sessionTime=time() + (10 * 365 * 24 * 60 * 60);
					}
					
					/* Create Cookie */
					$letheCookie = new sessionMaster;
					$letheCookie->sesName = "lethe";
					$letheCookie->sesVal = $logToken;
					$letheCookie->sesTime = $sessionTime;
					$letheCookie->sessMaster();
					
					/* Login Cache */
					$letheCookie->sesName = "lethe_login";
					$letheCookie->sesVal = $sr->Get('last_login');
					$letheCookie->sesTime = $sessionTime;
					$letheCookie->sessMaster();
					
					/* Update Login Data */
					$myconn->query("UPDATE ". db_table_pref ."users SET last_login='". date("Y-m-d H:i:s") ."',session_token='". $logToken ."',session_time='". date("Y-m-d H:i:s",$sessionTime) ."' WHERE ID=". $sr->Get('ID') ."") or die(mysqli_error($myconn));
					$errText = errMod('<strong>'. letheglobal_you_have_been_successfully_logged_in .'!</strong><br>
									   '. letheglobal_youll_redirect_to_dashboard_in_5_seconds .'. <a href="index.php?p=dashboard" class="alert-link">'. letheglobal_click_here .'</a>
									   <meta http-equiv="refresh" content="5; url=index.php" />
									   '
									   ,'success');
					$loginSucc = true;
				}
			}
			$LTH->close();
		
		}
	
	}

}

/* Password Reminder */
if(isset($_POST['sendPW'])){
	if(DEMO_MODE){
		$pwRem = true;
		$pwrm_res = mysql_prep(errMod(letheglobal_demo_mode_active,'danger'));
	}else{
		$pwRem = true;
		
		if(!isset($_POST['pw_rem']) || !mailVal($_POST['pw_rem'])){
			$pwrm_res = mysql_prep(errMod(letheglobal_invalid_e_mail_address,'danger'));
		}else{
			$opUser = $myconn->prepare("SELECT * FROM ". db_table_pref ."users WHERE mail=?") or die(mysqli_error($myconn));
			$opUser->bind_param('s',$_POST['pw_rem']);
			$opUser->execute();
			$opUser->store_result();
			if($opUser->num_rows<1){
				$pwrm_res = mysql_prep(errMod(letheglobal_record_not_found,'danger'));
			}else{
				$srm = new Statement_Result($opUser);
				$opUser->fetch();
				
				# Load phpMailer basic mail sender
				# Start
				
					/* Design Receiver Data (Mail body could be used with system template) */
					$rndPassEnc = encr('myLethe'.time().rand().uniqid(true));
					$newPass = substr($rndPassEnc,1,12);
					$newPassEnc = encr($newPass);
					$mailBody = '<p>Hello '. $srm->Get('real_name') .',</p>';
					$mailBody.= '<p><strong>Your new password:</strong> '. $newPass .'</p>';
					$mailBody.= '<p>Do not forget to change your password after logged in.</p>';
					$mailBody.= '<p>Thank you!</p>';
					
					$rcMail = showIn($srm->Get('mail'),'page');
					$rcName = showIn($srm->Get('real_name'),'page');
					$rcSubject = showIn(letheglobal_password_recovery,'page');
					$rcBody = $mailBody;
					$rcAltBody = $mailBody;
					
					$recData = array($rcMail=>array(
													'name'=>$rcName,
													'subject'=>$rcSubject,
													'body'=>$rcBody,
													'altbody'=>$rcAltBody,
													)						
									);
									
					$sendMail = new lethe();
					$sendMail->sub_mail_id = md5($rcMail.time());
					$sendMail->sub_mail_receiver = $recData;
					$sendMail->sysSubInit();
				
					if($sendMail->sendPos){
						# Change Password
						$upPass = $myconn->prepare("UPDATE ". db_table_pref ."users SET pass=? WHERE ID=". (int)$srm->Get('ID') ."") or die(mysqli_error($myconn));
						$upPass->bind_param('s',$newPassEnc);
						$upPass->execute();
						$upPass->close();
						$pwrm_res = mysql_prep(errMod(subscribers_e_mail_sent_successfully,'success'));
					}else{
						$pwrm_res = mysql_prep(errMod(letheglobal_error_occured.'<br>ERROR:'.$sendMail->sendingErrors,'danger'));
					}
					
				# End
				
			}
			$opUser->close();
		}
	}
}
?>
<!doctype html>
<html>
<head>
<?php include_once('inc/inc_meta.php');?>
</head>
<body>

<div id="lethe">
	<div id="lethe-head" class="hidden-xs">
		<!-- HEAD -->
		<?php include_once('inc/inc_head.php');?>
		<!-- HEAD -->
	</div>

	<div id="lethe-main" class="container">
		<!-- CONTENT -->
			<div class="panel panel-default" id="login-pan">
				<div class="panel-body">
				
					<div id="lethe-login">
						<h3><?php echo(letheglobal_login);?></h3>
						<hr>
						<?php echo($errText);
						if(!$loginSucc){
						?>
						<form method="POST" action="">
							<div class="form-group">
								<label for="email"><?php echo(letheglobal_e_mail);?></label>
								<input type="text" name="email" id="email" value="<?php echo(((DEMO_MODE) ? 'tester@newslether.com':''));?>" class="form-control autoWidth">
							</div>
							<div class="form-group">
								<label for="pass"><?php echo(letheglobal_password);?></label>
								<input type="password" name="pass" id="pass" value="<?php echo(((DEMO_MODE) ? 'demolethe':''));?>" class="form-control autoWidth" autocomplete="off">
							</div>
							<div class="form-group">
								<label for="remember"><?php echo(letheglobal_remember_me);?></label>
								<input type="checkbox" name="remember" id="remember" value="YES" class="ionc"><br>
								<small><a href="javascript:;" class="lethe-get-pw"><?php echo(letheglobal_forgot_my_password);?></a></small>
							</div>
							<div class="form-group">
								<button type="submit" name="signIn" class="btn btn-primary pull-right"><?php echo(letheglobal_sign_in);?></button>
							</div>
						</form>
						<?php }?>
					</div>
					<div id="lethe-pw-reminder">
						<h3><?php echo(letheglobal_password_recovery);?></h3>
						<hr>
						<div id="pwrm_res"></div>
						<form method="POST" name="pwrm_form" id="pwrm_form" action="">
							<div class="form-group">
								<label for="pw_rem"><?php echo(letheglobal_e_mail);?></label>
								<input type="email" name="pw_rem" id="pw_rem" value="" class="form-control">
							</div>
							<div class="form-group">
								<span class="pull-right"><button type="button" name="cancelPW" class="btn btn-danger lethe-no-pw"><?php echo(letheglobal_cancel);?></button> <button type="submit" name="sendPW" class="btn btn-primary"><?php echo(letheglobal_send);?></button></span>
							</div>
						</form>
					</div>
				</div>
			</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".lethe-get-pw").click(function(){
			$("#lethe-login").slideUp();
			$("#lethe-pw-reminder").slideDown();
		});
		$(".lethe-no-pw").click(function(){
			$("#lethe-login").slideDown();
			$("#lethe-pw-reminder").slideUp();
		});
		<?php 
			if($pwRem){
				echo('$("#lethe-login").slideUp();$("#lethe-pw-reminder").slideDown();$("#pwrm_res").html("'. $pwrm_res .'");');
			}
		?>
	});
</script>

		<!-- CONTENT -->
		<?php 
						if(DEMO_MODE){
							echo('<div class="row"><center><div class="help-block">
							<strong>Demo User:</strong> tester@newslether.com <strong>Demo Pass:</strong> demolethe
							</div></center></div>');
						}
		?>
	</div>
	
</div>

<!-- page end -->
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<script src="Scripts/ion.checkRadio.min.js"></script>
<script src="Scripts/lethe.js"></script>
</body>
</html>