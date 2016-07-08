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
include_once('mod.common.php');
include_once('mod.functions.php');
if($page_main=='subscribers'){
if(!permCheck($p)){
	echo(errMod(letheglobal_you_are_not_authorized_to_view_this_page,'danger'));
}else{

/* Requests */
$ID = ((!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? 0:intval($_GET['ID']));

/* Mod Settings */
$mod_confs = $lethe_modules[recursive_array_search('lethe.subscribers',$lethe_modules)];
$pg_title = $mod_confs['title'];
$pg_nav_buts = '';
$errText = '';
?>

<?php 
if($page_sub=='subscriber'){include_once('pg.subscribers.php');}
else if($page_sub=='groups'){include_once('pg.groups.php');}
else if($page_sub=='forms'){include_once('pg.forms.php');}
else if($page_sub=='blacklist'){include_once('pg.blacklist.php');}
else if($page_sub=='exp-imp'){include_once('pg.exp_imp.php');}
else{
	echo('<h1>'. $pg_title .'</h1><hr><div class="container-fluid"><div class="row">');
	foreach($mod_confs['contents'] as $k=>$v){
		echo('<div class="col-md-2 module-splash">
				<h4><span class="'. $v['icon'] .'"></span></h4>
				<div><a href="'. $v['page'] .'">'. $v['title'] .'</a></div>
			  </div>');
	} echo('</div></div>');
}
?>

<?php 
echo('
<script>
	$(document).ready(function(){
		$("head title").text("'. showIn($pg_title,'page') .' - "+$("head title").text());
	});
</script>
');
} # Permission Check End
} # Module Load End
?>