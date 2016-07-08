<div class="index-reports row">
    <div class="col-md-12">
        <h1><?php echo $lang['cp_index_content_title']; ?></h1>
        <?php
        // query users
        $query_users = mysqli_query($con, "SELECT * FROM users LIMIT 0 , 10") or trigger_error("Query Failed: " . mysqli_error($con));
        $user_results = mysqli_num_rows($query_users);
        // user_role = Administrator
        $query_admin_users = mysqli_query($con, "SELECT * FROM users WHERE user_role = 'Administrator'") or trigger_error("Query Failed: " . mysqli_error($con));
        $admin_user_results = mysqli_num_rows($query_admin_users);
        // user_role = Client
        $query_client_users = mysqli_query($con, "SELECT * FROM users WHERE user_role = 'Client'") or trigger_error("Query Failed: " . mysqli_error($con));
        $client_user_results = mysqli_num_rows($query_client_users);
        // query catering orders
        $query_catering = mysqli_query($con, "SELECT * FROM orders WHERE order_type = 'Catering'") or trigger_error("Query Failed: " . mysqli_error($con));
        $order_results = mysqli_num_rows($query_catering);
        // query book a table orders
        $query_book_table = mysqli_query($con, "SELECT * FROM orders WHERE order_type = 'BookATable'") or trigger_error("Query Failed: " . mysqli_error($con));
        $book_table_results = mysqli_num_rows($query_book_table);
        // query menus
        $query_menus = mysqli_query($con, "SELECT * FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));
        $menu_results = mysqli_num_rows($query_menus);
        //query categories of specialities
        $query_menu_category = mysqli_query($con, "SELECT DISTINCT menu_item_category FROM menus") or trigger_error("Query Failed: " . mysqli_error($con));
        ?>

        <div class="row reports_holder">
            <div class="left_section col-md-6">
                <!-- BOF: Small Report One -->
                <div class="col-md-12 first">
                    <div class="col-md-5 huge-icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="col-md-7">
                        <span class="report number_of_entries text-right clearfix">
                            <span class="text-right"><?php echo $user_results; ?></span>
                        </span>
                        <span class="report_subtitle">
                            <span class="text-right"><?php echo $lang['cp_registered_users']; ?></span>
                        </span>
                    </div>
                    <a href="<?php echo $CONF['installation_path']; ?>backend/parts/list-users.php" class="row bottom_links_holder">
                        <div class="col-md-6">
                            <span class="text-left"><?php echo $lang['Details']; ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-right"><i class="fa fa-chevron-right"></i></span>
                        </div>
                    </a>
                </div><!-- EOF: Small Report One -->

                <!-- BOF: Small Report One -->
<!--                <div class="col-md-12 second">
                    <div class="col-md-5 huge-icon">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="col-md-7">
                        <span class="report number_of_entries text-right clearfix">
                            <span class="text-right"><?php echo $order_results; ?></span>
                        </span>
                        <span class="report_subtitle">
                            <span class="text-right"><?php echo $lang['Catering']; ?></span>
                        </span>
                    </div>
                    <a href="<?php echo $CONF['installation_path']; ?>backend/parts/list-orders.php" class="row bottom_links_holder">
                        <div class="col-md-6">
                            <span class="text-left"><?php echo $lang['Details']; ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-right"><i class="fa fa-chevron-right"></i></span>
                        </div>
                    </a>
                </div> EOF: Small Report One -->

                <h2><?php echo $lang['cp_report_registered_users']; ?></h2>
                <div id="users_report" style="min-width: 310px; height: 400px;width: 100%; max-width: 100%; margin: 0 auto; float: left;"></div>
            </div>

            <div class="right_section col-md-6">
                <!-- BOF: Small Report One -->
                <div class="col-md-12 third">
                    <div class="col-md-5 huge-icon">
                        <i class="fa fa-glass"></i>
                    </div>
                    <div class="col-md-7">
                        <span class="report number_of_entries text-right clearfix">
                            <span class="text-right"><?php echo $book_table_results; ?></span>
                        </span>
                        <span class="report_subtitle">
                            <span class="text-right"><?php echo $lang['BookingTables']; ?></span>
                        </span>
                    </div>
                    <a href="<?php echo $CONF['installation_path']; ?>backend/parts/list-orders.php" class="row bottom_links_holder">
                        <div class="col-md-6">
                            <span class="text-left"><?php echo $lang['Details']; ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-right"><i class="fa fa-chevron-right"></i></span>
                        </div>
                    </a>
                </div><!-- EOF: Small Report One -->

                <!-- BOF: Small Report One -->
<!--                <div class="col-md-12 fourth">
                    <div class="col-md-5 huge-icon">
                        <i class="fa fa-coffee"></i>
                    </div>
                    <div class="col-md-7">
                        <span class="report number_of_entries text-right clearfix">
                            <span class="text-right"><?php echo $menu_results; ?></span>
                        </span>
                        <span class="report_subtitle">
                            <span class="text-right"><?php echo $lang['cp_foods']; ?></span>
                        </span>
                    </div>
                    <a href="<?php echo $CONF['installation_path']; ?>backend/parts/list-menus.php" class="row bottom_links_holder">
                        <div class="col-md-6">
                            <span class="text-left"><?php echo $lang['Details']; ?></span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-right"><i class="fa fa-chevron-right"></i></span>
                        </div>
                    </a>
                </div> EOF: Small Report One -->
                <h2><?php echo $lang['cp_report_orders_bookings']; ?></h2>
                <div id="orders_report" style="min-width: 310px; height: 400px;width: 100%; max-width: 100%; margin: 0 auto; float: left;"></div>
            </div>
        </div>

        <!--<ul class="list-group col-md-6">
            <h2><?php echo $lang['cp_report_foods']; ?></h2>
            <?php
            while ($query_menu_categories = mysqli_fetch_array($query_menu_category, MYSQLI_ASSOC)) {
                $query3 = mysqli_query($con, "SELECT * FROM menus WHERE menu_item_category = '" . $query_menu_categories['menu_item_category'] . "' LIMIT 0 , 10") or trigger_error("Query Failed: " . mysqli_error($con));
                $num_rows = mysqli_num_rows($query3);
                ?>
                <li class="list-group-item">
                    <span class="btn btn-primary btn-xs pull-right"><?php echo $num_rows . " " . $lang['cp_foods']; ?></span>
                    <?php echo $query_menu_categories['menu_item_category']; ?>
                </li>
            <?php } ?>
        </ul>-->

        <ul class="list-group col-md-12">
            <h2><?php echo $lang['cp_latest_registered_users']; ?></h2>
            <?php while ($user_reports = mysqli_fetch_array($query_users, MYSQLI_ASSOC)) { ?>
                <li class="list-group-item">
                    <span class="btn btn-primary btn-xs pull-right"><?php echo $lang['role'] . " " . $user_reports['user_role']; ?></span>
                    <?php echo $user_reports['user_nice_name']; ?>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>


<script type="text/javascript">
    $(function () {
        $('#users_report').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0, //null,
                plotShadow: false
            },
            title: {
                text: '',
                align: 'center'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                    type: 'pie',
                    name: '<?php echo $lang['cp_percentage']; ?>',
                    data: [
                        ['<?php echo $lang['administrators']; ?>', <?php echo $admin_user_results; ?>],
                        ['<?php echo $lang['clients']; ?>', <?php echo $client_user_results; ?>]
                    ]
                }]
        });
    });





    $(function () {
        $('#orders_report').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: 0,
                plotShadow: false
            },
            title: {
                text: '',
                align: 'center'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        distance: -50,
                        style: {
                            fontWeight: 'bold',
                            color: 'white',
                            textShadow: '0px 1px 2px black'
                        }
                    },
                    startAngle: -90,
                    endAngle: 90,
                    center: ['50%', '75%']
                }
            },
            series: [{
                    type: 'pie',
                    name: '<?php echo $lang['cp_percentage']; ?>',
                    innerSize: '50%',
                    data: [
                        ['<?php echo $lang['Catering']; ?>', <?php echo $order_results; ?>],
                        ['<?php echo $lang['BookingTables']; ?>', <?php echo $book_table_results; ?>],
                    ]
                }]
        });
    });

</script>