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
    <title><?php echo $lang['CP_BASE_TITLE'] . $lang['INDEX_ABOUT_US']; ?></title>
</head>

<body class="backend row index-about">
    <?php 
        $informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
        $information = mysqli_fetch_array($informations);
    ?>

    <!-- ####### HEADER for logged in users ############################################################## -->
    <?php if (loggedIn()) { 
        $query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") 
        or trigger_error("Query Failed: " . mysqli_error($con));
        $query2 = mysqli_fetch_array($query); ?>
        


        <?php #HEADER TOP BAR ?>
        <header class="row loggedin">
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
            <div class="col-md-6">
                <p class="pull-right"><?php echo $lang['cp_login_hello'] . $query2['user_nice_name']; ?>! 
                    <a href="<?php echo $CONF['installation_path']; ?>backend/login.php?action=logout">
                        <span class="label label-warning"><?php echo $lang['log_out']; ?></span>
                    </a>
                </p>
            </div>
        </header>



        <?php #HEADER LEFT SIDEBAR ?>
        <div class="col-md-2 v2-sidebar-menu">
            <?php if ($query2['user_role'] == 'Administrator') { ?>
                <?php include('menu-administrators.php'); ?>
            <?php }else if ($query2['user_role'] == 'Client') {  ?>
                <?php include('menu-clients.php');  ?>
            <?php } ?>
        </div>



        <?php #PAGE CONTENT ?>
        <div class="col-md-10 v2-page-content">
            <div class="row">
                <div class="col-md-12">
                    <h1><?php echo $lang['edit_about_us']; ?></h1>

                    <?php if(isset($_POST["submit"])){ 
                        $wysiwyg_about = $_POST['wysiwyg_about'];
                        if ( get_magic_quotes_gpc() )
                            $wysiwyg_about2 = htmlspecialchars( stripslashes((string)$wysiwyg_about) );
                        else
                            $wysiwyg_about2 = htmlspecialchars( (string)$wysiwyg_about );

                        $db_query = "UPDATE informations SET wysiwyg_about='$wysiwyg_about2'";
                        mysqli_query($con, $db_query);
                        ?>
                        <div role="alert" class="alert alert-success">
                          <?php echo $lang['cp_changes_done']; ?>
                        </div>
                    <?php } ?>

                    <form method="POST">
                        <textarea class="ckeditor" id="wysiwyg_about" name="wysiwyg_about"><?php echo $information['wysiwyg_about']; ?></textarea>
                        <input type="submit" name="submit" value="Save" class="btn btn-success" />
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