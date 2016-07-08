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
        <title><?php echo $lang['CP_BASE_TITLE'] . $lang['CP_LIST_ORDERS_PAGE_TITLE']; ?></title>
        <style>
            .print{
                display: none;
            }
            @media print
            {    
                .no-print, .no-print *
                {
                    display: none !important;
                }
                .print{
                    display: block !important;
                }
            }
        </style>
    </head>

    <body class="backend row index-list-orders">

        <!-- ####### HEADER for logged in users ############################################################## -->
        <?php
        if (loggedIn()) {
            //query users
            $query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
            $query2 = mysqli_fetch_array($query);

            // BOF: IF is Administrator ##################################################################
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

                <!-- BOF: IF is Client ################################################################## -->
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
                <?php } ?>





                <div class="col-md-2 v2-sidebar-menu">
                    <?php if ($query2['user_role'] == 'Administrator') { ?>
                        <?php include('menu-administrators.php'); ?>
                    <?php } else if ($query2['user_role'] == 'Client') { ?>
                        <?php include('menu-clients.php'); ?>
                    <?php } ?>
                </div>

                <div class="col-md-10 v2-page-content" id="print_section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="menu-list top-area">
                                <div class="show_order_type print">
                                    <h2 class="pull-left select_order_type">Table Booking List</h2>
                                </div>
                                <div class="show_order_type no-print">
                                    <h2 class="pull-left select_order_type"><?php echo $lang['cp_order_filter']; ?></h2>
                                    <div class="col-md-4">
                                        <select id="select_order_type" class="select_menu_type btn btn-success">
                                            <option value="bookatable"><?php echo $lang['BookingTables']; ?></option>
                                            <!--<option value="catering"><?php echo $lang['CateringOrders']; ?></option>-->
                                        </select>
                                    </div>
                                    <div class="col-md-4 right pull-right">
                                        <button class="btn btn-success pull-right" onclick="printContent('print_section')">Print</button>
                                    </div>
                                </div>
                            </div>
                            <!-- BOF: TABLE: LIST all Orders ######################################################## -->
                            <?php if ($query2['user_role'] == 'Administrator') { ?>
                                <table class="menu-list-table middle-area table-fill bookatable_orders">
                                    <thead>
                                        <tr>
                                            <th><?php echo $lang['cp_order_type']; ?></th>
                                            <th>Payment method</th>
                                            <th>Payment status</th>
                                            <th><?php echo $lang['cp_order_foods_list']; ?></th>
                                            <th><?php echo $lang['Order_comments']; ?></th>
                                            <th><?php echo $lang['cp_order_address']; ?></th>
                                            <th><?php echo $lang['cp_order_value']; ?></th>
                                            <th><?php echo $lang['cp_table_name']; ?></th>
                                            <th><?php echo $lang['Email']; ?></th>
                                            <th><?php echo $lang['Phone_number']; ?></th>
                                            <th><?php echo $lang['Date']; ?></th>
                                        </tr>
                                    </thead>

                                    <!-- CATERING ORDERS -->
                                    <tbody class="table-hover">
                                        <?php
                                        $query_orders = mysqli_query($con, "SELECT * FROM orders WHERE order_type='Catering' ORDER BY `order_date` DESC LIMIT 0 , 25") or trigger_error("Query Failed: " . mysqli_error($con));
                                        while ($query_userlist2 = mysqli_fetch_array($query_orders)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $query_userlist2['order_type']; ?></td>
                                                <td><?php echo $query_userlist2['order_payment_method']; ?></td>
                                                <td><?php echo $query_userlist2['order_paypal_default']; ?></td>
                                                <td><?php echo $query_userlist2['order_catering_products']; ?></td>
                                                <td><?php echo $query_userlist2['order_comments']; ?></td>
                                                <td><?php echo $query_userlist2['order_address']; ?></td>
                                                <td><?php echo $query_userlist2['order_value'] . $lang['Base_currency']; ?></td>
                                                <td><?php echo $query_userlist2['order_user_name']; ?></td>
                                                <td><?php echo $query_userlist2['order_user_email']; ?></td>
                                                <td><?php echo $query_userlist2['order_user_phone']; ?></td>
                                                <td><?php echo $query_userlist2['order_date']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <table class="menu-list-table middle-area table-fill catering_orders">
                                    <thead>
                                        <tr>
                                            <th class="no-print"><?php echo $lang['cp_order_type']; ?></th>
                                            <th><?php echo $lang['room']; ?></th>
                                            <th><?php echo $lang['cp_order_tables']; ?></th>
                                            <th class="no-print"><?php echo $lang['cp_table_name']; ?></th>
                                            <th><?php echo $lang['Email']; ?></th>
                                            <th><?php echo $lang['Phone_number']; ?></th>
                                            <th><?php echo $lang['Date']; ?></th>
                                        </tr>
                                    </thead>
                                    <!-- CATERING ORDERS -->
                                    <tbody class="table-hover">
                                        <?php
                                        $query_orders = mysqli_query($con, "SELECT * FROM orders WHERE order_type='BookATable' ORDER BY `order_date` DESC LIMIT 0 , 25") or trigger_error("Query Failed: " . mysqli_error($con));
                                        while ($query_userlist2 = mysqli_fetch_array($query_orders)) {
                                            ?>
                                            <tr>
                                                <td class="no-print"><?php echo $query_userlist2['order_type']; ?></td>
                                                <td><?php echo $lang['room_nr'] . $query_userlist2['booktable_room']; ?></td>
                                                <td class="no-print"><?php echo $query_userlist2['booktable_tables']; ?></td>
                                                <td><?php echo $query_userlist2['order_user_name']; ?></td>
                                                <td><?php echo $query_userlist2['order_user_email']; ?></td>
                                                <td><?php echo $query_userlist2['order_user_phone']; ?></td>
                                                <td><?php echo $query_userlist2['order_date']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else if ($query2['user_role'] == 'Client') { ?>
                                <table class="menu-list-table middle-area table-fill bookatable_orders">
                                    <thead>
                                        <tr>
                                            <th><?php echo $lang['cp_order_foods_list']; ?></th>
                                            <th><?php echo $lang['cp_order_address']; ?></th>
                                            <th><?php echo $lang['cp_order_value']; ?></th>
                                            <th><?php echo $lang['cp_table_name']; ?></th>
                                            <th><?php echo $lang['Email']; ?></th>
                                            <th><?php echo $lang['Phone_number']; ?></th>
                                            <th><?php echo $lang['Date']; ?></th>
                                        </tr>
                                    </thead>

                                    <!-- CATERING ORDERS -->
                                    <tbody class="table-hover">
                                        <?php
                                        $query_orders_by_user = mysqli_query($con, "SELECT * FROM orders WHERE order_type='Catering' AND order_user_name = '$query2[user_nice_name]' ORDER BY `order_date` DESC LIMIT 0 , 25") or trigger_error("Query Failed: " . mysqli_error($con));
                                        while ($query_orders_by_users = mysqli_fetch_array($query_orders_by_user)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $query_orders_by_users['order_catering_products']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_address']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_value'] . $lang['Base_currency']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_user_name']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_user_email']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_user_phone']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_date']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <table class="menu-list-table middle-area table-fill catering_orders">
                                    <thead>
                                        <tr>
                                            <th><?php echo $lang['cp_order_tables']; ?></th>
                                            <th><?php echo $lang['room']; ?></th>
                                            <th><?php echo $lang['cp_table_name']; ?></th>
                                            <th><?php echo $lang['Email']; ?></th>
                                            <th><?php echo $lang['Phone_number']; ?></th>
                                            <th><?php echo $lang['Date']; ?></th>
                                        </tr>
                                    </thead>
                                    <!-- CATERING ORDERS -->
                                    <tbody class="table-hover">
                                        <?php
                                        $query_orders_by_user = mysqli_query($con, "SELECT * FROM orders WHERE order_type='BookATable' AND order_user_name = '$query2[user_nice_name]' ORDER BY `order_date` DESC LIMIT 0 , 25") or trigger_error("Query Failed: " . mysqli_error($con));
                                        while ($query_orders_by_users = mysqli_fetch_array($query_orders_by_user)) {
                                            ?>
                                            <tr>
                                                <td><?php echo $query_orders_by_users['booktable_tables']; ?></td>
                                                <td><?php echo $lang['room_nr'] . $query_orders_by_users['booktable_room']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_user_name']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_user_email']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_user_phone']; ?></td>
                                                <td><?php echo $query_orders_by_users['order_date']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php } ?>
            <?php } else { ?>
                <script>window.location.replace("<?php echo $CONF['installation_path'] . 'backend/'; ?>");</script>
            <?php } ?>
            <script>
                function printContent(el) {
                    var restorepage = document.body.innerHTML;
                    var printcontent = document.getElementById(el).innerHTML;
                    document.body.innerHTML = printcontent;
                    window.print();
                    document.body.innerHTML = restorepage;
                }
            </script>
    </body>
</html>