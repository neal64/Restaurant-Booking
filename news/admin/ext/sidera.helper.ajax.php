<?php
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Lethe Newsletter & Mailing System                                      |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       2.0                                                      |
# | Last modified 16.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+

	/* Sidera Helper */
	if(SIDERA_HELPER){
		echo('<script type="text/javascript">

				$(".intoAjax .shd-mh").click(function(){
					var shd_key = $(this).data("shd-key");
					
					window.open("'. SIDERA_HELPER_URL .'"+shd_key, "SideraMiniHelp", "width=400, height=500, scrollbars=yes");return false;

				});

			  </script>');
	}
?>