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
$pos = ((!isset($_GET['pos']) || empty($_GET['pos'])) ? '':trim($_GET['pos']));
$ID = ((!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? 0:intval($_GET['ID']));

/* Demo Check */
if(DEMO_MODE){
	if($pos=='createdraft'){die(errMod(letheglobal_demo_mode_active,'danger'));}
}

/* Template Loader */
if($pos=='tempload'){
	
	$pgGo = ((!isset($_GET['pgGo']) || !is_numeric($_GET['pgGo'])) ? 1:intval($_GET['pgGo']));
	$pgLimit = ((!isset($_GET['pgLimit']) || !is_numeric($_GET['pgLimit'])) ? 1:intval($_GET['pgLimit']));
	
	$tempList = get_web_page('http://www.newslether.com/resources/feeds/lethe.temp.feed.php?pgGo='. $pgGo .'&pgLimit='. $pgLimit .'&key='.lethe_license_key);
	$showStyle = ((!isset($_GET['showStyle']) || empty($_GET['showStyle'])) ? '':trim($_GET['showStyle']));
	
	if($showStyle=="style2"){
		# Dashboard Style

		if($tempList['errno']!=0){
			echo(errMod(templates_templates_could_not_be_loaded,'danger'));
		}else{
			$tempRes = json_decode($tempList['content'],true);
			if($tempRes['err']!=''){
				if($tempRes['err']=='INVALID_LICENSE'){echo(errMod(letheglobal_invalid_license_key,'danger'));}
				else if($tempRes['err']=='NO_RECORD'){echo(errMod(templates_there_no_found_template,'danger'));}
			}else{
				$temps = '<ul class="list-group">';
				
					if(!is_array($tempRes['cont'])){
						$temps.='<li class="list-group-item">'.errMod(templates_template_server_is_temporarily_down,'danger').'</li>';
					}else{

					foreach($tempRes['cont'] as $k=>$v){
						$temps .= '<li class="list-group-item">
										<div class="media">
											<div class="media-body">
												<a href="?p=templates/loader">'. $v['name'] .'</a><span class="help-block">'. setMyDate($v['add_date'],2) .'</span>
											</div>
											<div class="media-right">
												<img class="media-object" src="'. (($v['image']=='') ? 'images/temp/tempHolder.png':showIn($v['image'],'input')) .'" alt="..." width="100">
											</div>
										</div>
									</li>';

					}
					
					}
				$temps .= '</ul>';
				echo($temps);
			}
		}

	}else{
		# Loader Style
		if($tempList['errno']!=0){
			echo('<div class="col-md-12">'. errMod(templates_templates_could_not_be_loaded,'danger') .'</div>');
		}else{
			$tempRes = json_decode($tempList['content'],true);
			if($tempRes['err']!=''){
				if($tempRes['err']=='INVALID_LICENSE'){echo('<div class="col-md-12">'. errMod(letheglobal_invalid_license_key,'danger') .'</div>');}
				else if($tempRes['err']=='NO_RECORD'){echo('<div class="col-md-12">'. errMod(templates_there_no_found_template,'danger') .'</div>');}
			}else{
				$temps = '';
					if(!is_array($tempRes['cont'])){
						$temps.='<div class="col-md-12">'.errMod(templates_template_server_is_temporarily_down,'danger').'</div>';
					}else{
					foreach($tempRes['cont'] as $k=>$v){
						$controller = cntData("SELECT ID FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." AND temp_id='". mysql_prep($v['temp_id']) ."'");
						$temps .= '
		  <div class="col-sm-5 col-md-3">
			<div class="thumbnail">
			  <a href="modules/lethe.templates/act.xmlhttp.php?pos=tempdown&tempID='. $v['down_link'] .'&prevs=1" data-fancybox-type="iframe" class="fancybox tempPrevs effect6"><span><img src="'. (($v['image']=='') ? 'images/temp/tempHolder.png':showIn($v['image'],'input')) .'" alt=""></span></a>
			  <div class="caption">
				<a href="modules/lethe.templates/act.xmlhttp.php?pos=tempdown&tempID='. $v['down_link'] .'&prevs=1" data-fancybox-type="iframe" class="fancybox">'. $v['name'] .'</a>
				<p><small>'. setMyDate($v['add_date'],2) .'</small></p>
				<p>';
				if($controller==0){
					if($v['isPremium']){
						$temps .= '<span class="t'. $v['ID'] .'"><a href="http://www.newslether.com/buyTemplate.php?id='. $v['premiumKey'] .'" target="_blank" id="t'. $v['ID'] .'" class="text-danger tooltips buyNow" title="'. templates_buy_now .'"><span class="glyphicon glyphicon-shopping-cart"></span></a></span> <span class="text-primary">'. number_format($v['price'],2) .'$</span><span class="premiumTemp label label-info"><span class="glyphicon glyphicon-star"></span> Premium</span>';
					}else{
						$temps .= '<span class="t'. $v['ID'] .'"><a href="javascript:;" id="t'. $v['ID'] .'" class="text-primary tooltips download" title="'. templates_download_now .'" data-temp-premium="0" data-temp-ids="'. $v['down_link'] .'"><span class="glyphicon glyphicon-save"></span></a></span>';
					}
				}else{
					$temps .= '<a href="javascript:;" class="text-success tooltips" title="'. templates_downloaded .'"><span class="glyphicon glyphicon-ok"></span></a>';
				}
				$temps .= '
				<span class="label label-warning pull-right">'. templates_download .': '. $v['downloads'] .'</span>
				</p>
			  </div>
			</div>
		  </div>
						';

					}
				$temps.='

						<div class="col-md-11">
							<hr>'. $tempRes['pgData'] .'
						</div>
						<script>
		$(".tempPg").click(function(){
			var pg = $(this).data("pggo");
			var pgLim = $(this).data("pglimit");
			loadTemplates(pg,pgLim,null);
		});
		$(".download").click(function(event){
			var id = $(this).attr("id");
			event.preventDefault();
			var dl = $(this).data("temp-ids");
			'. ((DEMO_MODE) ? 'alert("Demo Mode Active!");':'downTemplates(dl,id);') .'
		});
		$(".tooltips").tooltip();
						</script>
				';
					}
				echo($temps);
			}
		}
	}
}

/* Template Downloader */
if($pos=='tempdown'){
	$tempID = ((!isset($_GET['tempID']) || empty($_GET['tempID'])) ? '':trim($_GET['tempID']));
	$prevs = ((!isset($_GET['prevs']) || empty($_GET['prevs'])) ? '':'1');
	$tempLoad = get_web_page('http://www.newslether.com/resources/feeds/lethe.temp.loader.php?prevs='. $prevs .'&ID='. mysql_prep($tempID) .'&key='.lethe_license_key);
	if($tempLoad['errno']!=0){
		echo('<span class="text-danger glyphicon glyphicon-remove"></span>');
	}else{
		$tempRes = json_decode($tempLoad['content'],true);
		if($tempRes['err']=='OK'){
		$tempData = $tempRes['cont'][0];
		
		if($prevs=='1'){
			die($tempData['temp_data_real']);
		}
		
			$controller = cntData("SELECT ID FROM ". db_table_pref ."templates WHERE OID=". set_org_id ." AND temp_id='". mysql_prep($tempData['temp_id']) ."'");
			if($controller==0){
				
				$temp_name=$tempData['name'];
				$temp_contents=$tempData['temp_data'];
				$temp_type=$tempData['temp_type'];
				$isSystem=$tempData['isSystem'];
				$temp_id=$tempData['temp_id'];
				
				$temp_prev=$tempData['image'];
				# Try to upload preview image
				$path_parts = pathinfo($temp_prev);
				$newName = rand(1000,99999).'_'.$path_parts['basename'];
				$saveto = set_org_resource.DIRECTORY_SEPARATOR.$newName;
				if(grab_image($temp_prev,$saveto)){
					$temp_prev=set_org_resource_url.'/'.$newName;
				}else{
					$temp_prev = NULL;
				}
				
				$addTemp = $myconn->prepare("INSERT INTO 
															". db_table_pref ."templates 
												   SET 
															OID=". set_org_id .",
															UID=". LETHE_AUTH_ID .",
															temp_name=?,
															temp_contents=?,
															temp_prev=?,
															temp_type=?,
															isSystem=?,
															temp_id=?
													
													") or die(mysqli_error($myconn));
				$addTemp->bind_param('ssssis',
												$temp_name,
												$temp_contents,
												$temp_prev,
												$temp_type,
												$isSystem,
												$temp_id
									);
				if($addTemp->execute()){
					echo('<span class="text-success glyphicon glyphicon-ok"></span>');
				}else{
					echo('<span class="text-danger glyphicon glyphicon-remove"></span>');
				}
				$addTemp->close();
			}else{
				echo('<span class="text-danger glyphicon glyphicon-remove"></span>');
			}
		}else{
			echo('<span class="text-danger glyphicon glyphicon-remove"></span>');
		}
	}
}

$myconn->close();
ob_end_flush();
?>