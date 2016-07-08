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
	<title><?php echo $lang['CP_BASE_TITLE'] . $lang['CP_LIST_USERS_PAGE_TITLE']; ?></title>
</head>


<body class="backend row index-list-users">
<!-- ####### HEADER for logged in users ############################################################## -->
<?php if (loggedIn()) {
		
	$query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
    $query2 = mysqli_fetch_array($query);

    // if user_role is Administrator 
 	if ($query2['user_role'] == 'Administrator') { ?>
		<header class="row loggedin">
			<div class="">
				<div class="col-md-6">
					<span class="label label-success pull-left">
					<?php if ($query2['user_role'] == 'Administrator') { ?>
						<?php echo $lang['role']; ?><strong>Administrator</strong>
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
				<div class="col-md-12">
					<div class="user-list top-area">
						<div class="top_area_margin">
							<h2 class="pull-left"><?php echo $lang['cp_users_filter_by']; ?></h2>
							<div class="pull-left filter_table">
								<select id="select_user_role" class="select_menu_type btn btn-success">
								<?php
									$query_role = mysqli_query($con, "SELECT DISTINCT user_role FROM users") or trigger_error("Query Failed: " . mysqli_error($con));
						      		while($query_roles = mysqli_fetch_array($query_role)) {
								?>
									<option value="<?php echo $query_roles['user_role']; ?>"><?php echo $query_roles['user_role']; ?></option>
								<?php } ?>
								</select>
							</div>

							<div class="pull-left add-new-item-btn clearfix">
								<span class="onclick-register-form btn btn-success"><?php echo $lang['cp_users_add_new']; ?></span>
							</div>
						</div>

						<?php
						#Remove user
						if(isset($_POST["remove_user"])){
							$user_id = $_POST['user_id'];

							$query_edit_menu = "DELETE FROM users WHERE user_id='$user_id'";
							mysqli_query($con, $query_edit_menu); ?>

			                <div role="alert" class="alert alert-success">
			                  <?php echo $lang['cp_event_message_deleted']; ?>
			                </div>
						<?php } 

						#Edit user
						if(isset($_POST["submit_edit_user"])){
							$user_nice_name = $_POST["user_nice_name"];
							$user_email = $_POST["user_email"];
							$user_phone = $_POST["user_phone"];
							$user_id = $_POST['user_id'];
							//query
							$query_edit_menu = "UPDATE users SET user_nice_name='$user_nice_name', user_email='$user_email', user_phone='$user_phone' WHERE user_id='$user_id'";
							mysqli_query($con, $query_edit_menu); ?>

			                <div role="alert" class="alert alert-success">
			                  <?php echo $lang['cp_user_message_modified']; ?>
			                </div>
						<?php } ?>




						<div class="clearfix"></div>
						<div class="boxed-register-block unboxed-form toggle-register-form"> 
				          <div class="block-title">
				            <span><?php echo $lang['cp_users_add_new']; ?></span>
				          </div>
				          <div class="block-content">


				          	<?php if (isset($_POST['submit'])) {
					          	if (isset($_POST['user_name']) && isset($_POST['user_password']) && isset($_POST['user_nice_name']) && isset($_POST['user_nice_name']) && isset($_POST['user_email']) && isset($_POST['user_role'])) {
					            	if (createAccountCP($_POST['user_name'], $_POST['user_password'], $_POST['user_nice_name'], $_POST['user_email'], $_POST['user_role'])) { ?>
					            <h1 class="text-center"><?php echo $lang['cp_users_add_new_success_message']; ?></h1>
								<?php 
								}else {
						            $_SESSION['error'] = $lang['cp_users_add_new_error_message'];
						          }
								?>
				              <?php } } ?>


				              <?php $sError = ""; 
				              if (isset($_SESSION['error'])) { ?>
				                <p id="error"><?php echo $_SESSION['error']; ?></p>
				              <?php } ?>
				              <?php echo $sError; ?>


								<form id="backend-register-form" name="register" method="post">
									<div class="row form-group">
										<label class="col-md-4"><?php echo $lang['role']; ?></label>
										<div class="col-md-7">
											<select class="form-control" name="user_role">
												<option>Administrator</option>
												<option>Client</option>
											</select>
										</div>
									</div>
									<div class="row form-group">
										<label class="col-md-4"><?php echo $lang['Username']; ?></label>
										<div class="col-md-7">
											<input class="form-control" type="text" placeholder="<?php echo $lang['Username']; ?>" name="user_name" />
										</div>
									</div>
									<div class="row form-group">
										<label class="col-md-4"><?php echo $lang['Name_and_surname']; ?></label>
										<div class="col-md-7">
											<input class="form-control" type="text" placeholder="<?php echo $lang['Name_and_surname']; ?>" name="user_nice_name" />
										</div>
									</div>
									<div class="row form-group">
										<label class="col-md-4"><?php echo $lang['role']; ?></label>
										<div class="col-md-7">
											<input class="form-control" type="text" placeholder="<?php echo $lang['Email']; ?>" name="user_email" />
										</div>
									</div>
									<div class="row form-group">
										<label class="col-md-4"><?php echo $lang['Password']; ?></label>
										<div class="col-md-7">
											<input class="form-control" type="password" placeholder="<?php echo $lang['Password']; ?>" name="user_password" />
										</div>
									</div>
									<input type="submit" name="submit" class="btn btn-success" value="<?php echo $lang['CP_REGISTER']; ?>" />
								</form>
								
				            </div>
				          </div>
					</div>


					<table class="user-list middle-area table-fill">
						<thead>
							<tr>
								<th>#</th>
								<th><?php echo $lang['role_user']; ?></th>
								<th><?php echo $lang['Username']; ?></th>		
								<th><?php echo $lang['Name_and_surname']; ?></th>
								<th><?php echo $lang['Email']; ?></th>
								<th><?php echo $lang['Phone_number']; ?></th>
								<th><?php echo $lang['cp_table_actions']; ?></th>
							</tr>
						</thead>
						<?php 
							$query_user = mysqli_query($con, "SELECT DISTINCT user_role FROM users") or trigger_error("Query Failed: " . mysqli_error($con));
				      		while($query_users = mysqli_fetch_array($query_user)) {
						?>
							<tbody class="table-hover hidden-tbody <?php echo $query_users['user_role']; ?>">
							<?php 
								$role = $query_users['user_role'];
								$query_userlist1 = mysqli_query($con, "SELECT * FROM users WHERE user_role = '$role' ") or trigger_error("Query Failed: " . mysqli_error($con));
					      		while($query_userlist2 = mysqli_fetch_array($query_userlist1)) {
							?>
								<tr>
									<td><?php echo $query_userlist2['user_id']; ?></td>
									<td><?php echo $query_userlist2['user_role']; ?></td>
									<td><?php echo $query_userlist2['user_name']; ?></td>
									<td><?php echo $query_userlist2['user_nice_name']; ?></td>
									<td><?php echo $query_userlist2['user_email']; ?></td>
									<td><?php echo $query_userlist2['user_phone']; ?></td>
									<td>
										<?php include 'list-users-modal.php'; ?>
			                            <script type="text/javascript">
			                                //Bootstrap modal
			                                jQuery( ".edit_row" ).click(function() {
			                                    jQuery(this).parent().find('#modal_<?php echo $query_userlist2['user_id']; ?>').modal({
			                                        keyboard: false
			                                    })
			                                });
			                            </script>
										<div class="pull-left divider-right">
											<button class="edit_row label label-warning" data-toggle="modal" data-target="#modal_<?php echo $query_userlist2['user_id']; ?>"><i class="fa fa-pencil-square-o"></i></button>
										</div>
										<form class="pull-left remove_db_item" method="POST">
											<input type="hidden" name="user_id" value="<?php echo $query_userlist2['user_id']; ?>" />
											<button class="remove-db-item-btn label label-warning" type="submit" name="remove_user" onclick="return confirm('<?php echo $lang['cp_user_are_you_sure']; ?>')"><i class="fa fa-times"></i></button>
											<p class="p-update-user-informations"><?php echo $lang['success']; ?></p>
										</form>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
	<?php }
} else { ?>
   		<script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
<?php } ?>


</body>
</html>