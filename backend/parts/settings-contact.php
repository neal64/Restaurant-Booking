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
    <title><?php echo $lang['CP_BASE_TITLE'] . $lang['cp_settings_change_contact_informations']; ?></title>
    <script type='text/javascript' src='ckeditor.js'></script>
</head>

<body class="backend row index-edit-user-profile">
    <!-- ####### HEADER for logged in users ############################################################## -->
    <?php if (loggedIn()) { 

        $query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") 
        or trigger_error("Query Failed: " . mysqli_error($con));
        $query2 = mysqli_fetch_array($query); 

        //Query infos from DB
        $informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
        $information = mysqli_fetch_array($informations);

        ?>
        
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
                <div class="col-md-12">
                    <?php if(isset($_POST["phpr_contact"])) {
                        //Contact informations
                        $contact_phone_number = $_POST["contact_phone_number"];
                        $contact_email = $_POST["contact_email"];
                        $contact_latitude = $_POST["contact_latitude"];
                        $contact_longitude = $_POST["contact_longitude"];
                        $contact_address = $_POST["contact_address"];
                        $contact_monday_hours = $_POST['contact_monday_hours'];
                        $contact_tuesday_hours = $_POST['contact_tuesday_hours'];
                        $contact_wednesday_hours = $_POST['contact_wednesday_hours'];
                        $contact_thursday_hours = $_POST['contact_thursday_hours'];
                        $contact_friday_hours = $_POST['contact_friday_hours'];
                        $contact_saturday_hours = $_POST['contact_saturday_hours'];
                        $contact_sunday_hours = $_POST['contact_sunday_hours'];
                        $wysiwyg_contact = $_POST['wysiwyg_contact'];
                        if ( get_magic_quotes_gpc() )
                            $wysiwyg_contact2 = htmlspecialchars( stripslashes((string)$wysiwyg_contact) );
                        else
                            $wysiwyg_contact2 = htmlspecialchars( (string)$wysiwyg_contact );

                        //Query update DB
                        $query_update = "UPDATE informations SET 
                        contact_address='$contact_address', 
                        contact_email='$contact_email', 
                        wysiwyg_contact='$wysiwyg_contact2', 
                        contact_phone_number='$contact_phone_number', 
                        contact_latitude='$contact_latitude', 
                        contact_longitude='$contact_longitude', 
                        contact_monday_hours='$contact_monday_hours',
                        contact_tuesday_hours='$contact_tuesday_hours',
                        contact_wednesday_hours='$contact_wednesday_hours',
                        contact_thursday_hours='$contact_thursday_hours',
                        contact_friday_hours='$contact_friday_hours',
                        contact_saturday_hours='$contact_saturday_hours',
                        contact_sunday_hours='$contact_sunday_hours'";
                        mysqli_query($con, $query_update);
                        #Success message ?>
                        <div class="container">
                            <div role="alert" class="alert alert-success">
                              <?php echo $lang['cp_settings_success_message']; ?>
                            </div>
                        </div>
                    <?php } ?>


                    <h1><?php echo $lang['cp_settings_change_contact_informations']; ?></h1>

                    <form class="change_contact_infos col-md-12" method="POST">
                        <div class="group_label_input settings">
                            <textarea class="ckeditor" id="wysiwyg_contact" name="wysiwyg_contact"><?php echo $information['wysiwyg_contact']; ?></textarea>
                        </div> 
                            
                        <div class="row"> 
                            <div class="col-md-7"> 
                                <h2><?php echo $lang['cp_settings_general_informations']; ?></h2>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['cp_settings_restaurant_phone']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_phone_number" placeholder="<?php echo $lang['cp_settings_restaurant_phone_eg']; ?>" value="<?php echo $information['contact_phone_number']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['cp_settings_restaurant_email']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_email" placeholder="<?php echo $lang['cp_settings_restaurant_email_eg']; ?>" value="<?php echo $information['contact_email']; ?>" />
                                    </div>
                                </div>
                                <h2><?php echo $lang['cp_settings_map_location']; ?></h2>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['cp_settings_lat']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_latitude" placeholder="<?php echo $lang['cp_settings_lat_eg']; ?>" value="<?php echo $information['contact_latitude']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['cp_settings_long']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_longitude" placeholder="<?php echo $lang['cp_settings_long_eg']; ?>" value="<?php echo $information['contact_longitude']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['Address']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_address" placeholder="<?php echo $lang['cp_settings_restaurant_address_eg']; ?>" value="<?php echo $information['contact_address']; ?>" />
                                    </div>
                                </div>

                                <h2><?php echo $lang['cp_settings_open_hours']; ?></h2>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['Monday']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_monday_hours" placeholder="<?php echo $lang['cp_settings_open_hours_week_eg']; ?>" value="<?php echo $information['contact_monday_hours']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['Tuesday']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_tuesday_hours" placeholder="<?php echo $lang['cp_settings_open_hours_week_eg']; ?>" value="<?php echo $information['contact_tuesday_hours']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['Wednesday']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_wednesday_hours" placeholder="<?php echo $lang['cp_settings_open_hours_week_eg']; ?>" value="<?php echo $information['contact_wednesday_hours']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['Thursday']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_thursday_hours" placeholder="<?php echo $lang['cp_settings_open_hours_week_eg']; ?>" value="<?php echo $information['contact_thursday_hours']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['Friday']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_friday_hours" placeholder="<?php echo $lang['cp_settings_open_hours_week_eg']; ?>" value="<?php echo $information['contact_friday_hours']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['Saturday']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_saturday_hours" placeholder="<?php echo $lang['cp_settings_open_hours_week_eg']; ?>" value="<?php echo $information['contact_saturday_hours']; ?>" />
                                    </div>
                                </div>
                                <div class="group_label_input settings">
                                    <label class="col-md-5"><?php echo $lang['Sunday']; ?></label>
                                    <div class="col-md-7">
                                        <input class="col-md-7 form-control" type="text" name="contact_sunday_hours" placeholder="<?php echo $lang['cp_settings_open_hours_week_eg']; ?>" value="<?php echo $information['contact_sunday_hours']; ?>" />
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <input type="submit" name="phpr_contact" class="btn btn-success" value="<?php echo $lang['cp_settings_save_changes']; ?>" />
                            </div> 
                        </div> 
                    </form>
                </div>
            </div>
        </div>

    <!-- ####### HEADER for logged in users ############################################################## -->
    <?php } else { ?>
        <script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
    <?php } ?>

</body>
</html>