<?php
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | Sirius - PHP Multi Language File Editor                                |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | File Version  3.0                                                      |
# | Last modified 30.12.14                                                 |
# | Email         developer@artlantis.net                                  |
# | Developer     http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
class sirius{

	public $langFiles = null;
	public $langLocation = '';
	
	/* Define Languages */
	public function defineLanguages($SLNG){
		foreach($SLNG as $k=>$v){
			if(!defined($k)){
				define($k,$v);
			}
		}
	}
	
	public function loadLanguages(){
	
		global $SLNG;
	
		foreach($this->langFiles as $k=>$v){
			if(file_exists(realpath($this->langLocation.'/'.$v))){
				include_once(realpath($this->langLocation.'/'.$v));
			}else{
				die('<strong>' . $v . '</strong> Not Found!<br>');
			}
		}
		
		$this->defineLanguages($SLNG);
	
	}
	
}
?>