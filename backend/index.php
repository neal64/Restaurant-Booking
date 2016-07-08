<?php
//require configuration file
require_once('../configuration.php');
//get languages
require_once('../system/languages.php');
?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <?php require('head.php'); ?>
        <title><?php echo $lang['SITE_BASE_TITLE'] . $lang['INDEX_CONTROL_PANEL']; ?></title>
        <script src="<?php echo $CONF['installation_path']; ?>skin/js/highcharts.js"></script>
        <script src="<?php echo $CONF['installation_path']; ?>skin/js/exporting.js"></script>
    </head>

    <body class="backend row">

        <!-- ####### Logged IN users ############################################################## -->
        <?php
        if (loggedIn()) {

            $query = mysqli_query($con, "SELECT user_role, user_nice_name, user_email FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
            $query2 = mysqli_fetch_array($query);

            // if user_role is Administrator or Manager
            if ($query2['user_role'] == 'Administrator' || $query2['user_role'] == 'Manager') {
                ?>
                <header class="row loggedin">
                    <div class="">
                        <div class="col-md-6">
                            <span class="label label-success pull-left">
                                <?php if ($query2['user_role'] == 'Administrator') { ?>
                                    <?php echo $lang['role']; ?><strong>Administrator</strong>
                                <?php } else if ($query2['user_role'] == 'Manager') { ?>
                                    <?php echo $lang['role']; ?><strong>Manager</strong>
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

                <?php
                // if user_role is Client
            } elseif ($query2['user_role'] == 'Client') {
                ?>
                <script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/parts/user-profile.php'; ?>");</script>
            <?php } ?>

            <!-- ####### Logged OUT users ############################################################## -->
        <?php } else { ?>
            <header class="row loggedin">
                <div class="container">
                    <h2 class="white text-center"><?php echo $lang['cp_login_welcome_message']; ?></h2>
                </div>
            </header>
            <?php include ('../nav.php'); ?>
            <div class="block-index-backend container">
                <div class="col-md-6 user-account">
                    <span><a href="login.php" title="<?php echo $lang['CP_LOGIN']; ?>"><i class="fa fa-share"></i><?php echo $lang['CP_LOGIN']; ?></a></span>
                </div>			
                <div class="col-md-6 user-account">
                    <span><a href="register.php" title="<?php echo $lang['CP_REGISTER']; ?>"><i class="fa fa-plus"></i><?php echo $lang['CP_REGISTER']; ?></a></span>
                </div>
            </div>
        <?php } ?>


        <div class="col-md-2 v2-sidebar-menu">
            <?php include('parts/menu-administrators.php'); ?>
        </div>
        <div class="col-md-10 v2-page-content">
            <?php
            if ($query2['user_role'] == 'Administrator' || $query2['user_role'] == 'Manager') {

                //include index-charts
                include('parts/index-charts.php');
            }
            ?>
        </div>

    </body>
</html>