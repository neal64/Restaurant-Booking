<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 15.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
include_once(dirname(dirname(dirname(dirname(__FILE__)))).DIRECTORY_SEPARATOR.'lethe.php');
include_once(LETHE.DIRECTORY_SEPARATOR.'/lib/lethe.class.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_auth.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/inc_module_loader.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'/inc/org_set.php');

/* Module Functions */
include_once('mod.common.php');
include_once('mod.functions.php');
$pos = ((!isset($_GET['pos']) || empty($_GET['pos'])) ? '':trim($_GET['pos']));
$pos2 = ((!isset($_GET['pos2']) || empty($_GET['pos2'])) ? '':trim($_GET['pos2']));
$impF = ((!isset($_POST['impF']) || empty($_POST['impF'])) ? '':trim($_POST['impF']));
$sepMod = array('sep1'=>',','sep2'=>';','sep3'=>"\n");
$importDebug = '';

/* Demo Check */
if(DEMO_MODE){
	if($pos=='import1'){die(errMod(letheglobal_demo_mode_active,'danger'));}
	if($pos=='export'){die(errMod(letheglobal_demo_mode_active,'danger'));}
	if($pos=='importfromdb'){die(errMod(letheglobal_demo_mode_active,'danger'));}
	if($pos=='csvAnalyserUpload'){die(errMod(letheglobal_demo_mode_active,'danger'));}
}

	@set_time_limit(0);
	@ini_set('mysql.connect_timeout','0');   
	@ini_set('max_execution_time', '0'); 
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime; 


/* Import Custom */
if($pos=='import1'){
	echo('<script>$("#import_prog .well").html("");</script>');
	$dest = set_org_resource.'/expimp/'.$impF;
	$model = ((!isset($_POST['exp_model']) || empty($_POST['exp_model'])) ? 'model1':trim($_POST['exp_model']));
	$separator = ((!isset($_POST['exp_sep']) || empty($_POST['exp_sep'])) ? 'sep1':trim($_POST['exp_sep']));
	$separator = $sepMod[$separator];
	$page = ((!isset($_POST['page']) || !is_numeric($_POST['page'])) ? 1:intval($_POST['page']));
	$isActive = ((!isset($_POST['markas']) || !is_numeric($_POST['markas'])) ? 0:intval($_POST['markas']));
	$isVerfiy = ((!isset($_POST['markverif']) || !is_numeric($_POST['markverif'])) ? 0:intval($_POST['markverif']));
	$add_date = date('Y-m-d H:i:s');
	$sub_web = null;
	$sub_date = null;
	$sub_tel = null;
	$sub_comp = null;
	
	
	# Advanced CSV Importer
	if(isset($_POST['adv_csv']) && $_POST['adv_csv']=='YES'){
		$model = 'modelCsv';
	}
	
	
	$recInv = ((!isset($_GET['recInv']) || !is_numeric($_GET['recInv'])) ? 0:intval($_GET['recInv']));
	$recBL = ((!isset($_GET['recBL']) || !is_numeric($_GET['recBL'])) ? 0:intval($_GET['recBL']));
	$recEx = ((!isset($_GET['recEx']) || !is_numeric($_GET['recEx'])) ? 0:intval($_GET['recEx']));
	$recSc = ((!isset($_GET['recSc']) || !is_numeric($_GET['recSc'])) ? 0:intval($_GET['recSc']));
	
	/* Check Group */
	if(!isset($_POST['exp_groups']) || !is_numeric($_POST['exp_groups'])){die('<script>$("#import_prog .well").append("- '. subscribers_invalid_group .'!<br>");</script>');}else{
		/* Check Group Owner */
		$chkGRP = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		$chkGRP->bind_param('i',$_POST['exp_groups']);
		$chkGRP->execute();
		$chkGRP->store_result();
		if($chkGRP->num_rows==0){die('<script>$("#import_prog .well").append("- '. subscribers_invalid_group .'!<br>");</script>');}else{
			$impGrp = intval($_POST['exp_groups']);
		}
		$chkGRP->close();
	}
	
	/* Check File */
	if(!file_exists($dest) || $impF==''){
		die('<script>$("#import_prog .well").append("- '. subscribers_file_could_not_be_opened .'!");</script>');
	}
	/* Load File */
	$f = file_get_contents($dest);
	$fList = array();
	
	
	/* Advanced CSV Importer */
	if($model=='modelCsv'){
		$importer = new CsvImporter($dest,false,$separator);
		$dataCSV = $importer->get();
	}
	
	
	/* Model Splitter */
	if($model=='model1'){
		$f = preg_replace('/("[^",]+),([^"]+")/','',$f); # Remove comma between quotes
	}
	else if($model=='model4'){
		$new_f = explode($separator,$f);
		$new_data = array();
		$cn = 0;
		foreach($new_f as $k=>$v){
			if(!mailVal($v)){
				$new_data[$cn] = '"'. $v .'" ';
			}else{
				$new_data[$cn] = $new_data[$cn].'<'. $v .'>';
				$cn++;
			}			
		}
		$f = implode(',',$new_data);
		
		# Debug Test
/* 		$current = file_get_contents(LETHE."/newfile.txt");
		$current.=$f;
		file_put_contents(LETHE."/newfile.txt", $current); */
		
		$model='model1';
	}
	else if($model=='model5'){
		$new_f = explode($separator,$f);
		$new_data = array();
		$cn = 0;
		$prs = 0;
		$tempD = '';
		foreach($new_f as $k=>$v){
			if(mailVal($v) && $prs==0){
				$new_data[$cn] = '<'. $v .'>';
				$prs++;
			}else if(!mailVal($v) && $prs==1){
				$tempD = $v;
				$prs++;
			}else if(!mailVal($v) && $prs==2){
				$tempD = '"'.$tempD .' '. $v.'" ';
				$new_data[$cn] = $tempD.$new_data[$cn];
				$prs=0;
				$tempD='';
				$cn++;
			}
		}
		$f = implode(',',$new_data);
		$model='model1';
	}
	else if($model=='modelCsv'){
		$fList = $dataCSV;
		$model='modelCsv';
	}
	/* Create Array */
	if($model!='modelCsv'){
		$fList = explode($separator,$f);
	}
	$fTotal = count($fList);
	$fTotalPhase = number_format($fTotal/$LETHE_IMP_LOAD_PAGE,2);
	$progPer = percentage($page, $fTotalPhase, 2);
	$sourceLimit = calcSource(set_org_id,'subscribers');
	$sourceCntTemp = $sourceLimit;
	
  
	/* Current Pos */
	$page_arr = PaginateArray($fList, $page, $LETHE_IMP_LOAD_PAGE);
	
  	$imp = $myconn->prepare("INSERT INTO 
											". db_table_pref ."subscribers 
									 SET
											OID=". set_org_id .",
											GID=". $impGrp .",
											subscriber_name=?,
											subscriber_mail=?,
											subscriber_web=?,
											subscriber_date=?,
											subscriber_phone=?,
											subscriber_company=?,
											subscriber_active=?,
											subscriber_verify=?,
											subscriber_key=?,
											subscriber_full_data=?,
											add_date=?,
											subscriber_verify_key=?
									 ") or die(mysqli_error($myconn)); 
	/* Mail Checker */
	$mailChk = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND subscriber_mail=?") or die(mysqli_error($myconn));
	$mailBLChk = $myconn->prepare("SELECT ID FROM ". db_table_pref ."blacklist WHERE OID=". set_org_id ." AND email=?") or die(mysqli_error($myconn));
	
	# Data Loop
	foreach($page_arr as $k=>$v){
		
	# Limit Control
	if(!limitBlock($sourceCntTemp,set_org_max_subscriber)){
		$progPer=100;
		$limitExceeded = letheglobal_limit_exceeded;
		break;
	}
		
	# Test Mode
/* 	$isActive = rand(0,1);
	$isVerfiy = rand(0,2);
	$add_date = '2015-' . rand(1,12) . '-' . rand(1,28) . ' 00:00:00';
	$add_date = date('Y-m-d H:i:s',strtotime($add_date)); */

		/* Model 1 ("Name" <mail>) */
		if($model=='model1'){
		
			/* Parse */
			preg_match("/\<(.*)\>/",$v,$gm);
			preg_match("/\"(.*)\"/",$v,$gn);
			$sub_name = ((array_key_exists(1,$gn)) ? $gn[1]:'');
			$sub_mail = ((array_key_exists(1,$gm)) ? $gm[1]:'NULL');
			//$sub_mail = trim($sub_mail);
			$fullData = array();
			$jsonObject = null;
			
			if(mailVal($sub_mail)){
		
				/* Check Blacklist */
					$mailBLChk->bind_param('s',$sub_mail);
					$mailBLChk->execute();
					$mailBLChk->store_result();
					if($mailBLChk->num_rows==0){
					
						/* Check Data */
						$mailChk->bind_param('s',$sub_mail);
						$mailChk->execute();
						$mailChk->store_result();
							if($mailChk->num_rows==0){	
								/* Add Record */
/* 								$jsonObject = $sub_mail;
								$fullData[$jsonObject][] = array('label'=>'Group','content'=>$impGrp);
								$fullData[$jsonObject][] = array('label'=>'Name','content'=>$sub_name);
								$fullData[$jsonObject][] = array('label'=>'E-Mail','content'=>$sub_mail); */
								$fullData = json_encode($fullData);
								$subKey = encr('lethe'.time().$fullData.uniqid(true).$sub_mail);
								$subVerifyKey = encr('letheVerify'.$subKey.uniqid(true));
								$imp->bind_param('ssssssiissss',$sub_name,$sub_mail,$sub_web,$sub_date,$sub_tel,$sub_comp,$isActive,$isVerfiy,$subKey,$fullData,$add_date,$subVerifyKey);
								$imp->execute();
								$recSc++;
								$sourceCntTemp++;
						}else{
							$recEx++;
						}
					}else{
						$recBL++;
					}
			
			}else{
				$recInv++;
			} # Mail Val End
		
		}
		/* Model 2 (<mail>) */
		else if($model=='model2'){
			/* Parse */
			preg_match("/\<(.*)\>/",$v,$gm);
			$sub_name = '';
			$sub_mail = ((array_key_exists(1,$gm)) ? $gm[1]:'NULL');
			$sub_mail = trim($sub_mail);
			$fullData = array();
			$jsonObject = null;
			
			if(mailVal($sub_mail)){
		
				/* Check Blacklist */
					$mailBLChk->bind_param('s',$sub_mail);
					$mailBLChk->execute();
					$mailBLChk->store_result();
					if($mailBLChk->num_rows==0){
					
						/* Check Data */
						$mailChk->bind_param('s',$sub_mail);
						$mailChk->execute();
						$mailChk->store_result();
							if($mailChk->num_rows==0){	
								/* Add Record */
/* 								$jsonObject = $sub_mail;
								$fullData[$jsonObject][] = array('label'=>'Group','content'=>$impGrp);
								$fullData[$jsonObject][] = array('label'=>'Name','content'=>$sub_name);
								$fullData[$jsonObject][] = array('label'=>'E-Mail','content'=>$sub_mail); */
								$fullData = json_encode($fullData);
								$subKey = encr('lethe'.time().$fullData.uniqid(true).$sub_mail);
								$subVerifyKey = encr('letheVerify'.$subKey.uniqid(true));
								$imp->bind_param('ssssssiissss',$sub_name,$sub_mail,$sub_web,$sub_date,$sub_tel,$sub_comp,$isActive,$isVerfiy,$subKey,$fullData,$add_date,$subVerifyKey);
								$imp->execute();
								$recSc++;
						}else{
							$recEx++;
						}
					}else{
						$recBL++;
					}
			
			}else{
				$recInv++;
			} # Mail Val End
		}
		/* Model 3 (mail) */
		else if($model=='model3'){
			/* Parse */
			$gm[1] = $v;
			$sub_name = '';
			$sub_mail = ((array_key_exists(1,$gm)) ? $gm[1]:'NULL');
			$sub_mail = trim($sub_mail);
			$fullData = array();
			$jsonObject = null;
			
			if(mailVal($sub_mail)){
		
				/* Check Blacklist */
					$mailBLChk->bind_param('s',$sub_mail);
					$mailBLChk->execute();
					$mailBLChk->store_result();
					if($mailBLChk->num_rows==0){
					
						/* Check Data */
						$mailChk->bind_param('s',$sub_mail);
						$mailChk->execute();
						$mailChk->store_result();
							if($mailChk->num_rows==0){	
								/* Add Record */
/* 								$jsonObject = $sub_mail;
								$fullData[$jsonObject][] = array('label'=>'Group','content'=>$impGrp);
								$fullData[$jsonObject][] = array('label'=>'Name','content'=>$sub_name);
								$fullData[$jsonObject][] = array('label'=>'E-Mail','content'=>$sub_mail); */
								$fullData = json_encode($fullData);
								$subKey = encr('lethe'.time().$fullData.uniqid(true).$sub_mail);
								$subVerifyKey = encr('letheVerify'.$subKey.uniqid(true));
								$imp->bind_param('ssssssiissss',$sub_name,$sub_mail,$sub_web,$sub_date,$sub_tel,$sub_comp,$isActive,$isVerfiy,$subKey,$fullData,$add_date,$subVerifyKey);
								$imp->execute();
								$recSc++;
						}else{
							$recEx++;
						}
					}else{
						$recBL++;
					}
			
			}else{
				$recInv++;
			} # Mail Val End 
		}
		/* Model 4 (name,mail) */
		else if($model=='model4'){
			/* Called Model 1 */
		}
		/* Model 5 (mail,name,surname) */
		else if($model=='model5'){
			/* Called Model 1 */
		}
		/* Model CSV */
		else if($model=='modelCsv'){
			
			# CSV Condution
			$getCnd = ((!isset($_POST['csvCond']) || $_POST['csvCond']=='') ? '':trim($_POST['csvCond']));
			$getCnd = explode(",",$getCnd);
			$getCndToArr = array();
			foreach($getCnd as $gck=>$gcv){
				$gcvD = explode('@',$gcv);
				$getCndToArr[$gcvD[0]] = $gcvD[1];
			}
						
			# Reset Values
			$sub_name = null;
			$sub_mail = null;
			$sub_web = null;
			$sub_date = null;
			$sub_tel = null;
			$sub_comp = null;
						
			/* Parse */
			foreach($getCndToArr as $csvK=>$csvV){
				if($csvV=='subscriber_mail'){
					$sub_mail = ((array_key_exists($csvK,$v)) ? $v[$csvK]:null);
					$sub_mail = trim($sub_mail);
				}
				else if($csvV=='subscriber_name'){
					$sub_name = ((array_key_exists($csvK,$v)) ? $v[$csvK]:null);
				}
				else if($csvV=='subscriber_web'){
					$sub_web = ((array_key_exists($csvK,$v)) ? $v[$csvK]:null);
				}
				else if($csvV=='subscriber_date'){
					$sub_date = ((array_key_exists($csvK,$v)) ? $v[$csvK]:null);
				}
				else if($csvV=='subscriber_phone'){
					$sub_tel = ((array_key_exists($csvK,$v)) ? $v[$csvK]:null);
				}
				else if($csvV=='subscriber_company'){
					$sub_comp = ((array_key_exists($csvK,$v)) ? $v[$csvK]:null);
				}
			}
			
			
			$fullData = array();
			$jsonObject = null;
			
			if(mailVal($sub_mail)){
		
				/* Check Blacklist */
					$mailBLChk->bind_param('s',$sub_mail);
					$mailBLChk->execute();
					$mailBLChk->store_result();
					if($mailBLChk->num_rows==0){
					
						/* Check Data */
						$mailChk->bind_param('s',$sub_mail);
						$mailChk->execute();
						$mailChk->store_result();
							if($mailChk->num_rows==0){	
								/* Add Record */
/* 								$jsonObject = $sub_mail;
								$fullData[$jsonObject][] = array('label'=>'Group','content'=>$impGrp);
								$fullData[$jsonObject][] = array('label'=>'Name','content'=>$sub_name);
								$fullData[$jsonObject][] = array('label'=>'E-Mail','content'=>$sub_mail); */
								$fullData = json_encode($fullData);
								$subKey = encr('lethe'.time().$fullData.uniqid(true).$sub_mail);
								$subVerifyKey = encr('letheVerify'.$subKey.uniqid(true));
								$imp->bind_param('ssssssiissss',$sub_name,$sub_mail,$sub_web,$sub_date,$sub_tel,$sub_comp,$isActive,$isVerfiy,$subKey,$fullData,$add_date,$subVerifyKey);
								$imp->execute();
								$recSc++;
								$sourceCntTemp++;
						}else{
							$recEx++;
						}
					}else{
						$recBL++;
					}
			
			}else{
				$recInv++;
			} # Mail Val End
			
		}
		/* End Models */
	
	}
	
	# Progress Info
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
   $systemAllowTime = ini_get('max_execution_time');
   //$systemAllowTime = 100;
   $timeStatus = 0;
   if($systemAllowTime!=0){
	   $timeStatus = percentage($totaltime, $systemAllowTime, 0);
   }
   
	echo('
		  <script>
			function getGreenToRed(percent){
						r = percent<50 ? 255 : Math.floor(255-(percent*2-100)*255/100);
						g = percent>50 ? 255 : Math.floor((percent*2)*255/100);
						return "rgb("+g+","+r+",0)";
					}
		  
			$("#import_prog .well").append("- '. subscribers_file_opened .'<br>");
			$("#import_prog .well").append("- '. subscribers_counting_records .'..<br>");
			$("#import_prog .well").append("- '. subscribers_max_valid_data .': '. $fTotal .'<br>");
			$("#import_prog .well").append("- '. subscribers_total_phase .': ~'. $fTotalPhase .'<br>");
			$("#import_prog .well").append("- '. subscribers_parsing_has_begun .'..<br>");
			$("#import_prog .well").append("- '. subscribers_found_in_the_blacklist .': <span class=text-muted>'. $recBL .'</span><br>");
			$("#import_prog .well").append("- '. subscribers_invalid_record_founded .': <span class=text-danger>'. $recInv .'</span><br>");
			$("#import_prog .well").append("- '. subscribers_already_registered_found .': <span class=text-warning>'. $recEx .'</span><br>");
			$("#import_prog .well").append("- '. subscribers_recorded .': <span class=text-success>'. $recSc .'</span><br>");
			$("#import_prog .well").append("- '. subscribers_this_process_used .' '. number_format($totaltime,2) .' ms (Allowed by System: '. (($systemAllowTime==0) ? 'Unlimited':$systemAllowTime.'ms') .')<br><br>");
			$("#import_prog .well").append(\'<div class="form-group"><label>Server Status %'. $timeStatus .'</label><div class="serverStatus" style="width:1px; height:35px;"></div></div>\');
			$("#import_prog .well").append(\'<div class="form-group"><label>Progress</label>'. getMyLimits($recSc,$fTotal) .'</div>\');
			'. ((lethe_debug_mode) ? '$("#import_prog .well").append(\'<div class="form-group"><label>DEBUG</label>'. showIn($importDebug,'page') .'</div>\');':'') .'
			
			var perc = '. $timeStatus .';
			for(i=0;i<=100;i++){
				defColor = "#999";
				if(i<=perc){defColor=getGreenToRed(i);}
				$(".serverStatus").append(\'<span style="height:5px; background-color:\'+defColor+\'; border:1px solid #FAFAFA; -webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px; border:1px solid #fff; padding:2px;"></span>\');
			}
			$(".serverStatus").animate(
					{"width": "1000px"},
					3000,
					"easeOutQuart");
			
		  </script>
	');
	
	/* Process End Sleep and Call Another */
	$imp->close();
	$mailChk->close();
	$mailBLChk->close();
	sleep(1);
	if($progPer<100){
	echo('
	<script>
									$(".impRes").html("");
									$.ajax({
										url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=import1&recBL='. $recBL .'&recInv='. $recInv .'&recEx='. $recEx .'&recSc='. $recSc .'",
										type: "POST",
										data : {impF:"'. $impF .'",
												exp_groups:"'. intval($impGrp) .'",
												exp_model:"'. $model .'",
												exp_sep:"'. $_POST['exp_sep'] .'",
												adv_csv:"'. ((isset($_POST['adv_csv']) && $_POST['adv_csv']=='YES') ? 'YES':'') .'",
												csvCond:"'. ((isset($_POST['csvCond']) && $_POST['csvCond']!='') ? $_POST['csvCond']:'') .'",
												markas:"'. $isActive .'",
												markverif:"'. $isVerfiy .'",
												page:"'. (int)($page+1) .'"
												},
										contentType: "application/x-www-form-urlencoded",
										success: function(data, textStatus, jqXHR)
										{
											$(".impRes").html(data)
										},
										error: function (jqXHR, textStatus, errorThrown)
										{
											$(".impRes").html("<span class=text-danger>'. letheglobal_error_occured .'</span>")
										}
									});
	</script>');
	}else{
		if(!isset($limitExceeded)){
			echo('<script>
			$("#import_prog .well").append("<strong class=\"text-success\">'. subscribers_completed_successfully .'!</strong><br>");
			</script>');
		}else{
			echo('<script>
			$("#import_prog .well").append("<strong class=\"text-danger\">'. $limitExceeded .'!</strong><br>");
			</script>');

		}
	}
	
	
}


/* Export */
if($pos=='export'){

	$resultData='';
	$dest = set_org_resource.'/expimp/';
	
	if(!isset($_POST['exp_groups'])){$resultData.='* '. subscribers_please_choose_a_group .'<br>';}else{
		$_POST['exp_groups'] = explode(',',$_POST['exp_groups']);
		/* Check Group Owner */
		$chkGRP = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		foreach($_POST['exp_groups'] as $k=>$v){
			$chkGRP->bind_param('i',$v);
			$chkGRP->execute();
			$chkGRP->store_result();
			if($chkGRP->num_rows==0){$resultData.='* '. subscribers_invalid_group .'<br>';}
		}
		$chkGRP->close();
	}
	if(!isset($_POST['exp_model']) || empty($_POST['exp_model']) || !array_key_exists($_POST['exp_model'],$LETHE_IMP_EXP_MODELS)){$resultData.='* '. subscribers_please_choose_a_model .'<br>';}
	if(!isset($_POST['exp_sep']) || empty($_POST['exp_sep']) || !array_key_exists($_POST['exp_sep'],$LETHE_IMP_EXP_SEPARATORS)){$resultData.='* '. subscribers_please_choose_a_separator .'<br>';}
	if(!isset($_POST['exp_markas']) || !is_numeric($_POST['exp_markas'])){$_POST['exp_markas']=0;}
	if(!isset($_POST['exp_markverif']) || !is_numeric($_POST['exp_markverif'])){$_POST['exp_markverif']=0;}
	if(!isset($_POST['expF']) || !file_exists($dest.$_POST['expF'])){$resultData.='* '. subscribers_export_file_could_not_be_found .'!<br>';}
	
	if($resultData==''){
	
		$pgGo = ((!isset($_GET['pgGo']) || !is_numeric($_GET['pgGo'])) ? 1:intval($_GET['pgGo']));
		$expF = $_POST['expF'];
		$expFile = set_org_resource_url.'/expimp/'.$expF;
		$seperator = $sepMod[$_POST['exp_sep']];
		$model = $_POST['exp_model'];
		$extQry = '';
		
		/* Active & Verify */
		if($_POST['exp_markas']!=0){$extQry.=' AND subscriber_active='. (($_POST['exp_markas']==1) ? 1:0) .' ';}
		if($_POST['exp_markverif']!=0){$extQry.=' AND subscriber_verify='. intval($_POST['exp_markverif']) .' ';}
		
		$selGrp = implode(' OR GID=',$_POST['exp_groups']);
		$selGrp = ' AND (GID='.$selGrp.')';
		$selGrp = $extQry.$selGrp;
		
		$colData = array();
		$limit = $LETHE_EXP_LOAD_PAGE;
		$recSc = ((!isset($_GET['recSc']) || !is_numeric($_GET['recSc'])) ? 0:intval($_GET['recSc']));
		
		 $fTotal		 = mysqli_num_rows($myconn->query("SELECT * FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." ". $selGrp .""));
		 $fTotalPhase	 = ceil($fTotal / $limit);
		 $dtStart	 = ($pgGo-1)*$limit;
		
		$opData = $myconn->query("SELECT * FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." ". $selGrp ." LIMIT $dtStart,$limit") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opData)==0){die('<span class="text-danger">'. letheglobal_record_not_found .'</span>');}
		while($opDataRs = $opData->fetch_assoc()){
			/* Model 1 */
			if($model=='model1'){
				$colData[] = '"'. $opDataRs['subscriber_name'] .'" <'. $opDataRs['subscriber_mail'] .'>';
			}
			/* Model 2 */
			else if($model=='model2'){
				$colData[] = '<'. $opDataRs['subscriber_mail'] .'>';
			}
			/* Model 3 */
			else if($model=='model3'){
				$colData[] = $opDataRs['subscriber_mail'];
			}
			/* Model 4 */
			else if($model=='model4'){
				$colData[] = ''. $opDataRs['subscriber_name'] .''. $seperator .''. $opDataRs['subscriber_mail'] .'';
			}
			/* Model 5 */
			else if($model=='model5'){
				$colData[] = ''. $opDataRs['subscriber_name'] .''. $seperator .''. $opDataRs['subscriber_mail'] .'';
			}
			$recSc++;
		}$opData->free();
		
		/* Render Data */
		if($seperator=='\n'){
			$collectedData = implode(PHP_EOL,$colData);
		}else{
			$collectedData = implode($seperator,$colData);
		}
		
		/* Write Data */
		$conc=((fopen($dest.$expF,'a')) ? fopen($dest.$expF,'a'):false);
		if(!$conc){
			die(subscribers_export_file_could_not_be_found);
		}else{
			if(fwrite($conc,$collectedData)){
			
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime); 
			
				echo('
				- '. subscribers_max_valid_data .': '. $fTotal .'<br>
				- '. subscribers_total_phase .': ~'. $fTotalPhase .'<br>
				- '. subscribers_parsing_has_begun .'..<br>
				- '. subscribers_recorded .': <span class=text-success>'. $recSc .'</span><br>
				- '. subscribers_this_process_used .' '. number_format($totaltime,2) .' ms<br><br>
				'. getMyLimits($recSc,$fTotal) .'');
				
			if($pgGo!=$fTotalPhase){
				sleep(1);
				echo('
				<script>
				$.ajax({
					url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=export&recSc='. $recSc .'&pgGo='. (($fTotalPhase>1) ? ($pgGo+1):1) .'",
					type: "POST",
					data : {exp_groups:"'. implode(',',$_POST['exp_groups']) .'",
							exp_model:"'. $_POST['exp_model'] .'",
							exp_sep:"'. $_POST['exp_sep'] .'",
							exp_markas:"'. $_POST['exp_markas'] .'",
							exp_markverif:"'. $_POST['exp_markverif'] .'",
							expF:"'. $expF .'"
							},
					contentType: "application/x-www-form-urlencoded",
					success: function(data, textStatus, jqXHR)
					{
						$("#import_prog .well").html(data);
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
				 
					}
				});
				</script>
				');
				
			}else{
				echo('
					<script>
					$("#import_prog .well").append("<strong class=\"text-success\">'. subscribers_completed_successfully .'!</strong><br>");
					$("#import_prog .well").append("'. subscribers_exported_file .': <a href=\"'. $expFile .'\" download=\"'. $expFile .'\">'. $expF .'</a><br>");
					</script>
				');
			}
				
			}else{
				die(subscribers_data_could_not_be_written);
			}
		}
		fclose($conc);
	
	}else{
		die($resultData);
	}

} # Export End

/* DB Checker */
if($pos=='dbcheck'){
	if(DEMO_MODE){die('<span class="text-danger">'. letheglobal_demo_mode_active .'</span>');}
	
	$db_host = ((!isset($_POST['db_host']) || empty($_POST['db_host'])) ? 'NULL':trim($_POST['db_host']));
	$db_name = ((!isset($_POST['db_name']) || empty($_POST['db_name'])) ? 'NULL':trim($_POST['db_name']));
	$db_user = ((!isset($_POST['db_user']) || empty($_POST['db_user'])) ? 'NULL':trim($_POST['db_user']));
	$db_pass = ((!isset($_POST['db_pass']) || empty($_POST['db_pass'])) ? '':trim($_POST['db_pass']));
	error_reporting(0);
	if(chkDB($db_host,$db_name,$db_user,$db_pass)){
		die('
			<span class="text-success">'. subscribers_connection_ok .'</span>
			<script>
				$("#db_imp_res").html("");
				$("#tblRes").html("");
				$("#collapseOne").collapse("hide");
				$("#collapseTwo").collapse("show");
			</script>
			');
	}else{
		die('<span class="text-danger">'. subscribers_unable_to_connect_to_database .'!</span>');
	}
}

/* Table Checker */
if($pos=='tblcheck'){
	if(DEMO_MODE){die('<span class="text-danger">'. letheglobal_demo_mode_active .'</span>');}
	
	$db_host = ((!isset($_POST['db_host']) || empty($_POST['db_host'])) ? 'NULL':trim($_POST['db_host']));
	$db_name = ((!isset($_POST['db_name']) || empty($_POST['db_name'])) ? 'NULL':trim($_POST['db_name']));
	$db_user = ((!isset($_POST['db_user']) || empty($_POST['db_user'])) ? 'NULL':trim($_POST['db_user']));
	$db_pass = ((!isset($_POST['db_pass']) || empty($_POST['db_pass'])) ? '':trim($_POST['db_pass']));
	$dbl_data = $LETHE_IMPORT_PART_SOFTWARES[$_POST['db_platform']];
	$dbl_pref = ((!isset($_POST['db_pref']) || empty($_POST['db_pref'])) ? '':trim($_POST['db_pref']));
	error_reporting(0);
	if(chkDB($db_host,$db_name,$db_user,$db_pass)){
	
		$myconnx = new mysqli($db_host,$db_user,$db_pass,$db_name) or die(mysqli_error());
		$myconnx->set_charset('utf8');
		$chkPos = 0;
		$chkr = $myconnx->query("SHOW COLUMNS FROM `". $dbl_pref.$dbl_data['table'] ."` LIKE '". $dbl_data['field_email'] ."'");
		$chkPos = (mysqli_num_rows($chkr))?1:0;
		$chkr2 = $myconnx->query("SHOW COLUMNS FROM `". $dbl_pref.$dbl_data['table'] ."` LIKE '". $dbl_data['field_name'] ."'");
		$chkPos = (mysqli_num_rows($chkr2))?1:0;
		if($dbl_data['field_name2']!=''){
			$chkr3 = $myconnx->query("SHOW COLUMNS FROM `". $dbl_pref.$dbl_data['table'] ."` LIKE '". $dbl_data['field_name2'] ."'");
			$chkPos = (mysqli_num_rows($chkr3))?1:0;
		}
		
		if($chkPos){
		
			$counts = $myconnx->query("SELECT * FROM `". $dbl_pref.$dbl_data['table'] ."`");
			$totCnt = intval(mysqli_num_rows($counts));
		
			die('<span class="text-success">'. subscribers_accessed_to_tables .'!</span>
			<script>
				$("#db_imp_res").html("<strong><span class=\"text-danger\">'. $totCnt .'</span> '. subscribers_record_found .'!</strong><br><br>'. (($totCnt>0) ? '<button type=\"button\" class=\"btn btn-success\" name=\"impFromDB\" id=\"impFromDB\">'. subscribers_start_import .'</button>':'') .'");
				$("#collapseTwo").collapse("hide");
				$("#collapseThree").collapse("show");
				
				$("#impFromDB").click(function(){
				
					$("#import_prog").html(\'<div class="well">'. subscribers_connecting_to_database .'...<div><span class="spin glyphicon glyphicon-refresh"></div></div>\');
				
					$.ajax({
						url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=importfromdb",
						type: "POST",
						data : $("#prty_form").serialize(),
						contentType: "application/x-www-form-urlencoded",
						success: function(data, textStatus, jqXHR)
						{
							$("#import_prog .well").html(data);
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
					 
						}
					});
				});
			</script>
			');
		}else{
			die('<span class="text-danger">'. subscribers_tables_could_not_be_found .'!</span>');
		}
		
	}else{
		die('<span class="text-danger">'. subscribers_unable_to_connect_to_database .'!</span>');
	}
}

/* Import From DB */
if($pos=='importfromdb'){
	error_reporting(0);
	if(!isset($_POST['db_groups']) || !is_numeric($_POST['db_groups'])){die('<span class="text-danger">'. subscribers_please_choose_a_group .'</span>');}else{
		/* Check Group Owner */
		$chkGRP = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		$chkGRP->bind_param('i',$_POST['db_groups']);
		$chkGRP->execute();
		$chkGRP->store_result();
		if($chkGRP->num_rows==0){$chkGRP->close();die('<span class="text-danger">'. subscribers_invalid_group .'</span>');}else{
			$impGrp = intval($_POST['db_groups']);
		}
		$chkGRP->close();
	}
	if(!isset($_POST['db_host']) || empty($_POST['db_host'])){die('<span class="text-danger">'. subscribers_please_enter_a_db_host_address .'</span>');}else{$db_host=trim($_POST['db_host']);}
	if(!isset($_POST['db_name']) || empty($_POST['db_name'])){die('<span class="text-danger">'. subscribers_please_enter_a_db_name .'</span>');}else{$db_name=trim($_POST['db_name']);}
	if(!isset($_POST['db_user']) || empty($_POST['db_user'])){die('<span class="text-danger">'. subscribers_please_enter_a_db_login_name .'</span>');}else{$db_user=trim($_POST['db_user']);}
	if(!isset($_POST['db_pass']) || empty($_POST['db_pass'])){$db_pass='';}else{$db_pass=trim($_POST['db_pass']);}
	
	if(!isset($_POST['db_platform']) || !array_key_exists($_POST['db_platform'],$LETHE_IMPORT_PART_SOFTWARES)){die('<span class="text-danger">'. subscribers_please_choose_a_platform .'</span>');}else{$dbl_data=$LETHE_IMPORT_PART_SOFTWARES[$_POST['db_platform']];}
	if(!isset($_POST['db_pref']) || empty($_POST['db_pref'])){$dbl_pref='';}else{$dbl_pref=trim($_POST['db_pref']);}
	
	$isActive = ((!isset($_POST['db_markas']) || $_POST['db_markas']!='YES') ? 0:intval($_POST['db_markas']));
	$isVerfiy = ((!isset($_POST['db_markverif']) || !is_numeric($_POST['db_markverif'])) ? 0:intval($_POST['db_markverif']));
	

	/* DB Check */
	if(!chkDB($db_host,$db_name,$db_user,$db_pass)){
		die('<span class="text-danger">'. subscribers_unable_to_connect_to_database .'!</span><br>');
	}else{
		echo('<span class="text-success">'. subscribers_connection_ok .'!</span><br>');
		
		/* Table Check */
		$myconnx = new mysqli($db_host,$db_user,$db_pass,$db_name) or die(mysqli_error());
		$myconnx->set_charset('utf8');
		$chkPos = 0;
		$chkr = $myconnx->query("SHOW COLUMNS FROM `". $dbl_pref.$dbl_data['table'] ."` LIKE '". $dbl_data['field_email'] ."'");
		$chkPos = (mysqli_num_rows($chkr))?1:0;
		$chkr2 = $myconnx->query("SHOW COLUMNS FROM `". $dbl_pref.$dbl_data['table'] ."` LIKE '". $dbl_data['field_name'] ."'");
		$chkPos = (mysqli_num_rows($chkr2))?1:0;
		if($dbl_data['field_name2']!=''){
			$chkr3 = $myconnx->query("SHOW COLUMNS FROM `". $dbl_pref.$dbl_data['table'] ."` LIKE '". $dbl_data['field_name2'] ."'");
			$chkPos = (mysqli_num_rows($chkr3))?1:0;
		}
		
		if(!$chkPos){
			die('<span class="text-danger">'. subscribers_tables_could_not_be_found .'!</span><br>');
		}else{
		
	
		echo('<span class="text-success">'. subscribers_table_opened_successfully .'!</span><br>');
		
		$recInv = ((!isset($_GET['recInv']) || !is_numeric($_GET['recInv'])) ? 0:intval($_GET['recInv']));
		$recBL = ((!isset($_GET['recBL']) || !is_numeric($_GET['recBL'])) ? 0:intval($_GET['recBL']));
		$recEx = ((!isset($_GET['recEx']) || !is_numeric($_GET['recEx'])) ? 0:intval($_GET['recEx']));
		$recSc = ((!isset($_GET['recSc']) || !is_numeric($_GET['recSc'])) ? 0:intval($_GET['recSc']));
		$pgGo = ((!isset($_GET['pgGo']) || !is_numeric($_GET['pgGo'])) ? 1:intval($_GET['pgGo']));
		
		
		$limit = $LETHE_EXP_LOAD_PAGE;
		$fTotal		 = mysqli_num_rows($myconnx->query("SELECT * FROM `". $dbl_pref.$dbl_data['table'] ."`"));
		$fTotalPhase	 = ceil($fTotal / $limit);
		$dtStart	 = ($pgGo-1)*$limit;
		
   $mtime = microtime(); 
   $mtime = explode(" ",$mtime); 
   $mtime = $mtime[1] + $mtime[0]; 
   $endtime = $mtime; 
   $totaltime = ($endtime - $starttime);
	$sourceLimit = calcSource(set_org_id,'subscribers');
	$sourceCntTemp = $sourceLimit;
   
		/* Prepares */
		$imp = $myconn->prepare("INSERT INTO 
												". db_table_pref ."subscribers 
										 SET
												OID=". set_org_id .",
												GID=". $impGrp .",
												subscriber_name=?,
												subscriber_mail=?,
												subscriber_active=". $isActive .",
												subscriber_verify=". $isVerfiy .",
												subscriber_key=?,
												subscriber_full_data=?,
												subscriber_verify_key=?
										 ") or die(mysqli_error($myconn)); 
		/* Mail Checker */
		$mailChk = $myconn->prepare("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND subscriber_mail=?") or die(mysqli_error($myconn));
		$mailBLChk = $myconn->prepare("SELECT ID FROM ". db_table_pref ."blacklist WHERE OID=". set_org_id ." AND email=?") or die(mysqli_error($myconn));
			
	
		$fetchData = $myconnx->query("SELECT * FROM `". $dbl_pref.$dbl_data['table'] ."`  LIMIT $dtStart,$limit") or die(mysqli_error($myconn));
		while($fetchDataRs = $fetchData->fetch_assoc()){
			$fname = ((isset($fetchDataRs[$dbl_data['field_name']]) && $fetchDataRs[$dbl_data['field_name']]!='') ? $fetchDataRs[$dbl_data['field_name']]:'');
			$fname2 = ((isset($fetchDataRs[$dbl_data['field_name2']]) && $fetchDataRs[$dbl_data['field_name2']]!='') ? $fetchDataRs[$dbl_data['field_name2']]:'');
			$sub_mail = ((isset($fetchDataRs[$dbl_data['field_email']]) && $fetchDataRs[$dbl_data['field_email']]!='') ? $fetchDataRs[$dbl_data['field_email']]:'NULL');
			$sub_name = $fname . (($fname!='') ? ' '.$fname2:'');
			
			$fullData = array();
			$jsonObject = null;
			
			# Limit Control
			if(!limitBlock($sourceCntTemp,set_org_max_subscriber)){
				$progPer=100;
				$limitExceeded = letheglobal_limit_exceeded;
				break;
			}
			
			
			/* Invalid Check */
			if(!mailVal($sub_mail)){
				$recInv++;
			}else{
				/* Check Blacklist */
					$mailBLChk->bind_param('s',$sub_mail);
					$mailBLChk->execute();
					$mailBLChk->store_result();
					if($mailBLChk->num_rows==0){
					
						/* Check Data */
						$mailChk->bind_param('s',$sub_mail);
						$mailChk->execute();
						$mailChk->store_result();
							if($mailChk->num_rows==0){	
								/* Add Record */
								$jsonObject = $sub_mail;
								
/* 								
								JSON Disabled Here
								$fullData[$jsonObject][] = array('label'=>'Group','content'=>$impGrp);
								$fullData[$jsonObject][] = array('label'=>'Name','content'=>$sub_name);
								$fullData[$jsonObject][] = array('label'=>'E-Mail','content'=>$sub_mail);
								$fullData = json_encode($fullData); */
								$fullData = "[]";
								$subKey = encr('lethe'.time().$fullData.uniqid(true).$sub_mail);
								$subVerifyKey = encr('letheVerify'.$subKey.uniqid(true));
								$imp->bind_param('sssss',$sub_name,$sub_mail,$subKey,$fullData,$subVerifyKey);
								$imp->execute();
								$recSc++;
								$sourceCntTemp++;
						}else{
							$recEx++;
						}
					}else{
						$recBL++;
					}
			}
			
			
		} $fetchData->free();
		
			echo('
				- '. subscribers_counting_records .'..<br>
				- '. subscribers_max_valid_data .': '. $fTotal .'<br>
				- '. subscribers_total_phase .': ~'. $fTotalPhase .'<br>
				- '. subscribers_parsing_has_begun .'..<br>
				- '. subscribers_found_in_the_blacklist .': <span class=text-muted>'. $recBL .'</span><br>
				- '. subscribers_invalid_record_founded .': <span class=text-danger>'. $recInv .'</span><br>
				- '. subscribers_already_registered_found .': <span class=text-warning>'. $recEx .'</span><br>
				- '. subscribers_recorded .': <span class=text-success>'. $recSc .'</span><br>
				- '. subscribers_this_process_used .' '. number_format($totaltime,2) .' ms<br><br>
				'. getMyLimits($recSc,$fTotal) .'
			');
		
		/* Callback */
			if($pgGo!=$fTotalPhase){
				sleep(1);
				echo('
				<script>
					$.ajax({
						url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=importfromdb&recEx='. $recEx .'&recBL='. $recBL .'&recInv='. $recInv .'&recSc='. $recSc .'&pgGo='. (($fTotalPhase>1) ? ($pgGo+1):1) .'",
						type: "POST",
						data : $("#prty_form").serialize(),
						contentType: "application/x-www-form-urlencoded",
						success: function(data, textStatus, jqXHR)
						{
							$("#import_prog .well").html(data);
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
					 
						}
					});
				</script>
				');
				
			}else{
				if(!isset($limitExceeded)){
					echo('<script>
					$("#import_prog .well").append("<strong class=\"text-success\">'. subscribers_completed_successfully .'!</strong><br>");
					</script>');
				}else{
					echo('<script>
					$("#import_prog .well").append("<strong class=\"text-danger\">'. $limitExceeded .'!</strong><br>");
					</script>');

				}
			}
		

			
		} # Table Check End
		
	} # DB Check End
	
	
}


/* CSV Analyser */
if($pos=='csvAnalyser'){
	
	$analyser = '
				<h3>'. subscribers_csv_analyser .'</h3><hr>
				<form name="csvUpload" id="csvUpload" method="POST" action="" enctype="multipart/form-data">
					<div class="form-group">
						<label for="delimiter">'. subscribers_delimiter .'</label>
						<select name="delimiter" id="delimiter" class="form-control autoWidth input-sm">';

						foreach($LETHE_IMP_EXP_SEPARATORS as $k=>$v){
							$analyser .= '<option value="'. $k .'">'. showIn($v,'page') .'</option>';
						}
	$analyser .= '
						</select>
					</div>
					<div class="form-group">
						<label for="filecsv">'. subscribers_csv_file .'</label>
						<div class="input-group">
						<input type="file" class="form-control input-sm" id="filecsv" name="filecsv">
						<span class="input-group-btn">
							<button class="btn btn-primary btn-sm" type="submit">'. subscribers_upload .'</button>
						</span>
						</div>
					</div>
				</form>
				<div id="analyserResult"></div>
				
				<script>
						
					$("#csvUpload").on("submit",function(e){
						
						e.stopPropagation();
						e.preventDefault();
						
						var formData = new FormData();
						formData.append("delimiter", $("#delimiter option:selected").val());
						formData.append("file", $("#filecsv")[0].files[0]);
																								
						$.ajax({
							url : "modules/lethe.subscribers/exip.xmlhttp.php?pos=csvAnalyserUpload",
							type: "POST",
							data : formData,
							contentType: false,
							cache:false,
							processData: false,
							success: function(data, textStatus, jqXHR)
							{
								$("#analyserResult").html(data);
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								$("#analyserResult").html("'. letheglobal_error_occured .'");
								console.log("ERRORS: " + textStatus);
							}
						});
					
					});										
				</script>
				';
				
	echo($analyser);
	
}

/* CSV Analyser Upload */
if($pos=='csvAnalyserUpload'){

/* Start Upload */
	$errText = '';
	$parsedList = '';
	$delimiter = ((!isset($_POST['delimiter']) || $_POST['delimiter']=="") ? 'sep1':trim($_POST['delimiter']));
	$file_name = 'lethe.importcsv.'.uniqid();
	include_once(LETHE_ADMIN.'/classes/class.upload.php');
	$dest = set_org_resource.DIRECTORY_SEPARATOR.'expimp'.DIRECTORY_SEPARATOR;
	if(!isset($_FILES['file']) || $_FILES['file']['error']!=0){die(errMod(subscribers_please_choose_a_file,'danger'));}
	$handle = new upload($_FILES['file']);
	
			if ($handle->uploaded) {
				$handle->file_new_name_body   = $file_name;
				$handle->file_safe_name = true;
				$handle->file_overwrite = false;
				$handle->file_auto_rename = true;
				$handle->allowed = $LETHE_EXP_IMP_MIMES; //*
				$handle->file_max_size = $LETHE_MAX_IMPORT_FILE_SIZE;
										
						
				//** Processing
				$handle->process($dest);
				if ($handle->processed) { # Uploaded
				
						/* Parsing Start */
						$uploadedFile = $dest.$handle->file_dst_name;
						$importer = new CsvImporter($uploadedFile,false,$sepMod[$delimiter]);
						$data = $importer->get(1);

						if(!array_key_exists(0,$data)){
							die(errMod(subscribers_invalid_csv_content."!",'danger'));
						}
						
						if(count($data[0])<2){
							die(errMod(subscribers_incorrect_delimiter."!",'danger'));
						}
						
						$parsedList = '<ul id="sortable1" class="list-unstyled csvlist connectedSortable">';
						foreach($data[0] as $k=>$v){
							$parsedList .= '<li><input type="hidden" class="csvkey" value="'. $k .'">'. (($v=='') ? '<span class="label label-danger">{'. subscribers_empty .'}</span>':'<span class="label label-success">'. showIn($v,'page') .'</span>') .'</li>';
						}
						$parsedList .= '</ul>';
						/* Parsing End */
				
						$handle->clean();
					}
				else{ # Uploading Error
						$parsedList = errMod($handle->error,'danger');
					}
				# Uploading Finished
		
			}else{
				$parsedList = errMod($handle->error,'danger');
			}
	
	
	$csvAnalyst = '
	<style>
		  .ui-state-highlight { height: 1.5em; line-height: 1.2em; }
		  .connectedSortable{padding:5px; border:1px dashed #99CDFF}
		  .connectedSortable li{cursor:pointer;}
	</style>
	<div class="panel"><div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<h4>'. subscribers_csv_contents .'</h4><hr>
								'. $parsedList .'
							</div>
							<div class="col-md-6">
								<h4>'. subscribers_destination_table_columns .'</h4><hr>
								<div class="row">
								<div class="col-md-6">
									<div class="alert alert-info">
										'. subscribers_subscriber_name .'
										<ul id="subscriber_name" class="list-unstyled tablelist connectedSortable">

										</ul>
									</div>
									<div class="alert alert-info">
										'. letheglobal_subscriber_e_mail .'
										<ul id="subscriber_mail" class="list-unstyled tablelist connectedSortable">

										</ul>
									</div>
									<div class="alert alert-info">
										'. letheglobal_subscriber_web .'
										<ul id="subscriber_web" class="list-unstyled tablelist connectedSortable">

										</ul>
									</div>
								</div>
								<div class="col-md-6">	
									<div class="alert alert-info">
										'. letheglobal_subscriber_date .'
										<ul id="subscriber_date" class="list-unstyled tablelist connectedSortable">

										</ul>
									</div>
									<div class="alert alert-info">
										'. letheglobal_subscriber_phone .'
										<ul id="subscriber_phone" class="list-unstyled tablelist connectedSortable">

										</ul>
									</div>
									<div class="alert alert-info">
										'. letheglobal_subscriber_company .'
										<ul id="subscriber_company" class="list-unstyled tablelist connectedSortable">

										</ul>
									</div>
								</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<input type="hidden" value="" id="collectedData"> <button type="button" class="btn btn-success" id="getDatas">'. subscribers_save_conditions .'</button>
									</div>
								</div>
							</div>
						</div>
				   </div></div>
				   <script>
					 $( ".connectedSortable" ).sortable({
							connectWith: "ul",
							placeholder: "ui-state-highlight",
							revert: 200,
							receive: function(event,ui){
								var droppedArea = $(this).attr("id");
								var liSize = $("#"+droppedArea+" li").length;
								if(liSize>1){
									$("#sortable1").append("<li>"+ $(ui.item).html() +"</li>");
									$(ui.item).remove();
								}
							}
						}).disableSelection();
						
						
						$("#getDatas").click(function(){
							
							var dataArr = new Array();
							var saveArea = ""; var csvKey = "";
							
							$("ul.tablelist").each(function(e){
								saveArea = $(this).attr("id");
								csvKey = $("#"+saveArea+" input").val();
								if($("#"+saveArea+" input").length>0){
									dataArr.push(csvKey+"@"+saveArea);
								}
							});
							
							var jsonData = dataArr.join(",");
							$("#collectedData").val(jsonData);
							
							if(jsonData=="[]" || jsonData==""){
								alert("'. subscribers_please_choose_fields .'!");
							}else{
								if($("#subscriber_mail input").length<1){
									alert("'. subscribers_invalid_e_mail_field .'");
								}else{
									/* Save Conditions and Close Modal */
									$("#csvCond").val(jsonData);
									var selectedDelim = $("#delimiter option:selected").val();
									$("#imp_sep option[value=\'" + selectedDelim + "\']").attr("selected", true);
									$.fancybox.close();
								}
							}
							
						});
				   </script>
				  ';
	echo($csvAnalyst);
	
}

$myconn->close();
ob_end_flush();
?>
