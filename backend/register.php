<?php

//require configuration file
require_once('../configuration.php');
//get languages
require_once('../system/languages.php');

$informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
$information = mysqli_fetch_array($informations);
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<?php include ('head.php'); ?>
	<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['CP_REGISTER']; ?></title>
</head>
<!-- End of head -->

<?php include ('../style.php'); ?>

<body class="backend index-register-page">
	<div class="row">
	<?php 
	if (isset($_GET['action'])) { 
		switch (strtolower($_GET['action'])) {
		case 'register':
		// If the form was submitted try to create the account.
		if (isset($_POST['user_name']) && isset($_POST['user_password']) && isset($_POST['user_nice_name']) && isset($_POST['user_nice_name']) && isset($_POST['user_email'])) {
		if (createAccount($_POST['user_name'], $_POST['user_password'], $_POST['user_nice_name'], $_POST['user_email'], 'Client')) { 
	?>

		<header class="row loggedin">
			<div class="container">
				<h2 class="white text-center"><?php echo $lang['cp_register_success']; ?></h2>
			</div>
		</header>
		<!-- End of header -->

		<?php include ('../nav.php'); ?>

		<!-- End of nav -->

		<div class="block-index-backend container logged">
			<div class="col-md-6 user-account">
				<span><a href="<?php echo $CONF['installation_path']; ?>backend/login.php"><i class="fa fa-power-off"></i><?php echo $lang['CP_LOGIN']; ?></a></span>
			</div>
			<div class="col-md-6 user-account">
				<span><a href="<?php echo $CONF['installation_path']; ?>"><i class="fa fa-home"></i><?php echo $lang['HOMEPAGE']; ?></a></span>
			</div>      
		</div>

		<?php }else {
			// unset the action to display the registration form.
			unset($_GET['action']);
		} }else {
			$_SESSION['error'] = $lang['cp_login_error_message_third'];
			unset($_GET['action']);
		} break;
		} }

		// If the user is logged in display them a message.
		if (loggedIn()) { ?>     
			<script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
		<?php } elseif (!isset($_GET['action'])) {
		// incase there was an error
		// see if we have a previous username
		$sUsername = "";
		if (isset($_POST['user_name'])) {
			$sUsername = $_POST['user_name'];
		} 
		$sUserNicename = "";
		if (isset($_POST['user_nice_name'])) {
			$sUserNicename = $_POST['user_nice_name'];
		} 
		$sUserEmail = "";
		if (isset($_POST['user_email'])) {
			$sUserEmail = $_POST['user_email'];
		} 
		$sUserRole = "";
		if (isset($_POST['user_role'])) {
			$sUserRole = $_POST['user_role'];
		} 
		?>

		<header class="row loggedin">
			<div class="container">
				<h2 class="white text-center"><?php echo $lang['cp_register_welcome_message']; ?></h2>
			</div>
		</header>

		<?php include ('../nav.php'); ?>

		<div class="boxed-register-block boxed-form"> 

			<div class="block-footer">
				<span><?php echo $lang['cp_register_form_head']; ?></span>
			</div>

			<div class="block-content">
			<?php $sError = ""; 
			if (isset($_SESSION['error'])) { ?>
				<p id="error"><?php echo $_SESSION['error']; ?></p>
			<?php } ?>
			<?php echo $sError; ?>

				<form id="backend-register-form" name="register" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=register">
					<div class="row form-group">
						<input class="form-control" type="text" placeholder="<?php echo $lang['Username']; ?>" name="user_name" value="<?php echo $sUsername; ?>" />
					</div>
					<div class="row form-group">
						<input class="form-control" placeholder="<?php echo $lang['Name_and_surname']; ?>" type="text" name="user_nice_name" value="<?php echo $sUserNicename; ?>" />
					</div>
					<div class="row form-group">
						<input class="form-control" placeholder="<?php echo $lang['Email']; ?>" type="text" name="user_email" value="<?php echo $sUserEmail; ?>" />
					</div>
					<div class="row form-group">
						<input class="form-control" placeholder="<?php echo $lang['Password']; ?>" type="password" name="user_password" value="" />
					</div>
					<input type="submit" name="submit" class="btn btn-success" value="<?php echo $lang['CP_REGISTER']; ?>" />
				</form><!-- End of form #backend-register-form -->
			</div><!-- End of .block-content -->
		</div><!-- END of .boxed-register-block -->
	<?php } ?>
	</div><!-- END of .row -->
</body>
</html>