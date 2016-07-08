<?php
  //require configuration file
  require_once('../../configuration.php');
  //get languages
  require_once('../../system/languages.php');
  //require paypal.class
  require_once('../../system/paypal.class.php');  // include the class file

  $informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
  $information = mysqli_fetch_array($informations);


?>



<!DOCTYPE html>
<html lang="en-US">
<head>
  <?php include ('../../head.php'); ?>
  <title><?php echo $lang['SITE_BASE_TITLE'] . $lang['CATERING_PAGE_TITLE']; ?></title>
</head>
<body class="index-catering-orders">
<?php include ('../../header.php'); ?>
<?php
$p = new paypal_class;             // initiate an instance of the class

if ($information['paypal_sandbox'] == 'Live') {
  $p->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';     // paypal url
}else{
  $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url
}

            
// setup a variable for this script (ie: 'http://www.micahcarrick.com/paypal.php')
$this_script = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
// if there is not action variable, set the default action of 'process'
if (empty($_GET['action'])) $_GET['action'] = 'process';  
switch ($_GET['action']) {
    
   case 'process':      // Process and order...
      // There should be no output at this point.  To process the POST data,
      // the submit_paypal_post() function will output all the HTML tags which
      // contains a FORM which is submited instantaneously using the BODY onload
      // attribute.  In other words, don't echo or printf anything when you're
      // going to be calling the submit_paypal_post() function.
 
      // This is where you would have your form validation  and all that jazz.
      // You would take your POST vars and load them into the class like below,
      // only using the POST values instead of constant string expressions.
 
      // For example, after ensureing all the POST variables from your custom
      // order form are valid, you might have:
      //
      // $p->add_field('first_name', $_POST['first_name']);
      // $p->add_field('last_name', $_POST['last_name']);
      
      $CatDescription = $_REQUEST['CatDescription'];
      $payment = $_REQUEST['payment'];
      $id = $_REQUEST['id'];
      $key = $_REQUEST['key'];
      $email = $_REQUEST['usr_email'];

      $p->add_field('business', $information['paypal_email']);
      $p->add_field('return', $this_script.'?action=success');
      $p->add_field('cancel_return', $this_script.'?action=cancel');
      $p->add_field('notify_url', $this_script.'?action=ipn');
      $p->add_field('item_name', $CatDescription);
      $p->add_field('amount', $payment);
      $p->add_field('key', $key);
      $p->add_field('item_number', $id);
      $p->add_field('custom', $email);
      //custom fields
      // $p->add_field('usr_comment', $_POST['usr_comment']);
      // $p->add_field('usr_phone', $_POST['usr_phone']);
      // $p->add_field('rm', $_POST['rm']);
      // $p->add_field('cbt', $_POST['cbt']);

      require('page-content-process.php');

      $p->submit_paypal_post(); // submit the fields to paypal

      //var_dump($_REQUEST);
     // $p->dump_fields();      // for debugging, output a table of all the fields
      break;
      
   case 'success':      // Order was successful...
   
      // This is where you would probably want to thank the user for their order
      // or what have you.  The order information at this point is in POST 
      // variables.  However, you don't want to "process" the order until you
      // get validation from the IPN.  That's where you would have the code to
      // email an admin, update the database with payment status, activate a
      // membership, etc.  
      //foreach ($_POST as $key => $value) { echo "$key: $value<br>"; }
      require('page-content-success.php');
      
      break;
    
    //If order is canceled
    case 'cancel':       // Order was canceled...
      require('page-content-canceled.php');
    break;
      
   case 'ipn':          // Paypal is calling page for IPN validation...
   
      // It's important to remember that paypal calling this script.  There
      // is no output here.  This is where you validate the IPN data and if it's
      // valid, update your database to signify that the user has payed.  If
      // you try and use an echo or printf function here it's not going to do you
      // a bit of good.  This is on the "backend".  That is why, by default, the
      // class logs all IPN data to a text file.
      
      if ($p->validate_ipn()) { 
          
         // Payment has been recieved and IPN is verified.  This is where you
         // update your database to activate or process the order, or setup
         // the database with the user's order details, email an administrator,
         // etc.  You can access a slew of information via the ipn_data() array.
  
         // Check the paypal documentation for specifics on what information
         // is available in the IPN POST variables.  Basically, all the POST vars
         // which paypal sends, which we send back for validation, are now stored
         // in the ipn_data() array.
  
         // For this example, we'll just email ourselves ALL the data.
         $dated = date("D, d M Y H:i:s", time()); 
         
         $subject = 'Instant Payment Notification - Recieved Payment';
         $to = 'hb@supertec.com';    //  your email
         $body =  "An instant payment notification was successfully recieved\n";
         $body .= "from ".$p->ipn_data['payer_email']." on ".date('m/d/Y');
         $body .= " at ".date('g:i A')."\n\nDetails:\n";
         $headers = "";
         $headers .= "From: Test Paypal \r\n";
         $headers .= "Date: $dated \r\n";
        
        $PaymentStatus =  $p->ipn_data['payment_status']; 
        $Email        =  $p->ipn_data['payer_email'];
        $id           =  $p->ipn_data['item_number'];
        
        if($PaymentStatus == 'Completed' or $PaymentStatus == 'Pending'){
            $PaymentStatus = '2';
        }else{
            $PaymentStatus = '1';
        }
        /*                                                                           
        *
        * 
        *
        *      Here you write your quries to make payment received or pending etc. 
        * 
        *  
        * 
        */
        foreach ($p->ipn_data as $key => $value) { $body .= "\n$key: $value"; }
        fopen("http://www.virtualphoneline.com/admins/TestHMS.php?to=".urlencode($to)."&subject=".urlencode($subject)."&message=".urlencode($body)."&headers=".urlencode($headers)."","r");         
  } 
      break;
 }     
?>
<?php require ('../../footer.php'); ?>
</body>
</html>