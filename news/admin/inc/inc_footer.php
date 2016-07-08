<?php echo(base64_decode(LETHE_POWERED));?>
<?php 
	/* System Notices */
	if(lethe_system_notices){
		if(_iscurl()){
			echo('<div id="lethe-notice-box" class="hidden-xs hidden-sm"><div id="lethe-notices"></div></div>
			<script type="text/javascript">
				$(document).ready(function () {
					$("#lethe-notices").ticker({
						htmlFeed: false,
						ajaxFeed: true,
						feedUrl: "ext/lethe.feeds.php?v='. LETHE_VERSION .'",
						feedType: "xml",
						titleText: "NOTICES",
						displayType: "fade",
						pauseOnItems: 5000,
						controls: false
					});
				});
			</script>
			');
		}
	}
	/* Sidera Helper */
	if(SIDERA_HELPER){
		echo('<script type="text/javascript">
				var sidera_helper_uri = "'.SIDERA_HELPER_URL.'";
			  </script>');
	}
?>
