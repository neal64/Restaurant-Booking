<div class="container">
    <!-- Filter by ROOM -->
    <div class="head-book-a-table room_no">
        <h1 class="text-left"><?php echo $lang['tables_from_room']; ?></h1>
        <div class="clearfix"></div>
        <div class="row">
            <?php
            $query_rooms = mysqli_query($con, "SELECT DISTINCT restaurant_room_nr FROM tables") or trigger_error("Query Failed: " . mysqli_error($con));
            while ($query_room = mysqli_fetch_array($query_rooms, MYSQLI_ASSOC)) {
                ?>
                <a class="pull-left button btn btn-primary room_nr room_no_<?php echo $query_room['restaurant_room_nr']; ?>">Room No. <?php echo $query_room['restaurant_room_nr']; ?><i class="fa fa-check"></i></a>
            <?php } ?>
        </div>
    </div>
    <!-- Filter by Number of places at each table -->
    <div class="head-book-a-table nr_of_table">
        <h1 class="text-left"><?php echo $lang['BookTables_top_text']; ?></h1>
        <div class="clearfix"></div>
        <div class="row">
            <a class="pull-left button btn btn-primary two-places-on-table nr_of_tables_filter"><?php echo $lang['BookTables_2x_tables'] . " "; ?><i class="fa fa-check"></i></a>
            <a class="pull-left button btn btn-primary three-places-on-table nr_of_tables_filter"><?php echo $lang['BookTables_3x_tables'] . " "; ?><i class="fa fa-check"></i></a>
            <a class="pull-left button btn btn-primary four-places-on-table nr_of_tables_filter"><?php echo $lang['BookTables_4x_tables'] . " "; ?><i class="fa fa-check"></i></a>
            <a class="pull-left button btn btn-primary remove-places-on-table nr_of_tables_filter"><?php echo $lang['BookTables_reset_filter'] . " "; ?><i class="fa fa-check"></i></a>
        </div>
    </div>



    <?php
    if (loggedIn()) {
        $query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
        $query2 = mysqli_fetch_array($query);

        $user_id = $query2['user_id'];
        $order_user_nice_name = $query2['user_nice_name'];
        $order_user_email = $query2["user_email"];
        $order_user_phone = $query2["user_phone"];
    }
    if (isset($_POST['order_date'])) {
        //$checkdates = "SELECT order_date FROM orders WHERE order_date = '$_POST[order_date]' AND table_details = '$_POST[table_details]'";
        $time = $_POST['order_date'];
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $time, new DateTimeZone('America/New_York'));
        $get_data = $date->format('Y-m-d H');

        $checkdate = mysqli_query($con, "SELECT order_date, book_date_out, booktable_tables FROM orders WHERE order_type = 'BookATable' AND order_date LIKE '$get_data%' LIMIT 0 , 1") or trigger_error("Query Failed: " . mysqli_error($con));
        $data = mysqli_fetch_array($checkdate, MYSQLI_ASSOC);

        $date_in = $data['order_date'];
        $date_out = $data['book_date_out'];
        $table_name_db = $data['booktable_tables'];

        $date_from_user = $_POST['order_date'];
        $table_name_new = $_POST['table_details'];

        //if( ( $date_from_user >= $date_in ) && ( $date_from_user <= $date_out) ) {
        if (( $date_from_user >= $date_in ) && ( $date_from_user <= $date_out) && (strcmp($table_name_db, $table_name_new) == 0)) {
            echo "<div class='container'><h2><strong>" . $_POST['table_details'] . "</strong> " . $lang['table_not_available'] . "</h2></div><div class='clearfix divider30'></div>";
        } else {
            if (isset($_POST["submit_order_book_table"])) {

                $time = $_POST['order_date'];
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $time, new DateTimeZone('America/New_York'));

                echo "<div class='container'><h2>" . $lang['Reservation_for'] . ": <strong>" . $_POST['table_details'] . "</strong>," . $lang['Reservation_date'] . "<strong>" . $date->format('d-m-Y') . " </strong>, " . $lang['Reservation_time'] . ": <strong>" . $date->format('H:i') . "</strong> " . $lang['Reservation_done'] . " <br />" . $lang['Reservation_time_to_stay'] . "<strong class='text-center'>"
                ?> <?php
                if ($_POST['number_of_hours'] == '1') {
                    echo $lang['one_hour'];
                } else {
                    echo $_POST['number_of_hours'] . " " . $lang['hours'];
                }
                ?> <?php
                echo "</strong>. <strong class='red'>" . $lang['success_message'] . "<strong></h2></div>";


                if (!loggedIn()) {
                    $order_user_nice_name = $_POST['order_user_nice_name'];
                    $order_user_email = $_POST["order_user_email"];
                    $order_user_phone = $_POST["order_user_phone"];
                    $user_id = '';
                }

                include('../../system/functions_mail.php');

                $table_id = $_POST["table_id"];
                $order_date = $_POST["order_date"];
                $date = $_POST['order_date'];
                $order_date_first = new DateTime($date);
                $number_of_hours = $_POST["number_of_hours"];
                $order_date_first->modify('+' . $number_of_hours . 'hours');
                $book_date_out = $order_date_first->format('Y-m-d H:i:s');
                $pay_type = $_REQUEST['pay_type'];
                //echo $book_date_out;

                $order_type = 'BookATable';
                $table_details = $_POST['table_details'];
                $booktable_room = $_POST['booktable_room'];

                $query_orders = "INSERT INTO orders (order_type,booktable_room,booktable_tables,order_user_name, order_user_email, order_user_phone,order_date,book_date_out,order_payment_method) VALUES ('$order_type','$booktable_room','$table_details', '$order_user_nice_name', '$order_user_email', '$order_user_phone', '$order_date', '$book_date_out',$pay_type);";
                mysqli_query($con, $query_orders);
                $order_id = mysqli_insert_id($con);

                $query_orderuser = "INSERT INTO orderuser (order_id,user_id) VALUES ('$order_id','$user_id');";
                mysqli_query($con, $query_orderuser);

                foreach ($table_id as $key => $value) {
                    $query_tablesorder = "INSERT INTO tablesorders (tablesorders_id,order_id) VALUES ('$value','$order_id');";
                    mysqli_query($con, $query_tablesorder);
                }
            }
        }
    }
    ?>
    <!-- BOF: .submit_book_a_table -->
    <div class="container700">
        <script LANGUAGE="JavaScript">
            function ValidateForm(form) {
                ErrorText = "";
                if (document.registerationform.order_date.value == "")
                {
                    registerationform.order_date.focus();
                    return false;
                }
                if (document.registerationform.order_user_nice_name.value == "")
                {
                    registerationform.order_user_nice_name.focus();
                    return false;
                }
                if (document.registerationform.order_user_email.value == "")
                {
                    registerationform.order_user_email.focus();
                    return false;
                }
                if (document.registerationform.order_user_phone.value == "")
                {
                    registerationform.order_user_phone.focus();
                    return false;
                } else {
                    $('#myModal').modal();
                }
            }
            function save_now(){
                var pay_type = $('input[name="pay"]:checked').val();
                //alert(pay_type);
                document.getElementById('pay_type').value=pay_type;
                $('#book').submit();
            }
        </script>
        <form method="POST" name = "registerationform" id="book" class="container submit_book_a_table">
            <h2><?php echo $lang['Booking_details']; ?></h2>
            <div class="form-input-holder-base">
                <div class="row label_input_group">
                    <label class="pull-left col-md-6"><?php echo $lang['Booking_date_in']; ?></label>
                    <div id="datetimepicker" class="date-time-picker input-append col-md-6 pull-left">
                        <input required placeholder="<?php echo $lang['Date_and_time']; ?>" class="form-control" name="order_date" data-format="yyyy-MM-dd hh:mm:ss" type="text" />
                        <span class="add-on">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                </div>					
                <div class="row label_input_group">
                    <label class="pull-left col-md-6"><?php echo $lang['Hours_to_stay']; ?></label>
                    <div class="col-md-6">
                        <select name="number_of_hours" class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            <option>6</option>
                            <option>7</option>
                            <option>8</option>
                        </select>
                    </div>
                </div>
                <!-- Here will be added inputs via jQuery -->
            </div>

            <h2><?php echo $lang['Client_infos']; ?></h2>
            <div class="label_input_group">
                <label class="col-md-6"><?php echo $lang['Name_and_surname']; ?></label>
                <div class="col-md-6">
                    <input required class="form-control" type='text' name='order_user_nice_name' placeholder="<?php echo $lang['Name_and_surname']; ?>" value="<?php
                    if (loggedIn()) {
                        echo $order_user_nice_name;
                    }
                    ?>">
                </div>
            </div>
            <div class="label_input_group">
                <label class="col-md-6"><?php echo $lang['Email']; ?></label>
                <div class="col-md-6">
                    <input required class="form-control" type='text' name='order_user_email' placeholder="<?php echo $lang['Email']; ?>" value="<?php
                    if (loggedIn()) {
                        echo $order_user_email;
                    }
                    ?>" >
                </div>
            </div>
            <div class="label_input_group">
                <label class="col-md-6"><?php echo $lang['Phone_number']; ?></label>
                <div class="col-md-6">
                    <input required class="form-control" type='text' name='order_user_phone' placeholder="<?php echo $lang['Phone_number']; ?>" value="<?php
                    if (loggedIn()) {
                        echo '0' . $order_user_phone;
                    }
                    ?>" >
                    <input type="hidden" name="pay_type" id="pay_type">
                </div>
            </div>
            <div class="row label_input_group">
                <label class="pull-left col-md-6"><?php echo $lang['Order_comments']; ?></label>
                <div class="col-md-6">
                    <textarea class="form-control" name="book_a_table_comments" placeholder="<?php echo $lang['Order_comments_eg']; ?>"></textarea>
                </div>
            </div>
            <input class="pull-right btn btn-success" type="hidden" name="submit_order_book_table"  value="<?php echo $lang['Book_now']; ?>" />
            <button type="button" class="pull-right btn btn-success" onClick="ValidateForm(this.form)"><?php echo $lang['Book_now']; ?></button>
            <p class="p-update-user-informations"><?php echo $lang['Success']; ?></p>
        </form><!-- EOF: .submit_book_a_table -->
    </div>
    <?php include './book_table-modal.php'; ?>




    <?php
    $query_rooms = mysqli_query($con, "SELECT DISTINCT restaurant_room_nr FROM tables") or trigger_error("Query Failed: " . mysqli_error($con));
    while ($query_room = mysqli_fetch_array($query_rooms, MYSQLI_ASSOC)) {
        ?>


        <div class="hide_on_mobiles book-a-table tables-holder room_nr_<?php echo $query_room['restaurant_room_nr']; ?>">
            <?php
            $room_nr = $query_room['restaurant_room_nr'];
            $query = mysqli_query($con, "SELECT * FROM tables WHERE restaurant_room_nr='$room_nr'") or trigger_error("Query Failed: " . mysqli_error($con));
            while ($result = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                ?>
                <!-- Tables -->
                <div class="nr_of_places_<?php echo $result['table_number_of_places']; ?> book_table_number_<?php echo $result['table_id']; ?>">
                    <div class="display_none booked-input-<?php echo $result['table_id']; ?>">
                        <div class="row label_input_group">
                            <label class="pull-left col-md-6"><?php echo $lang['chosen_room']; ?></label>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control pull-left" name="booktable_room" value="<?php echo $room_nr; ?>" />
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <span class="badge pull-left"><span class="white"><?php echo $lang['room_nr'] . $room_nr; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row label_input_group">
                            <label class="pull-left col-md-6"><?php echo $lang['Chosen_table']; ?></label>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control pull-left" name="table_details" value="<?php echo $result['table_details']; ?>" />
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <span class="badge pull-left"><span class="white"><?php echo $result['table_details']; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row label_input_group">
                            <label class="pull-left col-md-6"><?php echo $lang['Nr_places']; ?></label>
                            <div class="col-md-6">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <span class="badge pull-left"><?php echo $result['table_number_of_places']; ?></span><span class="white">( <?php echo $lang['Nr_places_message']; ?> )</span>
                                    </li>
                                </ul>
                                <input type="hidden" class="pull-left form-control" name="table_number_of_places" value="<?php echo $result['table_number_of_places']; ?>" />
                                <input type="hidden" class="pull-left" name="table_id[]" value="<?php echo $result['table_id']; ?>" />
                            </div>
                        </div>
                        <div class="row label_input_group">
                            <label class="pull-left col-md-6"><?php echo $lang['Table_details']; ?></label>
                            <div class="col-md-6">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <span class="badge pull-left"><span class="white"><?php echo $result['table_position']; ?></span>
                                    </li>
                                </ul>
                                <input type="hidden" class="form-control pull-left" name="table_position" value="<?php echo $result['table_position']; ?>" />
                            </div>
                        </div>
                    </div>

                    <div data-toggle="tooltip" data-placement="top" title="<?php echo $result['table_details'] . " - ( " . $result['table_number_of_places'] . $lang['BookTables_places'] . " )"; ?>" class='pin<?php echo $result['table_id']; ?> bounce general_style_pin'></div>
                    <div class='pulse<?php echo $result['table_id']; ?> general_style_pulse'></div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>


    <?php
    $query_rooms2 = mysqli_query($con, "SELECT DISTINCT restaurant_room_nr FROM tables") or trigger_error("Query Failed: " . mysqli_error($con));
    while ($query_room2 = mysqli_fetch_array($query_rooms2, MYSQLI_ASSOC)) {
        ?>
        <div class="book-a-table tables-holder hide_on_pcs room_nr_<?php echo $query_room2['restaurant_room_nr']; ?>">
            <?php
            $room_nr = $query_room2['restaurant_room_nr'];
            $query2 = mysqli_query($con, "SELECT * FROM tables WHERE restaurant_room_nr='$room_nr'") or trigger_error("Query Failed: " . mysqli_error($con));
            while ($result2 = mysqli_fetch_array($query2, MYSQLI_ASSOC)) {
                ?>
                <!-- Tables -->
                <div class="table_nr nr_of_places_<?php echo $result2['table_number_of_places']; ?> book_table_number_<?php echo $result2['table_id']; ?>">
                    <div class="display_none booked-input-<?php echo $result2['table_id']; ?>">
                        <div class="row label_input_group">
                            <label class="pull-left col-md-6"><?php echo $lang['chosen_room']; ?></label>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control pull-left" name="booktable_room" value="<?php echo $room_nr; ?>" />
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <span class="badge pull-left"><span class="white"><?php echo $lang['room_nr'] . $room_nr; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row label_input_group">
                            <label class="pull-left col-md-6"><?php echo $lang['Chosen_table']; ?></label>
                            <div class="col-md-6">
                                <input type="hidden" class="form-control pull-left" name="table_details" value="<?php echo $result2['table_details']; ?>" />
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <span class="badge pull-left"><span class="white"><?php echo $result2['table_details']; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row label_input_group">
                            <label class="pull-left col-md-6"><?php echo $lang['Nr_places']; ?></label>
                            <div class="col-md-6">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <span class="badge pull-left"><?php echo $result2['table_number_of_places']; ?></span><span class="white">( Cu posibilitatea adaugarii mai multor locuri )</span>
                                    </li>
                                </ul>
                                <input type="hidden" class="pull-left form-control" name="table_number_of_places" value="<?php echo $result2['table_number_of_places']; ?>" />
                                <input type="hidden" class="pull-left" name="table_id[]" value="<?php echo $result2['table_id']; ?>" />
                            </div>
                        </div>
                        <div class="row label_input_group">
                            <label class="pull-left col-md-6"><?php echo $lang['Table_details']; ?></label>
                            <div class="col-md-6">
                                <ul class="nav nav-pills nav-stacked">
                                    <li class="active">
                                        <span class="badge pull-left"><span class="white"><?php echo $result2['table_position']; ?></span>
                                    </li>
                                </ul>
                                <input type="hidden" class="form-control pull-left" name="table_position" value="<?php echo $result2['table_position']; ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="table_details"><?php echo $result2['table_details'] . " = " . $result2['table_number_of_places'] . $lang['BookTables_places'] . " "; ?></div>
                    <span class="bounce general_style_pin onclick_mobile label label-success pin<?php echo $result2['table_id']; ?> "><?php echo $lang['Book_this_table']; ?></span>
                </div>
                <div class="clearfix"> </div>

            <?php } ?>

        </div>
    <?php } ?>

</div>

<script type="text/javascript">
    //FILTER BY NUMBER OF PLACES
    jQuery(document).ready(function () {
        //two-places-on-table
        jQuery('.two-places-on-table').click(function () {
            jQuery('.two-places-on-table i').fadeIn('slow');
            jQuery('.three-places-on-table i').fadeOut('slow');
            jQuery('.four-places-on-table i').fadeOut('slow');

            jQuery('.nr_of_places_2').fadeIn('slow');
            jQuery('.nr_of_places_3').fadeOut('slow');
            jQuery('.nr_of_places_4').fadeOut('slow');
        });
        //three-places-on-table
        jQuery('.three-places-on-table').click(function () {
            jQuery('.two-places-on-table i').fadeOut('slow');
            jQuery('.three-places-on-table i').fadeIn('slow');
            jQuery('.four-places-on-table i').fadeOut('slow');

            jQuery('.nr_of_places_3').fadeIn('slow');
            jQuery('.nr_of_places_2').fadeOut('slow');
            jQuery('.nr_of_places_4').fadeOut('slow');
        });
        //four-places-on-table
        jQuery('.four-places-on-table').click(function () {
            jQuery('.two-places-on-table i').fadeOut('slow');
            jQuery('.three-places-on-table i').fadeOut('slow');
            jQuery('.four-places-on-table i').fadeIn('slow');

            jQuery('.nr_of_places_4').fadeIn('slow');
            jQuery('.nr_of_places_3').fadeOut('slow');
            jQuery('.nr_of_places_2').fadeOut('slow');
        });
        //remove-places-on-table
        jQuery('.remove-places-on-table').click(function () {
            jQuery('.two-places-on-table i').fadeOut('slow');
            jQuery('.three-places-on-table i').fadeOut('slow');
            jQuery('.four-places-on-table i').fadeOut('slow');

            jQuery('.nr_of_places_4').fadeIn('slow');
            jQuery('.nr_of_places_3').fadeIn('slow');
            jQuery('.nr_of_places_2').fadeIn('slow');
        });

        jQuery('.general_style_pin').click(function () {
            jQuery('.submit_book_a_table').fadeIn('slow');
        });

        //room 0
        jQuery('.room_no_0').click(function () {
            jQuery('.room_no_0 i').fadeIn('slow');
            jQuery('.room_no_1 i').fadeOut('slow');
            jQuery('.room_no_2 i').fadeOut('slow');
            jQuery('.room_no_3 i').fadeOut('slow');
            jQuery('.room_no_4 i').fadeOut('slow');
            jQuery('.room_no_5 i').fadeOut('slow');
            jQuery('.room_no_6 i').fadeOut('slow');

            jQuery('.room_nr_0').fadeIn('slow');
            jQuery('.room_nr_1').fadeOut('slow');
            jQuery('.room_nr_2').fadeOut('slow');
            jQuery('.room_nr_3').fadeOut('slow');
            jQuery('.room_nr_4').fadeOut('slow');
            jQuery('.room_nr_5').fadeOut('slow');
            jQuery('.room_nr_6').fadeOut('slow');
        });

        //room 1
        jQuery('.room_no_1').click(function () {
            jQuery('.room_no_1 i').fadeIn('slow');
            jQuery('.room_no_0 i').fadeOut('slow');
            jQuery('.room_no_2 i').fadeOut('slow');
            jQuery('.room_no_3 i').fadeOut('slow');
            jQuery('.room_no_4 i').fadeOut('slow');
            jQuery('.room_no_5 i').fadeOut('slow');
            jQuery('.room_no_6 i').fadeOut('slow');

            jQuery('.room_nr_1').fadeIn('slow');
            jQuery('.room_nr_0').fadeOut('slow');
            jQuery('.room_nr_2').fadeOut('slow');
            jQuery('.room_nr_3').fadeOut('slow');
            jQuery('.room_nr_4').fadeOut('slow');
            jQuery('.room_nr_5').fadeOut('slow');
            jQuery('.room_nr_6').fadeOut('slow');
        });
        //room 2
        jQuery('.room_no_2').click(function () {
            jQuery('.room_no_2 i').fadeIn('slow');
            jQuery('.room_no_0 i').fadeOut('slow');
            jQuery('.room_no_1 i').fadeOut('slow');
            jQuery('.room_no_3 i').fadeOut('slow');
            jQuery('.room_no_4 i').fadeOut('slow');
            jQuery('.room_no_5 i').fadeOut('slow');
            jQuery('.room_no_6 i').fadeOut('slow');

            jQuery('.room_nr_2').fadeIn('slow');
            jQuery('.room_nr_0').fadeOut('slow');
            jQuery('.room_nr_1').fadeOut('slow');
            jQuery('.room_nr_3').fadeOut('slow');
            jQuery('.room_nr_4').fadeOut('slow');
            jQuery('.room_nr_5').fadeOut('slow');
            jQuery('.room_nr_6').fadeOut('slow');
        });
        //room 3
        jQuery('.room_no_3').click(function () {
            jQuery('.room_no_3 i').fadeIn('slow');
            jQuery('.room_no_0 i').fadeOut('slow');
            jQuery('.room_no_1 i').fadeOut('slow');
            jQuery('.room_no_2 i').fadeOut('slow');
            jQuery('.room_no_4 i').fadeOut('slow');
            jQuery('.room_no_5 i').fadeOut('slow');
            jQuery('.room_no_6 i').fadeOut('slow');

            jQuery('.room_nr_3').fadeIn('slow');
            jQuery('.room_nr_0').fadeOut('slow');
            jQuery('.room_nr_1').fadeOut('slow');
            jQuery('.room_nr_2').fadeOut('slow');
            jQuery('.room_nr_4').fadeOut('slow');
            jQuery('.room_nr_5').fadeOut('slow');
            jQuery('.room_nr_6').fadeOut('slow');
        });
        //room 4
        jQuery('.room_no_4').click(function () {
            jQuery('.room_no_4 i').fadeIn('slow');
            jQuery('.room_no_0 i').fadeOut('slow');
            jQuery('.room_no_1 i').fadeOut('slow');
            jQuery('.room_no_2 i').fadeOut('slow');
            jQuery('.room_no_3 i').fadeOut('slow');
            jQuery('.room_no_5 i').fadeOut('slow');
            jQuery('.room_no_6 i').fadeOut('slow');

            jQuery('.room_nr_4').fadeIn('slow');
            jQuery('.room_nr_0').fadeOut('slow');
            jQuery('.room_nr_1').fadeOut('slow');
            jQuery('.room_nr_2').fadeOut('slow');
            jQuery('.room_nr_3').fadeOut('slow');
            jQuery('.room_nr_5').fadeOut('slow');
            jQuery('.room_nr_6').fadeOut('slow');
        });

        //room 5
        jQuery('.room_no_5').click(function () {
            jQuery('.room_no_5 i').fadeIn('slow');
            jQuery('.room_no_0 i').fadeOut('slow');
            jQuery('.room_no_1 i').fadeOut('slow');
            jQuery('.room_no_2 i').fadeOut('slow');
            jQuery('.room_no_3 i').fadeOut('slow');
            jQuery('.room_no_4 i').fadeOut('slow');
            jQuery('.room_no_6 i').fadeOut('slow');

            jQuery('.room_nr_5').fadeIn('slow');
            jQuery('.room_nr_0').fadeOut('slow');
            jQuery('.room_nr_1').fadeOut('slow');
            jQuery('.room_nr_2').fadeOut('slow');
            jQuery('.room_nr_3').fadeOut('slow');
            jQuery('.room_nr_4').fadeOut('slow');
            jQuery('.room_nr_6').fadeOut('slow');
        });

        //room 6
        jQuery('.room_no_6').click(function () {
            jQuery('.room_no_6 i').fadeIn('slow');
            jQuery('.room_no_0 i').fadeOut('slow');
            jQuery('.room_no_1 i').fadeOut('slow');
            jQuery('.room_no_2 i').fadeOut('slow');
            jQuery('.room_no_3 i').fadeOut('slow');
            jQuery('.room_no_4 i').fadeOut('slow');
            jQuery('.room_no_5 i').fadeOut('slow');

            jQuery('.room_nr_6').fadeIn('slow');
            jQuery('.room_nr_0').fadeOut('slow');
            jQuery('.room_nr_1').fadeOut('slow');
            jQuery('.room_nr_2').fadeOut('slow');
            jQuery('.room_nr_3').fadeOut('slow');
            jQuery('.room_nr_4').fadeOut('slow');
            jQuery('.room_nr_5').fadeOut('slow');
        });



<?php
$tables = mysqli_query($con, "SELECT * FROM tables") or trigger_error("Query Failed: " . mysqli_error($con));
while ($table = mysqli_fetch_array($tables, MYSQLI_ASSOC)) {
    ?>
            //Get booking form for computers / big resolutions
            jQuery(".pin<?php echo $table['table_id']; ?>").click(function () {
                jQuery(this).parent().find(".booked-input-<?php echo $table['table_id']; ?>").clone().appendTo(".form-input-holder-base");
                jQuery(".book-a-table.tables-holder").fadeOut("slow");
            });
            //Get booking form for mobile phones / small resolutions
            jQuery(".pin_mobile<?php echo $table['table_id']; ?>").click(function () {
                jQuery(this).parent().find(".booked-input-mobile-<?php echo $table['table_id']; ?>").clone().appendTo(".form-input-holder-base");
                jQuery(".book-a-table.tables-holder").fadeOut("slow");
            });
<?php } ?>
    });
</script>

<!-- Date/Time Picker -->
<link rel='stylesheet' href='<?php echo $CONF['installation_path']; ?>skin/css/bootstrap-datetimepicker.min.css' type='text/css' media='all' />
<!-- Date/Time Picker -->
<script type="text/javascript" src="<?php echo $CONF['installation_path']; ?>skin/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker').datetimepicker({
            language: 'pt-BR'
        });
        $('#datetimepicker_2').datetimepicker({
            language: 'pt-BR'
        });
    });
</script>