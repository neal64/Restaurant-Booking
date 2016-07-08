<?php

$informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
$information = mysqli_fetch_array($informations);

$host = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
?>

<div id="phpRestaurantPreloader">
    <div class="phprestaurant_loader">
        <img src="<?php echo $CONF['installation_path']; ?>skin/images/Preloader_3.gif">
    </div>
</div>

<header>
    <div class="header-top-area restaurant-bg">
        <div class="header-holder-top first">
            <div class="container">
                <div class="col-md-4">
<?php if ($information['language_is_active'] === "Yes") { ?>
                        <!-- Site languages -->
                        <div class="pull-left site-languages">
                            <ul class="pull-left">
                                <!-- English language -->
                                <li class="pull-left single-language">
                                    <a title="<?php echo $lang['ENGLISH']; ?>" role="menuitem" tabindex="-1" href="?lang=en">
                                        <img class="pull-left" src="<?php echo $CONF['installation_path']; ?>skin/images/languages/en.png" alt="<?php echo $lang['ENGLISH']; ?>" />
                                    </a>
                                </li>
                                <!-- Romanian language -->
                                <li class="pull-left single-language">
                                    <a title="<?php echo $lang['ROMANIAN']; ?>" role="menuitem" tabindex="-1" href="?lang=ro">
                                        <img class="pull-left" src="<?php echo $CONF['installation_path']; ?>skin/images/languages/ro.png" alt="<?php echo $lang['ROMANIAN']; ?>" />
                                    </a>
                                </li>
                            </ul>
                        </div>
<?php } ?>
                    <div class="restaurant-call-us">
                        <span><?php echo $lang['PhoneOrders'] . ":"; ?></span>
                        <span><?php echo $information['contact_phone_number']; ?></span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="restaurant-user-area pull-right">
<?php if (loggedIn()) { ?>
                            <div class="pull-left hello-message">
                            <?php echo $lang['WELCOME_MESSAGE'] . ', ' . $_SESSION["user_name"] . '!'; ?>
                            </div>
                            <div class="pull-left user-links">
                                <div class="label label-link">
                                    <a href="<?php echo $CONF['installation_path']; ?>backend/" title="<?php echo $lang["CONTROL_PANEL"]; ?>"><?php echo $lang["CONTROL_PANEL"]; ?></a>
                                </div> / 
                                <div class="label label-link">
                                    <a href="<?php echo $CONF['installation_path']; ?>backend/login.php?action=logout" title="<?php echo $lang["LOG_OUT"]; ?>"><?php echo $lang["LOG_OUT"]; ?></a>
                                </div>
                            </div>
<?php } else { ?>
                            <div class="pull-left hello-message">
                            <?php echo $lang['WELCOME_MESSAGE']; ?>
                            </div>
                            <div class="pull-left user-links">
                                <div class="label label-link">
                                    <a title="<?php echo $lang["REGISTER"]; ?>" href="<?php echo $CONF['installation_path']; ?>backend/register.php"><?php echo $lang["REGISTER"]; ?></a>
                                </div> / 
                                <div class="label label-link">
                                    <a title="<?php echo $lang["LOGIN"]; ?>" href="<?php echo $CONF['installation_path']; ?>backend/login.php"><?php echo $lang["LOGIN"]; ?></a>
                                </div>
                            </div>
<?php } if (false !== strpos($host, 'page/catering')) { ?>
                            <div class="pull-right catering-order-details" data-toggle="modal" data-target="#myModal">
                                <div class="cart-contents">
                                    <span><?php echo $lang['cp_order_cart']; ?><i class="fa fa-shopping-cart"></i></span>
                                </div>
                            </div>
<?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="header-holder-top second">
            <div class="container">
                <a class="restaurant_logo" href="<?php echo $CONF['installation_path']; ?>"><img src="<?php echo $CONF['logo_url']; ?>" alt="<?php echo $lang['SITE_TITLE']; ?>" /></a>
            </div>
        </div>
    </div>
<?php include('nav.php'); ?>
</header>


<?php
if (false !== strpos($host, 'page/catering')) {
    if (loggedIn()) {
        $query = mysqli_query($con, "SELECT * FROM users WHERE user_name = '" . $_SESSION['user_name'] . "' LIMIT 1") or trigger_error("Query Failed: " . mysqli_error($con));
        $query2 = mysqli_fetch_array($query);

        $order_user_nice_name = $query2['user_nice_name'];
        $order_user_email = $query2["user_email"];
        $order_user_phone = $query2["user_phone"];
        $user_delivery_address = $query2["user_delivery_address"];
    }
    ?>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="cateringOrderModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $lang['cp_table_popup_close']; ?></span></button>
                    <h4 class="modal-title" id="cateringOrderModal"><?php echo $lang['cp_order_details']; ?></h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form action="" id="update-users-informations" class="sends_catering_order" method="POST">

                        <div class="clearfix"></div>

                        <div class="label_input_group">
                            <label class="pull-left row hidden"><?php echo $lang['cp_order_purchased_products']; ?></label>
                            <textarea rows="4" class="hidden col-md-8 list_of_purchased_itemsx" placeholder="<?php echo $lang['cp_order_empty_cart']; ?>" name="order_catering_products"></textarea>	
                            <div class="menuitemids goeshere row">
                                <div class="cart_items_goes_up"></div>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="label_input_group">
                            <label><?php echo $lang['cp_order_value']; ?></label>
                            <input class="col-md-8 order_sum form-control" readonly="readonly" value="" type='text' name='order_value' placeholder="<?php echo $lang['cp_order_value']; ?>" />
                        </div>
                        <div class="label_input_group">
                            <label><?php echo $lang['cp_order_address']; ?></label>
                            <input required class="col-md-8 form-control" type='text' name='order_address' placeholder="<?php echo $lang['cp_order_address']; ?>" value="<?php if (loggedIn()) {
        echo $user_delivery_address;
    } ?>" />
                        </div>
                        <div class="label_input_group">
                            <label><?php echo $lang['Name_and_surname']; ?></label>
                            <input required class="form-control" type='text' name='order_user_nice_name' placeholder="<?php echo $lang['Name_and_surname']; ?>" value="<?php if (loggedIn()) {
        echo $order_user_nice_name;
    } ?>">
                        </div>
                        <div class="label_input_group">
                            <label><?php echo $lang['Email']; ?></label>
                            <input required class="form-control" type='text' id="usr_email1" name='order_user_email' placeholder="<?php echo $lang['cp_settings_restaurant_email_eg']; ?>" value="<?php if (loggedIn()) {
        echo $order_user_email;
    } ?>" ><br/>
                        </div>
                        <div class="label_input_group">
                            <label><?php echo $lang['Phone_number']; ?></label>
                            <input required class="col-md-8 form-control" type='text' id="order_phone1" name='order_user_phone' placeholder="<?php echo $lang['cp_settings_restaurant_phone_eg']; ?>" value="<?php if (loggedIn()) {
        echo $order_user_phone;
    } ?>" ><br/>
                        </div>
                        <div class="label_input_group">
                            <label><?php echo $lang['Order_comments']; ?></label>
                            <textarea id="order_comment1" name="order_comments" class="form-control" placeholder="<?php echo $lang['cp_food_example_name']; ?>"></textarea>
                        </div>
                        <div class="label_input_group">
                            <label><?php echo ' Please choose a payment method'; ?></label>
                            <select name="payment_method" class="form-control select_payment_method" required>
                                <option>Choose a payment method</option>
                                <option value="via_delivery">Pay on delivery</option>
                                <option value="via_paypal">Pay now with PayPal</option>
                            </select>
                        </div>
                        <input type="submit" class="button via_delivery btn btn-success" name="submit_order" value="<?php echo $lang['send_order']; ?>" />

                        <!-- PAYPAL -->
    <!-- 					<input type="hidden" name="rm" value="2">
                        <input type="hidden" name="cbt" value="Please Click Here to Complete Payment"> -->
                        <input type="hidden" name="usr_phone" id="order_phone2" value="<?php if (loggedIn()) {
        echo $order_user_phone;
    } ?>">
                        <input type="hidden" name="usr_email" id="usr_email2" value="<?php if (loggedIn()) {
        echo $order_user_email;
    } ?>">
                        <input type="hidden" name="usr_comment" id="order_comment2" value="">
                        <input type="hidden" name="id" value="Custom order">
                        <input type="hidden" name="CatDescription" value="<?php echo $lang['phprestaurant_catering_order']; ?>">
                        <input type="hidden" name="payment" class="order_sum" value="">  
                        <input type="hidden" name="key" value="<?php echo md5(date("Y-m-d:") . rand()); ?>">    
                        <!-- SUBMIT -->
                        <input type="submit" class="via_paypal button btn btn-success pay_via_paypal" value="<?php echo $lang['send_order_paypal']; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php require('style.php'); ?>
