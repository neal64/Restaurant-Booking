<?php
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 18.02.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */
include_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'lethe.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'classes/class.chronos.php');

# IMPORTANT
# This file using Timezone of "GENERAL SETTINGS" NOT "ORGANIZATION"
# Organization Specific Cron Handler Must Be Called Organization Settings

if(DEMO_MODE){die('Demo Mode Active!');}

$debugs = array();
$debugs[] = '* Current Date is: ' . date("Y-m-d H:i:s");

/* Submission Account Limit Resetter */
$opSubAcc = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE isActive=1 AND daily_reset<'". date("Y-m-d H:i:s") ."'") or die(mysqli_error($myconn));
while($opSubAccRs = $opSubAcc->fetch_assoc()){
	$newReset = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")."+". $opSubAccRs['limit_range'] ." minutes"));
	$myconn->query("UPDATE ". db_table_pref ."submission_accounts SET daily_sent=0, daily_reset='". $newReset ."' WHERE ID=". $opSubAccRs['ID'] ."") or die(mysqli_error($myconn));
}$opSubAcc->free();
$debugs[] = '* Submission Account Limits Controlled';

/* Organization Daily Limit Resetter */
$newReset = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")."+1 day"));
if($myconn->query("UPDATE ". db_table_pref ."organizations SET daily_sent=0, daily_reset='". $newReset ."' WHERE isActive=1 AND daily_reset<'". date("Y-m-d H:i:s") ."'")){
	$debugs[] = '* Organization Daily Limit Reset';
}

/* Task List Modifier */
$letChr = new Crontab();
$currJobs = $letChr->getJobs();
$remJobs = array();
$addJobs = array();
$opTasks = $myconn->query("SELECT * FROM ". db_table_pref ."chronos ORDER BY pos DESC") or die(mysqli_error($myconn));
while($opTasksRs = $opTasks->fetch_assoc()){
	/* Remove Crons */
	if($opTasksRs['pos']==1){
		if($letChr->doesJobExist($opTasksRs['cron_command'])){
			$remJobs[] = $opTasksRs['cron_command'];
			$myconn->query("DELETE FROM ". db_table_pref ."chronos WHERE ID=". $opTasksRs['ID'] ."");
			$debugs[] = '* Removed Cron: ' . $opTasksRs['cron_command'];
		}else{
			$debugs[] = '* There No Expired Cron Command Found';
		}
	}
	/* Add Crons */
	else{
		if($opTasksRs['launch_date']<=date('Y-m-d H:i:s')){
			if(!$letChr->doesJobExist($opTasksRs['cron_command'])){
				$addJobs[] = $opTasksRs['cron_command'];
				$debugs[] = '* New Cron: ' . $opTasksRs['cron_command'];
			}
		}else{
			//
		}
	}
} $opTasks->free();

# Remove First
$result = array_diff($currJobs, $remJobs);

# Add Jobs
foreach($addJobs as $k=>$v){
	$result[] = $v;
}

# Get Backup ?

# Save Jobs
exec("crontab -r");
$letChr->saveJobs($result);
$debugs[] = '* Cron Tab Updated';

/* Clear Resource Caches */
$fileCounts = 0;
$opOrgs = $myconn->query("SELECT * FROM ". db_table_pref ."organizations") or die(mysqli_error($myconn));
while($opOrgsRs = $opOrgs->fetch_assoc()){
	$orgFold = LETHE_RESOURCE.DIRECTORY_SEPARATOR.$opOrgsRs['orgTag'].'/expimp';
	$fileList = getDirFiles($orgFold);
	$now = time();
	$days = 1;
	foreach($fileList as $k=>$v){
		  if( $v['file_date'] < $now-60*60*24*$days ){
			if(unlink($orgFold.DIRECTORY_SEPARATOR.$v['file_name'])){
				$fileCounts++;
			}
		  }
	}
} $opOrgs->free();
$debugs[] = '* '. $fileCounts .' Cache File Removed';
if(lethe_debug_mode){
echo(implode(PHP_EOL,$debugs));
}

$myconn->close();
?>