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

                        $query_edit_menu = "DELETE FROM ratting WHERE id='$id'";
                        mysqli_query($con, $query_edit_menu);
                        ?>

                        <div role="alert" class="alert alert-success">
                            <?php echo $lang['cp_event_message_deleted']; ?>
                        </div>
                        <?php
                    }

                    #Edit user
                    if (isset($_POST["submit_edit_ratting"])) {
                        $rating = $_POST["rating"];
                        $review = $_POST["review"];
                        $rate_id = $_POST['id'];
                        //query
                        $query_edit_menu = "UPDATE ratting SET ratting='$rating', review='$review' WHERE id='$rate_id'";
                        mysqli_query($con, $query_edit_menu);
                        ?>

                        <div role="alert" class="alert alert-success">
                            <?php echo $lang['cp_user_message_modified']; ?>
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
                                        <th>Email</th>
                                        <th>Ratting</th>
                                        <th>Review</th>
                                        <th>date</th>
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
                                        $query_userlist1 = mysqli_query($con, "SELECT r.*,u.* FROM users u join ratting r on u.user_id=r.uid where r.flag='1'") or trigger_error("Query Failed: " . mysqli_error($con));
                                        while ($query_userlist2 = mysqli_fetch_array($query_userlist1)) {
                                            $aa = 0;
                                            ?>
                                            <tr>
                                                <td><?php echo $query_userlist2['id']; ?></td>
                                                <td><?php echo $query_userlist2['user_email']; ?></td>
                                                <td>
                                                    <?php
                                                    $starNumber = $query_userlist2['ratting'];
                                                    $aa++;
                                                    ?>
                                                    <fieldset class="rating" >
                                                        <input class="stars" type="radio" id="star5<?php echo $aa;?>" value="5" <?php if ($starNumber == '5') {  echo "checked"; }else{ echo "disabled"; } ?> />
                                                        <label class = "full" for="star5<?php echo $aa;?>" title="Awesome - 5 stars"></label>
                                                        <input class="stars" type="radio" id="star4half<?php echo $aa;?>" value="4.5" <?php if ($starNumber == '4.5') {  echo "checked"; }else{ echo "disabled"; } ?> />
                                                        <label class="half" for="star4half<?php echo $aa;?>" title="Pretty good - 4.5 stars"></label>
                                                        <input class="stars" type="radio" id="star4<?php echo $aa;?>" value="4" <?php if ($starNumber == '4'){  echo "checked"; }else{ echo "disabled"; } ?>  />
                                                        <label class = "full" for="star4<?php echo $aa;?>" title="Pretty good - 4 stars"></label>
                                                        <input class="stars" type="radio" id="star3half<?php echo $aa;?>" value="3.5" <?php if ($starNumber == '3.5') {  echo "checked"; }else{ echo "disabled"; } ?>  />
                                                        <label class="half" for="star3half<?php echo $aa;?>" title="Meh - 3.5 stars"></label>
                                                        <input class="stars" type="radio" id="star3<?php echo $aa;?>" value="3" <?php if ($starNumber == '3') {  echo "checked"; }else{ echo "disabled"; } ?> />
                                                        <label class = "full" for="star3<?php echo $aa;?>" title="Meh - 3 stars"></label>
                                                        <input class="stars" type="radio" id="star2half<?php echo $aa;?>" value="2.5" <?php if ($starNumber == '2.5') {  echo "checked"; }else{ echo "disabled"; } ?> />
                                                        <label class="half" for="star2half<?php echo $aa;?>" title="Kinda bad - 2.5 stars"></label>
                                                        <input class="stars" type="radio" id="star2<?php echo $aa;?>" value="2" <?php if ($starNumber == '2') {  echo "checked"; }else{ echo "disabled"; } ?>  />
                                                        <label class = "full" for="star2<?php echo $aa;?>" title="Kinda bad - 2 stars"></label>
                                                        <input class="stars" type="radio" id="star1half<?php echo $aa;?>" value="1.5" <?php if ($starNumber == '1.5'){ echo "checked"; }else{ echo "disabled"; } ?> />
                                                        <label class="half" for="star1half<?php echo $aa;?>" title="Meh - 1.5 stars"></label>
                                                        <input class="stars" type="radio" id="star1<?php echo $aa;?>" value="1" <?php if ($starNumber == '1') {  echo "checked"; }else{ echo "disabled"; } ?>  />
                                                        <label class = "full" for="star1<?php echo $aa;?>" title="Sucks big time - 1 star"></label>
                                                        <input class="stars" type="radio" id="starhalf<?php echo $aa;?>" value="0.5" <?php if ($starNumber == '0.5')  {  echo "checked"; }else{ echo "disabled"; } ?> />
                                                        <label class="half" for="starhalf<?php echo $aa;?>" title="Sucks big time - 0.5 stars"></label>
                                                    </fieldset>
                                                </td>
                                                <td><?php echo $query_userlist2['review']; ?></td>
                                                <td><?php echo $query_userlist2['date']; ?></td>
                                                <td>
                                                    <?php include 'list-ratting-modal.php'; ?>
                                                    <script type="text/javascript">
                                                        //Bootstrap modal
                                                        jQuery(".edit_row").click(function () {
                                                            jQuery(this).parent().find('#modal_<?php echo $query_userlist2['id']; ?>').modal({
                                                                keyboard: false
                                                            })
                                                        });
                                                    </script>
                                                    <div class="pull-left divider-right">
                                                        <button class="edit_row label label-warning" data-toggle="modal" data-target="#modal_<?php echo $query_userlist2['id']; ?>"><i class="fa fa-pencil-square-o"></i></button>
                                                    </div>
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