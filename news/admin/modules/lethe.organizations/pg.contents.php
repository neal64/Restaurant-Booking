<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 18.11.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
$pgnt = true;
if($page_main=='organizations'){
if(!permCheck($p)){
	echo(errMod(letheglobal_you_are_not_authorized_to_view_this_page,'danger'));
}else{

/* Requests */
if(!isset($_GET['ID']) || !is_numeric($_GET['ID'])){$ID=0;}else{$ID=intval($_GET['ID']);}

/* Mod Settings */
$mod_confs = $lethe_modules[recursive_array_search('lethe.organizations',$lethe_modules)];
$pg_title = $mod_confs['title'];
$pg_nav_buts = '';
$errText = '';

/* Demo Check */
if(!isDemo('addUser,editUser')){$errText = errMod(letheglobal_demo_mode_active,'danger');}
?>

<?php 
if($page_sub=='organization'){include_once('pg.organization.php');}
else if($page_sub=='users'){include_once('pg.users.php');}
else if($page_sub=='shortcodes'){include_once('pg.shortcodes.php');}
else{
	echo('<h1>'. $pg_title .'</h1><hr>');
	foreach($mod_confs['contents'] as $k=>$v){
		echo('<div class="col-md-2 module-splash">
				<h4><span class="'. $v['icon'] .'"></span></h4>
				<div><a href="'. $v['page'] .'">'. $v['title'] .'</a></div>
			  </div>');
	}
}
?>

<?php 
} # Permission Check End
} # Module Load End
?>