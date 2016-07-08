<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 04.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+

/* Load Organization Settings */
$letheOrg = $myconn->query("SELECT * FROM ". db_table_pref ."organizations WHERE isPrimary=1") or die(mysqli_error($myconn));
if(mysqli_num_rows($letheOrg)==0){
	die('Organization Setting Cannot Load!');
}else{
	$letheOrgRs = $letheOrg->fetch_assoc();
	$LETHE_ORG_SETS['set_org_id'] = $letheOrgRs['ID'];
	$LETHE_ORG_SETS['set_org_tag'] = $letheOrgRs['orgTag'];
	$LETHE_ORG_SETS['set_org_name'] = $letheOrgRs['orgName'];
	$LETHE_ORG_SETS['set_org_api_key'] = $letheOrgRs['api_key'];
	$LETHE_ORG_SETS['set_org_public_key'] = $letheOrgRs['public_key'];
	$LETHE_ORG_SETS['set_org_private_key'] = $letheOrgRs['private_key'];
	$LETHE_ORG_SETS['set_org_resource'] = LETHE_RESOURCE.DIRECTORY_SEPARATOR.$letheOrgRs['orgTag'];
	$LETHE_ORG_SETS['set_org_resource_url'] = $LETHE_SETS['lethe_root_url'].'resources/'.$letheOrgRs['orgTag'];
	$LETHE_ORG_SETS['set_org_daily_sent'] = $letheOrgRs['daily_sent'];
	$LETHE_ORG_SETS['set_org_rss_url'] = $letheOrgRs['rss_url'];
	
	/* Load Customs */
	$opSets = $myconn->query("SELECT * FROM ". db_table_pref ."organization_settings WHERE OID=". $letheOrgRs['ID'] ."") or die(mysqli_error($myconn));
	while($opSetsRs = $opSets->fetch_assoc()){
		$LETHE_ORG_SETS['set_'.$opSetsRs['set_key']] = $opSetsRs['set_val'];
	}$opSets->free();
	
	# ORGANIZATION SETTINGS ****
		/* Define Settings */
		if(!$SERVER_MODE){
			foreach($LETHE_ORG_SETS as $k=>$v){
				define($k,$v);
			}
		}

		/* Local Settings */
		date_default_timezone_set($LETHE_ORG_SETS['set_org_timezone']);
	# ORGANIZATION SETTINGS ****
	
}
$letheOrg->free();
?>