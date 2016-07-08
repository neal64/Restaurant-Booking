<?php

//require configuration file
require_once('configuration.php');
//get languages
require_once('system/languages.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<?php include ('head.php'); ?>
	<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['NOT_FOUND_PAGE_TITLE']; ?></title>
</head>

<body class="backend 404">

	<header class="row 404">
	  <div class="container">
	      <h2 class="white text-center"><?php echo $lang['error_page_not_found']; ?></h2>
	  </div>
	</header>

	<?php include ('nav.php'); ?>

	<div class="block-index-backend container">
		<div class="col-md-6 user-account">
	        <span><a href="<?php echo $CONF['installation_path']; ?>"><i class="fa fa-home"></i><?php echo $lang['HOMEPAGE']; ?></a></span>
		</div>			
		<div class="col-md-6 user-account">
			<span><a href="<?php echo $CONF['installation_path']; ?>"><i class="fa fa-plus"></i><?php echo $lang['Contact']; ?></a></span>
		</div>
	</div>

</body>
</html>