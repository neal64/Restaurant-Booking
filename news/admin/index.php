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
include_once(LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.class.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_module_loader.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_auth.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/org_set.php');

if(!isset($_GET['p']) || empty($_GET['p'])){
	$page_main = 'dashboard';
	$page_sub = '';
	$p = '';
}else{
	$p = $_GET['p'];
	$split_qry = explode('/',$p);
	$page_main = $split_qry[0];
	if(array_key_exists(1,$split_qry)){$page_sub = $split_qry[1];}else{$page_sub = '';}
	if(array_key_exists(2,$split_qry)){$page_sub2 = $split_qry[2];}else{$page_sub2 = '';}
	if(array_key_exists(3,$split_qry)){$page_sub3 = $split_qry[3];}else{$page_sub3 = '';}
}

?>
<!doctype html>
<html>
<head>
<?php include_once('inc/inc_meta.php');?>
</head>
<body>
<div class="getTheme"></div>
<div id="lethe">
	<div id="lethe-head" class="hidden-xs">
		<!-- HEAD -->
		<?php include_once('inc/inc_head.php');?>
		<!-- HEAD -->
	</div>

	<div id="lethe-nav" class="container">
		<!-- NAVIGATION -->
		<?php include_once('inc/inc_nav.php');?>
		<!-- NAVIGATION -->
	</div>
	
	<div id="lethe-main" class="container">
		<!-- CONTENT -->
		<div class="panel panel-default">
			<div class="panel-body">
				<?php 
				if($page_main=='dashboard') {
					include_once('pg.dashboard.php');
				}else if($page_main=='settings') {
					include_once('pg.settings.php');
				}else{
					foreach($lethe_modules as $k=>$v){
						include_once('modules/'. $v['mod_id'] .'/pg.contents.php');
					}
				}
				?>
			</div>
		</div>
		<!-- CONTENT -->
	</div>
	
	<div id="lethe-footer" class="container">
		<!-- FOOTER -->
		<?php include_once('inc/inc_footer.php');?>
		<!-- FOOTER -->
	</div>
	
</div>

<!-- page end -->
<script src="Scripts/jquery-ui.min.js"></script>
<script src="Scripts/footable.min.js"></script>
<script src="Scripts/footable.sort.min.js"></script>
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<script src="Scripts/ion.checkRadio.min.js"></script>
<script src="Scripts/jquery.switchButton.js"></script>
<script src="Scripts/jquery.fancybox.pack.js"></script>
<script src="Scripts/lethe.js"></script>

</body>
</html>