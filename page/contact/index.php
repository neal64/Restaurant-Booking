<?php
	//require configuration file
	require_once('../../configuration.php');
	//get languages
	require_once('../../system/languages.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<?php include ('../../head.php'); ?>
	<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['CONTACT_PAGE_TITLE']; ?></title>
</head>
<body>

<?php
//Get site header
include ('../../header.php');
//Get page content
include ('page-content.php');
//Get site footer
require ('../../footer.php');
?>

</body>
</html>