<?php 
/* Module Loader */
$lethe_modules = array();
include_once(LETHE_MODULES.'/lethe.autoresponder/module.conf.php');
include_once(LETHE_MODULES.'/lethe.newsletter/module.conf.php');
include_once(LETHE_MODULES.'/lethe.organizations/module.conf.php');
include_once(LETHE_MODULES.'/lethe.subscribers/module.conf.php');
include_once(LETHE_MODULES.'/lethe.templates/module.conf.php');
aasort($lethe_modules, 'sort');
?>