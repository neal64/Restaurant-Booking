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
    <title><?php echo $lang['CP_BASE_TITLE'] . $lang['social_media']; ?></title>
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
                <?php if(isset($_POST["phpr_social_media"])) {
                        //Social media
                        $social_fb = $_POST['social_fb'];
                        $social_tw = $_POST['social_tw'];
                        $social_gplus = $_POST['social_gplus'];
                        $social_dribbble = $_POST['social_dribbble'];
                        $social_stumbleupon = $_POST['social_stumbleupon'];
                        $social_linkedin = $_POST['social_linkedin'];
                        $social_pin = $_POST['social_pin'];
                        $social_tumblr = $_POST['social_tumblr'];
                        $social_instagram = $_POST['social_instagram'];
                        $social_vimeo = $_POST['social_vimeo'];
                        $social_flickr = $_POST['social_flickr'];
                        $social_digg = $_POST['social_digg'];
                        $social_youtube = $_POST['social_youtube'];

                        //Query update DB
                        $query_update = "UPDATE informations SET 
                        social_fb = '$social_fb',
                        social_tw = '$social_tw',
                        social_gplus = '$social_gplus',
                        social_dribbble = '$social_dribbble',
                        social_stumbleupon = '$social_stumbleupon',
                        social_linkedin = '$social_linkedin',
                        social_pin = '$social_pin',
                        social_tumblr = '$social_tumblr',
                        social_instagram = '$social_instagram',
                        social_vimeo = '$social_vimeo',
                        social_flickr = '$social_flickr',
                        social_digg = '$social_digg',
                        social_youtube = '$social_youtube'";
                        mysqli_query($con, $query_update);
                        #Success message ?>
                        <div class="container">
                            <div role="alert" class="alert alert-success">
                              <?php echo $lang['cp_settings_success_message']; ?>
                            </div>
                        </div>
                    <?php } ?>

                    <h1>Social media links</h1>

                    <form class="change_contact_infos col-md-7" method="POST">
                        <div class="group_label_input settings">
                            <label class="col-md-3">Facebook url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_fb']; ?>" name="social_fb" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Twitter url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_tw']; ?>" name="social_tw" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Goolge+ url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_gplus']; ?>" name="social_gplus" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Dribbble url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_dribbble']; ?>" name="social_dribbble" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Stumbleupon url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_stumbleupon']; ?>" name="social_stumbleupon" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">LinkedIn url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_linkedin']; ?>" name="social_linkedin" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Pinterest url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_pin']; ?>" name="social_pin" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Tumblr url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_tumblr']; ?>" name="social_tumblr" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Instagram url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_instagram']; ?>" name="social_instagram" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Vimeo url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_vimeo']; ?>" name="social_vimeo" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Flickr url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_flickr']; ?>" name="social_flickr" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">Digg url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_digg']; ?>" name="social_digg" />
                            </div>
                        </div>
                        <div class="group_label_input settings">
                            <label class="col-md-3">YouTube url</label>
                            <div class="col-md-9">
                                <input class="col-md-7 form-control" value="<?php echo $information['social_youtube']; ?>" name="social_youtube" />
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="submit" name="phpr_social_media" class="btn btn-success" value="<?php echo $lang['cp_settings_save_changes']; ?>" />
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