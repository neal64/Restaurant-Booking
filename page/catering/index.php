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
	<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['CATERING_PAGE_TITLE']; ?></title>
</head>
<body class="index-catering-orders">

<?php include ('../../header.php'); ?>
<?php include ('page-content.php'); ?>




<?php require ('../../footer.php'); ?>

</body>
</html>