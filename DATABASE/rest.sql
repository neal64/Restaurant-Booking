-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2016 at 09:41 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rest`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE IF NOT EXISTS `contact_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `subject` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `date` varchar(50) NOT NULL,
  `flag` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `message`, `subject`, `email`, `date`, `flag`) VALUES
(1, 'aaa', 'asdasdad', 'asss', 'aaa@aaa.com', '09-05-16 09:27:51 AM', '1');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(10) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(100) NOT NULL,
  `event_location` varchar(100) NOT NULL,
  `event_thumbnail` varchar(500) NOT NULL,
  `event_description` longtext NOT NULL,
  `event_date` datetime NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_location`, `event_thumbnail`, `event_description`, `event_date`) VALUES
(57, 'San Marco Plazza', 'Roma Italia IT', 'skin/images/events/ev1.jpg', 'Phasellus rutrum, purus eu eleifend blandit, felis est commodo tortor, ut ultricies lorem tellus id quam. Quisque purus sapien, vulputate condimentum mi nec, lacinia iaculis eros. Curabitur eu mauris ex. ', '2015-03-28 18:18:43'),
(60, 'Food grocery', 'Into the Restaurant', 'skin/images/events/tumblr_msuei3sMTo1st5lhmo1_1280.jpg', 'Phasellus rutrum, purus eu eleifend blandit, felis est commodo tortor, ut ultricies lorem tellus id quam. Quisque purus sapien, vulputate condimentum mi nec, lacinia iaculis eros. Curabitur eu mauris ex. ', '2015-03-31 00:37:20');

-- --------------------------------------------------------

--
-- Table structure for table `informations`
--

CREATE TABLE IF NOT EXISTS `informations` (
  `contact_id` int(10) NOT NULL AUTO_INCREMENT,
  `contact_phone_number` varchar(20) NOT NULL,
  `contact_email` varchar(50) NOT NULL,
  `contact_latitude` varchar(50) NOT NULL,
  `contact_longitude` varchar(50) NOT NULL,
  `contact_address` varchar(150) DEFAULT NULL,
  `contact_monday_hours` varchar(50) NOT NULL,
  `contact_tuesday_hours` varchar(20) DEFAULT NULL,
  `contact_wednesday_hours` varchar(20) DEFAULT NULL,
  `contact_thursday_hours` varchar(20) DEFAULT NULL,
  `contact_friday_hours` varchar(20) DEFAULT NULL,
  `contact_saturday_hours` varchar(20) DEFAULT NULL,
  `contact_sunday_hours` varchar(20) DEFAULT NULL,
  `site_body_background` varchar(255) DEFAULT NULL,
  `site_button_bg_normal` varchar(15) DEFAULT NULL,
  `site_button_txt_normal` varchar(15) DEFAULT NULL,
  `site_button_txt_hover` varchar(15) DEFAULT NULL,
  `site_button_bg_hover` varchar(15) DEFAULT NULL,
  `header_top_bar_bg` varchar(15) DEFAULT NULL,
  `header_middle_section_bg` varchar(15) DEFAULT NULL,
  `header_color` varchar(15) DEFAULT NULL,
  `header_nav_bg` varchar(15) DEFAULT NULL,
  `header_nav_item_bg_hover` varchar(15) DEFAULT NULL,
  `header_nav_txt` varchar(15) DEFAULT NULL,
  `header_nav_item_txt_hover` varchar(15) DEFAULT NULL,
  `footer_top_section_bg` varchar(15) DEFAULT NULL,
  `footer_bottom_bar_txt` varchar(15) DEFAULT NULL,
  `footer_bottom_bar_bg` varchar(15) DEFAULT NULL,
  `footer_top_section_txt` varchar(15) DEFAULT NULL,
  `site_main_font` varchar(255) DEFAULT NULL,
  `language_is_active` varchar(100) DEFAULT NULL,
  `social_fb` varchar(255) DEFAULT NULL,
  `social_tw` varchar(255) DEFAULT NULL,
  `social_gplus` varchar(255) DEFAULT NULL,
  `social_dribbble` varchar(255) DEFAULT NULL,
  `social_stumbleupon` varchar(255) DEFAULT NULL,
  `social_linkedin` varchar(255) DEFAULT NULL,
  `social_pin` varchar(255) DEFAULT NULL,
  `social_tumblr` varchar(255) DEFAULT NULL,
  `social_instagram` varchar(255) DEFAULT NULL,
  `social_youtube` varchar(255) DEFAULT NULL,
  `social_flickr` varchar(255) DEFAULT NULL,
  `social_digg` varchar(255) DEFAULT NULL,
  `social_vimeo` varchar(255) DEFAULT NULL,
  `facebook_appid` varchar(255) DEFAULT NULL,
  `facebook_secret` varchar(255) DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `paypal_sandbox` varchar(255) DEFAULT NULL,
  `wysiwyg_about` longtext,
  `wysiwyg_contact` longtext,
  PRIMARY KEY (`contact_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `informations`
--

INSERT INTO `informations` (`contact_id`, `contact_phone_number`, `contact_email`, `contact_latitude`, `contact_longitude`, `contact_address`, `contact_monday_hours`, `contact_tuesday_hours`, `contact_wednesday_hours`, `contact_thursday_hours`, `contact_friday_hours`, `contact_saturday_hours`, `contact_sunday_hours`, `site_body_background`, `site_button_bg_normal`, `site_button_txt_normal`, `site_button_txt_hover`, `site_button_bg_hover`, `header_top_bar_bg`, `header_middle_section_bg`, `header_color`, `header_nav_bg`, `header_nav_item_bg_hover`, `header_nav_txt`, `header_nav_item_txt_hover`, `footer_top_section_bg`, `footer_bottom_bar_txt`, `footer_bottom_bar_bg`, `footer_top_section_txt`, `site_main_font`, `language_is_active`, `social_fb`, `social_tw`, `social_gplus`, `social_dribbble`, `social_stumbleupon`, `social_linkedin`, `social_pin`, `social_tumblr`, `social_instagram`, `social_youtube`, `social_flickr`, `social_digg`, `social_vimeo`, `facebook_appid`, `facebook_secret`, `paypal_email`, `paypal_sandbox`, `wysiwyg_about`, `wysiwyg_contact`) VALUES
(1, '+987-654-3210', 'restaurant@gmail.com', '21.1697256', '72.8352567', 'Surat, Gujarat, India', '09:00 - 21:00', '09:00  - 21:00', '09:00  - 21:00', '09:00  - 21:00', '09:00  - 14:00', '09:00  - 14:00', 'Closed', '#fff', '#c79c60', '#ffffff', '#ffffff', '#a37b44', '#c79c60', '#ffffff', '#ffffff', '#c79c60', '#a37b44', '#ffffff', '#ffffff', '#f5f5f5', '#888888', '#000000', '#000', 'Zeyada', 'No', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, 'stan-merchant@gmail.com', 'Sandbox', '&lt;h2&gt;About phpRestaurant&lt;/h2&gt;\n\n&lt;p&gt;&lt;span style=&quot;font-size:16px&quot;&gt;Ut viverra ac ligula nec pulvinar. &lt;em&gt;Nam sit amet rutrum ex, quis tempor dui&lt;/em&gt;. Nam eu lacinia nisl. Vestibulum quis ex convallis, sollicitudin mi sed, tempor sapien. Aenean aliquet odio at lacus rhoncus, sit amet dignissim tellus consequat. Duis iaculis commodo convallis. &lt;/span&gt;&lt;/p&gt;\n\n&lt;p&gt;&lt;span style=&quot;font-size:16px&quot;&gt;&lt;strong&gt;Donec vulputate&lt;/strong&gt; ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum, nisl sit amet condimentum sollicitudin, dui felis scelerisque nulla, at efficitur ligula erat eget quam. Phasellus posuere auctor euismod. Nam vel imperdiet ex. Sed placerat vitae sem in fringilla. Fusce a nisl sit amet sem bibendum laoreet at sed ex.&lt;/span&gt;&lt;/p&gt;\n\n&lt;h2&gt;WYSIWYG editor&lt;/h2&gt;\n\n&lt;p&gt;&lt;span style=&quot;font-size:16px&quot;&gt;Ut viverra ac ligula nec pulvinar. &lt;u&gt;Nam sit amet rutrum ex&lt;/u&gt;, quis tempor dui. Nam eu lacinia nisl. Vestibulum quis ex convallis, sollicitudin mi sed, tempor sapien. Aenean aliquet odio at lacus rhoncus, sit amet dignissim tellus consequat. Duis iaculis commodo convallis. &lt;/span&gt;&lt;/p&gt;\n\n&lt;p&gt;&lt;span style=&quot;font-size:16px&quot;&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum, nisl sit amet condimentum sollicitudin, dui felis scelerisque nulla, at efficitur ligula erat eget quam. Phasellus posuere auctor euismod. Nam vel imperdiet ex. Sed placerat vitae sem in fringilla. Fusce a nisl sit amet sem bibendum laoreet at sed ex.&lt;/span&gt;&lt;/p&gt;\n\n&lt;h2&gt;Text and content in columns - 2 columns&lt;/h2&gt;\n\n&lt;div class=&quot;row two-col&quot;&gt;\n&lt;div class=&quot;col-md-6 col-1&quot;&gt;\n&lt;p&gt;&lt;span&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum, nisl sit amet condimentum sollicitudin, dui felis scelerisque nulla, at efficitur ligula erat eget quam. Phasellus posuere auctor euismod. Nam vel imperdiet ex.&lt;/span&gt;&lt;/p&gt;\n&lt;/div&gt;\n\n&lt;div class=&quot;col-md-6 col-2&quot;&gt;\n&lt;p&gt;&lt;span&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum, nisl sit amet condimentum sollicitudin, dui felis scelerisque nulla, at efficitur ligula erat eget quam. Phasellus posuere auctor euismod. Nam vel imperdiet ex.&lt;/span&gt;&lt;/p&gt;\n&lt;/div&gt;\n&lt;/div&gt;\n\n&lt;h2&gt;Text and content in columns - 2 columns&lt;/h2&gt;\n\n&lt;div class=&quot;row three-col&quot;&gt;\n&lt;div class=&quot;col-md-4 col-1&quot;&gt;\n&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;http://localhost/phprestaurant.v1.4/system/timthumb.php?src=http://localhost/phprestaurant.v1.4/skin/images/menus/event1.jpg&amp;amp;h=410&amp;amp;w=560&amp;amp;zc=1&quot; /&gt;&lt;/p&gt;\n\n&lt;h2&gt;Block with image, title and content&lt;/h2&gt;\n\n&lt;p&gt;&lt;span&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum&lt;/span&gt;&lt;/p&gt;\n&lt;/div&gt;\n\n&lt;div class=&quot;col-md-4 col-2&quot;&gt;\n&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;http://localhost/phprestaurant.v1.4/system/timthumb.php?src=http://localhost/phprestaurant.v1.4/skin/images/menus/event1.jpg&amp;amp;h=410&amp;amp;w=560&amp;amp;zc=1&quot; /&gt;&lt;/p&gt;\n\n&lt;h2&gt;Block with image, title and content&lt;/h2&gt;\n\n&lt;p&gt;&lt;span&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum&lt;/span&gt;&lt;/p&gt;\n&lt;/div&gt;\n\n&lt;div class=&quot;col-md-4 col-3&quot;&gt;\n&lt;p&gt;&lt;img alt=&quot;&quot; src=&quot;http://localhost/phprestaurant.v1.4/system/timthumb.php?src=http://localhost/phprestaurant.v1.4/skin/images/menus/event1.jpg&amp;amp;h=410&amp;amp;w=560&amp;amp;zc=1&quot; /&gt;&lt;/p&gt;\n\n&lt;h2&gt;Block with image, title and content&lt;/h2&gt;\n\n&lt;p&gt;&lt;span&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum&lt;/span&gt;&lt;/p&gt;\n&lt;/div&gt;\n&lt;/div&gt;\n\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n\n&lt;h2&gt;Embed YouTube videos&lt;/h2&gt;\n\n&lt;div class=&quot;row two-col-right&quot;&gt;\n&lt;div class=&quot;col-md-9 col-main&quot;&gt;\n&lt;p&gt;&lt;iframe height=&quot;500&quot; src=&quot;//www.youtube.com/embed/AJtDXIazrMo&quot; width=&quot;830&quot;&gt;&lt;/iframe&gt;&lt;/p&gt;\n&lt;/div&gt;\n\n&lt;div class=&quot;col-md-3 col-sidebar&quot;&gt;\n&lt;h2&gt;Add your own custom text&lt;/h2&gt;\n\n&lt;p&gt;&lt;span&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum&lt;/span&gt;&lt;/p&gt;\n\n&lt;p&gt;&lt;span&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum&lt;/span&gt;&lt;/p&gt;\n\n&lt;p&gt;&lt;span&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum&lt;/span&gt;&lt;/p&gt;\n&lt;/div&gt;\n&lt;/div&gt;\n\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n', '&lt;p style=&quot;text-align:center&quot;&gt;&lt;span style=&quot;font-size:16px&quot;&gt;Donec vulputate ligula finibus, maximus justo vel, porttitor dui. Suspendisse aliquam odio nibh, nec consectetur augue luctus eget. Ut condimentum, nisl sit amet condimentum sollicitudin, dui felis scelerisque nulla, at efficitur ligula erat eget quam. Phasellus posuere auctor euismod. Nam vel imperdiet ex. Sed placerat vitae sem in fringilla. Fusce a nisl sit amet sem bibendum laoreet at sed ex.&lt;/span&gt;&lt;/p&gt;\n');

-- --------------------------------------------------------

--
-- Table structure for table `menuorder`
--

CREATE TABLE IF NOT EXISTS `menuorder` (
  `menu_item_id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL,
  KEY `menu_item_id` (`menu_item_id`,`order_id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menuorder`
--

INSERT INTO `menuorder` (`menu_item_id`, `order_id`) VALUES
(78, 578),
(225, 574),
(243, 574);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `menu_item_id` int(10) NOT NULL AUTO_INCREMENT,
  `menu_item_category` varchar(100) NOT NULL,
  `menu_item_name` varchar(100) NOT NULL,
  `menu_item_details` varchar(300) DEFAULT NULL,
  `menu_preview_image` varchar(255) NOT NULL,
  `menu_item_price_per_slice` double NOT NULL,
  `menu_item_author` varchar(100) DEFAULT NULL,
  `menu_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`menu_item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=244 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_item_id`, `menu_item_category`, `menu_item_name`, `menu_item_details`, `menu_preview_image`, `menu_item_price_per_slice`, `menu_item_author`, `menu_date`) VALUES
(78, 'International dishes', 'FideuÃ¡', '300gr/por', 'skin/images/menus/2015-01-14 21.42.21.jpg', 28, 'Cristian Stan', NULL),
(80, 'International dishes', 'Chicken With White Sauce Valdostana', '200gr/por', 'skin/images/menus/event1.jpg', 12.99, 'Cristian Stan', NULL),
(99, 'Salads', 'Peynir Salatasi', '200gr/por', 'skin/images/menus/99.jpg', 6, 'Cristian Stan', NULL),
(102, 'Salads', 'Coban Salatasi', '200gr/por', 'skin/images/menus/102.jpg', 5, 'Cristian Stan', NULL),
(133, 'Desert', 'Apple pie', '250gr/por', 'skin/images/menus/133.jpg', 10, 'Cristian Stan', NULL),
(136, 'Desert', 'Pancakes with Jam', '300gr/por', 'skin/images/menus/136.jpg', 9, 'Cristian Stan', NULL),
(225, 'Pizza', 'Pizza Marguerita', 'Ciuperci, masline, sunca, cascaval pane.', 'skin/images/menus/piz.jpg', 45, 'bill', NULL),
(243, 'Desert', 'Wedding cake', 'Some details of this cake', 'skin/images/menus/IMG_9426.jpg', 22.99, 'bill', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE IF NOT EXISTS `newsletter` (
  `newsletter_id` int(255) NOT NULL AUTO_INCREMENT,
  `newsletter_email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`newsletter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_blacklist`
--

CREATE TABLE IF NOT EXISTS `new_blacklist` (
  `ID` bigint(15) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ipAddr` varchar(255) NOT NULL,
  `reasons` int(11) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_campaigns`
--

CREATE TABLE IF NOT EXISTS `new_campaigns` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `alt_details` text,
  `launch_date` datetime NOT NULL,
  `attach` varchar(255) DEFAULT NULL,
  `webOpt` tinyint(2) NOT NULL DEFAULT '0',
  `campaign_key` varchar(50) DEFAULT NULL,
  `campaign_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=Newsletter 1=Autoresponder',
  `campaign_pos` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=Pending, 1=Sending, 2=Stopped, 3=Completed',
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `campaign_sender_title` varchar(255) NOT NULL,
  `campaign_reply_mail` varchar(255) NOT NULL,
  `campaign_sender_account` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_campaign_ar`
--

CREATE TABLE IF NOT EXISTS `new_campaign_ar` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `ar_type` tinyint(2) NOT NULL COMMENT '0-After Subscription, 1-After Unsubscription, 2-Specific Date, 3-Special Date',
  `ar_time` smallint(5) NOT NULL DEFAULT '1' COMMENT 'Number as 1 minute, 1hour',
  `ar_time_type` varchar(30) NOT NULL COMMENT 'MINUTE, HOUR, DAY, MONTH, YEAR',
  `ar_end_date` datetime NOT NULL,
  `ar_week_0` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Sunday',
  `ar_week_1` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Monday',
  `ar_week_2` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Tuesday',
  `ar_week_3` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Wednesday',
  `ar_week_4` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Thursday',
  `ar_week_5` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Friday',
  `ar_week_6` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'Saturday',
  `ar_end` tinyint(2) NOT NULL DEFAULT '0' COMMENT 'On/Off',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_campaign_groups`
--

CREATE TABLE IF NOT EXISTS `new_campaign_groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL DEFAULT '0',
  `CID` int(11) NOT NULL DEFAULT '0',
  `GID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_chronos`
--

CREATE TABLE IF NOT EXISTS `new_chronos` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `pos` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0-In Process, 1-Flag for Remove',
  `cron_command` tinytext NOT NULL,
  `launch_date` datetime NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `SAID` int(11) NOT NULL DEFAULT '0' COMMENT 'Submission Account',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `new_chronos`
--

INSERT INTO `new_chronos` (`ID`, `OID`, `CID`, `pos`, `cron_command`, `launch_date`, `add_date`, `SAID`) VALUES
(1, 1, 0, 0, '*/5 * * * * curl -s ''http://localhost/rest/news/chronos/lethe.bounce.php?ID=1'' > /dev/null 2>&1', '2016-04-04 19:12:38', '2016-04-04 13:42:38', 1);

-- --------------------------------------------------------

--
-- Table structure for table `new_organizations`
--

CREATE TABLE IF NOT EXISTS `new_organizations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `orgTag` varchar(30) NOT NULL,
  `orgName` varchar(255) NOT NULL,
  `addDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `billingDate` date NOT NULL,
  `isActive` tinyint(2) NOT NULL DEFAULT '0',
  `public_key` varchar(50) NOT NULL,
  `private_key` varchar(50) NOT NULL,
  `isPrimary` tinyint(2) NOT NULL DEFAULT '0',
  `ip_addr` varchar(50) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  `daily_sent` int(11) NOT NULL DEFAULT '0',
  `daily_reset` datetime NOT NULL,
  `rss_url` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_organization_settings`
--

CREATE TABLE IF NOT EXISTS `new_organization_settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL DEFAULT '0',
  `set_key` varchar(255) NOT NULL,
  `set_val` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_reports`
--

CREATE TABLE IF NOT EXISTS `new_reports` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `pos` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=Click, 1=Open, 2=Bounce',
  `ipAddr` varchar(30) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(100) NOT NULL,
  `hit_cnt` int(11) NOT NULL DEFAULT '0',
  `bounceType` varchar(50) NOT NULL DEFAULT 'unknown',
  `extra_info` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_short_codes`
--

CREATE TABLE IF NOT EXISTS `new_short_codes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL DEFAULT '0',
  `code_key` varchar(255) NOT NULL,
  `code_val` varchar(255) NOT NULL,
  `isSystem` tinyint(2) NOT NULL DEFAULT '0',
  `UID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_submission_accounts`
--

CREATE TABLE IF NOT EXISTS `new_submission_accounts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `acc_title` varchar(255) NOT NULL,
  `daily_limit` int(11) NOT NULL,
  `daily_sent` int(11) NOT NULL,
  `daily_reset` datetime NOT NULL,
  `limit_range` int(11) NOT NULL DEFAULT '1440' COMMENT 'Limit range saved as minute',
  `send_per_conn` int(11) NOT NULL,
  `standby_time` int(11) NOT NULL,
  `systemAcc` tinyint(2) NOT NULL,
  `isDebug` tinyint(2) NOT NULL,
  `isActive` tinyint(2) NOT NULL,
  `from_title` varchar(255) NOT NULL,
  `from_mail` varchar(100) NOT NULL,
  `reply_mail` varchar(100) NOT NULL,
  `test_mail` varchar(100) NOT NULL,
  `mail_type` tinyint(2) NOT NULL,
  `send_method` tinyint(2) NOT NULL,
  `mail_engine` varchar(30) NOT NULL,
  `smtp_host` varchar(100) NOT NULL,
  `smtp_port` int(5) NOT NULL,
  `smtp_user` varchar(100) NOT NULL,
  `smtp_pass` varchar(100) NOT NULL,
  `smtp_secure` tinyint(2) NOT NULL DEFAULT '0',
  `pop3_host` varchar(100) NOT NULL,
  `pop3_port` int(5) NOT NULL,
  `pop3_user` varchar(100) NOT NULL,
  `pop3_pass` varchar(100) NOT NULL,
  `pop3_secure` tinyint(2) NOT NULL DEFAULT '0',
  `imap_host` varchar(100) NOT NULL,
  `imap_port` int(5) NOT NULL,
  `imap_user` varchar(100) NOT NULL,
  `imap_pass` varchar(100) NOT NULL,
  `imap_secure` tinyint(2) NOT NULL DEFAULT '0',
  `smtp_auth` tinyint(2) NOT NULL,
  `bounce_acc` tinyint(2) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aws_access_key` varchar(100) DEFAULT NULL,
  `aws_secret_key` varchar(100) DEFAULT NULL,
  `account_id` varchar(50) NOT NULL,
  `dkim_active` tinyint(2) NOT NULL DEFAULT '0',
  `dkim_domain` varchar(255) DEFAULT NULL,
  `dkim_private` text,
  `dkim_selector` varchar(255) DEFAULT NULL,
  `dkim_passphrase` varchar(255) DEFAULT NULL,
  `bounce_actions` text NOT NULL,
  `mandrill_user` varchar(255) DEFAULT NULL,
  `mandrill_key` varchar(255) DEFAULT NULL,
  `sendgrid_user` varchar(100) DEFAULT NULL,
  `sendgrid_pass` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

--
-- Dumping data for table `new_submission_accounts`
--

INSERT INTO `new_submission_accounts` (`ID`, `acc_title`, `daily_limit`, `daily_sent`, `daily_reset`, `limit_range`, `send_per_conn`, `standby_time`, `systemAcc`, `isDebug`, `isActive`, `from_title`, `from_mail`, `reply_mail`, `test_mail`, `mail_type`, `send_method`, `mail_engine`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass`, `smtp_secure`, `pop3_host`, `pop3_port`, `pop3_user`, `pop3_pass`, `pop3_secure`, `imap_host`, `imap_port`, `imap_user`, `imap_pass`, `imap_secure`, `smtp_auth`, `bounce_acc`, `add_date`, `aws_access_key`, `aws_secret_key`, `account_id`, `dkim_active`, `dkim_domain`, `dkim_private`, `dkim_selector`, `dkim_passphrase`, `bounce_actions`, `mandrill_user`, `mandrill_key`, `sendgrid_user`, `sendgrid_pass`) VALUES
(1, '# Server 1', 500, 0, '2016-04-05 19:12:38', 1440, 50, 1, 1, 0, 1, 'Test Sender', 'sender@example.com', 'reply@example.com', 'test@example.com', 0, 0, 'phpmailer', 'mail.example.com', 587, 'sender@example.com', 'TestSMTP', 0, 'mail.example.com', 110, 'sender@example.com', 'TestSMTP', 0, 'mail.example.com', 143, 'sender@example.com', 'TestSMTP', 0, 0, 0, '2016-04-04 13:42:38', '', '', '2b5b3a6ac336af0cb25d9778917343f9', 0, '', '', '', '', '{"antispam":1,"autoreply":1,"concurrent":1,"content_reject":1,"command_reject":1,"internal_error":1,"defer":1,"delayed":1,"dns_loop":1,"dns_unknown":1,"full":1,"inactive":1,"latin_only":1,"other":1,"oversize":1,"outofoffice":1,"unknown":1,"unrecognized":1,"user_reject":1,"warning":1}', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `new_subscribers`
--

CREATE TABLE IF NOT EXISTS `new_subscribers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) DEFAULT NULL,
  `GID` int(11) DEFAULT NULL,
  `subscriber_name` varchar(255) DEFAULT NULL,
  `subscriber_mail` varchar(50) DEFAULT NULL,
  `subscriber_web` varchar(255) DEFAULT NULL,
  `subscriber_date` datetime DEFAULT NULL,
  `subscriber_phone` varchar(50) DEFAULT NULL,
  `subscriber_company` varchar(255) DEFAULT NULL,
  `subscriber_full_data` text,
  `subscriber_active` tinyint(2) DEFAULT NULL,
  `subscriber_verify` tinyint(2) DEFAULT NULL,
  `subscriber_key` varchar(50) DEFAULT NULL,
  `add_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_addr` varchar(20) DEFAULT NULL,
  `subscriber_verify_key` varchar(50) NOT NULL,
  `subscriber_verify_sent_interval` datetime NOT NULL,
  `local_country` varchar(30) NOT NULL DEFAULT 'N/A',
  `local_country_code` varchar(5) NOT NULL DEFAULT 'N/A',
  `local_city` varchar(30) NOT NULL DEFAULT 'N/A',
  `local_region` varchar(30) NOT NULL DEFAULT 'N/A',
  `local_region_code` varchar(5) NOT NULL DEFAULT 'N/A',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_subscriber_groups`
--

CREATE TABLE IF NOT EXISTS `new_subscriber_groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `isUnsubscribe` tinyint(2) NOT NULL DEFAULT '0',
  `isUngroup` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_subscribe_forms`
--

CREATE TABLE IF NOT EXISTS `new_subscribe_forms` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `form_id` varchar(50) NOT NULL,
  `form_type` tinyint(2) NOT NULL,
  `form_success_url` varchar(255) DEFAULT NULL,
  `form_success_url_text` varchar(255) DEFAULT NULL,
  `form_success_text` varchar(255) DEFAULT NULL,
  `form_success_redir` int(11) NOT NULL DEFAULT '0',
  `form_remove` tinyint(2) NOT NULL DEFAULT '0',
  `add_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `isSystem` tinyint(2) NOT NULL DEFAULT '0',
  `form_view` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=Vertical, 1=Horizontal, 2=Table',
  `isDraft` tinyint(2) NOT NULL DEFAULT '1',
  `include_jquery` tinyint(2) NOT NULL DEFAULT '1',
  `include_jqueryui` tinyint(2) NOT NULL DEFAULT '1',
  `form_group` int(11) NOT NULL DEFAULT '0',
  `form_errors` tinytext NOT NULL,
  `subscription_stop` tinyint(2) NOT NULL DEFAULT '0',
  `UID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_subscribe_form_fields`
--

CREATE TABLE IF NOT EXISTS `new_subscribe_form_fields` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `FID` int(11) NOT NULL,
  `field_label` varchar(255) NOT NULL,
  `field_name` varchar(30) NOT NULL,
  `field_type` varchar(30) NOT NULL,
  `field_required` tinyint(2) NOT NULL,
  `field_pattern` varchar(255) DEFAULT NULL,
  `field_placeholder` varchar(255) DEFAULT NULL,
  `sorting` int(11) NOT NULL DEFAULT '0',
  `field_data` varchar(255) DEFAULT NULL,
  `field_static` tinyint(2) NOT NULL DEFAULT '0',
  `field_save` varchar(20) NOT NULL,
  `field_error` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_tasks`
--

CREATE TABLE IF NOT EXISTS `new_tasks` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `CID` int(11) NOT NULL,
  `subscriber_mail` varchar(100) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_templates`
--

CREATE TABLE IF NOT EXISTS `new_templates` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `temp_name` varchar(255) NOT NULL,
  `temp_contents` longtext NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `temp_prev` varchar(255) DEFAULT '',
  `temp_type` varchar(20) NOT NULL DEFAULT 'normal',
  `isSystem` tinyint(2) NOT NULL DEFAULT '0',
  `temp_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_unsubscribes`
--

CREATE TABLE IF NOT EXISTS `new_unsubscribes` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `CID` int(11) NOT NULL DEFAULT '0',
  `subscriber_mail` varchar(100) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_users`
--

CREATE TABLE IF NOT EXISTS `new_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL DEFAULT '0',
  `real_name` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `auth_mode` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0=User, 1=Admin, 2=Super Admin',
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL,
  `isActive` tinyint(2) NOT NULL DEFAULT '0',
  `isPrimary` tinyint(2) NOT NULL DEFAULT '0',
  `session_token` varchar(50) NOT NULL,
  `session_time` datetime NOT NULL,
  `private_key` varchar(50) NOT NULL,
  `public_key` varchar(50) NOT NULL,
  `user_spec_view` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `new_user_permissions`
--

CREATE TABLE IF NOT EXISTS `new_user_permissions` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `OID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `perm` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_payment_method` varchar(50) DEFAULT NULL,
  `order_paypal_default` varchar(50) DEFAULT NULL,
  `booktable_tables` varchar(500) NOT NULL,
  `booktable_room` varchar(55) DEFAULT NULL,
  `order_type` varchar(50) NOT NULL,
  `order_comments` varchar(500) DEFAULT NULL,
  `order_catering_products` varchar(500) NOT NULL,
  `order_address` varchar(100) NOT NULL,
  `order_value` varchar(100) NOT NULL,
  `order_user_name` varchar(100) NOT NULL,
  `order_user_email` varchar(100) NOT NULL,
  `order_user_phone` int(14) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `book_date_out` varchar(50) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=590 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_payment_method`, `order_paypal_default`, `booktable_tables`, `booktable_room`, `order_type`, `order_comments`, `order_catering_products`, `order_address`, `order_value`, `order_user_name`, `order_user_email`, `order_user_phone`, `order_date`, `book_date_out`) VALUES
(573, NULL, NULL, 'Takeaway', '1', 'BookATable', NULL, '', '', '', 'Jane Doe', 'phprestaurant@cristianstan.co', 2147483647, '2015-03-24 23:06:40', '2015-03-25 04:06:40'),
(574, '<div class=''label label-success''>On delivery</div>', '<div class=''label label-success''>On delivery</div>', '', NULL, 'Catering', '', '1x - Pizza Marguerita <br /> --------------------- <br /> 1x - Wedding cake', 'NYC, East Orange CR-434, West', '67.99', 'Cristian Stan', 'phprestaurant@gmail.com', 997345234, '2016-02-13 11:05:05', ''),
(575, NULL, NULL, 'Table Nr. 03', '2', 'BookATable', NULL, '', '', '', 'Cristian Stan', 'phprestaurant@gmail.com', 997345234, '2016-02-14 11:15:47', '2016-02-14 17:45:47'),
(576, NULL, NULL, 'Takeaway', '1', 'BookATable', NULL, '', '', '', 'Cristian Stan', 'phprestaurant@gmail.com', 997345234, '2016-02-14 11:17:45', '2016-02-14 17:47:45'),
(577, NULL, NULL, 'Table Nr. 05', '2', 'BookATable', NULL, '', '', '', 'Cristian Stan', 'phprestaurant@gmail.com', 997345234, '2016-02-14 11:18:08', '2016-02-14 17:48:08'),
(578, '<div class=''label label-success''>On delivery</div>', '<div class=''label label-success''>On delivery</div>', '', NULL, 'Catering', '', '1x - FideuÃ¡', 'NYC, East Orange CR-434, West', '28', 'Cristian Stan', 'phprestaurant@gmail.com', 997345234, '2016-02-13 11:21:06', ''),
(579, NULL, NULL, '10', '1', 'BookATable', NULL, '', '', '', 'Cristian Stan', 'ankit@aaptatechnologies.com', 997345234, '2016-02-15 11:29:29', '2016-02-15 17:59:29'),
(580, NULL, NULL, 'Table Nr. 02', '2', 'BookATable', NULL, '', '', '', 'anki aa', 'ankit@aaptatechnologies.com', 0, '2016-02-15 11:33:03', '2016-02-15 18:03:03'),
(581, NULL, NULL, 'Table Nr. 05', '2', 'BookATable', NULL, '', '', '', 'anki aa', 'ankit@aaptatechnologies.com', 0, '2016-02-15 12:10:53', '2016-02-15 18:40:53'),
(582, NULL, NULL, '5', '5', 'BookATable', NULL, '', '', '', 'anki aa', 'ankit@aaptatechnologies.com', 0, '2016-02-18 15:30:00', '2016-02-18 22:00:00'),
(583, NULL, NULL, 'Takeaway', '1', 'BookATable', NULL, '', '', '', 'anki aa', 'ankit@aaptatechnologies.com', 0, '2016-02-19 13:18:22', '2016-02-19 19:48:22'),
(584, NULL, NULL, 'Takeaway', '1', 'BookATable', NULL, '', '', '', 'sad', 'aa@aa.com', 2147483647, '2016-04-05 05:34:53', '2016-04-05 12:04:53'),
(585, NULL, NULL, '10', '1', 'BookATable', NULL, '', '', '', 'Cristian Stan', 'ankit@aaptatechnologies.com', 997345234, '2016-04-05 12:48:37', '2016-04-05 19:18:37'),
(586, NULL, NULL, '10', '1', 'BookATable', NULL, '', '', '', 'Cristian Stan', 'ankit@aaptatechnologies.com', 997345234, '2016-04-12 13:10:01', '2016-04-12 19:40:01'),
(589, '1', NULL, '10', '1', 'BookATable', NULL, '', '', '', 'anki aa', 'ankit.borad93@gmail.com', 0, '2016-04-22 06:37:39', '2016-04-22 13:07:39');

-- --------------------------------------------------------

--
-- Table structure for table `orderuser`
--

CREATE TABLE IF NOT EXISTS `orderuser` (
  `order_id` int(10) NOT NULL,
  `user_id` int(10) NOT NULL,
  UNIQUE KEY `order_id` (`order_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `order_id_2` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderuser`
--

INSERT INTO `orderuser` (`order_id`, `user_id`) VALUES
(589, 111);

-- --------------------------------------------------------

--
-- Table structure for table `ratting`
--

CREATE TABLE IF NOT EXISTS `ratting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ratting` varchar(10) NOT NULL,
  `review` text NOT NULL,
  `date` varchar(50) NOT NULL,
  `flag` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ratting`
--

INSERT INTO `ratting` (`id`, `uid`, `ratting`, `review`, `date`, `flag`) VALUES
(5, 100, '3', 'sdf', '05-04-2016 09:07:47 AM', '1'),
(8, 59, '2.5', 'sdf asd', '05-04-2016 09:07:47 AM', '1'),
(9, 112, '2.5', 'dfgdfg', '09-05-2016 09:17:30 AM', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE IF NOT EXISTS `tables` (
  `table_id` int(10) NOT NULL AUTO_INCREMENT,
  `restaurant_room_nr` int(10) NOT NULL,
  `table_number_of_places` int(10) NOT NULL,
  `table_details` varchar(200) NOT NULL,
  `table_position` varchar(50) NOT NULL,
  `table_css_position_left` varchar(10) DEFAULT NULL,
  `table_css_position_top` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`, `restaurant_room_nr`, `table_number_of_places`, `table_details`, `table_position`, `table_css_position_left`, `table_css_position_top`) VALUES
(2, 0, 4, 'Table Nr. 02', 'aasasasasaasa', '85%', '49%'),
(21, 2, 4, 'Table Nr. 01', 'Some details about this table', '10%', '85%'),
(22, 2, 4, 'Table Nr. 02', 'Some details about this table', '85%', '10%'),
(23, 2, 2, 'Table Nr. 03', 'Some details about this table', '35%', '35%'),
(24, 1, 2, 'Table Nr. 04', 'Some details about this table', '80%', '80%'),
(25, 2, 4, 'Table Nr. 05', 'Some details about this table', '67%', '3%'),
(26, 2, 4, 'Table Nr. 06', 'Some details about this table', '37%', '72%'),
(29, 3, 4, 'Table Nr. 01', 'Details about this table', '67%', '10%'),
(31, 1, 2, 'Table Nr. 03', 'Details about this table', '15%', '65%'),
(32, 2, 2, 'Table Nr. 04', 'Details about this table', '50%', '50%'),
(33, 3, 4, 'Table Nr. 05', 'Details about this table', '59%', '69%'),
(35, 3, 3, 'Table Nr. 07', 'Details about this table', '37%', '49%'),
(36, 3, 3, 'Table Nr. 08', 'Details about this table', '33%', '77%'),
(39, 1, 4, 'Table Nr. 02', 'Some lorem ipsum details', '12%', '65%'),
(41, 1, 3, 'Table Nr. 03', 'Some lorem ipsum details', '60%', '59%'),
(43, 4, 2, 'Table Nr. 04', 'Some lorem ipsum details', '80%', '49%'),
(44, 4, 2, 'Table Nr. 04', 'Some lorem ipsum details', '40%', '49%'),
(54, 1, 1, 'Takeaway', '', '30%', '50%'),
(55, 1, 1, '10', '1', '45%', '80%'),
(56, 4, 4, 'table Nr. 01', '70%', NULL, NULL),
(57, 5, 10, 'demo', '65%', '50%', '50%'),
(58, 0, 4, 'AC Room', '65%', '50%', '50%'),
(59, 5, 10, '5', '5', '40%', '60%');

-- --------------------------------------------------------

--
-- Table structure for table `tablesorders`
--

CREATE TABLE IF NOT EXISTS `tablesorders` (
  `tablesorders_id` int(10) NOT NULL,
  `order_id` int(10) NOT NULL,
  PRIMARY KEY (`tablesorders_id`),
  KEY `order_id` (`order_id`),
  KEY `tablesorders_id` (`tablesorders_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tablesorders`
--

INSERT INTO `tablesorders` (`tablesorders_id`, `order_id`) VALUES
(55, 589);

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE IF NOT EXISTS `testimonials` (
  `testimonial_id` int(255) NOT NULL AUTO_INCREMENT,
  `testimonal_client_name` varchar(100) DEFAULT NULL,
  `testimonial_content` longtext,
  `testimonial_client_job` varchar(255) DEFAULT NULL,
  `testimonial_thumb` varchar(255) NOT NULL,
  `testimonial_works_at` varchar(50) NOT NULL,
  PRIMARY KEY (`testimonial_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`testimonial_id`, `testimonal_client_name`, `testimonial_content`, `testimonial_client_job`, `testimonial_thumb`, `testimonial_works_at`) VALUES
(1, 'John Smith', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum justo quis justo mollis, non rutrum arcu accumsan. Vivamus quis dui sed est tincidunt laoreet. Vivamus sit amet dolor quis metus pharetra volutpat. Donec quis porttitor elit. ', 'Web Developer', 'skin/images/testimonials/08_01_10.jpg', ''),
(4, 'John Smith', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed elementum justo quis justo mollis, non rutrum arcu accumsan. Vivamus quis dui sed est tincidunt laoreet. Vivamus sit amet dolor quis metus pharetra volutpat. Donec quis porttitor elit. ', 'Web Developer', 'skin/images/testimonials/avatar-1.jpg', 'Code');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_role` varchar(100) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_nice_name` varchar(250) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_email` varchar(50) DEFAULT NULL,
  `user_delivery_address` varchar(200) DEFAULT NULL,
  `user_phone` int(14) NOT NULL,
  `user_since` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_role`, `user_name`, `user_nice_name`, `user_password`, `user_email`, `user_delivery_address`, `user_phone`, `user_since`) VALUES
(59, 'Client', 'client', 'Jane Doe', '010300ed4af61c9b98a0ce35f9284df91110a73c', 'phprestaurant@cristianstan.co', 'New York NYC, East Orange CR-434, West', 2147483647, '2015-01-02 14:35:12'),
(100, 'Administrator', 'admin', 'Cristian Stan', '010300ed4af61c9b98a0ce35f9284df91110a73c', 'ankit@aaptatechnologies.com', 'NYC, East Orange CR-434, West', 997345234, '2016-02-13 11:29:04'),
(111, 'Client', 'ankit', 'anki aa', '010300ed4af61c9b98a0ce35f9284df91110a73c', 'ankit.borad93@gmail.com', NULL, 0, '2016-04-05 09:24:32'),
(112, 'Client', 'aaa', 'aaa', '010300ed4af61c9b98a0ce35f9284df91110a73c', 'aaa@aaa.com', NULL, 0, '2016-05-09 07:17:13');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menuorder`
--
ALTER TABLE `menuorder`
  ADD CONSTRAINT `menuorder_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `menuorder_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menus` (`menu_item_id`);

--
-- Constraints for table `orderuser`
--
ALTER TABLE `orderuser`
  ADD CONSTRAINT `orderuser_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `orderuser_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tablesorders`
--
ALTER TABLE `tablesorders`
  ADD CONSTRAINT `tablesorders_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `tablesorders_ibfk_2` FOREIGN KEY (`tablesorders_id`) REFERENCES `tables` (`table_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
