<?php

//require configuration file
require_once('../configuration.php');
//get languages
require_once('../system/languages.php');

$informations = mysqli_query($con, "SELECT * FROM informations WHERE contact_id='1'") or trigger_error("Query Failed: " . mysqli_error($con));
$information = mysqli_fetch_array($informations);
?>


<!DOCTYPE html>
<html lang="en-US">
<head>
  <?php include ('head.php'); ?>
  <title><?php echo $lang['SITE_BASE_TITLE'] . $lang['CP_LOGIN']; ?></title>
</head>
<body class="backend index-login-page">

<?php include ('../style.php'); ?>

<?php
// If the user is logging in or out 
// then lets execute the proper functions 
if (isset($_GET['action'])) { 
  switch (strtolower($_GET['action'])) { 
    case 'login': 
      if (isset($_POST['user_name']) && isset($_POST['user_password'])) { 
        // We have both variables. Pass them to our validation function 
        if (!validateUser($_POST['user_name'], $_POST['user_password'])) { 
          // Well there was an error. Set the message and unset 
          // the action so the normal form appears. 
          $_SESSION['error'] = $lang['cp_login_error_message']; 
          unset($_GET['action']); 
        } 
      }else { 
        $_SESSION['error'] = $lang['cp_login_error_message_second']; 
        unset($_GET['action']); 
      }       
    break; 
    case 'logout':
      // If they are logged in log them out. 
      // If they are not logged in, well nothing needs to be done. 
      if (loggedIn()) {
        logoutUser(); ?>

      <header class="row loggedin">
        <div class="container">
            <h2 class="white text-center"><?php echo $lang['log_out_message']; ?></h2>
        </div>
      </header>
      <?php include ('../nav.php'); ?>
      <div class="block-index-backend container logged">
          <div class="col-md-6 user-account">
            <span><a href="<?php echo $CONF['installation_path']; ?>"><i class="fa fa-home"></i><?php echo $lang['HOMEPAGE']; ?></a></span>
          </div>      
          <div class="col-md-6 user-account">
            <span><a href="<?php echo $CONF['installation_path']; ?>backend/login.php"><i class="fa fa-power-off"></i><?php echo $lang['CP_LOGIN']; ?></a></span>
          </div>
      </div>

      <?php }else { 
        // unset the action to display the login form. 
        unset($_GET['action']); 
      } 
    break; 
  } 
} ?>
 
  <?php
  // See if the user is logged in. If they are greet them  
  // and provide them with a means to logout. 
  if (loggedIn()) { ?>
    
    <header class="row loggedin">
      <div class="container">
          <h2 class="white text-center"><?php echo $lang['cp_login_hello'] . $_SESSION["user_name"]; ?></h2>
      </div>
    </header>
    <?php include ('../nav.php'); ?>
    <div class="block-index-backend container logged">
        <div class="col-md-6 user-account">
          <span><a href="<?php echo $CONF['installation_path']; ?>backend/"><i class="fa fa-bar-chart-o"></i><?php echo $lang['CONTROL_PANEL']; ?></a></span>
        </div>      
        <div class="col-md-6 user-account">
          <span><a href="<?php echo $CONF['installation_path']; ?>backend/login.php?action=logout"><i class="fa fa-power-off"></i><?php echo $lang['log_out']; ?></a></span>
        </div>
    </div>

  <?php }elseif (!isset($_GET['action'])) { 
    // incase there was an error  
    // see if we have a previous username 
    $sUsername = ""; 
    if (isset($_POST['user_name'])) { 
      $sUsername = $_POST['user_name']; 
    } ?>
     
    <header class="row loggedin">
      <div class="container">
          <h2 class="white text-center"><?php echo $lang['cp_login_welcome_message']; ?></h2>
      </div>
    </header>
    <?php include ('../nav.php'); ?>

      <div class="boxed-login-block boxed-form"> 
        <div class="block-footer">
          <span><?php echo $lang['cp_login_form_head']; ?></span>
        </div>
        <div class="block-content">
          <?php
          $sError = ""; 
          if (isset($_SESSION['error'])) { ?>
            <span id="error" class="text-center"><?php echo $_SESSION['error']; ?></span>
          <?php } ?>
          <?php echo $sError; ?>
          <form id="backend-login-form" name="login" method="post" action="login.php?action=login">
          	<div class="clearfix">
          		<STRONG>Login as admin</STRONG>
          		<span>admin / 123456</span>
          	</div>
          	<div class="clearfix">
          		<STRONG>Login as client</STRONG>
          		<span>client / 123456</span>
          	</div>
            <div class="row form-group">
              <input type="text" class="form-control" name="user_name" placeholder="<?php echo $lang['Username']; ?>" value="<?php echo $sUsername; ?>" />
            </div>
            <div class="row form-group">
              <input type="password" class="form-control" name="user_password" placeholder="<?php echo $lang['Password']; ?>" value="" /><br />
            </div>
            <input type="submit" class="btn btn-success form-submit-btn" name="submit" class="btn-red" value="<?php echo $lang['CP_LOGIN']; ?>" /> 


          </form>
        </div>
      </div> 
  <?php } ?>


</body>
</html>