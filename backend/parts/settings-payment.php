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
    <title><?php echo $lang['CP_BASE_TITLE'] . $lang['payment_settings']; ?></title>
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
                    <?php if(isset($_POST['phpr_payment_methods'])){
                        //Payment methods
                        $paypal_email = $_POST['paypal_email'];
                        $paypal_sandbox = $_POST['paypal_sandbox'];

                        //Query update DB
                        $query_update = "UPDATE informations SET 
                        paypal_email='$paypal_email', 
                        paypal_sandbox='$paypal_sandbox'";
                        mysqli_query($con, $query_update);
                        #Success message ?>
                        <div class="container">
                            <div role="alert" class="alert alert-success">
                              <?php echo $lang['cp_settings_success_message']; ?>
                            </div>
                        </div>
                    <?php } ?>

                    <h1>Payment settings</h1>

                    <form class="change_contact_infos col-md-7" method="POST">
                        <div class="group_label_input settings">
                            <label class="col-md-5"><?php echo $lang['paypal_email']; ?></label>
                            <div class="col-md-7">
                                <input class="col-md-7 form-control" value="<?php echo $information['paypal_email']; ?>" name="paypal_email" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-5"><?php echo $lang['paypal_live_or_sandbox']; ?></label>
                            <div class="col-md-7">
                                <select name="paypal_sandbox" class="form-control">
                                    <option <?php if($information['paypal_sandbox'] == 'Live'){echo 'selected'; } ?> value="Live">Live</option>
                                    <option <?php if($information['paypal_sandbox'] == 'Sandbox'){echo 'selected'; } ?> value="Sandbox">Sandbox</option>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="submit" name="phpr_payment_methods" class="btn btn-success" value="<?php echo $lang['cp_settings_save_changes']; ?>" />
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