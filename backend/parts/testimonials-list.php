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
	<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['testimonials_list']; ?></title>
</head>


<body class="backend row index-events menu-block-backend">
<!-- ####### HEADER for logged in users ############################################################## -->
<?php if (loggedIn()) { //if A	
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
	        <div class="col-md-12 testimonial-list top-area">
                <h1 class="clearfix"><?php echo $lang['testimonials_list']; ?></h1>
                <?php
                    #Removing an Event
                    if(isset($_POST['remove_this_event'])){
                        #Get event ID
                        $remove_this_testimonial_id = $_POST['remove_this_testimonial_id'];
                        #Query to remove the event
                        $query_edit_menu = "DELETE FROM testimonials WHERE testimonial_id='$remove_this_testimonial_id'";
                        mysqli_query($con, $query_edit_menu);
                        #Success message ?>
                        <div role="alert" class="alert alert-success">
                          <?php echo $lang['cp_event_message_deleted']; ?>
                        </div>
                    <?php }

                    #Modify an Event
                    if(isset($_POST["update_submit_event"])){
                        $update_testimonal_client_name = htmlspecialchars( (string)$_POST["update_testimonal_client_name"] );
                        $update_testimonial_content = htmlspecialchars( (string)$_POST["update_testimonial_content"] );
                        $update_testimonial_client_job = htmlspecialchars( (string)$_POST["update_testimonial_client_job"] );
                        $update_testimonial_works_at = htmlspecialchars( (string)$_POST["update_testimonial_works_at"] );

                        $update_testimonial_id = $_POST['update_testimonial_id'];

                        if(isset($_FILES['update_testimonial_thumb']) && $_FILES['update_testimonial_thumb']['name']){
                            $errors= array();
                            $file_name = $_FILES['update_testimonial_thumb']['name'];
                            $file_size =$_FILES['update_testimonial_thumb']['size'];
                            $file_tmp =$_FILES['update_testimonial_thumb']['tmp_name'];
                            $file_type=$_FILES['update_testimonial_thumb']['type'];   

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
                            #Query to edit the event
                            $query_edit_menu = "UPDATE testimonials SET 
                            testimonial_thumb='$testimonial_thumb', 
                            testimonial_thumb='$testimonial_thumb', 
                            testimonal_client_name='$update_testimonal_client_name', 
                            testimonial_client_job='$update_testimonial_client_job', 
                            testimonial_works_at='$update_testimonial_works_at', 
                            testimonial_content='$update_testimonial_content' 
                            WHERE testimonial_id='$update_testimonial_id'";
                            mysqli_query($con, $query_edit_menu);
                        }else{
                            #Query to edit the event
                            $query_edit_menu = "UPDATE testimonials SET 
                            testimonal_client_name='$update_testimonal_client_name', 
                            testimonial_client_job='$update_testimonial_client_job', 
                            testimonial_works_at='$update_testimonial_works_at', 
                            testimonial_content='$update_testimonial_content' 
                            WHERE testimonial_id='$update_testimonial_id'";
                            mysqli_query($con, $query_edit_menu);
                        }
                        

                        #Success message ?>
                        <div role="alert" class="alert alert-success">
                          <?php echo $lang['cp_event_message_modified']; ?>
                        </div>

                    <?php }
                ?>

        	</div>
        	<div class="row bottom_area_margin">
        		<div class="container">
        		<?php $query_testimonials = mysqli_query($con, "SELECT * FROM testimonials") or trigger_error("Query Failed: " . mysqli_error($con));
        		?>
        		<ul class="testimonials_list row">
        			<?php while($query_testimonial = mysqli_fetch_array($query_testimonials)) { 
        				?>
        			<li class="single_testimonial col-md-10">
                        <div class="col-md-2">
						    <img src="<?php echo '../../system/timthumb.php?src=' . $CONF['installation_path'] . $query_testimonial['testimonial_thumb'] . '&amp;h=150&amp;w=150&amp;zc=1'; ?>" alt="<?php echo $query_testimonial['testimonal_client_name']; ?>" />
                        </div>
                        <div class="col-md-10">
                            <h4>
                                <strong><?php echo $query_testimonial['testimonal_client_name']; ?></strong>
                                - <?php echo $query_testimonial['testimonial_client_job']; ?> <strong>at</strong> <?php echo $query_testimonial['testimonial_works_at']; ?>
                            </h4>
                            <p><?php echo $query_testimonial['testimonial_content']; ?></p>
                            <div class="actions">
                                
                                <!-- Edit event -->
                                <?php include 'testimonials-modal.php'; ?>
                                <button data-toggle="modal" data-target="#modal_<?php echo $query_testimonial['testimonial_id']; ?>" class="edit_row edit_testimonial phpr_edit_event_modal btn btn-info btn-xs pull-left md-trigger"><i class="fa fa-pencil-square-o"></i> Edit</a>

                                <!-- Remove event -->
                                <form method="POST" class="mgf_remove_event">
                                    <input type="hidden" name="remove_this_testimonial_id" value="<?php echo $query_testimonial['testimonial_id']; ?>">
                                    <button onclick="return confirm(<?php echo "'" . $lang['cp_event_are_you_sure'] . "'"; ?>)" name="remove_this_event" type="submit" class="phpr_delete_event_modal btn btn-danger btn-xs pull-left"><i class="fa fa-times"></i>Delete</button>
                                </form>
                            </div>
                        </div>
        			</li>
        			<?php } ?>
        		</ul>
        		</div>
        	</div>



<?php }
} else { ?>
   		<script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
<?php } ?>


</body>
</html>