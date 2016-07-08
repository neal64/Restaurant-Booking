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
$pgnt = true;
if(LETHE_AUTH_MODE!=2){echo(errMod(letheglobal_you_are_not_authorized_to_view_this_page,'danger'));}else{

/* Languages */
$sirius->langFiles[] = "settings_back.php";
$sirius->loadLanguages();

/* Mod Settings */
$pg_title = letheglobal_general_settings;
$pg_nav_buts = '';
$errText = '';
?>
<?php if($page_sub=='users'){
	$pg_nav_buts = '<div class="nav-buts">
					<a href="?p=settings/users/add" class="btn btn-success">'. letheglobal_add .'</a>
					<a href="?p=settings/users" class="btn btn-primary">'. letheglobal_list .'</a>
					</div>
					';
					
	/* USERS START */
	include_once('manage/lethe.users.php');
	/* USERS END */
	
}else if($page_sub=='general'){
		echo('<h1>'. $pg_title .'</h1><hr>'.
			  $errText
			 );
			 
	/* SETTINGS START */
	include_once('manage/lethe.settings.php');
	/* SETTINGS END */
	
}else if($page_sub=='submission'){
	include_once('manage/lethe.submission.eximp.php');
	$pg_nav_buts = '<div class="nav-buts">
					<a href="?p=settings/submission/add" class="btn btn-success">'. letheglobal_add .'</a>
					<a href="?p=settings/submission" class="btn btn-primary">'. letheglobal_list .'</a>
					'. $LETHE_SUBACC_EXPIMP .'
					</div>
					';
		echo('<h1>'. $pg_title .'<span class="help-block"><span class="text-primary">'. letheglobal_submission_accounts .'</span></span></h1><hr>'.
			  $pg_nav_buts.
			  $errText
			 );
			 
	/* SUBMISSION START */
	include_once('manage/lethe.submission.php');
	/* SUBMISSION END */

} #Subs End?>

<?php 
} #Auth Check End
?>