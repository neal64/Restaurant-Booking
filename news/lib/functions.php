<?php
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 31.10.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+

/* MySQL Prepare */
	function mysql_prep($v){
		global $myconn;
		$v = trim($v);
		$v = $myconn->real_escape_string($v);
		
		return $v;
	}

/* Output Data */
	function showIn($v,$pl=''){
		

		
			if($pl=='page') { $v = htmlspecialchars($v,ENT_QUOTES,'UTF-8'); }
			else if($pl=='input'){$v = htmlspecialchars($v,ENT_COMPAT,'UTF-8'); }
			else if($pl=='htmledit'){$v = htmlspecialchars($v,ENT_COMPAT,'UTF-8'); }
			else if($pl=='textarea'){$v = htmlspecialchars($v,ENT_COMPAT,'UTF-8'); }
			else if($pl=='sconf'){$v = htmlspecialchars($v,ENT_QUOTES,'UTF-8'); }
			else if($pl=='urle'){$v = rawurlencode($v); }
			else if($pl=='urld'){$v = rawurldecode($v); }
			else if($pl=='decode') { $v = htmlspecialchars_decode($v); }

		
		return $v;
		
	}

/* Demo Mode Checker */
	function isDemo($po){
		$cp = 0;
		$po = explode(',',$po);
		foreach($po as $k){
			if(isset($_POST[$k])){
				if(DEMO_MODE){
					$cp=1;
				}
			}
		}
		if($cp==1){
			unset($_POST);
			return false;
		}else{
			return true;
		}
	}
	
/* SEO URL Generator */
function slugify($text){

  $text = trim($text);
  $str_f = array('ş','Ş','ı','İ','ğ','Ğ','ö','Ö','ü','Ü','ç','Ç','é');
  $str_r = array('s','s','i','i','g','g','o','o','u','u','c','c','e');
  $text = str_replace($str_f,$str_r,$text);
  $text = preg_replace('/\W+/', '-', $text);
  $text = strtolower(trim($text, '-'));
  return $text;

}

/* Count Data */
	function cntData($qry){
		global $myconn;
		$getQD = $myconn->query($qry) or die(mysqli_error($myconn));
		$optCount = mysqli_num_rows($getQD);
		$getQD->free();
		return $optCount;
	}
	
/* Data Checker */
	function chkData($qry){
		global $myconn;
		$getQD = $myconn->query($qry) or die(mysqli_error());
		$optCount = mysqli_num_rows($getQD);
		$getQD->free();
		if($optCount==0){
			return true;
			}
		else{
			return false;
			}
	}
	
/* Selectbox and Checkbox Marker */
	function formSelector($f1,$f2,$ty){
		# f1 - First Option
		# f2 - Second Option
		# ty - Form Type (0=Selectbox, 1=Checkbox, 2=Radio, 3=Link, 4=Required, 5=Array values)
		if($ty==0){$cc = ' selected';}
		elseif($ty==1){$cc = ' checked';}
		elseif($ty==2){$cc = ' checked';}
		elseif($ty==3){$cc = ' class="selected-link"';}
		elseif($ty==4){$cc = ' required';}
		elseif($ty==5){
			if(is_array($f1)){
				if(in_array($f2,$f1)){
					return ' selected';
				}else{
					return '';
				}
			}else{
				return '';
			}
		}
		if($f1==$f2){return $cc;} else {return '';}
	}
	
/* Array Sort */
function aasort (&$array, $key) {
    $sorter=array();
    $ret=array();
    reset($array);
    foreach ($array as $ii => $va) {
        $sorter[$ii]=$va[$key];
    }
    asort($sorter);
    foreach ($sorter as $ii => $va) {
        $ret[$ii]=$array[$ii];
    }
    $array=$ret;
}

/* Error Ouput */
function errMod($t,$m){

	$r = '<div class="alert alert-'. $m .' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'. $t .'</div>';
	
	return $r;

}

/* Encryption */
function encr($t){

	$t = md5('youaremylethe'.sha1(sha1(sha1($t))));
	return $t;

}

/* E-Mail Validation */
	function mailVal($v){
		if (!filter_var($v, FILTER_VALIDATE_EMAIL)) {
			return false;
		}
		else {return true;}
	}
	
/* URL Validation */
	function urlVal($v){
		if (!filter_var($v, FILTER_VALIDATE_URL)) {
			return false;
		}
		else {return true;}
	}
	
/* Date Types */
function setMyDate($d,$t){

	$err = 0;
	$d = strtotime($d);
	if(date('Y',$d)=='1970'){$err=1;}
	
	switch($t){
	
		case 1 : $d = date('d.m.Y',$d); break; # 30.06.2014
		case 2 : $d = date('d.m.Y H:i:s A',$d); break; # 30.06.2014 08:13:47
		case 3 : $d = convDat(date('n',$d),0,'months') . date(' d Y, H:s A',$d); break; # March 10, 2001, 5:16 pm
		//case 4 : $d = time_elapsed($d); break; # x time ago
		case 4 : $d = tago($d); break; # x time ago
		case 5 : $d = time_elapsed($d); break; # remaning time
		case 6 : $d = date('Y/m/d H:i:s',$d); break; # 2014/01/18 08:13:47 Used for JS counter
		default : $d = date('d.m.Y',$d); break;
	
	}
	
	if($err){
		return '-';
	}else{
		return $d;
	}

}

/* Bullet Maker */
function getBullets($v){
	if($v==0){
		return '<span class="glyphicon glyphicon-remove text-danger"></span>';
	}else if($v==1){
		return '<span class="glyphicon glyphicon-ok text-success"></span>';
	}else if($v==2){
		return '<span class="glyphicon glyphicon-ok text-warning"></span>';
	}
}

/* Session Master */
class sessionMaster{

	public $sesType = 0; # 0 - Classic Cookie
	public $sesName = null;
	public $sesVal = '';
	public $sesTime = 0;
	public $sesPath = '/';
	public $sesDomain = null;
	public $sesSecure = false;
	public $sesHttp = true;
	public $sesList = '';
	
	public function sessMaster(){
	
		setcookie($this->sesName, 
				  $this->sesVal, 
				  $this->sesTime,
				  $this->sesPath,
				  $this->sesDomain,
				  $this->sesSecure,
				  $this->sesHttp
				 );
	
	}
	
	public function sessDestroy(){
	
		$cookieList = explode(',',$this->sesList);
		
		foreach($cookieList as $k=>$v){
		
			setcookie($v, 
					  '', 
					  time()-3600,
					  $this->sesPath,
					  $this->sesDomain,
					  $this->sesSecure,
					  $this->sesHttp
					 );
		}
	
	}

}

/* Recursive Array Finder */
function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

/* Permission Checker */
function permCheck($cPage){
	global $LETHE_PERMISSIONS;
	
	if(LETHE_AUTH_MODE==2){return true;}
	
	if (in_array($cPage, $LETHE_PERMISSIONS)) {
		return true;
	}else{
		return false;
	}
}

/* Get Percent */
function percentage($val1, $val2, $precision) 
{
	if($val1!=0 && $val2!=0){
		$res = round( ($val1 / $val2) * 100, $precision );
	}else{
		$res=0;
	}
	
	return $res;
}

/* Limit Progressbar */
function getMyLimits($currData,$quot,$unlimited=true){

	$perStat = percentage($currData,$quot,0);
	$progCol = '';
	
	switch($perStat){
		case ($perStat >= 0 && $perStat <= 25) : $progCol = 'progress-bar-success progress-bar-striped active'; break;
		case ($perStat >= 26 && $perStat <= 50) : $progCol = 'progress-bar-primary progress-bar-striped active'; break;
		case ($perStat >= 51 && $perStat <= 75) : $progCol = 'progress-bar-warning progress-bar-striped active'; break;
		case ($perStat >= 76 && $perStat <= 100) : $progCol = 'progress-bar-danger '. (($perStat!=100) ? 'progress-bar-striped active':'') .' '; break;
		default : break;
	}

/* 	$drawBar = '
			<div class="progress tooltips" data-placement="bottom" title="'. $perStat .'% - '.$currData.'/'.$quot.'">
			  <div class="progress-bar '. $progCol .'" role="progressbar" aria-valuenow="'. $perStat .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $perStat .'%;'. ($perStat>0 ? 'min-width:20px;':'color:#555;') .'">
				'. $perStat .'% - '.$currData.'/'.$quot.'
			  </div>
			</div>
	'; */
 	$drawBar = '<div class="progress tooltips" data-placement="bottom" title="'. $perStat .'% - '.$currData.'/'.$quot.'"> 			  <div class="progress-bar '. $progCol .'" role="progressbar" aria-valuenow="'. $perStat .'" aria-valuemin="0" aria-valuemax="100" style="width: '. $perStat .'%;'. ($perStat>0 ? 'min-width:20px;':'color:#555;') .'"> 				'. $perStat .'% - '.$currData.'/'.$quot.' 			  </div> 			</div>';
	
	if($quot==0 && $unlimited==true){
		$drawBar = '<span class="label label-success">'. letheglobal_unlimited .'</span>';
	}
	
	return $drawBar;

}

/* Used Sources */
function calcSource($OID,$w){

	$getVal = 0;

	switch($w){
		case ($w=='users') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."users WHERE OID=". $OID .""); break;
		case ($w=='shortcode') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."short_codes WHERE OID=". $OID ." AND isSystem=0"); break;
		case ($w=='templates') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."templates WHERE OID=". $OID ." AND temp_type='normal'"); break;
		case ($w=='subscriber.groups') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". $OID .""); break;
		case ($w=='subscribers') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". $OID .""); break;
		case ($w=='subscriber.blacklist') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."blacklist WHERE OID=". $OID .""); break;
		case ($w=='subscriber.forms') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."subscribe_forms WHERE OID=". $OID ." AND isSystem<>1"); break;
		case ($w=='newsletters') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."campaigns WHERE OID=". $OID ." AND campaign_type=0"); break;
		case ($w=='autoresponder') : $getVal = cntData("SELECT ID FROM ". db_table_pref ."campaigns WHERE OID=". $OID ." AND campaign_type=1"); break;
		default : break;
	}
	
	return $getVal;
}

/* Limit Blocker */
function limitBlock($l1,$l2){
	if($l2==0){return true;}
	else if($l1<$l2){return true;}else{return false;}
}

/* Too Short / Long */
function isToo($v,$t='',$min=3,$max=50){

	if(strlen($v)<=$min){return showIn($t,'page') . letheglobal_too_short;}
	else if(strlen($v)>=$max){return showIn($t,'page') . letheglobal_too_long;}
	else{return '';}
}

/* Mobile Performence */
function isMob(){

	define('set_gen_mobile_perform',1);
	require_once('Mobile_Detect.php');
	$detect_mobile = new Mobile_Detect();

	 if(set_gen_mobile_perform){
		 if($detect_mobile->isMobile()){
			return true;
		 }else{
			return false;
		 }
	 }
	 return false;
}

/* Timezone Creator */
function timezone_list() {
    static $timezones = null;

    if ($timezones === null) {
        $timezones = array();
        $offsets = array();
        $now = new DateTime();

        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $now->setTimezone(new DateTimeZone($timezone));
            $offsets[] = $offset = $now->getOffset();
            $timezones[$timezone] = '(' . format_GMT_offset($offset) . ') ' . format_timezone_name($timezone);
        }

        array_multisort($offsets, $timezones);
    }

    return $timezones;
}

function format_GMT_offset($offset) {
    $hours = intval($offset / 3600);
    $minutes = abs(intval($offset % 3600 / 60));
    return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
}

function format_timezone_name($name) {
    $name = str_replace('/', ', ', $name);
    $name = str_replace('_', ' ', $name);
    $name = str_replace('St ', 'St. ', $name);
    return $name;
}

/* Isset and Not Empty */
function isseter($v,$t=0,$m=0){

	# $v - Data, $t - Type (0-Text,1-Num), $m - Method (0-Post,1-Get)
	
	if($m==0){ # Post
		if($t==0){
			if(!isset($_POST[$v]) || empty($_POST[$v])){return false;}else{return true;}
		}else if($t==1){
			if(!isset($_POST[$v]) || !is_numeric($_POST[$v])){return false;}else{return true;}
		}
	}else if($m==1){ # Get
		if($t==0){
			if(!isset($_GET[$v]) || empty($_GET[$v])){return false;}else{return true;}
		}else if($t==1){
			if(!isset($_GET[$v]) || !is_numeric($_GET[$v])){return false;}else{return true;}
		}
	}

}

/* Organization Details */
function getOrg($OID,$a){

}

/* Short Code Lister */
function scList($scf){
	global $LETHE_SYSTEM_SHORTCODES;
	global $myconn;
	$list = '';
	foreach($LETHE_SYSTEM_SHORTCODES as $k=>$v){
		$list.='<a href="javascript:;" class="tooltips lethe-sc" data-lethe-scf="'. $scf .'" title="'. $v .'"><span class="label label-danger">{'. $k .'}</span></a>';
	}
	
	$opSc = $myconn->query("SELECT ID,OID,code_key,isSystem FROM ". db_table_pref ."short_codes ORDER BY isSystem DESC, code_key ASC") or die(mysqli_error($myconn));
	while($opScRs = $opSc->fetch_assoc()){
		$list.='<a href="javascript:;" class="lethe-sc" data-lethe-scf="'. $scf .'"><span class="label label-'. (($opScRs['isSystem']==0) ? 'primary':'warning') .'">{'. showIn($opScRs['code_key'],'page') .'}</span></a>';
	}$opSc->free();
	
	return $list;
}


/* Human Read Filesize */
	function bytesToSize1024($size, $unit = null, $decemals = 0) {
		$byteUnits = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		if (!is_null($unit) && !in_array($unit, $byteUnits)) {
			$unit = null;
		}
		$extent = 1;
		foreach ($byteUnits as $rank) {
			if ((is_null($unit) && ($size < $extent <<= 10)) || ($rank == $unit)) {
				break;
			}
		}
		return number_format($size / ($extent >> 10), $decemals) . $rank;
	}
	
    function formatBytes($size,$level=0,$precision=2,$base=1024,$show_unit=1) 
    {
		if($size==0){return '0B';}
        $unit = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB','YB');
        $times = floor(log($size,$base));
		return sprintf("%.".$precision."f",$size/pow($base,($times+$level))). (($show_unit) ? " ".$unit[$times+$level]:'');
    }
	
/* Directory Filter */
class DirFilter extends RecursiveFilterIterator
{
    protected $exclude;
    public function __construct($iterator, array $exclude)
    {
        parent::__construct($iterator);
        $this->exclude = $exclude;
    }
    public function accept()
    {
        return !($this->isDir() && in_array($this->getFilename(), $this->exclude));
    }
    public function getChildren()
    {
        return new DirFilter($this->getInnerIterator()->getChildren(), $this->exclude);
    }
}
	
/* Directory Size */
function GetDirectorySize($path){
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}

/* Directory Lister */
function getDirFiles($pt,$page=1,$perpage=2,$ignores=array()){
	$files = array();
	$dirignore = $ignores;
	
	$directory = new RecursiveDirectoryIterator($pt,FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS);
	$filtered = new DirFilter($directory, $dirignore); 
	$mega = new RecursiveIteratorIterator($filtered);
	
 	//foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pt,FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS)) as $filename)
	foreach ($mega as $filename)
	{

	if($filename!=$pt){
			$files[] = array(
								'file_path'=>$filename,
								'file_name'=>pathinfo($filename, PATHINFO_BASENAME),
								'file_ext'=>pathinfo($filename, PATHINFO_EXTENSION),
								'file_size'=>filesize($filename),
								'file_date'=>filemtime($filename),
								'file_type'=>isImgDoc(pathinfo($filename, PATHINFO_EXTENSION))
								);
	}

	} 
	
	return $files;
}

/* IMG - DOC Type Controller */
function isImgDoc($t){
	$imgTyp = array('jpg','jpe','jpeg','gif','png','tiff','bmp');
	if(in_array($t,$imgTyp)){return true;}else{return false;}
}

/* Sidera Helper */
function sh($hc){
	return ((lethe_sidera_helper) ? '<a href="javascript:;" class="shd-mh" data-shd-key="'. $hc .'" tabindex="-1"><i class="glyphicon glyphicon-question-sign"></i></a> ':'');
}

/* Organization User Content Loader */
function usrAllowRecords($mod){
	if(LETHE_AUTH_MODE==0){
		return (($mod) ? true:false);
	}else{
		return false;
	}
}

/* Subscribe Data Validation */
function validateDatas($v,$t){
	
	$retData = '';
	
	if($t=='checkbox'){
		$retData = implode(',',$v);
	}
	else if($t=='date'){
		$v = str_replace('/','-',$v);
		$retData = date("Y-m-d H:i:s",strtotime($v));
	}else{
		$retData = $v;
	}
	return $retData;
	
}

/* Admin Val Controller */
function isLogged(){

	if(!isset($_COOKIE['lethe']) || empty($_COOKIE['lethe'])){
		return false;
	}else{
		return true;
	}

}

/* Curl Controller */
function _iscurl(){
	if(function_exists('curl_version'))
	  return true;
	else 
	  return false;
}

/* Runtime Calculator */
function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

/* DB Conn Checker */
function chkDB($db_host,$db_name,$db_user,$db_pass){

   $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

   if($mysqli->connect_error){
		return false;
   }else{
		$mysqli->close();
		unset($mysqli);
		return true;
   }

}

/* Bind Param Modifier */
function refValues($arr){
    if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
    {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}

/* Get Subscriber Localization */
function getMyLocal($ip=''){

	$ips = (($ip=='') ? getIP():$ip);
	$sXML = curl_get_result("http://www.geoplugin.net/xml.gp?ip=".$ips);
	$oXML = new SimpleXMLElement($sXML);
	$fetch_status = $oXML->geoplugin_status;
	
	$list = array(
					'country_code'=>'N/A',
					'country_name'=>'N/A',
					'city_name'=>'N/A',
					'region_code'=>'N/A',
					'region_name'=>'N/A'
					);

	
	/* Code */
	if($fetch_status==200){
	
		$list = array(
						'country_code'=>$oXML->geoplugin_countryCode,
						'country_name'=>$oXML->geoplugin_countryName,
						'city_name'=>$oXML->geoplugin_city,
						'region_code'=>$oXML->geoplugin_regionCode,
						'region_name'=>$oXML->geoplugin_regionName
						);
	}
	
	return $list;
}


/* Get IP */
function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/* Get CURL Result */
function curl_get_result($url) {
	@set_time_limit(0);
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

/* PrettyPrint */
function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}

/* GZip Output */
function getGZip($d){
	
	if(function_exists('gzcompress')){
		return gzcompress($d);
	}else{
		return $d;
	}
	
}

/* Cron Command Builder */
function buildCommand($id){
	$comm = '*/2 * * * * /usr/bin/wget -O - -q "'. lethe_root_url .'chronos/" > /dev/null 2>&1';
}

/* Substr */
if(!function_exists('mb_substr')){
	function mb_substr($s,$f,$t,$u='UTF-8'){
		return substr($s,$f,$t);
	}
}

/* Get Submission Account Details */
function getSubmission($v,$d){
	global $myconn;
	# 0 - Submission Account Data via ID
	

	if($d==0){
		$retData = array();
		$opSbs = $myconn->query("SELECT * FROM ". db_table_pref ."submission_accounts WHERE ID=". intval($v) ."") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opSbs)==0){
			$opSbs->free();
			return $retData;
		}else{
			$retData = $opSbs->fetch_assoc();
			$opSbs->free();
			return $retData;
		}
	}
	
}

/* Get Subscriber Details */
function getSubscriber($v,$d){
	global $myconn;
	# 0 - Get Mail Address via ID
	# 1 - Get ID by Key
	# 2 - Get Data Array by Key
	# 3 - Get ID by Mail
	

	if($d==0){
		$retData = array();
		$opSbs = $myconn->query("SELECT * FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND ID=". intval($v) ."") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opSbs)==0){
			$opSbs->free();
			return "NULL";
		}else{
			$rs = $opSbs->fetch_assoc();
			$retData = trim($rs['subscriber_mail']);
			$opSbs->free();
			return $retData;
		}
	}
	else if($d==1){
		$opSbs = $myconn->prepare("SELECT ID,subscriber_key FROM ". db_table_pref ."subscribers WHERE subscriber_key=?") or die(mysqli_error($myconn));
		$opSbs->bind_param('s',$v);
		$opSbs->execute();
		$opSbs->store_result();
		if($opSbs->num_rows==0){
			$opSbs->close();
			return 0;
		}else{
			$srb = new Statement_Result($opSbs);
			$opSbs->fetch();
			$opSbs->close();
			return $srb->Get('ID');
		}
	}
	else if($d==2){
		$retData = array();
		$opSbs = $myconn->prepare("SELECT * FROM ". db_table_pref ."subscribers WHERE subscriber_key=?") or die(mysqli_error($myconn));
		$opSbs->bind_param('s',$v);
		$opSbs->execute();
		$opSbs->store_result();
		if($opSbs->num_rows==0){
			$opSbs->close();
		}else{
			$srb = new Statement_Result($opSbs);
			$opSbs->fetch();
			$opSbs->close();
			$retData['subscriber_ID'] = $srb->Get('ID');
			$retData['subscriber_OID'] = $srb->Get('OID');
			$retData['subscriber_name'] = $srb->Get('subscriber_name');
			$retData['subscriber_mail'] = $srb->Get('subscriber_mail');
		}
		return $retData;
	}
	else if($d==3){
		$opSbs = $myconn->prepare("SELECT ID,OID,subscriber_mail FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND subscriber_mail=?") or die(mysqli_error($myconn));
		$opSbs->bind_param('s',$v);
		$opSbs->execute();
		$opSbs->store_result();
		if($opSbs->num_rows==0){
			$opSbs->close();
			return 0;
		}else{
			$srb = new Statement_Result($opSbs);
			$opSbs->fetch();
			$opSbs->close();
			return (int)$srb->Get('ID');
		}
		return 0;
	}
	
}

/* Get Organization Details */
function getOrgData($v,$d){
	global $myconn;
	# 0 - Get Ungroup ID
	# 1 - Get ID by Public Key
	# 2 - Get System Form ID
	# 3 - Get ID by Private Key
	

	if($d==0){
		$retData = array();
		$opSbs = $myconn->query("SELECT * FROM ". db_table_pref ."subscriber_groups WHERE OID=". intval($v) ." AND isUngroup=1") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opSbs)==0){
			$opSbs->free();
			return 0;
		}else{
			$rs = $opSbs->fetch_assoc();
			$retData = trim($rs['ID']);
			$opSbs->free();
			return $retData;
		}
	}
	else if($d==1){
		$retData = 0;
		$opSbs = $myconn->prepare("SELECT * FROM ". db_table_pref ."organizations WHERE public_key=?") or die(mysqli_error($myconn));
		$opSbs->bind_param('s',$v);
		$opSbs->execute();
		$opSbs->store_result();
		if($opSbs->num_rows==0){
			$opSbs->close();
		}else{
			$srb = new Statement_Result($opSbs);
			$opSbs->fetch();
			$opSbs->close();
			$retData = $srb->Get('ID');
		}
		return $retData;
	}
	else if($d==2){
		$retData = array();
		$opSbs = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_forms WHERE OID=". intval($v) ." AND isSystem=1") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opSbs)==0){
			$opSbs->free();
			return 0;
		}else{
			$rs = $opSbs->fetch_assoc();
			$retData = trim($rs['ID']);
			$opSbs->free();
			return $retData;
		}
	}
	else if($d==3){
		$retData = 0;
		$opSbs = $myconn->prepare("SELECT * FROM ". db_table_pref ."organizations WHERE private_key=?") or die(mysqli_error($myconn));
		$opSbs->bind_param('s',$v);
		$opSbs->execute();
		$opSbs->store_result();
		if($opSbs->num_rows==0){
			$opSbs->close();
		}else{
			$srb = new Statement_Result($opSbs);
			$opSbs->fetch();
			$opSbs->close();
			$retData = $srb->Get('ID');
		}
		return $retData;
	}
	
}

/* Date Interval For Two Date */
 // Time format is UNIX timestamp or
  // PHP strtotime compatible strings
  function dateDiff($time1, $time2, $precision = 6) {
    // If not numeric then convert texts to unix timestamps
    if (!is_int($time1)) {
      $time1 = strtotime($time1);
    }
    if (!is_int($time2)) {
      $time2 = strtotime($time2);
    }

    // If time1 is bigger than time2
    // Then swap time1 and time2
    if ($time1 > $time2) {
      $ttime = $time1;
      $time1 = $time2;
      $time2 = $ttime;
    }

    // Set up intervals and diffs arrays
    $intervals = array('year','month','day','hour','minute','second');
    $diffs = array();

    // Loop thru all intervals
    foreach ($intervals as $interval) {
      // Create temp time from time1 and interval
      $ttime = strtotime('+1 ' . $interval, $time1);
      // Set initial values
      $add = 1;
      $looped = 0;
      // Loop until temp time is smaller than time2
      while ($time2 >= $ttime) {
        // Create new temp time from time1 and interval
        $add++;
        $ttime = strtotime("+" . $add . " " . $interval, $time1);
        $looped++;
      }
 
      $time1 = strtotime("+" . $looped . " " . $interval, $time1);
      $diffs[$interval] = $looped;
    }
    
    $count = 0;
    $times = array();
    // Loop thru all diffs
    foreach ($diffs as $interval => $value) {
      // Break if we have needed precission
      if ($count >= $precision) {
	break;
      }
      // Add value and interval 
      // if value is bigger than 0
      if ($value > 0) {
	// Add s if value is not 1
	if ($value != 1) {
	  $interval .= "s";
	}
	// Add value and interval to times array
	$times[] = $value . " " . $interval;
	$count++;
      }
    }

    // Return string with times
    return implode(", ", $times);
  }
  
/* Clear Some Short Codes */
function clearSCs($rvVal,$webOpt=false){
	$defVal = '';
	if($webOpt){$defVal = '{ONLY_MAILBOX}';}
	$frKeys = array(
						'#\{?(SUBSCRIBER_NAME)\}#'=>'',
						'#\{?(SUBSCRIBER_MAIL)\}#'=>'',
						'#\{?(SUBSCRIBER_WEB)\}#'=>'',
						'#\{?(SUBSCRIBER_PHONE)\}#'=>'',
						'#\{?(SUBSCRIBER_COMPANY)\}#'=>'',
						'#\{?(UNSUBSCRIBE_LINK\[(.*?)\])\}#'=>$defVal,
						'#\{?(VERIFY_LINK\[(.*?)\])\}#'=>'', # Verify Link Cannot Be Use In Campaigns
					);
	$rvVals = preg_replace(array_keys($frKeys), $frKeys,$rvVal);
	return $rvVals;
}
  
/* RSS Filter */
function rss_filter($v){
	$v = showIn($v,'page');
	$v = clearSCs($v);
	# Short Code Formatting
	$scr = new lethe();
	$rss_str = $scr->shortReplaces(array($v));
	$rss_str = '<![CDATA['.$rss_str[0].']]>';
	
	return $rss_str;
}

# Directory and URL
/* Rel Document Builder */
function relDocs($filePath){

        $filePath = str_replace('\\','/',$filePath);
        $ssl = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? true : false;
        $sp = strtolower($_SERVER['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $_SERVER['SERVER_PORT'];
        $stringPort = ((!$ssl && ($port == '80' || $port=='8080')) || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $filePath = preg_replace('/(\/+)/','/',$filePath);
		$fileUrl = str_replace($_SERVER['DOCUMENT_ROOT'] ,$protocol . '://' . $host . $stringPort, $filePath); 
		
		return $fileUrl;

}

# Folder Deleting with sub entries
function deleteAll($directory, $empty = false) {
    if(substr($directory,-1) == "/") {
        $directory = substr($directory,0,-1);
    }

    if(!file_exists($directory) || !is_dir($directory)) {
        return false;
    } elseif(!is_readable($directory)) {
        return false;
    } else {
        $directoryHandle = opendir($directory);
       
        while ($contents = readdir($directoryHandle)) {
            if($contents != '.' && $contents != '..') {
                $path = $directory . "/" . $contents;
               
                if(is_dir($path)) {
                    deleteAll($path);
                } else {
                    unlink($path);
                }
            }
        }
       
        closedir($directoryHandle);

        if($empty == false) {
            if(!rmdir($directory)) {
                return false;
            }
        }
       
        return true;
    }
}

# Get Feeds
function get_web_page( $url )
{
	@set_time_limit(0);
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        //CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

# Remote Upload
function grab_image($url,$saveto){
		$ret = false;
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $raw=curl_exec($ch);
        curl_close ($ch);
        $fp = fopen($saveto,'x');
        if(fwrite($fp, $raw)){
			$ret = true;
		}
        fclose($fp);
		return $ret;
    }
	
# URL Encoders / Decoders
function letheURLEnc($u,$ed=0){
	# u - url
	# ed - 0 ~ Encoding // 1 ~ Decoding
	
	if($ed==0){ # Encode
		$f = array('http://','https://','ftp://');
		$r = array('lethehttp','lethehttps','letheftp');
		$u = str_replace($f,$r,$u);
		return urlencode($u);
	}else{ # Decode
		$u = urldecode($u);
		$f = array('lethehttp','lethehttps','letheftp');
		$r = array('http://','https://','ftp://');
		$u = str_replace($f,$r,$u);
		return $u;
	}
}

# Allowed Domains
function isAllowesURI($d){
	
	if(!isset($_SERVER['HTTP_REFERER'])){return false;}
	$ref = $_SERVER['HTTP_REFERER'];
	$refData = parse_url($ref);
	
	if($refData['host'] !== $d) {
		return false;
	}else{
		return true;
	}

	
}

# CORS
function cors() {
	
	# Allowed Domains Can List Here Only PRO Version!
	# ----
	
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

}
?>