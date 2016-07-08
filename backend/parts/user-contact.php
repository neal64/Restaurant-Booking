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
        <title><?php echo $lang['CP_BASE_TITLE']; ?>Ratting & Review</title>
    </head>


    <body class="backend row index-list-users">
        <!-- ####### HEADER for logged in users ############################################################## -->
        <?php
        if (loggedIn()) {

            $query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
            $query2 = mysqli_fetch_array($query);

            // if user_role is Administrator 
            if ($query2['user_role'] == 'Administrator') {
                ?>
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
                    <?php } else if ($query2['user_role'] == 'Client') { ?>
                        <?php include('menu-clients.php'); ?>
                    <?php } ?>
                </div>

                <div class="col-md-10 v2-page-content">
                    <?php
                    #Remove user
                    if (isset($_POST["remove_rate"])) {
                        $id = $_POST['rate_id'];

                        $query_edit_menu = "update contact_us set flag='0' WHERE id='$id'";
                        mysqli_query($con, $query_edit_menu);
                        ?>

                        <div role="alert" class="alert alert-success">
                            <?php echo $lang['cp_event_message_deleted']; ?>
                        </div>

                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="user-list top-area">
                                <div class="top_area_margin"></div>
                                <div class="clearfix"></div>
                            </div>
                            <table class="user-list middle-area table-fill">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Date</th>
                                        <th><?php echo $lang['cp_table_actions']; ?></th>
                                    </tr>
                                </thead>
                                <?php
                                $query_user = mysqli_query($con, "SELECT DISTINCT user_role FROM users") or trigger_error("Query Failed: " . mysqli_error($con));
                                while ($query_users = mysqli_fetch_array($query_user)) {
                                    ?>
                                    <tbody class="table-hover hidden-tbody <?php echo $query_users['user_role']; ?>">
                                        <?php
                                        $role = $query_users['user_role'];
                                        $query_userlist1 = mysqli_query($con, "SELECT * FROM contact_us where flag='1'") or trigger_error("Query Failed: " . mysqli_error($con));
                                        $aa = 0;
                                        while ($query_userlist2 = mysqli_fetch_array($query_userlist1)) {
                                            $aa++;
                                            ?>
                                            <tr>
                                                <td><?php echo $aa; ?></td>
                                                <td><?php echo $query_userlist2['name']; ?></td>
                                                <td><?php echo $query_userlist2['email']; ?></td>
                                                <td><?php echo $query_userlist2['subject']; ?></td>
                                                <td><?php echo $query_userlist2['message']; ?></td>
                                                <td><?php echo $query_userlist2['date']; ?></td>
                                                <td>
                                                    <form class="pull-left remove_db_item" method="POST">
                                                        <input type="hidden" name="rate_id" value="<?php echo $query_userlist2['id']; ?>" />
                                                        <button class="remove-db-item-btn label label-warning" type="submit" name="remove_rate" onclick="return confirm('<?php echo $lang['cp_user_are_you_sure']; ?>')"><i class="fa fa-times"></i></button>
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
                <?php
            }
        } else {
            ?>
            <script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
        <?php } ?>


    </body>
</html>