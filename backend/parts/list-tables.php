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
        <title><?php echo $lang['CP_BASE_TITLE'] . $lang['CP_LIST_TABLES_PAGE_TITLE']; ?></title>
    </head>

    <body class="backend row index-list-orders index-tables">

        <!-- ####### HEADER for logged in users ############################################################## -->
        <?php
        if (loggedIn()) {
            //query users
            $query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
            $query2 = mysqli_fetch_array($query);
            // if is Administrator  ##################################################################
            if ($query2['user_role'] == 'Administrator') {
                ?>
                <header class="row loggedin">
                    <div class="">
                        <div class="col-md-6">
                            <span class="label label-success pull-left">
                                <?php
                                if ($query2['user_role'] == 'Administrator') {
                                    echo $lang['role'] . '<strong>Administrator</strong>';
                                }
                                ?>
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




                <!-- if is Client ################################################################## -->
            <?php } else if ($query2['user_role'] == 'Client') { ?>
                <header class="row loggedin">
                    <div class="">
                        <div class="col-md-6">
                            <span class="label label-success pull-left">
                                <?php echo $lang['role']; ?><strong>Client</strong>
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
                <div class="page-content">
                    <div class="nav-menu-height">
                        <?php include('menu-clients.php'); ?>
                    </div>
                <?php } ?>


                <div class="col-md-2 v2-sidebar-menu">
                    <?php if ($query2['user_role'] == 'Administrator') { ?>
                        <?php include('menu-administrators.php'); ?>
                    <?php } else if ($query2['user_role'] == 'Client') { ?>
                        <?php include('menu-clients.php'); ?>
                    <?php } ?>
                </div>

                <div class="col-md-10 v2-page-content">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BOF: TABLE: LIST all Tables ######################################################## -->
                            <?php if ($query2['user_role'] == 'Administrator') { ?>
                                <div class="row top_area_margin">
                                    <h2 class="pull-left select_order_type"><?php echo $lang['select_a_room_to_manage']; ?></h2>
                                    <div class="pull-left filter_table">
                                        <select id="select_order_type" class="select_menu_type btn btn-success">
                                            <?php
                                            $query_rooms = mysqli_query($con, "SELECT DISTINCT restaurant_room_nr FROM tables") or trigger_error("Query Failed: " . mysqli_error($con));
                                            while ($query_room = mysqli_fetch_array($query_rooms, MYSQLI_ASSOC)) {
                                                ?>
                                                <option value="<?php echo 'room_nr_' . $query_room['restaurant_room_nr']; ?>"><?php echo $query_room['restaurant_room_nr']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="pull-left add-new-item-btn show_order_type">
                                        <span class="btn btn-success onclick-register-form"><?php echo $lang['table_add_new']; ?></span>
                                    </div>
                                </div><!-- END of .row -->

                                <div class="clearfix"></div>

                                <?php
                                #Adding a table
                                if (isset($_POST["submit_table"])) {
                                    $table_details = $_POST["table_details"];
                                    $restaurant_room_nr = $_POST["restaurant_room_nr"];
                                    $table_number_of_places = $_POST["table_number_of_places"];
                                    $table_position = $_POST["table_position"];

                                    $query_orders = "INSERT INTO tables (restaurant_room_nr,table_details,table_number_of_places,table_position) 
						VALUES ('$restaurant_room_nr','$table_details','$table_number_of_places','$table_position');";
                                    mysqli_query($con, $query_orders);
                                    ?>

                                    <div role="alert" class="alert alert-success">
                                        <?php echo $lang['cp_table_message_added']; ?>
                                    </div>
                                    <?php
                                }

                                #Removing a table
                                if (isset($_POST["remove_table"])) {
                                    $table_id = $_POST['table_id'];

                                    $query_edit_menu = "DELETE FROM tables WHERE table_id='$table_id'";
                                    mysqli_query($con, $query_edit_menu);
                                    ?>

                                    <div role="alert" class="alert alert-success">
                                        <?php echo $lang['cp_table_message_deleted']; ?>
                                    </div>
                                    <?php
                                }

                                #Editing a table
                                if (isset($_POST["submit_edit_table"])) {
                                    $table_details = $_POST["table_details"];
                                    $restaurant_room_nr = $_POST["restaurant_room_nr"];
                                    $table_position = $_POST["table_position"];
                                    $table_number_of_places = $_POST["table_number_of_places"];
                                    $table_css_position_left = $_POST["table_css_position_left"];
                                    $table_css_position_top = $_POST["table_css_position_top"];
                                    $table_id = $_POST['table_id'];

                                    $query_edit_menu = "UPDATE tables SET table_css_position_top='$table_css_position_top',table_css_position_left='$table_css_position_left', table_details='$table_details', table_number_of_places='$table_number_of_places', table_position='$table_position' WHERE table_id='$table_id'";
                                    mysqli_query($con, $query_edit_menu);
                                    ?>

                                    <div role="alert" class="alert alert-success">
                                        <?php echo $lang['cp_table_message_modified']; ?>
                                    </div>
                                <?php } ?>

                                <div class="user-list menu-list bottom-area">
                                    <div class="boxed-register-block unboxed-form toggle-register-form"> 
                                        <div class="block-title">
                                            <span><?php echo $lang['cp_food_specifications']; ?></span>
                                        </div>
                                        <div class="block-content">
                                            <form id="submit-new-table" method="POST">
                                                <div class="row form-group">
                                                    <label class="col-md-4"><?php echo $lang['table_number']; ?></label>
                                                    <div class="col-md-7">
                                                        <input required class="form-control" placeholder="<?php echo $lang['table_number_eg']; ?>" type="text" name="table_details" />
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="col-md-4"><?php echo $lang['table_number_of_places']; ?></label>
                                                    <div class="col-md-7">
                                                        <input required class="form-control" placeholder="<?php echo $lang['table_number_of_places_eg']; ?>" type="text" name="table_number_of_places" />
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="col-md-4"><?php echo $lang['table_position']; ?></label>
                                                    <div class="col-md-7">
                                                        <input required class="form-control" placeholder="<?php echo $lang['table_position_eg']; ?>" type="text" name="table_position" />
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="col-md-4"><?php echo $lang['select_room']; ?></label>
                                                    <div class="col-md-7">
                                                        <select name="restaurant_room_nr" class="form-control copy-to-category valid">
                                                            <?php
                                                            $query_rooms = mysqli_query($con, "SELECT DISTINCT restaurant_room_nr FROM tables") or trigger_error("Query Failed: " . mysqli_error($con));
                                                            while ($query_room = mysqli_fetch_array($query_rooms, MYSQLI_ASSOC)) {
                                                                ?>
                                                                <option value="<?php echo $query_room['restaurant_room_nr']; ?>"><?php echo $query_room['restaurant_room_nr']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <label class="col-md-4"><?php echo $lang['new_room']; ?></label>
                                                    <div class="col-md-7">
                                                        <input required class="form-control add-category" placeholder="<?php echo $lang['new_room_eg']; ?>" type="text" name="restaurant_room_nr" />
                                                    </div>
                                                </div>
                                                <input type="submit" name="submit_table" class="btn btn-success" value="<?php echo $lang['table_add']; ?>" />
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <table class="table-fill ">
                                    <!-- Table head -->
                                    <thead>
                                        <tr>
                                            <th><?php echo $lang['table_nr']; ?></th>
                                            <th>Room</th>
                                            <th><?php echo $lang['table_number_of_places']; ?></th>
                                            <th><?php echo $lang['table_position']; ?></th>
                                            <th><?php echo $lang['table_css_position_left']; ?></th>
                                            <th><?php echo $lang['table_css_position_top']; ?></th>
                                            <th><?php echo $lang['cp_table_actions']; ?></th>
                                        </tr>
                                    </thead>


                                    <?php
                                    $query_rooms = mysqli_query($con, "SELECT DISTINCT restaurant_room_nr FROM tables") or trigger_error("Query Failed: " . mysqli_error($con));
                                    while ($query_room = mysqli_fetch_array($query_rooms, MYSQLI_ASSOC)) {
                                        $restaurant_room_nr = $query_room['restaurant_room_nr'];
                                        ?>
                                        <!-- Table body -->
                                        <tbody class="table-hover hidden-tbody <?php echo 'room_nr_' . $restaurant_room_nr; ?>">
                                            <?php
                                            $query_tables = mysqli_query($con, "SELECT * FROM tables WHERE restaurant_room_nr='$restaurant_room_nr'") or trigger_error("Query Failed: " . mysqli_error($con));
                                            while ($table = mysqli_fetch_array($query_tables)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $table['table_details']; ?></td>
                                                    <td>
                                                        <?php
                                                        if ($table['restaurant_room_nr'] == 1) {
                                                            echo "<div class='label label-success'>1</div>";
                                                        } elseif ($table['restaurant_room_nr'] == 2) {
                                                            echo "<div class='label label-warning'>2</div>";
                                                        } elseif ($table['restaurant_room_nr'] == 3) {
                                                            echo "<div class='label label-info'>3</div>";
                                                        } elseif ($table['restaurant_room_nr'] == 4) {
                                                            echo "<div class='label label-danger'>4</div>";
                                                        } elseif ($table['restaurant_room_nr'] == 5) {
                                                            echo "<div class='label label-primary'>5</div>";
                                                        } else {
                                                            echo "<div class='label label-success'>" . $table['restaurant_room_nr'] . "</div>";
                                                        }
                                                        ?></td>
                                                    <td><?php echo $table['table_number_of_places']; ?></td>
                                                    <td><?php echo $table['table_position']; ?></td>
                                                    <td><?php echo $table['table_css_position_left']; ?></td>
                                                    <td><?php echo $table['table_css_position_top']; ?></td>
                                                    <td>
                                                        <?php include 'list-tables-modal.php'; ?>
                                                        <script type="text/javascript">
                                                            //Bootstrap modal
                                                            jQuery(".edit_row").click(function () {
                                                                jQuery(this).parent().find('#modal_<?php echo $table['table_id']; ?>').modal({
                                                                    keyboard: false
                                                                })
                                                            });
                                                        </script>
                                                        <div class="pull-left divider-right">
                                                            <button class="edit_row label label-warning" data-toggle="modal" data-target="#modal_<?php echo $table['table_id']; ?>"><i class="fa fa-pencil-square-o"></i></button>
                                                        </div>

                                                        <form class="pull-left remove_db_item" method="POST">
                                                            <input type="hidden" name="table_id" value="<?php echo $table['table_id']; ?>" />
                                                            <button class="remove-db-item-btn label label-warning" type="submit" onclick="return confirm('<?php echo $lang['cp_table_are_you_sure']; ?>')" name="remove_table"><i class="fa fa-times"></i></button>
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



                <?php } else if ($query2['user_role'] == 'Client') { ?>
                    <script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
                <?php
                }
            } else {
                ?>
                <script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
            <?php } ?>
    </body>
</html>