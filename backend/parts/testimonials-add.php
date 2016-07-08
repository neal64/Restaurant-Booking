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
	<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['testimonials_add']; ?></title>
</head>


<body class="backend row index-events menu-block-backend">
<!-- ####### HEADER for logged in users ############################################################## -->
<?php if (loggedIn()) { //if A	
	$query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
    $query2 = mysqli_fetch_array($query);

 	// if user_role is Administrator 
 	if ($query2['user_role'] == 'Administrator' || $query2['user_role'] == 'Manager') { ?>
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
	        <div class="col-md-12 testimonial-list top-area">
        		<div class="top_area_margin">
        			<h1 class="clearfix"><?php echo $lang['testimonials_add']; ?></h1>

        			<form id="add_events_form" class="add_testimonials col-md-9" method="POST" enctype="multipart/form-data">
        				<div class="row">
                            <div class="col-md-12 input_holder">
                                <div class="row">
                                    <div class="titles_infos_group">
                                        <label class="col-md-4">Thumbnail</label>
                                        <div class="col-md-8">
                                            <div class="relative">
                                                <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
                            					<input type="file" name="testimonial_thumb" class="file" />
                            			        <div class="fake_input_holder">
                            			            <input required type="button" value="<?php echo $lang['cp_event_picture']; ?>" />
                                                </div>
                        			        </div>
                                        </div>
                                    </div>

                                    <div class="titles_infos_group">
                                        <label class="col-md-4">Client name</label>
                                        <div class="col-md-8">
                                            <input required type="text" class="form-control" name="testimonal_client_name" placeholder="Client name" />
                                        </div>
                                    </div>

                                    <div class="titles_infos_group">
                                        <label class="col-md-4">Client job</label>
                                        <div class="col-md-8">
                                            <input required type="text" class="form-control" name="testimonial_client_job" placeholder="Client job" />
                                        </div>
                                    </div>

                                    <div class="titles_infos_group">
                                        <label class="col-md-4">Client works at</label>
                                        <div class="col-md-8">
                                            <input required type="text" class="form-control" name="testimonial_works_at" placeholder="Client works at" />
                                        </div>
                                    </div>

                                    <div class="titles_infos_group">
                                        <label class="col-md-4">Content</label>
                                        <div class="col-md-8">
                                            <textarea required placeholder="Content" class="form-control" name="testimonial_content"></textarea>
                                        </div>
                                    </div>
                                    <input type="submit" name="submit_event" value="Add testimonial" class="btn btn-success" />
            				    </div>
                            </div>
                        </div>
        			</form>
        		</div>

                <?php
                    #Adding an Event
                    if(isset($_POST["submit_event"])) {
                        // infos from form names using $_POST[];
                        $testimonal_client_name = htmlspecialchars( (string)$_POST["testimonal_client_name"] );
                        $testimonial_content = htmlspecialchars( (string)$_POST["testimonial_content"] );
                        $testimonial_client_job = htmlspecialchars( (string)$_POST["testimonial_client_job"] );
                        $testimonial_works_at = htmlspecialchars( (string)$_POST["testimonial_works_at"] );

                        if(isset($_FILES['testimonial_thumb'])){
                            $errors= array();
                            $file_name = $_FILES['testimonial_thumb']['name'];
                            $file_size =$_FILES['testimonial_thumb']['size'];
                            $file_tmp =$_FILES['testimonial_thumb']['tmp_name'];
                            $file_type=$_FILES['testimonial_thumb']['type'];   

                            $file_ext = explode('.', $file_name);
                            $extension = end($file_ext);

                            $testimonial_thumb = 'skin/images/testimonials/'.$file_name;

                            $expensions= array("jpeg","jpg","png","gif","bmp");         
                            if(in_array($extension,$expensions)=== false){
                                $errors[]= $lang['cp_event_error'] . "jpeg, jpg, png, gif, bmp.";
                            }
                            if($file_size > 4194304){
                                $errors[] = $lang['cp_food_picture'];
                            }               
                            if(empty($errors)==true){
                                move_uploaded_file($file_tmp,'../../skin/images/testimonials/'.$file_name);
                            }else{
                                //print_r($errors);
                            }
                        }

                        #Query to insert in the database the event
                        $query_orderuser = "INSERT INTO testimonials (
                            testimonal_client_name,
                            testimonial_client_job,
                            testimonial_works_at,
                            testimonial_thumb,
                            testimonial_content
                        ) VALUES (
                            '$testimonal_client_name',
                            '$testimonial_client_job',
                            '$testimonial_works_at',
                            '$testimonial_thumb',
                            '$testimonial_content'
                        );";
                        mysqli_query($con, $query_orderuser); 
                        #Success message ?>
                        <div role="alert" class="alert alert-success">
                          <?php echo $lang['cp_event_message_added']; ?>
                        </div>
                    <?php } ?>
            </div>
        </div>
    </div>

<?php }
} else { ?>
   		<script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
<?php } ?>


</body>
</html>