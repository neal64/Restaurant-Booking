<?php


$logo       = $CONF['installation_path'] . 'skin/images/phpRestaurant_email_template.png';
$to_admin   = $information['contact_email']; #Email address from Control Panel



/**

**/
#Email Template for: -Contact us Page form-
#For: Administrator only
if(isset($_POST['contact_us'])){
    $contact_name       = $_POST['contact_name']; // this is your Email address
    $contact_message    = $_POST['contact_message']; // this is the sender's Email address
    $contact_subject    = $_POST['contact_subject'];
    $contact_email      = $_POST['contact_email'];

    $message  = '<html><body style="background-color: #EAECED; padding: 10px; color: #000000;">';
    $message .= '<img src="'.$logo.'" alt="" />';
    $message .= '<table rules="all" cellpadding="10">';
    $message .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['Username']."</strong> </td><td style='border-color: #c6c6c6;'>" . $contact_name . "</td></tr>";
    $message .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['Email']."</strong> </td><td style='border-color: #c6c6c6;'>" . $contact_email . "</td></tr>";
    $message .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['Message']."</strong> </td><td style='border-color: #c6c6c6;'>" . $contact_message . "</td></tr>";
    $message .= "</table>";
    $message .= "</body></html>";

    // $headers  = "From: " . $contact_email . "\r\n";
    // $headers .= "Reply-To: ". $to . "\r\n";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if (mail($to_admin,$contact_subject,$message,$headers)){
        //Success message
        echo "<div class='clearfix'></div>";
        echo $lang['Contact_us_form_success_message'];
    }
}



/**

**/
#Email Template for -Book a Travel Bookings-
#For: Administrator and Clients

if(isset($_REQUEST['pay_type'])){
    $contact_subject    = $lang['email_book_table_title'];
    $to_client          = $order_user_email;
    $pay_type = $_REQUEST['pay_type'];
    if($pay_type == '1'){
        $ppq = "Debit Card Payment";
    }elseif($pay_type == '2'){
        $ppq = "Creadit Card Payment";
    }elseif($pay_type == '3'){
        $ppq = "Net Banking Payment";
    }else{
        $ppq = "Cash less";
    }

    #message for administrator
    $message_admin  = '<html><body style="background-color: #EAECED; padding: 10px; color: #000000;">';
    $message_admin .= '<img src="'.$logo.'" alt="" />';
    $message_admin .= '<h3>'.$lang['email_book_table_notification_admin'].'</h3>';
    $message_admin .= "<h4 style='color: #00BC8C;'>".$lang['email_book_table_client_details']."</h4>";
    $message_admin .= '<table rules="all" cellpadding="10">';
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_name']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_nice_name . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_email']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_email . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_phone']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_phone . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_order_extra_comments']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['book_a_table_comments'] . "</td></tr>";
    $message_admin .= "</table>";
    $message_admin .= "<h4 style='color: #00BC8C;'>".$lang['email_book_table_booked1']."</h4>";
    $message_admin .= '<table rules="all" cellpadding="10">';
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_nr']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['table_details'] . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_date']."</strong> </td><td style='border-color: #c6c6c6;'>" . $date->format( 'd-m-Y') . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_time']."</strong> </td><td style='border-color: #c6c6c6;'>" . $date->format( 'H:i') . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_hours']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['number_of_hours'] . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>Pay Type </strong> </td><td style='border-color: #c6c6c6;'>" . $ppq . "</td></tr>";
    $message_admin .= "</table>";
    $message_admin .= "<h3>".$lang['email_book_table_success_admin']."</h3>";
    $message_admin .= "</body></html>";

    #message for customer
    $message_client  = '<html><body style="background-color: #EAECED; padding: 10px; color: #000000;">';
    $message_client .= '<img src="'.$logo.'" alt="" />';
    $message_client .= '<h3>'.$lang['email_book_table_notification_customer'].'</h3>';
    $message_client .= "<h4 style='color: #00BC8C;'>".$lang['email_book_table_client_details']."</h4>";
    $message_client .= '<table rules="all" cellpadding="10">';
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_name']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_nice_name . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_email']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_email . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_phone']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_phone . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_order_extra_comments']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['book_a_table_comments'] . "</td></tr>";
    $message_client .= "</table>";
    $message_client .= "<h4 style='color: #00BC8C;'>".$lang['email_book_table_booked2']."</h4>";
    $message_client .= '<table rules="all" cellpadding="10">';
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_nr']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['table_details'] . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_date']."</strong> </td><td style='border-color: #c6c6c6;'>" . $date->format( 'd-m-Y') . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_time']."</strong> </td><td style='border-color: #c6c6c6;'>" . $date->format( 'H:i') . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_hours']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['number_of_hours'] . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>Pay Type </strong> </td><td style='border-color: #c6c6c6;'>" . $ppq . "</td></tr>";
    $message_client .= "</table>";
    $message_client .= "<h3>".$lang['email_book_table_success_customer']."</h3>";
    $message_client .= "</body></html>";

    #headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if (mail($to_admin,$contact_subject,$message_admin,$headers)){
        //Success message
    }    
    if (mail($to_client,$contact_subject,$message_client,$headers)){
        //Success message
    }
}

/*
if(isset($_POST['submit_order_book_table'])){

    $contact_subject    = $lang['email_book_table_title'];
    $to_client          = $order_user_email;

    #message for administrator
    $message_admin  = '<html><body style="background-color: #EAECED; padding: 10px; color: #000000;">';
    $message_admin .= '<img src="'.$logo.'" alt="" />';
    $message_admin .= '<h3>'.$lang['email_book_table_notification_admin'].'</h3>';
    $message_admin .= "<h4 style='color: #00BC8C;'>".$lang['email_book_table_client_details']."</h4>";
    $message_admin .= '<table rules="all" cellpadding="10">';
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_name']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_nice_name . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_email']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_email . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_phone']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_phone . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_order_extra_comments']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['book_a_table_comments'] . "</td></tr>";
    $message_admin .= "</table>";
    $message_admin .= "<h4 style='color: #00BC8C;'>".$lang['email_book_table_booked1']."</h4>";
    $message_admin .= '<table rules="all" cellpadding="10">';
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_nr']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['table_details'] . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_date']."</strong> </td><td style='border-color: #c6c6c6;'>" . $date->format( 'd-m-Y') . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_time']."</strong> </td><td style='border-color: #c6c6c6;'>" . $date->format( 'H:i') . "</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_hours']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['number_of_hours'] . "</td></tr>";
    $message_admin .= "</table>";
    $message_admin .= "<h3>".$lang['email_book_table_success_admin']."</h3>";
    $message_admin .= "</body></html>";

    #message for customer
    $message_client  = '<html><body style="background-color: #EAECED; padding: 10px; color: #000000;">';
    $message_client .= '<img src="'.$logo.'" alt="" />';
    $message_client .= '<h3>'.$lang['email_book_table_notification_customer'].'</h3>';
    $message_client .= "<h4 style='color: #00BC8C;'>".$lang['email_book_table_client_details']."</h4>";
    $message_client .= '<table rules="all" cellpadding="10">';
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_name']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_nice_name . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_email']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_email . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_phone']."</strong> </td><td style='border-color: #c6c6c6;'>" . $order_user_phone . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_order_extra_comments']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['book_a_table_comments'] . "</td></tr>";
    $message_client .= "</table>";
    $message_client .= "<h4 style='color: #00BC8C;'>".$lang['email_book_table_booked2']."</h4>";
    $message_client .= '<table rules="all" cellpadding="10">';
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_nr']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['table_details'] . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_date']."</strong> </td><td style='border-color: #c6c6c6;'>" . $date->format( 'd-m-Y') . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_time']."</strong> </td><td style='border-color: #c6c6c6;'>" . $date->format( 'H:i') . "</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_book_table_hours']."</strong> </td><td style='border-color: #c6c6c6;'>" . $_POST['number_of_hours'] . "</td></tr>";
    $message_client .= "</table>";
    $message_client .= "<h3>".$lang['email_book_table_success_customer']."</h3>";
    $message_client .= "</body></html>";

    #headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if (mail($to_admin,$contact_subject,$message_admin,$headers)){
        //Success message
    }    
    if (mail($to_client,$contact_subject,$message_client,$headers)){
        //Success message
    }
}
*/


/**

**/
#Email Template for -Catering Orders-
#For: Administrator and Clients
if(isset($_POST['submit_order'])){

    $contact_subject    = $lang['email_catering_orders_title'];
    $to_client          = $order_user_email;

    #message for administrator
    $message_admin  = '<html><body style="background-color: #EAECED; padding: 10px; color: #000000;">';
    $message_admin .= '<img src="'.$logo.'" alt="" />';
    $message_admin .= '<h3>'.$lang['email_book_table_new_order'].'</h3>';
    $message_admin .= "<h4 style='color: #00BC8C;'>".$lang['email_client_details']."</h4>";
    $message_admin .= '<table rules="all" cellpadding="10">';
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_name']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_user_nice_name."</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_email']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_user_email."</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_phone']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_user_phone."</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_customer_address']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_address."</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_order_value']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_value."$</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_payment_method']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_payment_method."</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_purchased_products']."</strong> </td><td style='border-color: #c6c6c6;'>".$concatenate."</td></tr>";
    $message_admin .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_order_extra_comments']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_comments."</td></tr>";
    $message_admin .= "</table>";
    $message_admin .= "<h3>".$lang['email_check_order_in_cp']."</h3>";
    $message_admin .= "</body></html>";

    #message for customer
    $message_client  = '<html><body style="background-color: #EAECED; padding: 10px; color: #000000;">';
    $message_client .= '<img src="'.$logo.'" alt="" />';
    $message_client .= '<h3>'.$lang['email_book_table_new_order2'].'</h3>';
    $message_client .= "<h4 style='color: #00BC8C;'>".$lang['email_your_details']."</h4>";
    $message_client .= '<table rules="all" cellpadding="10">';
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_name']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_user_nice_name."</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_email']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_user_email."</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_phone']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_user_phone."</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_your_address']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_address."</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_order_value']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_value."$</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_payment_method']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_payment_method."</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_purchased_products']."</strong> </td><td style='border-color: #c6c6c6;'>".$concatenate."</td></tr>";
    $message_client .= "<tr><td style='border-color: #c6c6c6;'><strong>".$lang['email_order_extra_comments']."</strong> </td><td style='border-color: #c6c6c6;'>".$order_comments."</td></tr>";
    $message_client .= "</table>";
    $message_client .= "<h3>".$lang['email_order_success']."</h3>";
    $message_client .= "</body></html>";

    #headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    if (mail($to_admin,$contact_subject,$message_admin,$headers)){
        //Success message
    }    
    if (mail($to_client,$contact_subject,$message_client,$headers)){
        //Success message
    }
}
?>