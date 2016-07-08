<?php
# +------------------------------------------------------------------------+
# | Artlantis CMS Solutions                                                |
# +------------------------------------------------------------------------+
# | LeUpload - Lethe Newsletter Upload Plugin                              |
# | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
# | Version       1.0                                                      |
# | Last modified 07.01.2015                                               |
# | Email         developer@artlantis.net                                  |
# | Web           http://www.artlantis.net                                 |
# +------------------------------------------------------------------------+
include_once('config.php');
include_once(LETHE_ADMIN.DIRECTORY_SEPARATOR.'classes/class.upload.php');
$errText = '{"status":"error"}';
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
	
	# Demo Mode Check ****
	if(DEMO_MODE){$errText = '<div class="alert alert-danger">Demo Mode Active!</div>';}else{
	# Demo Mode Check ****

			# *************** Uploading Start *******************
			$file_name = null; # If you wanna use custom file name change this value
			//$dest = $pan_base; # Upload in base directory
			$dest = LEUPLOAD_STORE.DIRECTORY_SEPARATOR;
			
			#Image Upload
			if($_FILES["file"]["error"]==0){
				$handle = new upload($_FILES['file'],'en_EN');
				
			if ($handle->uploaded) {
				$handle->file_new_name_body   = $file_name;
				$handle->file_safe_name = true;
				$handle->file_overwrite = false;
				$handle->file_auto_rename = true;
				$handle->allowed = $LEUPLOAD_MIMES; //*
				$handle->file_max_size = $LEUPLOAD_MAX_UPL;
										
						
			//** Processing
			$handle->process($dest);
			if ($handle->processed) { # Uploaded
					$errText = '<div class="alert alert-success">Uploadded Success</div>';
					$handle->clean();
				}
			else{ # Uploading Error
					$errText = '<div class="alert alert-danger">Error</div>';
				}
			# Uploading Finished
		
				}
										
			
			}else{
				$errText = '<div class="alert alert-danger">Error</div>';
				}
			
}
}
echo $errText;
exit;
?>