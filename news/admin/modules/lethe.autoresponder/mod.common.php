<?php
/*  +------------------------------------------------------------------------+ */
/*  | Artlantis CMS Solutions                                                | */
/*  +------------------------------------------------------------------------+ */
/*  | Lethe Newsletter & Mailing System                                      | */
/*  | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       | */
/*  | Version       2.0                                                      | */
/*  | Last modified 23.02.2015                                               | */
/*  | Email         developer@artlantis.net                                  | */
/*  | Web           http://www.artlantis.net                                 | */
/*  +------------------------------------------------------------------------+ */

/* Action Types */
$LETHE_AR_TYPES = array(autoresponder_after_subscription,
						autoresponder_after_unsubscription,
						autoresponder_specific_date,
						autoresponder_special_date
						);
						
/* Action Time Type */
$LETHE_AR_TIME_TYPES = array('MINUTE'=>autoresponder_minute,
						'HOUR'=>autoresponder_hour,
						'DAY'=>autoresponder_day,
						'MONTH'=>autoresponder_month,
						'YEAR'=>autoresponder_year
						);
?>