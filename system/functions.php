<?php
define('SALT1', '24859f@#$#@$');
define('SALT2', '^&@#_-=+Afda$#%');


// ==================================================================
//Functia [createAccount] - to create user accounts
function createAccount($p_username, $p_password, $p_usernicename, $p_useremail, $p_userrole) {
require ('../configuration.php');
require ('../system/languages.php');
$con = mysqli_connect($CONF["host"], $CONF["user"], $CONF["pass"]) or trigger_error($lang['db_imposible_to_connect'] . mysqli_error($con));
mysqli_select_db($con, $CONF["name"]) or trigger_error($lang['db_imposible_to_change_the_db'] . mysqli_error($con));

  // Check if inputs are not empty
  if (!empty($p_username) && !empty($p_password)) {
    $userLength = strlen($p_username);
    $passwordLength = strlen($p_password);
    $usernicenameLength = strlen($p_usernicename);
    $userEmailLength = strlen($p_useremail);


    // escape $p_username to solve SQL Injections issues
    $e_username = mysqli_real_escape_string($con, $p_username);
    $e_usernicename = mysqli_real_escape_string($con, $p_usernicename);

    $query = mysqli_query($con, "SELECT user_name FROM users WHERE user_name = '" . $e_username . "' LIMIT 1") 
    or trigger_error($lang['db_query_failed'] . mysqli_error($con));

    // Check for errors
    if ($userLength <= 2 || $userLength >= 50 || $usernicenameLength >= 100 || $userEmailLength >= 50) {
      $_SESSION['error'] = $lang['cp_register_error_first'];
    }elseif ($passwordLength < 6) {
      $_SESSION['error'] = $lang['cp_register_error_password'];
    }elseif (mysqli_num_rows($query) == 1) {
      $_SESSION['error'] = $lang['cp_register_error_username'];
    }else {
      // create SQL to be insert into the db
      $query = mysqli_query($con, "INSERT INTO users (`user_name`, `user_password`, `user_nice_name`, `user_email`, `user_role`)
      VALUES ('".$e_username."','".hashPassword($p_password, SALT1, SALT2)."', '".$e_usernicename."', '".$p_useremail."', '".$p_userrole."');") or trigger_error($lang['db_query_failed'] . mysqli_error($con));

      if ($query) {
        //if success
        return true;
      }
    }
  }
  return false;
}

function createAccountCP($p_username, $p_password, $p_usernicename, $p_useremail, $p_userrole) {
require ('../../configuration.php');
require ('../../system/languages.php');
$con = mysqli_connect($CONF["host"], $CONF["user"], $CONF["pass"]) or trigger_error($lang['db_imposible_to_connect'] . mysqli_error($con));
mysqli_select_db($con, $CONF["name"]) or trigger_error($lang['db_imposible_to_change_the_db'] . mysqli_error($con));

  // Check if inputs are not empty
  if (!empty($p_username) && !empty($p_password)) {
    $userLength = strlen($p_username);
    $passwordLength = strlen($p_password);
    $usernicenameLength = strlen($p_usernicename);
    $userEmailLength = strlen($p_useremail);


    // escape $p_username to solve SQL Injections issues
    $e_username = mysqli_real_escape_string($con, $p_username);
    $e_usernicename = mysqli_real_escape_string($con, $p_usernicename);

    $query = mysqli_query($con, "SELECT user_name FROM users WHERE user_name = '" . $e_username . "' LIMIT 1") 
    or trigger_error($lang['db_query_failed'] . mysqli_error($con));

    // Check for errors
    if ($userLength <= 2 || $userLength >= 50 || $usernicenameLength >= 100 || $userEmailLength >= 50) {
      $_SESSION['error'] = $lang['cp_register_error_first'];
    }elseif ($passwordLength < 6) {
      $_SESSION['error'] = $lang['cp_register_error_password'];
    }elseif (mysqli_num_rows($query) == 1) {
      $_SESSION['error'] = $lang['cp_register_error_username'];
    }else {
      // create SQL to be insert into the db
      $query = mysqli_query($con, "INSERT INTO users (`user_name`, `user_password`, `user_nice_name`, `user_email`, `user_role`)
      VALUES ('".$e_username."','".hashPassword($p_password, SALT1, SALT2)."', '".$e_usernicename."', '".$p_useremail."', '".$p_userrole."');") or trigger_error($lang['db_query_failed'] . mysqli_error($con));

      if ($query) {
        //if success
        return true;
      }
    }
  }
  return false;
}


// ==================================================================
//Function [hashPassword] - to create password HASH-es 
function hashPassword($p_password, $pSalt1="2345#$%@3e", $pSalt2="taesa%#@2%^#") {
  return sha1(md5($pSalt2 . $p_password . $pSalt1));
}


// ==================================================================
//Function [loggedIn] - check if user exist in db and session is open for it
function loggedIn() {
  // check both loggedin and username to verify user.
  if (isset($_SESSION['loggedin']) && isset($_SESSION['user_name'])) {
    return true;
  }

  return false;
}


// ==================================================================
//Function [logoutUser] - log users out
function logoutUser() {
  unset($_SESSION['user_name']);
  unset($_SESSION['loggedin']);

  return true;
}



// ==================================================================
//Function [validateUser] - validate usernames/passwords
function validateUser($p_username, $p_password) {
require ('../configuration.php');
require ('../system/languages.php');
$con = mysqli_connect($CONF["host"], $CONF["user"], $CONF["pass"]) or trigger_error($lang['db_imposible_to_connect'] . mysqli_error($con));
mysqli_select_db($con, $CONF["name"]) or trigger_error($lang['db_imposible_to_change_the_db'] . mysqli_error($con));

  // Check if username and its typed password are correct in the db.
  $query = mysqli_query($con, "SELECT user_name FROM users WHERE user_name = '" . mysqli_real_escape_string($con, $p_username) . "' AND user_password = '" . hashPassword($p_password,SALT1, SALT2) . "' LIMIT 1") or trigger_error($lang['db_query_failed'] . mysqli_error($con));

  // If one line is returned, then the action result is success.
  if (mysqli_num_rows($query) == 1) {
    $row = mysqli_fetch_assoc($query);
    $_SESSION['user_name'] = $row['user_name'];
    $_SESSION['loggedin'] = true;

    return true;
  }

  return false;
}
?>