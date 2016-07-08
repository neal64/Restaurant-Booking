<?php
session_start();
//header('Cache-control: private'); // IE 6 FIX


//get current directory
$currentDirectory = dirname(__FILE__);


if(isSet($_GET['lang'])) {
  $lang = $_GET['lang'];
  // register the session and set the cookie
  $_SESSION['lang'] = $lang;
  setcookie("lang", $lang, time() + (3600 * 24 * 30));
} else if(isSet($_SESSION['lang'])) {
  $lang = $_SESSION['lang'];
} else if(isSet($_COOKIE['lang'])) {
  $lang = $_COOKIE['lang'];
} else {
  //DEFAULT Language
  $lang = 'en';
}

switch ($lang) {
  // English language (default, included in initial package)
  case 'en':
  $lang_file = 'lang.en.php';
  break;
  // Romanian language (included in initial package)
  case 'ro':
  $lang_file = 'lang.ro.php';
  break;
  // German (NOT included in initial package)
  case 'de':
  $lang_file = 'lang.de.php';
  break;
  // French (NOT included in initial package)
  case 'fr':
  $lang_file = 'lang.fr.php';
  break;
  // Italian (NOT included in initial package)
  case 'it':
  $lang_file = 'lang.it.php';
  break;
  // Portuguese (NOT included in initial package)
  case 'pt':
  $lang_file = 'lang.pt.php';
  break;
  // Russian (NOT included in initial package)
  case 'ru':
  $lang_file = 'lang.ru.php';
  break;
  // Dutch (NOT included in initial package)
  case 'nl':
  $lang_file = 'lang.nl.php';
  break;

  default:
  $lang_file = 'lang.en.php';
}

require $currentDirectory . '/languages/' . $lang_file;
?>