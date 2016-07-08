<?php

//require configuration file
require_once('../../configuration.php');
//get languages
require_once('../../system/languages.php');
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
	<?php require('../head.php'); ?>
	<title><?php echo $lang['CP_BASE_TITLE'] . $lang['CP_USER_PAGE_TITLE']; ?></title>
</head>

<body class="backend row index-edit-user-profile">
	<!-- ####### HEADER for logged in users ############################################################## -->
	<?php if (loggedIn()) { 

		$query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") 
		or trigger_error("Query Failed: " . mysqli_error($con));
	    $query2 = mysqli_fetch_array($query); ?>
	    
		<header class="row loggedin">
			<div class="">
				<div class="col-md-6">
					<span class="label label-success pull-left">
					<?php if ($query2['user_role'] == 'Administrator') { ?>
						<?php echo $lang['role']; ?><strong>Administrator</strong>
					<?php }else if ($query2['user_role'] == 'Client') { ?>
						<?php echo $lang['role']; ?><strong>Client</strong>
					<?php } ?>
					</span>
					<div class="pull-left">
						<a title="<?php echo $lang['back_into_the_site']; ?>" href="<?php echo $CONF['installation_path']; ?>">
							<?php echo $lang['back_into_the_site']; ?>
						</a>
					</div>
				</div>
				<div class="col-md-6"><p class="pull-right"><?php echo $lang['cp_login_hello'] . $query2['user_nice_name']; ?>! <a href="<?php echo $CONF['installation_path']; ?>backend/login.php?action=logout"><span class="label label-warning"><?php echo $lang['log_out']; ?></span></a></p></div>
			</div>
		</header>


		<div class="col-md-2 v2-sidebar-menu">
			<?php if ($query2['user_role'] == 'Administrator') { ?>
				<?php include('menu-administrators.php'); ?>
			<?php }else if ($query2['user_role'] == 'Client') {  ?>
				<?php include('menu-clients.php');  ?>
			<?php } ?>
		</div>

		<div class="col-md-10 v2-page-content">
			<div class="row">
				<div class="col-md-12 user-list">
					<?php
					if(isset($_POST["submit_edit_profile"])){
						$user_email = $_POST["user_email"];
						$user_nice_name = $_POST["user_nice_name"];
						$user_phone = $_POST["user_phone"];
						$user_delivery_address = $_POST["user_delivery_address"];
						$user_id = $query2['user_id'];

						$query_edit_profile = "UPDATE users SET user_email='$user_email', user_nice_name='$user_nice_name',user_delivery_address='$user_delivery_address', user_phone='$user_phone' WHERE user_id='$user_id'";
						mysqli_query($con, $query_edit_profile); ?>

			            <div role="alert" class="alert alert-success container">
			              <?php echo $lang['cp_user_update_profile']; ?>
			            </div>
					<?php } ?>

					<h1><?php echo $lang['hello'] . "<strong>" . $query2['user_nice_name'] . "</strong>! "; ?></h1>
					<section class="change_infos white-container col-md-7">
						<div class="titles_infos_group clearfix">
							<span class="left_title col-md-4"><?php echo $lang['role']; ?></span>
							<span class="right_infos col-md-6"><?php echo $query2['user_role']; ?></span>
						</div>
						<div class="titles_infos_group clearfix">
							<span class="left_title col-md-4"><?php echo $lang['Username']; ?></span>
							<span class="right_infos col-md-6"><?php echo $query2['user_name']; ?></span>
						</div>
						<div class="titles_infos_group clearfix">
							<span class="left_title col-md-4"><?php echo $lang['Name_and_surname']; ?></span>
							<span class="right_infos col-md-6"><?php echo $query2['user_nice_name']; ?></span>
						</div>
						<div class="titles_infos_group clearfix">
							<span class="left_title col-md-4"><?php echo $lang['cp_order_address']; ?></span>
							<span class="right_infos col-md-6"><?php echo $query2['user_delivery_address']; ?></span>
						</div>
						<div class="titles_infos_group clearfix">
							<span class="left_title col-md-4"><?php echo $lang['Email']; ?></span>
							<span class="right_infos col-md-6"><?php echo $query2['user_email']; ?></span>
						</div>
						<div class="titles_infos_group clearfix">
							<span class="left_title col-md-4"><?php echo $lang['Phone_number']; ?></span>
							<span class="right_infos col-md-6"><?php echo $query2['user_phone']; ?></span>
						</div>
						<div class="titles_infos_group clearfix">
							<div class="right_infos">
								<?php include 'user-profile-modal.php'; ?>
	                            <script type="text/javascript">
	                                //Bootstrap modal
	                                jQuery( ".edit_row" ).click(function() {
	                                    jQuery(this).parent().find('#modal').modal({
	                                        keyboard: false
	                                    })
	                                });
	                            </script>
								<div class="pull-left divider-right">
									<button class="edit_row btn btn-success" data-toggle="modal" data-target="#modal"><?php echo $lang['change_user_profile_informations']; ?></button>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>


		<script>
			// this is important for IEs
			var polyfilter_scriptpath = '/js/';
		</script>

	<!-- ####### HEADER for logged in users ############################################################## -->
	<?php } else { ?>
   		<script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
	<?php } ?>

</body>
</html>