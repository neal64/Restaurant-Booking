<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 13.11.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
/* Load Settings */
if(isLogged()){

	/* Load User Settings */
	$LTH = $myconn->stmt_init();
	$LTH = $myconn->prepare("SELECT * FROM ". db_table_pref ."users WHERE session_token=?") or die(mysqli_error($myconn));
	$LTH->bind_param('s',$_COOKIE['lethe']);
	$LTH->execute();
	$LTH->store_result();
	if($LTH->num_rows==0){
		header('Location: pg.login.php');
		die('<script>window.location.href="'. lethe_admin_url .'pg.login.php";</script>');
	}
	$sr = new Statement_Result($LTH);
	$LTH->fetch();

	/* Settings */
	define('LETHE_AUTH_MODE',$sr->Get('auth_mode'));
	define('LETHE_AUTH_ID',$sr->Get('ID'));
	define('LETHE_AUTH_NAME',$sr->Get('real_name'));
	define('LETHE_AUTH_ORG_ID',$sr->Get('OID'));
	define('LETHE_AUTH_VIEW_TYPE',usrAllowRecords($sr->Get('user_spec_view')));
	
	/* Permissions */
	if(LETHE_AUTH_MODE!=2){
		$opPerm = $myconn->query("SELECT * FROM ". db_table_pref ."user_permissions WHERE UID=". $sr->Get('ID') ."") or die(mysqli_error($myconn));
		while($opPermRs = $opPerm->fetch_assoc()){
			$LETHE_PERMISSIONS[] = $opPermRs['perm'];
		} $opPerm->free();
	}
	
	$LTH->close();
	unset($LTH);

}else{
	header('Location: pg.login.php');
	die('<script>window.location.href="'. lethe_admin_url .'pg.login.php";</script>');
}
?>