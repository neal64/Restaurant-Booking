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
	<title><?php echo $lang['SITE_BASE_TITLE'] . $lang['CP_ADD_EVENTS_PAGE_TITLE']; ?></title>

	<!-- Date/Time Picker -->
	<link rel='stylesheet' href='<?php echo $CONF['installation_path']; ?>skin/css/bootstrap-datetimepicker.min.css' type='text/css' media='all' />
	<script type="text/javascript" src="<?php echo $CONF['installation_path']; ?>skin/js/bootstrap-datetimepicker.min.js"></script>
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
	        <div class="col-md-12 event-list top-area">
        		<div class="top_area_margin">
        			<h1 class="clearfix"><?php echo $lang['cp_add_new_events']; ?></h1>
        			<form id="add_events_form" class="col-md-9" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="titles_infos_group">
                                <label class="col-md-3"><?php echo $lang['cp_event_name']; ?></label>
                                <div class="col-md-8">
                                    <input required type="text" class="form-control" name="event_name" placeholder="<?php echo $lang['cp_event_name']; ?>" />
                                </div>
                            </div>
                            <div class="titles_infos_group">
                                <label class="col-md-3"><?php echo $lang['cp_event_location']; ?></label>
                                <div class="col-md-8">
                                    <input required type="text" class="form-control" name="event_location" placeholder="<?php echo $lang['cp_event_location']; ?>" />
                                </div>
                            </div>
                            <div class="titles_infos_group">
                                <label class="col-md-3"><?php echo $lang['Date_and_time']; ?></label>
                                <div class="col-md-8">
                                    <div id="datetimepicker1" class="input-append date">
                                        <input class="form-control" required placeholder="<?php echo $lang['Date_and_time']; ?>" name="event_date" data-format="yyyy-MM-dd hh:mm:ss" type="text"></input>
                                        <span class="add-on">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="titles_infos_group">
                                <label class="col-md-3"><?php echo $lang['change_image']; ?></label>
                                <div class="col-md-8">
                                    <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
                                    <input type="file" name="event_thumbnail" class="file" />
                                    <div class="fake_input_holder">
                                        <input required type="button" value="<?php echo $lang['cp_event_picture']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="titles_infos_group">
                                <label class="col-md-3"><?php echo $lang['cp_event_description']; ?></label>
                                <div class="col-md-8">
                                    <textarea required placeholder="<?php echo $lang['cp_event_description']; ?>" class="form-control" name="event_description"></textarea>
                                </div>
                            </div>
            				<input type="submit" name="submit_event" value="<?php echo $lang['cp_event_add']; ?>" class="btn btn-success" />
        				</div>
        			</form>
        		</div>

                <?php if(isset($_POST["submit_event"])) {
                    // infos from form names using $_POST[];
                    $event_name = $_POST["event_name"];
                    $event_date = $_POST["event_date"];
                    $event_location = $_POST["event_location"];
                    $event_description = $_POST["event_description"];
                    if(isset($_FILES['event_thumbnail'])){
                        $errors= array();
                        $file_name = $_FILES['event_thumbnail']['name'];
                        $file_size =$_FILES['event_thumbnail']['size'];
                        $file_tmp =$_FILES['event_thumbnail']['tmp_name'];
                        $file_type=$_FILES['event_thumbnail']['type'];   

                        $file_ext = explode('.', $file_name);
                        $extension = end($file_ext);

                        $event_thumbnail = 'skin/images/events/'.$file_name;

                        $expensions= array("jpeg","jpg","png","gif","bmp");         
                        if(in_array($extension,$expensions)=== false){
                            $errors[]= $lang['cp_event_error'] . "jpeg, jpg, png, gif, bmp.";
                        }
                        if($file_size > 4194304){
                            $errors[] = $lang['cp_food_picture'];
                        }               
                        if(empty($errors)==true){
                            move_uploaded_file($file_tmp,'../../skin/images/events/'.$file_name);
                        }else{
                            //print_r($errors);
                        }
                    }

                    #Query to insert in the database the event
                    $query_orderuser = "INSERT INTO events (event_name,event_thumbnail,event_location, event_date,event_description) VALUES ('$event_name','$event_thumbnail','$event_location','$event_date','$event_description');";
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