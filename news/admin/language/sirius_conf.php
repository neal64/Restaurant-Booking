<?php
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Sirius - PHP Multi Language File Editor                                |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | File Version  3.0                                                      |
# | Last modified 28.05.15                                                 |
# | Email         developer@artlantis.net                                  |
# | Developer     http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+


include_once("class.sirius.php");
# Language Datas
$SLNG=array();
# Active Languages
$SLNG_LIST=array();
$SLNG_LIST["en"] = array("skey"=>"en","sname"=>"English","sfolder"=>"english","slocale"=>"en_GB","scharset"=>"utf-8","stimezone"=>"America/Los_Angeles");
$SLNG_LIST["tr"] = array("skey"=>"tr","sname"=>"Türkçe","sfolder"=>"turkish","slocale"=>"tr_TR","scharset"=>"utf-8","stimezone"=>"Europe/Istanbul");
$SLNG_LIST["az"] = array("skey"=>"az","sname"=>"Azerice","sfolder"=>"azerbaijani","slocale"=>"az_AZ","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["de"] = array("skey"=>"de","sname"=>"Deutsch","sfolder"=>"deutsch","slocale"=>"de_DE","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["fr"] = array("skey"=>"fr","sname"=>"Français","sfolder"=>"french","slocale"=>"fr_FR","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["ru"] = array("skey"=>"ru","sname"=>"Pусский","sfolder"=>"russian","slocale"=>"ru_RU","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["it"] = array("skey"=>"it","sname"=>"Italiano","sfolder"=>"italian","slocale"=>"it_IT","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["es"] = array("skey"=>"es","sname"=>"Español","sfolder"=>"spanish","slocale"=>"es_ES","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["pt"] = array("skey"=>"pt","sname"=>"Português","sfolder"=>"portugal","slocale"=>"pt_PT","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["cn"] = array("skey"=>"cn","sname"=>"中国的","sfolder"=>"chinese","slocale"=>"zh_CN","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["jp"] = array("skey"=>"jp","sname"=>"日本の","sfolder"=>"japanese","slocale"=>"ja_JP","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["sv"] = array("skey"=>"sv","sname"=>"Svenska","sfolder"=>"swedish","slocale"=>"sv_SE","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["no"] = array("skey"=>"no","sname"=>"Norsk","sfolder"=>"norwegian","slocale"=>"no_NO","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["bg"] = array("skey"=>"bg","sname"=>"български","sfolder"=>"bulgarian","slocale"=>"bg_BG","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["da"] = array("skey"=>"da","sname"=>"Dansk","sfolder"=>"dansk","slocale"=>"da_DA","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["hi"] = array("skey"=>"hi","sname"=>"हिंदी","sfolder"=>"hindi","slocale"=>"hi_HI","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["hu"] = array("skey"=>"hu","sname"=>"Magyar","sfolder"=>"hungarian","slocale"=>"hu_HU","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["mk"] = array("skey"=>"mk","sname"=>"македонски","sfolder"=>"macedonian","slocale"=>"mk_MK","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["fa"] = array("skey"=>"fa","sname"=>"فارسی","sfolder"=>"persian","slocale"=>"fa_FA","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["pl"] = array("skey"=>"pl","sname"=>"Polski","sfolder"=>"polish","slocale"=>"pl_PL","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["vi"] = array("skey"=>"vi","sname"=>"Tiếng Việt","sfolder"=>"vietnamese","slocale"=>"vi_VI","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["ar"] = array("skey"=>"ar","sname"=>"العربية","sfolder"=>"arabic","slocale"=>"ar_SA","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["sk"] = array("skey"=>"sk","sname"=>"Slovenčina","sfolder"=>"slovak","slocale"=>"sk_SK","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["fi"] = array("skey"=>"fi","sname"=>"Suomalainen","sfolder"=>"finnish","slocale"=>"fi_FI","scharset"=>"utf-8","stimezone"=>"UTC");
$SLNG_LIST["gr"] = array("skey"=>"gr","sname"=>"ελληνικά","sfolder"=>"greek","slocale"=>"el_GR","scharset"=>"utf-8","stimezone"=>"UTC");
# Cookie Controls
define("LOADED_LANG",((!isset($_COOKIE["slang"]) || empty($_COOKIE["slang"])) ? "en":$_COOKIE["slang"]));
# Defined Charset
header("Content-Type: text/html; charset=".$SLNG_LIST[LOADED_LANG]["scharset"]);
# Load Class
$sirius = new sirius();
$sirius->langFiles = array();
$sirius->langLocation = dirname(__FILE__)."/".$SLNG_LIST[LOADED_LANG]["sfolder"];
# Global Keys
$sirius->langFiles[] = "letheglobal_front.php";
$sirius->langFiles[] = "letheglobal_back.php";
?>