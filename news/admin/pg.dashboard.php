<?php 
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 13.11.2014                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
?>
<script type="text/javascript" src="Scripts/Chart.min.js"></script>
<script type="text/javascript" src="Scripts/jquery.countTo.js"></script>
<div class="row">
	<div class="col-md-6">
		<h3>
		<?php echo(letheglobal_hello);?> <span class="text-danger"><?php echo(LETHE_AUTH_NAME);?></span>
		<span class="help-block txxs"><?php echo(((setMyDate($_COOKIE['lethe_login'],2)=='-') ? letheglobal_your_first_login:'<strong>'. letheglobal_last_login .':</strong> <span class="text-info">'.setMyDate($_COOKIE['lethe_login'],2).'</span>'));?></span>
		<span class="help-block text-warning txxs"><strong><?php echo(set_org_name);?></strong></span>
		</h3>
	</div>
	<div class="col-md-6">
		<div class="text-right">
			<h3><?php echo('<small>'.set_org_timezone.'</small><br><span id="livedate">'.date('d.m.Y H:i:s A').'</span>');?></h3>
		</div>
	</div>
</div>
<hr>
<!-- Status Widgets -->
<div class="row dashboardWidgets">
	<div class="col-md-3">
		<div class="alert alert-info">
			<ul class="list-unstyled list-inline">
				<li><h2><span class="glyphicon glyphicon-time"></span></h2></li>
				<li>
					<h3><?php echo(letheglobal_pending);?></h3>
					<?php echo(cntData("SELECT ID FROM ". db_table_pref ."campaigns WHERE OID=". set_org_id ." AND campaign_type=0 AND campaign_pos=0").' '.letheglobal_campaign);?>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-md-3">
		<div class="alert alert-warning">
			<ul class="list-unstyled list-inline">
				<li><h2><span class="glyphicon glyphicon-send"></span></h2></li>
				<li>
					<h3><?php echo(letheglobal_in_process);?></h3>
					<?php echo(cntData("SELECT ID FROM ". db_table_pref ."campaigns WHERE OID=". set_org_id ." AND campaign_type=0 AND campaign_pos=1").' '.letheglobal_campaign);?>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-md-3">
		<div class="alert alert-danger">
			<ul class="list-unstyled list-inline">
				<li><h2><span class="glyphicon glyphicon-pause"></span></h2></li>
				<li>
					<h3><?php echo(letheglobal_stopped);?></h3>
					<?php echo(cntData("SELECT ID FROM ". db_table_pref ."campaigns WHERE OID=". set_org_id ." AND campaign_type=0 AND campaign_pos=2").' '.letheglobal_campaign);?>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-md-3">
		<div class="alert alert-success">
			<ul class="list-unstyled list-inline">
				<li><h2><span class="glyphicon glyphicon-ok"></span></h2></li>
				<li>
					<h3><?php echo(letheglobal_completed);?></h3>
					<?php echo(cntData("SELECT ID FROM ". db_table_pref ."campaigns WHERE OID=". set_org_id ." AND campaign_type=0 AND campaign_pos=3").' '.letheglobal_campaign);?>
				</li>
			</ul>
		</div>
	</div>
</div>

<!-- Stat Widgets -->
<div class="row">
	<div class="col-xs-11 col-sm-11 col-md-6">
		<h3><?php echo(subscribers_subscribers.' '.date("Y"));?></h3><hr>
		<canvas id="subChart" width="500" height="250"></canvas>
		<?php 
		# Gatherup Stats
		$counts = array();
		$labels = array();
		$counts_act = array();
		for($i=1;$i<=12;$i++){
			$calcCounts = $myconn->query("SELECT 
													ID,
													(SELECT COUNT(ID) FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND MONTH(add_date)='". $i ."' AND YEAR(add_date)='". date("Y") ."' AND subscriber_active=1) AS ACTSUB,
													(SELECT COUNT(ID) FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND MONTH(add_date)='". $i ."' AND YEAR(add_date)='". date("Y") ."' AND subscriber_verify=1) AS SVERIFSUB,
													(SELECT COUNT(ID) FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND MONTH(add_date)='". $i ."' AND YEAR(add_date)='". date("Y") ."' AND subscriber_verify=2) AS DVERIFSUB
												FROM ".db_table_pref ."subscribers WHERE OID=". set_org_id ." AND MONTH(add_date)='". $i ."' AND YEAR(add_date)='". date("Y") ."'") or die(mysqli_error($myconn));
			$calcCountsRs = $calcCounts->fetch_assoc();
			$labels[] = '"'.$LETHE_MONTH_NAMES['short'][$i].'"';
			$counts[] = mysqli_num_rows($calcCounts);
			$counts_act[] = intval($calcCountsRs['ACTSUB']);
			$counts_verif[] = intval($calcCountsRs['SVERIFSUB']);
			$counts_verif2[] = intval($calcCountsRs['DVERIFSUB']);
			$calcCounts->free();
		}
		?>
		<script>
			var ctx = document.getElementById("subChart").getContext("2d");
			var options = {
							pointDot : true,
							showTooltips: true,
							scaleStartValue: 0,
							bezierCurve : true,
							responsive: true,
							multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
			}
			var data = {
				labels: [<?php echo(implode(',',$labels));?>],
				datasets: [

					{
						label: "<?php echo(subscribers_single.' '.subscribers_verification);?>",
						fillColor: "rgba(220,220,220,0.2)",
						strokeColor: "rgba(220,220,220,1)",
						pointColor: "rgba(220,220,220,1)",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(220,220,220,1)",
						data: [<?php echo(implode(",",$counts_verif));?>]
					},
					{
						label: "<?php echo(subscribers_double.' '.subscribers_verification);?>",
						fillColor: "rgba(151,187,205,0.2)",
						strokeColor: "rgba(151,187,205,1)",
						pointColor: "rgba(151,187,205,1)",
						pointStrokeColor: "#fff",
						pointHighlightFill: "#fff",
						pointHighlightStroke: "rgba(151,187,205,1)",
						data: [<?php echo(implode(",",$counts_verif2));?>]
					}
				]
			};
			
			var myNewChart = new Chart(ctx).Line(data,options);
		</script>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6">
		<h3><?php echo(letheglobal_general_statistics);?></h3><hr>
		<div class="row dashboardGeneral">
			<div class="col-xs-6 col-sm-3 col-md-3">
				<h4><?php echo(letheglobal_opens);?></h4>
				<canvas id="glob1" width="100" height="100"></canvas>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-3">
				<h4><?php echo(letheglobal_clicks);?></h4>
				<canvas id="glob2" width="100" height="100"></canvas>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-3">
				<h4><?php echo(letheglobal_bounces);?></h4>
				<canvas id="glob3" width="100" height="100"></canvas>
			</div>
			<div class="col-xs-6 col-sm-3 col-md-3">
				<h4><?php echo(letheglobal_unsubscribe);?></h4>
				<canvas id="glob4" width="100" height="100"></canvas>
			</div>
		</div>
		
<?php 
# Stat Calcs
$total_subscriber = cntData("SELECT ID FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ."");
$total_unsubscriber = cntData("SELECT ID FROM ". db_table_pref ."unsubscribes WHERE OID=". set_org_id ."");
$total_sent = cntData("SELECT ID FROM ". db_table_pref ."tasks WHERE OID=". set_org_id ."");

$opRepCnt = $myconn->query("SELECT 
									*,
									(SELECT COUNT(ID) FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=1) AS t_open,
									(SELECT COUNT(ID) FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=0) AS t_click,
									(SELECT COUNT(ID) FROM ". db_table_pref ."reports WHERE OID=". set_org_id ." AND pos=2) AS t_bounce
							FROM 
									". db_table_pref ."reports 
							WHERE 
									OID=". set_org_id ."
							") or die(mysqli_error($myconn));
$opRepCntRs = $opRepCnt->fetch_assoc();

$total_open = $opRepCntRs['t_open'];
$total_click = $opRepCntRs['t_click'];
$total_bounce = $opRepCntRs['t_bounce'];
$nonopens = ($total_sent-$total_open);

$opRepCnt->free();

# Percs
$open_perc = percentage($total_open,$total_sent, 0);
$click_perc = percentage($total_click,$total_sent, 0);
$bounce_perc = percentage($total_bounce,$total_sent, 0);
$unsub_perc = percentage($total_unsubscriber,$total_subscriber, 0);
$unsub_perc=(($unsub_perc>100) ? 100:$unsub_perc);

/* Get Score */
$score = ((($total_sent-($total_unsubscriber+$total_bounce+$nonopens))*100));
$score = (($total_sent!=0) ? ($score/$total_sent):$score);
$score = (($score<=0) ? 0:$score);
?>
		
		<script>
			/* Opens */
			var ctx1 = document.getElementById("glob1").getContext("2d");
			var data = [
				{
					value: <?php echo($open_perc);?>,
					color: "#5CB85C",
					highlight: "#5CB85C",
					label: "<?php echo(letheglobal_opens);?>",
				},
				{
					value: <?php echo(100-$open_perc);?>,
					color: "#637B85",
					highlight: "#637B85",
				}
			];		
			var myNewChart = new Chart(ctx1).Doughnut(data,{
				showTooltips: false,
				onAnimationComplete : function(){
				  ctx1.font = '14px Arial';
				  ctx1.textAlign = 'center';
				  ctx1.fillStyle = '#555';
				  ctx1.fillText("<?php echo($open_perc);?>%", 50, 55);
				}
			});		
			
			/* Clicks */
			var ctx2 = document.getElementById("glob2").getContext("2d");
			var data = [
				{
					value: <?php echo($click_perc);?>,
					color: "#5BC0DE",
					highlight: "#5BC0DE",
					label: "<?php echo(letheglobal_clicks);?>"
				},
				{
					value: <?php echo(100-$click_perc);?>,
					color: "#637B85",
					highlight: "#637B85",
				}
			];		
			var myNewChart = new Chart(ctx2).Doughnut(data,{
				showTooltips: false,
				onAnimationComplete : function(){
				  ctx2.font = '14px Arial';
				  ctx2.textAlign = 'center';
				  ctx2.fillStyle = '#555';
				  ctx2.fillText("<?php echo($click_perc);?>%", 50, 55);
				}
			});
			/* Bounces */
			var ctx3 = document.getElementById("glob3").getContext("2d");
			var data = [
				{
					value: <?php echo($bounce_perc);?>,
					color: "#F0AD4E",
					highlight: "#F0AD4E",
					label: "<?php echo(letheglobal_bounces);?>"
				},
				{
					value: <?php echo(100-$bounce_perc);?>,
					color: "#637B85",
					highlight: "#637B85",
				}
			];		
			var myNewChart = new Chart(ctx3).Doughnut(data,{
				showTooltips: false,
				onAnimationComplete : function(){
				  ctx3.font = '14px Arial';
				  ctx3.textAlign = 'center';
				  ctx3.fillStyle = '#555';
				  ctx3.fillText("<?php echo($bounce_perc);?>%", 50, 55);
				}
			});
			/* Unsubscribes */
			var ctx4 = document.getElementById("glob4").getContext("2d");
			var data = [
				{
					value: <?php echo($unsub_perc);?>,
					color: "#F7464A",
					highlight: "#F7464A",
					label: "<?php echo(letheglobal_unsubscribe);?>"
				},
				{
					value: <?php echo(100-$unsub_perc);?>,
					color: "#637B85",
					highlight: "#637B85",
				}
			];		
			var myNewChart = new Chart(ctx4).Doughnut(data,{
				showTooltips: false,
				onAnimationComplete : function(){
				  ctx4.font = '14px Arial';
				  ctx4.textAlign = 'center';
				  ctx4.fillStyle = '#555';
				  ctx4.fillText("<?php echo($unsub_perc);?>%", 50, 55);
				}
			});
		</script>
		
		<div class="row dashboardScore">
		<hr>
			<div class="col-xs-6 col-md-3">
				<h5><?php echo(letheglobal_score);?></h5>
				<h3><span class="score-count"><span class="countScore" data-from="0" data-to="<?php echo($score);?>">0</span>% <span class="glyphicon glyphicon-thumbs-up"></span></span></h3>
			</div>
			<div class="col-xs-6 col-md-3">
				<h5><?php echo(letheglobal_losses);?></h5>
				<h3 class="text-danger"><span class="count" data-from="0" data-to="<?php echo($unsub_perc);?>">0</span>% <span class="glyphicon glyphicon-thumbs-down"></span></h3>
			</div>
			<div class="col-xs-6 col-md-3">
				<h5><?php echo(letheglobal_list_quality);?></h5>
				<h3 class="text-success"><span class="count" data-from="0" data-to="<?php echo(100-$bounce_perc);?>">0</span>% <span class="glyphicon glyphicon-thumbs-up"></span></h3>
			</div>
			<div class="col-xs-6 col-md-3">
				<h5><?php echo(letheglobal_campaign_quality);?></h5>
				<h3 class="text-success"><span class="count" data-from="0" data-to="<?php echo((percentage(($click_perc+$open_perc)/2,100,0)));?>">0</span>% <span class="glyphicon glyphicon-thumbs-up"></span></h3>
			</div>
		</div>
		<script>
		$(".countScore").countTo({
			onUpdate: function (value) {
				$(".score-count").css("color",getGreenToRed(parseInt(value)));
				if(value<50){
					$(".score-count .glyphicon").removeClass("glyphicon-thumbs-up");
					$(".score-count .glyphicon").addClass("glyphicon-thumbs-down");
				}else{
					$(".score-count .glyphicon").removeClass("glyphicon-thumbs-down");
					$(".score-count .glyphicon").addClass("glyphicon-thumbs-up");
				}
			}
		});
		$(".count").countTo({
			formatter: function (value, options) {
				return value.toFixed(2);
			},
		});
		</script>
		
	</div>
</div>
<hr>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6">
		<h3><?php echo(newsletter_recent_newsletters);?></h3><hr>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><?php echo(letheglobal_campaign);?></th>
						<th><?php echo(newsletter_launch_date);?></th>
					</tr>
				</thead>
				<tbody>
				<?php $recCamp = $myconn->query("SELECT 
															ID,OID,subject,campaign_pos,campaign_type,launch_date 
												   FROM 
															". db_table_pref ."campaigns 
												  WHERE 
															OID=". set_org_id ." 
													AND 
															campaign_type=0 
													AND 
															(campaign_pos=0 OR campaign_pos=1) 
															". ((LETHE_AUTH_VIEW_TYPE) ? ' AND UID='. LETHE_AUTH_ID .'':'') ."
											   ORDER BY 
															launch_date 
													ASC 
													LIMIT 0,5") or die(mysqli_error($myconn));
				if(mysqli_num_rows($recCamp)==0){
					echo('<tr><td colspan="2">'. errMod(letheglobal_record_not_found,'danger') .'</td></tr>');
				}else{
				while($recCampRs = $recCamp->fetch_assoc()){
				?>
					<tr>
						<td><?php echo(showIn($recCampRs['subject'],'page'));?></td>
						<td><span <?php echo('data-countdown="'. setMyDate($recCampRs['launch_date'],6) .'"');?>></span></td>
					</tr>
				<?php }}
				$recCamp->free();
				?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6">
		<h3><?php echo(autoresponder_active_autoresponders);?></h3><hr>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th><?php echo(letheglobal_campaign);?></th>
						<th><?php echo(newsletter_launch_date);?></th>
						<th><?php echo(letheglobal_status);?></th>
					</tr>
				</thead>
				<tbody>
				<?php $recCamp = $myconn->query("SELECT 
															C.ID,C.OID,C.subject,C.campaign_pos,C.campaign_type,C.launch_date,
															CA.CID,CA.ar_type
												   FROM 
															". db_table_pref ."campaigns AS C,
															". db_table_pref ."campaign_ar AS CA
												  WHERE 
															C.OID=". set_org_id ." 
													AND 
															C.campaign_type=1 
													AND 
															(C.campaign_pos=0 OR C.campaign_pos=1)
													AND
															(CA.CID=C.ID)
															". ((LETHE_AUTH_VIEW_TYPE) ? ' AND C.UID='. LETHE_AUTH_ID .'':'') ."
											   ORDER BY 
															C.launch_date 
													ASC 
													LIMIT 0,5") or die(mysqli_error($myconn));
				if(mysqli_num_rows($recCamp)==0){
					echo('<tr><td colspan="3">'. errMod(letheglobal_record_not_found,'danger') .'</td></tr>');
				}else{
				include_once('modules/lethe.autoresponder/mod.common.php');
				while($recCampRs = $recCamp->fetch_assoc()){
				?>
					<tr>
						<td><?php echo(showIn($recCampRs['subject'],'page'));?><br><span class="txxs help-block"><?php echo($LETHE_AR_TYPES[$recCampRs['ar_type']]);?></span></td>
						<td><span <?php echo('data-countdown="'. setMyDate($recCampRs['launch_date'],6) .'"');?>></span></td>
						<td><span class="<?php echo($LETHE_CAMPAIGN_STATUS[$recCampRs['campaign_pos']]['icon']);?>"></span></td>
					</tr>
				<?php }}
				$recCamp->free();
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<hr>
<?php 
$availableFlags = array("ad","ae","af","ag","ai","al","am","an","ao","ar","as","at","au","aw","ax","az","ba","basque","bb","bd","be","bf","bg","bh","bi","bj","bm","bn","bo","br","bs","bt","bv","bw","by","bz","ca","catalonia","cc","cd","cf","cg","ch","ci","ck","cl","cm","cn","co","cr","cs","cu","cv","cx","cy","cz","de","dj","dk","dm","do","dz","ec","ee","eg","eh","england","er","es","et","eu","fi","fj","fk","fm","fo","fr","ga","galicia","gb","gd","ge","gf","gg","gh","gi","gl","gm","gn","gp","gq","gr","gs","gt","gu","gw","gy","hk","hm","hn","hr","ht","hu","id","ie","il","im","in","io","iq","ir","is","it","je","jm","jo","jp","ke","kg","kh","ki","km","kn","kp","kr","kw","ky","kz","la","lb","lc","li","lk","lr","ls","lt","lu","lv","ly","ma","mc","md","me","mf","mg","mh","mk","ml","mm","mn","mo","mp","mq","mr","ms","mt","mu","mv","mw","mx","my","mz","na","nc","ne","nf","ng","ni","nl","no","np","nr","nu","nz","om","pa","pe","pf","pg","ph","pk","pl","pm","pn","pr","ps","pt","pw","py","qa","re","ro","rs","ru","rw","sa","sb","sc","scotland","sd","se","sg","sh","si","sj","sk","sl","sm","sn","so","sr","st","sv","sy","sz","tc","td","tf","tg","th","tj","tk","tm","tm","tn","to","tr","tt","tv","tw","tz","ua","ug","um","us","uy","uz","va","vc","ve","vg","vi","vn","vu","wales","wf","ws","ye","yt","za","zm","zw");
?>
<div class="row dashGeoStat">
	<div class="col-md-12">
		<div role="tabpanel">

		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php echo(letheglobal_country);?></a></li>
		  </ul>

		  <!-- Tab panes -->
		  <div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="home">
			&nbsp;
			<?php $opCountry = $myconn->query("
												SELECT 
															SBS.*,
															(SELECT COUNT(ID) FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." AND local_country_code=SBS.local_country_code) AS SBS_C
												  FROM 
															". db_table_pref ."subscribers AS SBS
												 WHERE 
															SBS.OID=". set_org_id ." 
											  GROUP BY 
															SBS_C
											  ORDER BY 
															SBS_C DESC															
												 LIMIT 
															0,20
													") or die(mysqli_error($myconn));?>
				<ul class="list-group list-inline">
					<?php while($opCountryRs = $opCountry->fetch_assoc()){
						echo('<li class="list-group-item"><span class="'. ((in_array(strtolower($opCountryRs['local_country_code']),$availableFlags)) ? 'flag flag-'.strtolower($opCountryRs['local_country_code']):'glyphicon glyphicon-flag') .'"></span> '. (($opCountryRs['local_country']=='' || $opCountryRs['local_country']=='N/A') ? 'N/A':showIn($opCountryRs['local_country'],'page')) .' <span class="badge">'. $opCountryRs['SBS_C'] .'</span></li>');
					} $opCountry->free();?>
				</ul>
			</div>
		  </div>

		</div>
	</div>
</div>
<hr>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-3">
		<h3><?php echo(organizations_organization);?></h3><hr>
		<ul class="list-group">
			<li class="list-group-item"><?php echo(organizations_disk_usage);?> <span class="badge"><?php echo(formatBytes(GetDirectorySize(set_org_resource)));?></span></li>
			<li class="list-group-item"><?php echo(organizations_users);?> <span class="badge"><?php echo(calcSource(set_org_id,'users'));?></span></li>
			<li class="list-group-item"><?php echo(newsletter_newsletter);?> <span class="badge"><?php echo(calcSource(set_org_id,'newsletters'));?></span></li>
			<li class="list-group-item"><?php echo(autoresponder_autoresponder);?> <span class="badge"><?php echo(calcSource(set_org_id,'autoresponder'));?></span></li>
			<li class="list-group-item"><?php echo(subscribers_subscribers);?> <span class="badge"><?php echo(calcSource(set_org_id,'subscribers'));?></span></li>
			<li class="list-group-item"><?php echo(subscribers_groups);?> <span class="badge"><?php echo(calcSource(set_org_id,'subscriber.groups'));?></span></li>
			<li class="list-group-item"><?php echo(subscribers_subscribe_forms);?> <span class="badge"><?php echo(calcSource(set_org_id,'subscriber.forms'));?></span></li>
			<li class="list-group-item"><?php echo(subscribers_blacklist);?> <span class="badge"><?php echo(calcSource(set_org_id,'subscriber.blacklist'));?></span></li>
			<li class="list-group-item"><?php echo(templates_templates);?> <span class="badge"><?php echo(calcSource(set_org_id,'templates'));?></span></li>
			<li class="list-group-item"><?php echo(organizations_short_codes);?> <span class="badge"><?php echo(calcSource(set_org_id,'shortcode'));?></span></li>
			<li class="list-group-item"><?php echo(organizations_daily_sent);?> <span class="badge"><?php echo(set_org_daily_sent);?></span></li>
		</ul>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-5">
		<h3><?php echo(subscribers_precious_subscribers);?></h3><hr>
		<ul class="list-group">
			<?php $opReport = $myconn->query("SELECT 
														*,
														(SELECT SUM(hit_cnt) FROM ". db_table_pref ."reports WHERE email=R.email) AS score
												FROM ". db_table_pref ."reports AS R WHERE OID=". set_org_id ." AND (pos=1 OR pos=0) GROUP BY email ORDER BY score DESC LIMIT 0,5") or die(mysqli_error($myconn));
				while($opReportRs = $opReport->fetch_assoc()){
					echo('<li class="list-group-item"><a href="javascript:;" data-sbr-id="'. getSubscriber($opReportRs['email'],3) .'" class="sbr-acts text-success tooltips" title="'. subscribers_stats .'"><span class="glyphicon glyphicon-stats"></span></a> '. showIn($opReportRs['email'],'page') .' <span class="label label-warning pull-right">'. letheglobal_score .': '. $opReportRs['score'] .'</span></li>');
				} $opReport->free();
			?>
		</ul>
		<script type="text/javascript">
			$(".sbr-acts").click(function(){
				var subid = $(this).data('sbr-id');
				$.fancybox({
				
					type: "ajax",
					href: "modules/lethe.subscribers/act.xmlhttp.php?pos=sbrstats&ID="+subid,
					width: 700,
					height: 600,
					autoSize: false
				
				});
			});
		</script>
		
		<script type="text/javascript" src="Scripts/jquery.scrollbox.min.js"></script>
		<h3><?php echo(subscribers_latest_subscriptions);?> (25)</h3><hr>
		<?php $opLatests = $myconn->query("SELECT ID,OID,subscriber_mail,add_date,local_country,subscriber_verify FROM ". db_table_pref ."subscribers WHERE OID=". set_org_id ." ORDER BY ID DESC LIMIT 0,25") or die(mysqli_error($myconn));?>
		<div id="latestSubscribers" style="height:150px; overflow:hidden;">
		  <ul class="list-group">
			<?php while($opLatestsRs = $opLatests->fetch_assoc()){
				echo('<li class="list-group-item">
						<strong class="text-primary">'. showIn($opLatestsRs['subscriber_mail'],'page') .'</strong>
						<span class="help-block">'. setMyDate($opLatestsRs['add_date'],2) .'
							<span class="pull-right">
								<span class="flag flag-'. $opLatestsRs['local_country'] .'"></span>
								<span class="tooltips" title="'. $LETHE_VERIFICATION_TYPE[$opLatestsRs['subscriber_verify']] .'">'. getBullets($opLatestsRs['subscriber_verify']) .'</span>
							</span>
						</span>
					  </li>');
			} $opLatests->free();?>
		  </ul>
		</div>
		<script>
			$(document).ready(function(){
				$('#latestSubscribers').scrollbox({
				  linear: true,
				  step: 1,
				  delay: 0,
				  speed: 50
				});
			});
		</script>
		
	</div>
	<div class="col-xs-12 col-sm-12 col-md-4">
		<h3><?php echo(templates_new_templates);?></h3><hr>
		<div id="tempAPI"></div>
	</div>
</div>

<script type="text/javascript" src="Scripts/jquery.countdown.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		loadTemplates(1,6,'style2');
		
		/* Time Remaning */
		$('[data-countdown]').each(function() {
		  var $this = $(this), finalDate = $(this).data('countdown');
		  $this.countdown(finalDate, function(event) {
			$this.html(event.strftime('%D <?php echo(letheglobal_day);?> %H:%M:%S'));
		  });
		});
	});

	function updateLiveDate() {
      $.ajax({
       type: 'POST',
       url: 'act.xmlhttp.php?pos=getlivedate',
       timeout: 1000,
       success: function(data) {
          $('#livedate').html(data); 
          window.setTimeout(updateLiveDate, 1000);
       },
      });
     }
	 updateLiveDate();
</script>