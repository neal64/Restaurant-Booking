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
	<title><?php echo $lang['CP_BASE_TITLE'] . $lang['CP_LIST_MENUS_PAGE_TITLE']; ?></title>
</head>


<body class="backend row menu-block-backend index-specialities">
<!-- ####### HEADER for logged in users ############################################################## -->
<?php if (loggedIn()) { //if A
		
	$query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
    	$query2 = mysqli_fetch_array($query); ?>


		 	<?php 
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
						<div class="menu-list top-area">
							<div class="show_menu_type">
								<div class="top_area_margin">
									<h2 class="pull-left select_order_type"><?php echo $lang['cp_menus_filter']; ?></h2>
									<div class="pull-left filter_foods">
										<select id="select_order_type" class="select_menu_type btn btn-success">
										<?php
											$query_menu_category = mysqli_query($con, "SELECT DISTINCT menu_item_category FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));
								      		while($query_menu_categories = mysqli_fetch_array($query_menu_category)) {
												$menu_item_category = $query_menu_categories["menu_item_category"];
												$replace_menu_href_link = str_replace(" ","_",$menu_item_category);
												$replace_menu_href_link2 = str_replace(str_split("-,/*&'."), "", $replace_menu_href_link);
												$menu_href_link = strtolower($replace_menu_href_link2);
										?>
											<option value="<?php echo $menu_href_link; ?>"><?php echo $query_menu_categories['menu_item_category']; ?></option>
										<?php } ?>
										</select>
									</div>
									<div class="pull-left add-new-item-btn">
										<span class="btn btn-success onclick-register-form"><?php echo $lang['cp_add_new_food']; ?></span>
									</div>
								</div>

								<?php 
								#Editing a food
								if(isset($_POST["submit_edit_menu"])){
									$menu_item_name 			= $_POST["menu_item_name"];
									$menu_item_details 			= $_POST["menu_item_details"];
									$menu_item_price_per_slice 	= $_POST["menu_item_price_per_slice"];
									$menu_item_id 				= $_POST['menu_item_id'];

									if(isset($_FILES['menu_preview_image']) && $_FILES['menu_preview_image']['name']){
										$errors= array();
										$file_name 	=  	$_FILES['menu_preview_image']['name'];
										$file_size 	=  	$_FILES['menu_preview_image']['size'];
										$file_tmp 	=  	$_FILES['menu_preview_image']['tmp_name'];
										$file_type	=  	$_FILES['menu_preview_image']['type'];   

										$file_ext 	= 	explode('.', $file_name);
										$extension 	= 	end($file_ext);

										$menu_preview_image = 'skin/images/menus/'.$file_name;

										$extensions= array("jpeg","jpg","png","gif","bmp"); 		
										if(in_array($extension,$extensions)=== false){
											$errors[]= $lang['cp_event_error'] . "jpeg, jpg, png, gif, bmp.";
										}
										if($file_size > 4194304){
										  $errors[] = $lang['cp_food_picture'];
										}				
										if(empty($errors)==true){
											move_uploaded_file($file_tmp,'../../skin/images/menus/'.$file_name);
										}else{
											//print_r($errors);
										}
										
										$query_edit_menu = "UPDATE menus SET menu_preview_image='$menu_preview_image', menu_item_name='$menu_item_name', menu_item_details='$menu_item_details', menu_item_price_per_slice='$menu_item_price_per_slice' WHERE menu_item_id='$menu_item_id'";
										mysqli_query($con, $query_edit_menu); 
									}else{
										$menu_preview_image = $_POST["menu_preview_image"];
										$query_edit_menu = "UPDATE menus SET menu_item_name='$menu_item_name', menu_item_details='$menu_item_details', menu_item_price_per_slice='$menu_item_price_per_slice' WHERE menu_item_id='$menu_item_id'";
										mysqli_query($con, $query_edit_menu); 
									}

									?>

					                <div role="alert" class="alert alert-success">
					                  <?php echo $lang['cp_food_message_modified']; ?>
					                </div>

								<?php } 

								#Removing a food
								if(isset($_POST["remove_menu_item"])){
									$menu_item_id = $_POST['menu_item_id'];
									$query_edit_menu = "DELETE FROM menus WHERE menu_item_id='$menu_item_id'";
									mysqli_query($con, $query_edit_menu); ?>

					                <div role="alert" class="alert alert-success">
					                  <?php echo $lang['cp_food_message_deleted']; ?>
					                </div>

								<?php }

								#Adding a food
								if(isset($_POST["submitmenu"])){
									$menu_item_category = $_POST["menu_item_category"];
									$menu_item_name = $_POST["menu_item_name"];
									$menu_item_details = $_POST["menu_item_details"];
									$menu_item_price_per_slice = $_POST["menu_item_price_per_slice"];
									
									if(isset($_FILES['menu_preview_image'])){
										$errors= array();
										$file_name = $_FILES['menu_preview_image']['name'];
										$file_size =$_FILES['menu_preview_image']['size'];
										$file_tmp =$_FILES['menu_preview_image']['tmp_name'];
										$file_type=$_FILES['menu_preview_image']['type'];   

										$file_ext = explode('.', $file_name);
										$extension = end($file_ext);

										$menu_preview_image = 'skin/images/menus/'.$file_name;

										$extensions= array("jpeg","jpg","png","gif","bmp"); 		
										if(in_array($extension,$extensions)=== false){
											$errors[]= $lang['cp_event_error'] . "jpeg, jpg, png, gif, bmp.";
										}
										if($file_size > 4194304){
										  $errors[] = $lang['cp_food_picture'];
										}				
										if(empty($errors)==true){
											move_uploaded_file($file_tmp,'../../skin/images/menus/'.$file_name);
										}else{
											//print_r($errors);
										}
									}

									$menu_item_author = $_POST["menu_item_author"];
									// replace space with _
									$replace_menu_href_link = str_replace(" ","_",$menu_item_category);
									$menu_href_link = strtolower($replace_menu_href_link);

									$query_orders = "INSERT INTO menus (menu_item_category,menu_item_name,menu_item_details,menu_preview_image, menu_item_price_per_slice,menu_item_author) 
									VALUES ('$menu_item_category','$menu_item_name','$menu_item_details','$menu_preview_image', '$menu_item_price_per_slice', '$menu_item_author');";
									mysqli_query($con, $query_orders); ?>

					                <div role="alert" class="alert alert-success">
					                  <?php echo $lang['cp_food_message_added']; ?>
					                </div>

								<?php } ?>


								<div class="user-list menu-list bottom-area">
							        <div class="boxed-register-block unboxed-form toggle-register-form"> 
							          <div class="block-title">
							            <span><?php echo $lang['cp_food_specifications']; ?></span>
							          </div>
							          <div class="block-content">
											<form enctype="multipart/form-data" method="POST">
												<div class="row form-group">
													<label class="col-md-4"><?php echo $lang['cp_food_category']; ?></label>
													<div class="col-md-7">
														<input required class="form-control add-category" placeholder="<?php echo $lang['cp_food_example_name']; ?>" type="text" name="menu_item_category" />
													</div>
												</div>
												<div class="row form-group">
													<label class="col-md-4"><?php echo $lang['cp_food_choose_category']; ?></label>
													<div class="col-md-7">
														<select class="form-control copy-to-category">
															<?php
																$query_menu_category = mysqli_query($con, "SELECT DISTINCT menu_item_category FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));
													      		while($query_menu_categories = mysqli_fetch_array($query_menu_category)) {
															?>
																<option><?php echo $query_menu_categories['menu_item_category']; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="row form-group">
													<label class="col-md-4"><?php echo $lang['cp_food_name']; ?></label>
													<div class="col-md-7">
														<input required class="form-control" placeholder="<?php echo $lang['cp_food_example_name']; ?>" type="text" name="menu_item_name" />
													</div>
												</div>
												<div class="row form-group">
													<label class="col-md-4"><?php echo $lang['Details']; ?></label>
													<div class="col-md-7">
														<input required class="form-control" placeholder="<?php echo $lang['cp_food_example_details']; ?>" type="text" name="menu_item_details" />
													</div>
												</div>
												<div class="row form-group">
													<label class="col-md-4"><?php echo $lang['Price']; ?></label>
													<div class="col-md-7">
														<input required class="form-control" placeholder="<?php echo $lang['cp_food_example_price']; ?>" type="text" name="menu_item_price_per_slice" />
													</div>
												</div>
												<div class="row form-group">
													<label class="col-md-4"><?php echo $lang['cp_event_picture']; ?></label>
													<input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
													<div class="inputs_holder col-md-7">
														<input type="file" class="file" name="menu_preview_image" />
												        <div class="fake_input_holder">
												            <input type="button" value="<?php echo $lang['cp_food_picture']; ?>" />
												        </div>
												    </div>
												</div>

												<!--Hidden input: Author (Nice Name) -->
												<input type="hidden" name="menu_item_author" value="<?php echo $query2['user_nice_name']; ?>" />
												<input type="submit" name="submitmenu" class="btn btn-success" value="<?php echo $lang['cp_food_add']; ?>" />
												<p id="sendmailsuccess"><?php echo $lang['cp_food_submit_success']; ?></p>
											</form>
							            </div>
							          </div>
								</div>
							</div>
						</div>
						<table class="menu-list-table middle-area table-fill">
							<thead>
								<tr>
									<th>#</th>
									<th><?php echo $lang['cp_menu_preview_image']; ?></th>
									<th><?php echo $lang['cp_table_category']; ?></th>
									<th><?php echo $lang['cp_table_name']; ?></th>		
									<th><?php echo $lang['cp_table_details']; ?></th>
									<th><?php echo $lang['cp_table_price']; ?></th>
									<th><?php echo $lang['cp_table_actions']; ?></th>
								</tr>
							</thead>

							<?php
								$query_menu_category = mysqli_query($con, "SELECT DISTINCT menu_item_category FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));
					      		while($query_menu_categories = mysqli_fetch_array($query_menu_category)) {
								$menu_item_category = $query_menu_categories["menu_item_category"];
								$replace_menu_href_link = str_replace(" ","_",$menu_item_category);
								$replace_menu_href_link2 = str_replace(str_split("-,/*&'."), "", $replace_menu_href_link);
								$menu_href_link = strtolower($replace_menu_href_link2);
							?>
							
							<tbody class="table-hover hidden-tbody <?php echo $menu_href_link; ?>">
							<?php
								$category = $query_menu_categories['menu_item_category'];
								$query_userlist1 = mysqli_query($con, "SELECT * FROM menus WHERE menu_item_category = '$category' ORDER BY `menu_date` DESC") or trigger_error("Query Failed: " . mysqli_error($con));
					      		while($query_userlist2 = mysqli_fetch_array($query_userlist1)) {
							?>
								<tr>							
									<td><?php echo $query_userlist2['menu_item_id']; ?></td>
									<td>
										<a title="<?php echo $query_userlist2['menu_item_name']; ?>" data-lightbox="gallery-popup" href="<?php echo $CONF['installation_path'] . $query_userlist2['menu_preview_image']; ?>">
											<img alt="<?php echo $query_userlist2['menu_item_name']; ?>" src="<?php echo $CONF['installation_path'] . 'system/timthumb.php?src=' . $CONF['installation_path'] . $query_userlist2['menu_preview_image'] . '&amp;h=50&amp;w=70&amp;zc=1'; ?>" />
										</a>
									</td>
									<td><?php echo $query_userlist2['menu_item_category']; ?></td>
									<td><?php echo $query_userlist2['menu_item_name']; ?></td>
									<td><?php echo $query_userlist2['menu_item_details']; ?></td>
									<td><?php echo $query_userlist2['menu_item_price_per_slice'] . $lang['Base_currency']; ?></td>
									<td>
										<?php include 'list-menus-modal.php'; ?>
		                                <script type="text/javascript">
		                                    //Bootstrap modal
		                                    jQuery( ".edit_row" ).click(function() {
			                                    jQuery(this).parent().find('#modal_<?php echo $query_userlist2['menu_item_id']; ?>').modal({
			                                        keyboard: false
			                                    })
		                                    });
		                                </script>
										<div class="pull-left divider-right">
											<button class="edit_row label label-warning" data-toggle="modal" data-target="#modal_<?php echo $query_userlist2['menu_item_id']; ?>"><i class="fa fa-pencil-square-o"></i></button>
										</div>
										<form class="pull-left remove_db_item" method="POST">
											<input type="hidden" name="menu_item_id" value="<?php echo $query_userlist2['menu_item_id']; ?>" />
											<button type="submit" class="remove-db-item-btn label label-warning" onclick="return confirm('<?php echo $lang['cp_food_are_you_sure']; ?>')" name="remove_menu_item"><i class="fa fa-times"></i></button>
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