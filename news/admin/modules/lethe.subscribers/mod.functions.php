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

/* Subscriber Group Data */
function getGroup($v,$t){

	global $myconn;

	# t0 - Group Name by ID
	
	$rt = '';
	
	if($t==0){
		$opGroup = $myconn->prepare("SELECT ID,group_name FROM ". db_table_pref ."subscriber_groups WHERE OID=". set_org_id ." AND ID=?") or die(mysqli_error($myconn));
		$opGroup->bind_param('i',$v);
		$opGroup->execute();
		$opGroup->store_result();
		if($opGroup->num_rows==0){$rt = letheglobal_record_not_found;}else{
			$sr = new Statement_Result($opGroup);
			$opGroup->fetch();
			$rt = $sr->Get('group_name');
		}
		$opGroup->close();
	}
	
	return $rt;

}

/* Static Field Controller */
function fieldController($f,$fid){

	if($f=='email'){return false;}
	else if($f=='submit'){return false;}
	else if($f=='recaptcha'){if(cntData("SELECT ID FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". set_org_id ." AND FID=". $fid ." AND field_type='recaptcha'")==0){return true;}else{return false;}}
	else if($f=='addremove'){if(cntData("SELECT ID FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". set_org_id ." AND FID=". $fid ." AND field_type='addremove'")==0){return true;}else{return false;}}
	else{return true;}

}

/* Subscribe Form Class */
class lethe_forms{

	public $fieldType = '';
	public $isEdit = false;
	public $FID = 0; # Form ID
	public $FFID = 0; # Form Field ID
	public $fieldSettings = array();
	
	/* Subscribing Field Editor */
	public function fieldOptionEditor(){
	
		global $LETHE_SUBSCRIBE_SAVE_FIELDS;
		$fieldData = '<div class="intoAjax" id="fieldUpdForm">';
		$fieldSets = $this->fieldSettings;
				
		/* Save Area */
		if($fieldSets['field_type']!='recaptcha' && $fieldSets['field_type']!='addremove' && $fieldSets['field_type']!='submit' && $fieldSets['field_type']!='email'){
			$fieldData.='<div class="form-group"><label for="fieldSave">'.sh('kA6j3CA0Ci'). subscribers_recording_area .'</label>';
			$fieldData.='<select name="fieldSave" id="fieldSave" class="form-control autoWidth">';
			foreach($LETHE_SUBSCRIBE_SAVE_FIELDS as $kf=>$vf){
				$fieldData.= (($fieldSets['field_save']!='subscriber_full_data' && $fieldSets['field_save']==$kf) ? '<option value="'. $fieldSets['field_save'] .'" selected>'. $LETHE_SUBSCRIBE_SAVE_FIELDS[$fieldSets['field_save']] .'</option>':'');
				if(cntData("SELECT ID FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". set_org_id ." AND FID=". $fieldSets['FID'] ." AND field_save='". mysql_prep($kf) ."'")==0 || $kf=='subscriber_full_data'){
					$fieldData.= '<option value="'. $kf .'">'. $vf .'</option>';
				}
			}
			$fieldData.='</select></div>';
		}
		
		
		/* Text */
		if($fieldSets['field_type']=='text' || 
				$fieldSets['field_type']=='phone' || 
					$fieldSets['field_type']=='number' || 
						$fieldSets['field_type']=='url' ||
							$fieldSets['field_type']=='textarea' 
			){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" value="'. showIn($fieldSets['field_label'],'input') .'" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_placeholder">'.sh('ynYKnxzFx4'). subscribers_placeholder .'</label>';
			$fieldData.='<input type="text" name="f_field_placeholder" id="f_field_placeholder" value="'. showIn($fieldSets['field_placeholder'],'input') .'" class="form-control">';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_pattern">'.sh('b7H8gLtspF'). subscribers_regex_pattern .'</label>';
			$fieldData.='<input type="text" name="f_field_pattern" id="f_field_pattern" value="'. showIn($fieldSets['field_pattern'],'input') .'" class="form-control">';
			$fieldData.='</div>';
			$fieldData.='<div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"'. formSelector($fieldSets['field_required'],1,2) .'> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField '. (($fieldSets['field_required']==0) ? 'sHide':'') .'">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" value="'. showIn($fieldSets['field_error'],'input') .'" class="form-control input-sm">';
			$fieldData.='</div></div></div>';
		}
		
		/* Date */
		else if($fieldSets['field_type']=='date'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" value="'. showIn($fieldSets['field_label'],'input') .'" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_placeholder">'.sh('ynYKnxzFx4'). subscribers_placeholder .'</label>';
			$fieldData.='<input type="text" name="f_field_placeholder" id="f_field_placeholder" class="form-control" value="'. showIn($fieldSets['field_placeholder'],'input') .'">';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_pattern">'.sh('b7H8gLtspF'). subscribers_regex_pattern .'</label>';
			$fieldData.='<input type="text" name="f_field_pattern" id="f_field_pattern" class="form-control" value="'. showIn($fieldSets['field_pattern'],'input') .'">';
			$fieldData.='</div>';
			$fieldData.='<div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"'. formSelector($fieldSets['field_required'],1,2) .'> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField '. (($fieldSets['field_required']==0) ? 'sHide':'') .'">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm" value="'. showIn($fieldSets['field_error'],'input') .'">';
			$fieldData.='</div></div></div>';
			$fieldData.='<div class="container-fluid"><div class="col-md-5"><div class="form-group">';
			$fieldData.='<span>'. sh('rt7Cq8bHvu') .'</span> <label><input type="checkbox" name="isDatepicker" id="isDatepicker" value="YES"'. formSelector($fieldSets['field_data'],'YES',2) .'> '. subscribers_add_datepicker .'</label>';
			$fieldData.='</div></div></div>';
		}
		/* Selectbox */
		else if($fieldSets['field_type']=='select'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" value="'. showIn($fieldSets['field_label'],'input') .'" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_select">'.sh('MTpWva4d4t'). subscribers_options .'</label>';
			$fieldData.='<select name="f_field_select" id="f_field_select" class="form-control" multiple>';
			
				$catchOpt = $fieldSets['field_data'];
				if(!is_null($catchOpt)){
				$catchOpt = explode(",",$catchOpt);
				foreach($catchOpt as $o1=>$o2){
					$catchData = explode(':',$o2);
					$fieldData.='<option value="'. showIn(((array_key_exists(0,$catchData)) ? $catchData[0]:''),'input') .'">'. showIn(((array_key_exists(1,$catchData)) ? $catchData[1]:''),'page') .'</option>';
				}}
			
			$fieldData.='</select></div>';
			$fieldData.='<div class="form-group">
							<label>'.sh('6WYoauQXgG'). subscribers_controls .'</label>
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'up\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_up .'"><span class="glyphicon glyphicon-chevron-up"></span></button> 
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'down\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_down .'"><span class="glyphicon glyphicon-chevron-down"></span></button>
							<button type="button" onclick="javascript:listbox_remove(\'f_field_select\',\''. letheglobal_please_select_a_option_to_remove .'\');" class="btn btn-danger btn-sm init-list tooltips" title="'. letheglobal_delete .'"><span class="glyphicon glyphicon-remove"></span></button>
						 </div>						
						';
			$fieldData.='<div class="container-fluid"><label>'.sh('iqHcTILAYR'). subscribers_new_option .'</label><div class="clearfix"></div>';
			$fieldData.='<div class="col-md-6"><input id="boxKey" type="text" class="form-control" placeholder="'. subscribers_value .'"></div>';
			$fieldData.='<div class="col-md-6">
							<div class="input-group">
								<input type="text" id="boxVal" class="form-control" placeholder="'. subscribers_text .'">
								  <span class="input-group-btn">
									<button class="btn btn-success" id="addBoxItem" type="button">'. letheglobal_add .'</button>
								  </span>
							</div>
						 </div>';
			$fieldData.='</div>';
			$fieldData.='<hr><div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"'. formSelector($fieldSets['field_required'],1,2) .'> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField '. (($fieldSets['field_required']==0) ? 'sHide':'') .'">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm" value="'. showIn($fieldSets['field_error'],'input') .'">';
			$fieldData.='</div></div></div>';
			
			$fieldData.='<input type="hidden" name="f_field_data" id="tempbox" value="'. showIn($fieldSets['field_data'],'input') .'">';
			$fieldData.='<script>
							/* Add Item */
							$("#addBoxItem").click(function(){
								var commaSep = "";
								if($("#tempbox").val()==""){commaSep="";}else{commaSep=",";}
								if($("#boxKey").val()=="" || $("#boxVal").val()==""){alert("'. letheglobal_please_enter_a_value .'");return false;}
								$("#tempbox").val($("#tempbox").val()+commaSep+$("#boxKey").val()+":"+$("#boxVal").val());
								$("#boxKey").val("");
								$("#boxVal").val("");
								var newList = $("#tempbox").val().split(",");
								/* Clear List */
								$("#f_field_select").html("");
								/* Make List */
								for (a in newList) {
									parseData = newList[a].split(":");
									$("#f_field_select").append(\'<option value="\'+ parseData[0] +\'">\'+ parseData[1] +\'</option>\');
								}
							});
							
							/* Init List */
							$(".init-list").click(function(){
							
								var initData = "";
								$("#f_field_select > option").each(function() {
									if(initData!=""){initData=initData+",";}
									initData = initData + $(this).text() + ":" + $(this).val();
								});
								$("#tempbox").val(initData);
							
							});
							
							/* Init Helpers */
							$(".tooltips").tooltip();
			</script>';
		}
		/* Checkbox */
		else if($fieldSets['field_type']=='checkbox'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" value="'. showIn($fieldSets['field_label'],'input') .'" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_select">'.sh('MTpWva4d4t'). subscribers_options .'</label>';
			$fieldData.='<select name="f_field_select" id="f_field_select" class="form-control" multiple>';
				$catchOpt = $fieldSets['field_data'];
				if(!is_null($catchOpt)){
				$catchOpt = explode(",",$catchOpt);
				foreach($catchOpt as $o1=>$o2){
					$catchData = explode(':',$o2);
					$fieldData.='<option value="'. showIn(((array_key_exists(0,$catchData)) ? $catchData[0]:''),'input') .'">'. showIn(((array_key_exists(1,$catchData)) ? $catchData[1]:''),'page') .'</option>';
				}}
			$fieldData.='</select></div>';
			$fieldData.='<div class="form-group">
							<label>'.sh('6WYoauQXgG'). subscribers_controls .'</label>
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'up\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_up .'"><span class="glyphicon glyphicon-chevron-up"></span></button> 
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'down\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_down .'"><span class="glyphicon glyphicon-chevron-down"></span></button>
							<button type="button" onclick="javascript:listbox_remove(\'f_field_select\',\''. letheglobal_please_select_a_option_to_remove .'\');" class="btn btn-danger btn-sm init-list tooltips" title="'. letheglobal_delete .'"><span class="glyphicon glyphicon-remove"></span></button>
						 </div>						
						';
			$fieldData.='<div class="container-fluid"><label>'.sh('iqHcTILAYR'). subscribers_new_option .'</label><div class="clearfix"></div>';
			$fieldData.='<div class="col-md-6"><input id="boxKey" type="text" class="form-control" placeholder="'. subscribers_value .'"></div>';
			$fieldData.='<div class="col-md-6">
							<div class="input-group">
								<input type="text" id="boxVal" class="form-control" placeholder="'. subscribers_text .'">
								  <span class="input-group-btn">
									<button class="btn btn-success" id="addBoxItem" type="button">'. letheglobal_add .'</button>
								  </span>
							</div>
						 </div>';
			$fieldData.='</div>';
			$fieldData.='<hr><div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"'. formSelector($fieldSets['field_required'],1,2) .'> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField '. (($fieldSets['field_required']==0) ? 'sHide':'') .'">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm" value="'. showIn($fieldSets['field_error'],'input') .'">';
			$fieldData.='</div></div></div>';
			
			$fieldData.='<input type="hidden" name="f_field_data" id="tempbox" value="'. showIn($fieldSets['field_data'],'input') .'">';
			$fieldData.='<script>
							/* Add Item */
							$("#addBoxItem").click(function(){
								var commaSep = "";
								if($("#tempbox").val()==""){commaSep="";}else{commaSep=",";}
								if($("#boxKey").val()=="" || $("#boxVal").val()==""){alert("'. letheglobal_please_enter_a_value .'");return false;}
								$("#tempbox").val($("#tempbox").val()+commaSep+$("#boxKey").val()+":"+$("#boxVal").val());
								$("#boxKey").val("");
								$("#boxVal").val("");
								var newList = $("#tempbox").val().split(",");
								/* Clear List */
								$("#f_field_select").html("");
								/* Make List */
								for (a in newList) {
									parseData = newList[a].split(":");
									$("#f_field_select").append(\'<option value="\'+ parseData[0] +\'">\'+ parseData[1] +\'</option>\');
								}
							});
							
							/* Init List */
							$(".init-list").click(function(){
							
								var initData = "";
								$("#f_field_select > option").each(function() {
									if(initData!=""){initData=initData+",";}
									initData = initData + $(this).text() + ":" + $(this).val();
								});
								$("#tempbox").val(initData);
							
							});
							
							/* Init Helpers */
							$(".tooltips").tooltip();
			</script>';
		}
		/* Radio */
		else if($fieldSets['field_type']=='radio'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" value="'. showIn($fieldSets['field_label'],'input') .'" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_select">'.sh('MTpWva4d4t'). subscribers_options .'</label>';
			$fieldData.='<select name="f_field_select" id="f_field_select" class="form-control" multiple>';
				$catchOpt = $fieldSets['field_data'];
				if(!is_null($catchOpt)){
				$catchOpt = explode(",",$catchOpt);
				foreach($catchOpt as $o1=>$o2){
					$catchData = explode(':',$o2);
					$fieldData.='<option value="'. showIn(((array_key_exists(0,$catchData)) ? $catchData[0]:''),'input') .'">'. showIn(((array_key_exists(1,$catchData)) ? $catchData[1]:''),'page') .'</option>';
				}}
			$fieldData.='</select></div>';
			$fieldData.='<div class="form-group">
							<label>'.sh('6WYoauQXgG'). subscribers_controls .'</label>
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'up\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_up .'"><span class="glyphicon glyphicon-chevron-up"></span></button> 
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'down\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_down .'"><span class="glyphicon glyphicon-chevron-down"></span></button>
							<button type="button" onclick="javascript:listbox_remove(\'f_field_select\',\''. letheglobal_please_select_a_option_to_remove .'\');" class="btn btn-danger btn-sm init-list tooltips" title="'. letheglobal_delete .'"><span class="glyphicon glyphicon-remove"></span></button>
						 </div>						
						';
			$fieldData.='<div class="container-fluid"><label>'.sh('iqHcTILAYR'). subscribers_new_option .'</label><div class="clearfix"></div>';
			$fieldData.='<div class="col-md-6"><input id="boxKey" type="text" class="form-control" placeholder="'. subscribers_value .'"></div>';
			$fieldData.='<div class="col-md-6">
							<div class="input-group">
								<input type="text" id="boxVal" class="form-control" placeholder="'. subscribers_text .'">
								  <span class="input-group-btn">
									<button class="btn btn-success" id="addBoxItem" type="button">'. letheglobal_add .'</button>
								  </span>
							</div>
						 </div>';
			$fieldData.='</div>';
			$fieldData.='<hr><div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"'. formSelector($fieldSets['field_required'],1,2) .'> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField '. (($fieldSets['field_required']==0) ? 'sHide':'') .'">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm" value="'. showIn($fieldSets['field_error'],'input') .'">';
			$fieldData.='</div></div></div>';
						
			$fieldData.='<input type="hidden" name="f_field_data" id="tempbox" value="'. showIn($fieldSets['field_data'],'input') .'">';
			$fieldData.='<script>
							/* Add Item */
							$("#addBoxItem").click(function(){
								var commaSep = "";
								if($("#tempbox").val()==""){commaSep="";}else{commaSep=",";}
								if($("#boxKey").val()=="" || $("#boxVal").val()==""){alert("'. letheglobal_please_enter_a_value .'");return false;}
								$("#tempbox").val($("#tempbox").val()+commaSep+$("#boxKey").val()+":"+$("#boxVal").val());
								$("#boxKey").val("");
								$("#boxVal").val("");
								var newList = $("#tempbox").val().split(",");
								/* Clear List */
								$("#f_field_select").html("");
								/* Make List */
								for (a in newList) {
									parseData = newList[a].split(":");
									$("#f_field_select").append(\'<option value="\'+ parseData[0] +\'">\'+ parseData[1] +\'</option>\');
								}
							});
							
							/* Init List */
							$(".init-list").click(function(){
							
								var initData = "";
								$("#f_field_select > option").each(function() {
									if(initData!=""){initData=initData+",";}
									initData = initData + $(this).text() + ":" + $(this).val();
								});
								$("#tempbox").val(initData);
							
							});
							
							/* Init Helpers */
							$(".tooltips").tooltip();
			</script>';
		}
		/* ReCaptcha */
		else if($fieldSets['field_type']=='recaptcha'){
			
			global $LETHE_SUBSCRIBE_FORM_RECAPTCHA_LANG;
			$recaptData = explode("@",$fieldSets['field_data']);
			if(!array_key_exists(1,$recaptData)){$recaptData[1]='en';}
			if(!array_key_exists(2,$recaptData)){$recaptData[2]='light';}
			if(!array_key_exists(3,$recaptData)){$recaptData[3]='image';}
			
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" value="'. showIn($fieldSets['field_label'],'input') .'" class="form-control" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" value="'. showIn($fieldSets['field_error'],'input') .'" name="f_field_error" id="f_field_error" class="form-control"></div>';
			$fieldData.='<div class="form-group"><label for="f_field_recapt_api">'. sh('za7rBvqrZy') .subscribers_api_version.'</label><select name="f_field_recapt_api" id="f_field_recapt_api" class="form-control autoWidth"><option value="v1"'. formSelector($recaptData[0],'v1',0) .'>API V1</option><option value="v2"'. formSelector($recaptData[0],'v2',0) .'>API V2</option></select></div>
			<div id="f_field_recapt_api_lang_area">
			';
			if($recaptData[0]=='v1'){
				$fieldData.='
								<input type="hidden" name="f_field_recapt_api_lang" value="en">
								<input type="hidden" name="f_field_recapt_api_theme" value="light">
								<input type="hidden" name="f_field_recapt_api_type" value="image">
							';
			}else{
			$fieldData.='<div class="form-group">
							<label for="f_field_recapt_api_lang">'. sh('ZlPryzxM0A') .subscribers_language.'</label>
							<select name="f_field_recapt_api_lang" id="f_field_recapt_api_lang" class="form-control autoWidth">';
								foreach($LETHE_SUBSCRIBE_FORM_RECAPTCHA_LANG as $kk=>$vv){
									$fieldData.='<option value="'. $kk .'"'. formSelector($recaptData[1],$kk,0) .'>'. $vv .'</option>';
								}
			$fieldData.='
							</select>
						 
						 </div>
						 
						 <div class="form-group">
							<label for="f_field_recapt_api_theme">'. sh('zb3rOGprAQ') .subscribers_theme.'</label>
							<select name="f_field_recapt_api_theme" id="f_field_recapt_api_theme" class="form-control autoWidth">
								<option value="light"'. formSelector($recaptData[2],'light',0) .'>Light</option>
								<option value="dark"'. formSelector($recaptData[2],'dark',0) .'>Dark</option>
							<select>
						 </div>
						 <div class="form-group">
							<label for="f_field_recapt_api_type">'. sh('41PgQN2rna') .subscribers_type.'</label>
							<select name="f_field_recapt_api_type" id="f_field_recapt_api_type" class="form-control autoWidth">
								<option value="image"'. formSelector($recaptData[3],'image',0) .'>Image</option>
								<option value="audio"'. formSelector($recaptData[3],'audio',0) .'>Audio</option>
							<select>
						 </div>
						 ';
						 
			}
			$fieldData.='
			</div>
			<script>
				$("#f_field_recapt_api").on("change",function(){
					if($(this).val()=="v1"){
						$("#f_field_recapt_api_lang_area").html(\'<input type="hidden" name="f_field_recapt_api_lang" value="en">\');
					}else{
						$("#f_field_recapt_api_lang_area").html(\'<div class="form-group"><label for="f_field_recapt_api_lang">'. sh('ZlPryzxM0A') .subscribers_language.'</label><select name="f_field_recapt_api_lang" id="f_field_recapt_api_lang" class="form-control autoWidth"></select></div>\');';
						foreach($LETHE_SUBSCRIBE_FORM_RECAPTCHA_LANG as $kk=>$vv){
							$fieldData.='$("#f_field_recapt_api_lang").append(\'<option value="'. $kk .'">'.$vv.'</option>\');';
						}						
			$fieldData.='
					}
				});
			</script>';
			
		}
		/* Add - Remove */
		else if($fieldSets['field_type']=='addremove'){
			
			$addRemOpt = explode("[@]",$fieldSets['field_data']);
			$fieldData.='<div class="form-group"><label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control" value="'. showIn($fieldSets['field_error'],'input') .'"></div>';
			$fieldData.='<div class="form-group"><label for="f_addremove_add">'. subscribers_add_label .':</label><input type="text" name="f_addremove_add" id="f_addremove_add" class="form-control" placeholder="'. letheglobal_add .'" value="'. showIn($addRemOpt[0],'input') .'"></div>';
			$fieldData.='<div class="form-group"><label for="f_addremove_remove">'. subscribers_label_of_remove .':</label><input type="text" name="f_addremove_remove" id="f_addremove_remove" class="form-control" placeholder="'. letheglobal_remove .'" value="'. showIn($addRemOpt[1],'input') .'"></div>';
			
		}
		/* E-Mail */
		if($fieldSets['field_type']=='email'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" value="'. showIn($fieldSets['field_label'],'input') .'" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_placeholder">'.sh('ynYKnxzFx4'). subscribers_placeholder .'</label>';
			$fieldData.='<input type="text" name="f_field_placeholder" id="f_field_placeholder" value="'. showIn($fieldSets['field_placeholder'],'input') .'" class="form-control">';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_pattern">'.sh('b7H8gLtspF'). subscribers_regex_pattern .'</label>';
			$fieldData.='<input type="text" name="f_field_pattern" id="f_field_pattern" value="'. showIn($fieldSets['field_pattern'],'input') .'" class="form-control">';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" value="'. showIn($fieldSets['field_error'],'input') .'" class="form-control input-sm">';
			$fieldData.='</div>';
		}
		/* Submit */
		if($fieldSets['field_type']=='submit'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" value="'. showIn($fieldSets['field_label'],'input') .'" required>';
			$fieldData.='</div>';
		}
		
		/* Delete Box */
		if($fieldSets['field_type']!='email' && $fieldSets['field_type']!='submit'){
			$fieldData.='<hr><div class="form-group">
				<span>'. sh('Y3lrxePM75') .'</span> <label><input type="checkbox" name="del" id="del" value="YES"> '. letheglobal_delete .'</label>
			</div>';
		}
		
		/* Save Button */
		$fieldData.='<hr><div class="form-group"><button type="button" name="updateFields" id="updateFields" class="btn btn-primary">'. letheglobal_save .'</button></div>';
		
		/* Actions */
		$fieldData.='</div><div id="field-upd-result"></div><script>
						$("#isRequired").change(function(){
							$(".errAlertField").slideToggle();
						});
						$(".intoAjax input[type=checkbox]").ionCheckRadio();
						
						/* Send Data */
						$("#updateFields").click(function(){
							$.ajax({
								url: "modules/lethe.subscribers/act.xmlhttp.php?pos=updateFields&ID='. $fieldSets['ID'] .'",
								type: "POST",
								data: $("#fieldUpdForm input,#fieldUpdForm select").serialize(),
								success: function(data){
									$("#field-upd-result").html(data);
									getAjax("#sortable-container","modules/lethe.subscribers/act.xmlhttp.php?pos=fieldorders&ID='. $fieldSets['FID'] .'","<span class=\"spin glyphicon glyphicon-refresh\"></span>");
								},
								error: function(){
									$("#field-upd-result").html("<div class=\"alert alert-danger\">'. subscribers_there_is_error_while_submit .'</div>");
								}
							});
							});
						
						
					</script>
					';
		
		return $fieldData;
	
	}

	/* Subscribe Form Field Update */
	public function fieldUpdate(){
	
		global $myconn;
		global $LETHE_SUBSCRIBE_SAVE_FIELDS;
		global $LETHE_SUBSCRIBE_FIELD_TYPES;
		$fieldData = '';
		$errText = '';
		$FFID = intval($this->FFID);
		
		/* Open Field Data */
		$opField = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". $this->OID ." AND ID=". $FFID ."") or die(mysqli_error($myconn));
		if(mysqli_num_rows($opField)==0){
			return errMod(letheglobal_record_not_found,'danger');
		}else{
		
		$opFieldRs = $opField->fetch_assoc();
		$_POST['fieldChooser'] = $opFieldRs['field_type'];
		
		/* Remover */
		if($_POST['fieldChooser']!='email' && $_POST['fieldChooser']!='submit'){
		
			if(isset($_POST['del']) && $_POST['del']=='YES'){
			
				$myconn->query("DELETE FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". $this->OID ." AND ID=". $FFID ."") or die(mysqli_error($myconn));
				return '<script>$("#fieldUpdForm").html("");</script>'.errMod(letheglobal_deleted_successfully,'success');
			
			}
		
		}
		
		/* ReCaptcha Check */
		if(isset($_POST['fieldChooser']) && $_POST['fieldChooser']=='recaptcha'){
			$_POST['isRequired']=1;
			$_POST['fieldSave'] = 'recaptcha';
			if(!isset($_POST['f_field_error']) || empty($_POST['f_field_error'])){$errText.='* '. subscribers_please_enter_a_field_error_message .'<br>';}
			$recaptFieldData = array();
			if(!isset($_POST['f_field_recapt_api']) || empty($_POST['f_field_recapt_api'])){
				$recaptFieldData = array('v1','en','light','image'); # Defaults
			}else{
				$recaptFieldData[] = trim($_POST['f_field_recapt_api']);
				
				# Language
				if(!isset($_POST['f_field_recapt_api_lang']) || empty($_POST['f_field_recapt_api_lang'])){
					$recaptFieldData[] = 'en';
				}else{
					$recaptFieldData[] = trim($_POST['f_field_recapt_api_lang']);
				}
				
				# Theme
				if(!isset($_POST['f_field_recapt_api_theme']) || empty($_POST['f_field_recapt_api_theme'])){
					$recaptFieldData[] = 'light';
				}else{
					$recaptFieldData[] = trim($_POST['f_field_recapt_api_theme']);
				}
				
				# Type
				if(!isset($_POST['f_field_recapt_api_type']) || empty($_POST['f_field_recapt_api_type'])){
					$recaptFieldData[] = 'light';
				}else{
					$recaptFieldData[] = trim($_POST['f_field_recapt_api_type']);
				}
				
			}
			$_POST['f_field_data'] = implode("@",$recaptFieldData);
		}
		
		/* Add - Remove Check */
		else if(isset($_POST['fieldChooser']) && $_POST['fieldChooser']=='addremove'){
			$catchData = 0;
			$_POST['isRequired']=1;
			$_POST['fieldSave'] = 'addremove';
			$_POST['f_field_label'] = 'Add / Remove';
			if(!isset($_POST['f_field_error']) || empty($_POST['f_field_error'])){$errText.='* '. subscribers_please_enter_a_field_error_message .'<br>';}
			if(!isset($_POST['f_addremove_add']) || empty($_POST['f_addremove_add'])){$errText.='* '. subscribers_please_enter_a_add_option_label .'<br>';}else{$catchData++;}
			if(!isset($_POST['f_addremove_remove']) || empty($_POST['f_addremove_remove'])){$errText.='* '. subscribers_please_enter_a_remove_option_label .'<br>';}else{$catchData++;}
			/* Add Remove Labels */
			if($catchData==2){
				$_POST['f_field_data'] = $_POST['f_addremove_add'].'[@]'.$_POST['f_addremove_remove'];
			}
		/* E-Mail */
		}else if(isset($_POST['fieldChooser']) && $_POST['fieldChooser']=='email'){
			$_POST['isRequired']=1;
			$_POST['fieldSave'] = 'subscriber_mail';
			if(!isset($_POST['f_field_error']) || empty($_POST['f_field_error'])){$errText.='* '. subscribers_please_enter_a_field_error_message .'<br>';}

		}else{
			if(!isset($_POST['isRequired']) || empty($_POST['isRequired'])){$_POST['isRequired']=0;$_POST['f_field_error']=null;}else{
				$_POST['isRequired']=1;
				if(!isset($_POST['f_field_error']) || empty($_POST['f_field_error'])){$errText.='* '. subscribers_please_enter_a_field_error_message .'<br>';}
			}
		}
		
		if(!isset($_POST['fieldChooser']) || !array_key_exists($_POST['fieldChooser'],$LETHE_SUBSCRIBE_FIELD_TYPES)){$errText.='* '. subscribers_incorrect_field_type .'<br>';}
		if(!isset($_POST['f_field_label']) || empty($_POST['f_field_label'])){$errText.='* '. subscribers_please_enter_a_field_label .'<br>';}
		if(!isset($_POST['f_field_placeholder']) || empty($_POST['f_field_placeholder'])){$_POST['f_field_placeholder']=null;}
		if(!isset($_POST['f_field_pattern']) || empty($_POST['f_field_pattern'])){$_POST['f_field_pattern']=null;}
		if(!isset($_POST['f_field_data']) || empty($_POST['f_field_data'])){$_POST['f_field_data']=null;}
		if(!isset($_POST['fieldSave']) || 
				!array_key_exists($_POST['fieldSave'],$LETHE_SUBSCRIBE_SAVE_FIELDS) && 
					($_POST['fieldSave']!='recaptcha' && $_POST['fieldSave']!='addremove'  && $_POST['fieldSave']!='subscriber_mail')){$errText.='* '. subscribers_incorrect_field_save_area .'<br>';}
		
		if($errText==''){
		
			/* Type Spec */
			if($_POST['fieldChooser']=='date'){
				if(isset($_POST['isDatepicker']) && $_POST['isDatepicker']=='YES'){
					$_POST['f_field_data']='YES';
				}
			}
		
			$newField = 'Lethe_'.$_POST['fieldChooser'].'_'. substr(md5(time().rand().uniqid(true)),0,5);
					
			$addField = $myconn->prepare("UPDATE 
														". db_table_pref ."subscribe_form_fields 
												  SET
														field_label=?,
														field_required=?,
														field_pattern=?,
														field_placeholder=?,
														field_data=?,
														field_save=?,
														field_error=?
												WHERE
														OID=". $this->OID ."
												  AND
														ID=?
												  ") or die(mysqli_error($myconn));
			$addField->bind_param('sisssssi',
												$_POST['f_field_label'],
												$_POST['isRequired'],
												$_POST['f_field_pattern'],
												$_POST['f_field_placeholder'],
												$_POST['f_field_data'],
												$_POST['fieldSave'],
												$_POST['f_field_error'],
												$FFID
									);
			$addField->execute();
			$addField->close();
		
			$errText = errMod(letheglobal_recorded_successfully,'success');
			
		}else{
			$errText = errMod($errText,'danger');
		}
		
		
		$opField->free();
		return $errText;
		
		}
	
	}
	
	/* Subscribe Field Modeller */
	public function fieldModeller(){
	
		global $LETHE_SUBSCRIBE_SAVE_FIELDS;
		$fieldData = '';
				
		/* Save Area */
		if($this->fieldType!='recaptcha' && $this->fieldType!='addremove'){
			$fieldData.='<div class="form-group"><label for="fieldSave">'.sh('kA6j3CA0Ci'). subscribers_recording_area .'</label>';
			$fieldData.='<select name="fieldSave" id="fieldSave" class="form-control autoWidth">';
			foreach($LETHE_SUBSCRIBE_SAVE_FIELDS as $kf=>$vf){
				if(cntData("SELECT ID FROM ". db_table_pref ."subscribe_form_fields WHERE OID=". set_org_id ." AND FID=". $this->FID ." AND field_save='". mysql_prep($kf) ."'")==0 || $kf=='subscriber_full_data'){
					$fieldData.= '<option value="'. $kf .'">'. $vf .'</option>';
				}
			}
			$fieldData.='</select></div>';
		}
		
		
		# **********************************
		
		/* Text */
		if($this->fieldType=='text' || 
				$this->fieldType=='phone' || 
					$this->fieldType=='number' || 
						$this->fieldType=='url' ||
							$this->fieldType=='textarea' 
			){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_placeholder">'.sh('ynYKnxzFx4'). subscribers_placeholder .'</label>';
			$fieldData.='<input type="text" name="f_field_placeholder" id="f_field_placeholder" class="form-control">';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_pattern">'.sh('b7H8gLtspF'). subscribers_regex_pattern .'</label>';
			$fieldData.='<input type="text" name="f_field_pattern" id="f_field_pattern" class="form-control">';
			$fieldData.='</div>';
			$fieldData.='<div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField sHide">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm">';
			$fieldData.='</div></div></div>';
		}
		/* Date */
		else if($this->fieldType=='date'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_placeholder">'.sh('ynYKnxzFx4'). subscribers_placeholder .'</label>';
			$fieldData.='<input type="text" name="f_field_placeholder" id="f_field_placeholder" class="form-control">';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_pattern">'.sh('b7H8gLtspF'). subscribers_regex_pattern .'</label>';
			$fieldData.='<input type="text" name="f_field_pattern" id="f_field_pattern" class="form-control">';
			$fieldData.='</div>';
			$fieldData.='<div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField sHide">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm">';
			$fieldData.='</div></div></div>';
			$fieldData.='<div class="container-fluid"><div class="col-md-5"><div class="form-group">';
			$fieldData.='<span>'. sh('rt7Cq8bHvu') .'</span> <label><input type="checkbox" name="isDatepicker" id="isDatepicker" value="YES"> '. subscribers_add_datepicker .'</label>';
			$fieldData.='</div></div></div>';
		}
		/* Selectbox */
		else if($this->fieldType=='select'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_select">'.sh('MTpWva4d4t'). subscribers_options .'</label>';
			$fieldData.='<select name="f_field_select" id="f_field_select" class="form-control" multiple></select>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group">
							<label>'.sh('6WYoauQXgG'). subscribers_controls .'</label>
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'up\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_up .'"><span class="glyphicon glyphicon-chevron-up"></span></button> 
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'down\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_down .'"><span class="glyphicon glyphicon-chevron-down"></span></button>
							<button type="button" onclick="javascript:listbox_remove(\'f_field_select\',\''. letheglobal_please_select_a_option_to_remove .'\');" class="btn btn-danger btn-sm init-list tooltips" title="'. letheglobal_delete .'"><span class="glyphicon glyphicon-remove"></span></button>
						 </div>						
						';
			$fieldData.='<div class="container-fluid"><label>'.sh('iqHcTILAYR'). subscribers_new_option .'</label><div class="clearfix"></div>';
			$fieldData.='<div class="col-md-6"><input id="boxKey" type="text" class="form-control" placeholder="'. subscribers_value .'"></div>';
			$fieldData.='<div class="col-md-6">
							<div class="input-group">
								<input type="text" id="boxVal" class="form-control" placeholder="'. subscribers_text .'">
								  <span class="input-group-btn">
									<button class="btn btn-success" id="addBoxItem" type="button">'. letheglobal_add .'</button>
								  </span>
							</div>
						 </div>';
			$fieldData.='</div>';
			$fieldData.='<hr><div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField sHide">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm">';
			$fieldData.='</div></div></div>';
			$fieldData.='<input type="hidden" name="f_field_data" id="tempbox" value="">';
			$fieldData.='<script>
							/* Add Item */
							$("#addBoxItem").click(function(){
								var commaSep = "";
								if($("#tempbox").val()==""){commaSep="";}else{commaSep=",";}
								if($("#boxKey").val()=="" || $("#boxVal").val()==""){alert("'. letheglobal_please_enter_a_value .'");return false;}
								$("#tempbox").val($("#tempbox").val()+commaSep+$("#boxKey").val()+":"+$("#boxVal").val());
								$("#boxKey").val("");
								$("#boxVal").val("");
								var newList = $("#tempbox").val().split(",");
								/* Clear List */
								$("#f_field_select").html("");
								/* Make List */
								for (a in newList) {
									parseData = newList[a].split(":");
									$("#f_field_select").append(\'<option value="\'+ parseData[0] +\'">\'+ parseData[1] +\'</option>\');
								}
							});
							
							/* Init List */
							$(".init-list").click(function(){
							
								var initData = "";
								$("#f_field_select > option").each(function() {
									if(initData!=""){initData=initData+",";}
									initData = initData + $(this).text() + ":" + $(this).val();
								});
								$("#tempbox").val(initData);
							
							});
							
							/* Init Helpers */
							$(".tooltips").tooltip();
			</script>';
		}
		/* Checkbox */
		else if($this->fieldType=='checkbox'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_select">'.sh('MTpWva4d4t'). subscribers_options .'</label>';
			$fieldData.='<select name="f_field_select" id="f_field_select" class="form-control" multiple></select>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group">
							<label>'.sh('6WYoauQXgG'). subscribers_controls .'</label>
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'up\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_up .'"><span class="glyphicon glyphicon-chevron-up"></span></button> 
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'down\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_down .'"><span class="glyphicon glyphicon-chevron-down"></span></button>
							<button type="button" onclick="javascript:listbox_remove(\'f_field_select\',\''. letheglobal_please_select_a_option_to_remove .'\');" class="btn btn-danger btn-sm init-list tooltips" title="'. letheglobal_delete .'"><span class="glyphicon glyphicon-remove"></span></button>
						 </div>						
						';
			$fieldData.='<div class="container-fluid"><label>'.sh('iqHcTILAYR'). subscribers_new_option .'</label><div class="clearfix"></div>';
			$fieldData.='<div class="col-md-6"><input id="boxKey" type="text" class="form-control" placeholder="'. subscribers_value .'"></div>';
			$fieldData.='<div class="col-md-6">
							<div class="input-group">
								<input type="text" id="boxVal" class="form-control" placeholder="'. subscribers_text .'">
								  <span class="input-group-btn">
									<button class="btn btn-success" id="addBoxItem" type="button">'. letheglobal_add .'</button>
								  </span>
							</div>
						 </div>';
			$fieldData.='</div>';
			$fieldData.='<hr><div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField sHide">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm">';
			$fieldData.='</div></div></div>';
			$fieldData.='<input type="hidden" name="f_field_data" id="tempbox" value="">';
			$fieldData.='<script>
							/* Add Item */
							$("#addBoxItem").click(function(){
								var commaSep = "";
								if($("#tempbox").val()==""){commaSep="";}else{commaSep=",";}
								if($("#boxKey").val()=="" || $("#boxVal").val()==""){alert("'. letheglobal_please_enter_a_value .'");return false;}
								$("#tempbox").val($("#tempbox").val()+commaSep+$("#boxKey").val()+":"+$("#boxVal").val());
								$("#boxKey").val("");
								$("#boxVal").val("");
								var newList = $("#tempbox").val().split(",");
								/* Clear List */
								$("#f_field_select").html("");
								/* Make List */
								for (a in newList) {
									parseData = newList[a].split(":");
									$("#f_field_select").append(\'<option value="\'+ parseData[0] +\'">\'+ parseData[1] +\'</option>\');
								}
							});
							
							/* Init List */
							$(".init-list").click(function(){
							
								var initData = "";
								$("#f_field_select > option").each(function() {
									if(initData!=""){initData=initData+",";}
									initData = initData + $(this).text() + ":" + $(this).val();
								});
								$("#tempbox").val(initData);
							
							});
							
							/* Init Helpers */
							$(".tooltips").tooltip();
			</script>';
		}
		/* Radio */
		else if($this->fieldType=='radio'){
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_select">'.sh('MTpWva4d4t'). subscribers_options .'</label>';
			$fieldData.='<select name="f_field_select" id="f_field_select" class="form-control" multiple></select>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group">
							<label>'.sh('6WYoauQXgG'). subscribers_controls .'</label>
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'up\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_up .'"><span class="glyphicon glyphicon-chevron-up"></span></button> 
							<button type="button" onclick="javascript:listbox_move(\'f_field_select\', \'down\',\''. letheglobal_please_select_a_option_to_move .'\');" class="btn btn-warning btn-sm init-list tooltips" title="'. letheglobal_down .'"><span class="glyphicon glyphicon-chevron-down"></span></button>
							<button type="button" onclick="javascript:listbox_remove(\'f_field_select\',\''. letheglobal_please_select_a_option_to_remove .'\');" class="btn btn-danger btn-sm init-list tooltips" title="'. letheglobal_delete .'"><span class="glyphicon glyphicon-remove"></span></button>
						 </div>						
						';
			$fieldData.='<div class="container-fluid"><label>'.sh('iqHcTILAYR'). subscribers_new_option .'</label><div class="clearfix"></div>';
			$fieldData.='<div class="col-md-6"><input id="boxKey" type="text" class="form-control" placeholder="'. subscribers_value .'"></div>';
			$fieldData.='<div class="col-md-6">
							<div class="input-group">
								<input type="text" id="boxVal" class="form-control" placeholder="'. subscribers_text .'">
								  <span class="input-group-btn">
									<button class="btn btn-success" id="addBoxItem" type="button">'. letheglobal_add .'</button>
								  </span>
							</div>
						 </div>';
			$fieldData.='</div>';
			$fieldData.='<hr><div class="container-fluid"><div class="col-md-4"><div class="form-group">';
			$fieldData.='<span>'. sh('DQzwYjoVhQ') .'</span> <label><input type="checkbox" name="isRequired" id="isRequired" value="YES"> '. subscribers_required .'?</label>';
			$fieldData.='</div></div>';
			$fieldData.='<div class="col-md-8"><div class="form-group errAlertField sHide">';
			$fieldData.='<label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control input-sm">';
			$fieldData.='</div></div></div>';
			$fieldData.='<input type="hidden" name="f_field_data" id="tempbox" value="">';
			$fieldData.='<script>
							/* Add Item */
							$("#addBoxItem").click(function(){
								var commaSep = "";
								if($("#tempbox").val()==""){commaSep="";}else{commaSep=",";}
								if($("#boxKey").val()=="" || $("#boxVal").val()==""){alert("'. letheglobal_please_enter_a_value .'");return false;}
								$("#tempbox").val($("#tempbox").val()+commaSep+$("#boxKey").val()+":"+$("#boxVal").val());
								$("#boxKey").val("");
								$("#boxVal").val("");
								var newList = $("#tempbox").val().split(",");
								/* Clear List */
								$("#f_field_select").html("");
								/* Make List */
								for (a in newList) {
									parseData = newList[a].split(":");
									$("#f_field_select").append(\'<option value="\'+ parseData[0] +\'">\'+ parseData[1] +\'</option>\');
								}
							});
							
							/* Init List */
							$(".init-list").click(function(){
							
								var initData = "";
								$("#f_field_select > option").each(function() {
									if(initData!=""){initData=initData+",";}
									initData = initData + $(this).text() + ":" + $(this).val();
								});
								$("#tempbox").val(initData);
							
							});
							
							/* Init Helpers */
							$(".tooltips").tooltip();
			</script>';
		}
		/* ReCaptcha */
		else if($this->fieldType=='recaptcha'){
			global $LETHE_SUBSCRIBE_FORM_RECAPTCHA_LANG;
			$fieldData.='<div class="form-group"><label for="f_field_label">'.sh('MTpWva4d4t'). subscribers_field_label .'</label>';
			$fieldData.='<input type="text" name="f_field_label" id="f_field_label" class="form-control" required>';
			$fieldData.='</div>';
			$fieldData.='<div class="form-group"><label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control"></div>';
			$fieldData.='<div class="form-group"><label for="f_field_recapt_api">'. sh('za7rBvqrZy') .subscribers_api_version.'</label><select name="f_field_recapt_api" id="f_field_recapt_api" class="form-control autoWidth"><option value="v1">API V1</option><option value="v2">API V2</option></select></div>
			<div id="f_field_recapt_api_lang_area"><input type="hidden" name="f_field_recapt_api_lang" value="en"></div>
			<div id="f_field_recapt_api_theme_area"><input type="hidden" name="f_field_recapt_api_theme" value="light"></div>
			<div id="f_field_recapt_api_type_area"><input type="hidden" name="f_field_recapt_api_type" value="image"></div>
			<script>
				$("#f_field_recapt_api").on("change",function(){
					if($(this).val()=="v1"){
						$("#f_field_recapt_api_lang_area").html(\'<input type="hidden" name="f_field_recapt_api_lang" value="en">\');
						$("#f_field_recapt_api_theme_area").html(\'<input type="hidden" name="f_field_recapt_api_theme" value="light">\');
						$("#f_field_recapt_api_type_area").html(\'<input type="hidden" name="f_field_recapt_api_type" value="image">\');
					}else{
						// Language
						$("#f_field_recapt_api_lang_area").html(\'<div class="form-group"><label for="f_field_recapt_api_lang">'. sh('ZlPryzxM0A') .subscribers_language.'</label><select name="f_field_recapt_api_lang" id="f_field_recapt_api_lang" class="form-control autoWidth"></select></div>\');';
						foreach($LETHE_SUBSCRIBE_FORM_RECAPTCHA_LANG as $kk=>$vv){
							$fieldData.='$("#f_field_recapt_api_lang").append(\'<option value="'. $kk .'">'.$vv.'</option>\');';
						}						
			$fieldData.='
			
						// Theme
						$("#f_field_recapt_api_theme_area").html(\'<div class="form-group"><label for="f_field_recapt_api_theme">'. sh('zb3rOGprAQ') .subscribers_theme.'</label><select name="f_field_recapt_api_theme" id="f_field_recapt_api_theme" class="form-control autoWidth"><option value="light">Light</option><option value="dark">Dark</option></select></div>\');
						
						// Type
						$("#f_field_recapt_api_type_area").html(\'<div class="form-group"><label for="f_field_recapt_api_type">'. sh('41PgQN2rna') .subscribers_type.'</label><select name="f_field_recapt_api_type" id="f_field_recapt_api_type" class="form-control autoWidth"><option value="image">Image</option><option value="audio">Audio</option></select></div>\');
			
			
					}
				});
			</script>
			';
		}
		/* Add - Remove */
		else if($this->fieldType=='addremove'){
			$fieldData.='<div class="form-group"><label for="f_field_error">'. sh('pkikR7j8Zb').subscribers_error_message .'</label><input type="text" name="f_field_error" id="f_field_error" class="form-control"></div>';
			$fieldData.='<div class="form-group"><label for="f_addremove_add">'. subscribers_add_label .':</label><input type="text" name="f_addremove_add" id="f_addremove_add" class="form-control" placeholder="'. letheglobal_add .'"></div>';
			$fieldData.='<div class="form-group"><label for="f_addremove_remove">'. subscribers_label_of_remove .':</label><input type="text" name="f_addremove_remove" id="f_addremove_remove" class="form-control" placeholder="'. letheglobal_remove .'"></div>';
		}
		
		/* Actions */
		$fieldData.='<script>
						$("#isRequired").change(function(){
							$(".errAlertField").slideToggle();
						});
						$(".intoAjax input[type=checkbox]").ionCheckRadio();
					</script>
					';
		
		return $fieldData;
		
	}
	
	/* Subscribe Field Add */
	public function fieldAdd(){
	
		global $myconn;
		global $LETHE_SUBSCRIBE_SAVE_FIELDS;
		global $LETHE_SUBSCRIBE_FIELD_TYPES;
		$fieldData = '';
		$errText = '';
		$FID = $this->FID;
		
		/* ReCaptcha Check */
		if(isset($_POST['fieldChooser']) && $_POST['fieldChooser']=='recaptcha'){
			$_POST['isRequired']=1;
			$_POST['fieldSave'] = 'recaptcha';
			if(!isset($_POST['f_field_error']) || empty($_POST['f_field_error'])){$errText.='* '. subscribers_please_enter_a_field_error_message .'<br>';}
			$recaptFieldData = array();
			if(!isset($_POST['f_field_recapt_api']) || empty($_POST['f_field_recapt_api'])){
				$recaptFieldData = array('v1','en'); # Defaults
			}else{
				$recaptFieldData[] = trim($_POST['f_field_recapt_api']);
				if(!isset($_POST['f_field_recapt_api_lang']) || empty($_POST['f_field_recapt_api_lang'])){
					$recaptFieldData[] = 'en';
				}else{
					$recaptFieldData[] = trim($_POST['f_field_recapt_api_lang']);
				}
			}
			$_POST['f_field_data'] = implode("@",$recaptFieldData);
		}
		
		/* Add - Remove Check */
		else if(isset($_POST['fieldChooser']) && $_POST['fieldChooser']=='addremove'){
			$catchData = 0;
			$_POST['isRequired']=1;
			$_POST['fieldSave'] = 'addremove';
			$_POST['f_field_label'] = 'Add / Remove';
			if(!isset($_POST['f_field_error']) || empty($_POST['f_field_error'])){$errText.='* '. subscribers_please_enter_a_field_error_message .'<br>';}
			if(!isset($_POST['f_addremove_add']) || empty($_POST['f_addremove_add'])){$errText.='* '. subscribers_please_enter_a_add_option_label .'<br>';}else{$catchData++;}
			if(!isset($_POST['f_addremove_remove']) || empty($_POST['f_addremove_remove'])){$errText.='* '. subscribers_please_enter_a_remove_option_label .'<br>';}else{$catchData++;}
			/* Add Remove Labels */
			if($catchData==2){
				$_POST['f_field_data'] = $_POST['f_addremove_add'].'[@]'.$_POST['f_addremove_remove'];
			}
		}else{
			if(!isset($_POST['isRequired']) || empty($_POST['isRequired'])){$_POST['isRequired']=0;$_POST['f_field_error']=null;}else{
				$_POST['isRequired']=1;
				if(!isset($_POST['f_field_error']) || empty($_POST['f_field_error'])){$errText.='* '. subscribers_please_enter_a_field_error_message .'<br>';}
			}
		}
		
		if(!isset($_POST['fieldChooser']) || !array_key_exists($_POST['fieldChooser'],$LETHE_SUBSCRIBE_FIELD_TYPES)){$errText.='* '. subscribers_incorrect_field_type .'<br>';}
		if(!isset($_POST['f_field_label']) || empty($_POST['f_field_label'])){$errText.='* '. subscribers_please_enter_a_field_label .'<br>';}
		if(!isset($_POST['f_field_placeholder']) || empty($_POST['f_field_placeholder'])){$_POST['f_field_placeholder']=null;}
		if(!isset($_POST['f_field_pattern']) || empty($_POST['f_field_pattern'])){$_POST['f_field_pattern']=null;}
		if(!isset($_POST['f_field_data']) || empty($_POST['f_field_data'])){$_POST['f_field_data']=null;}
		if(!isset($_POST['fieldSave']) || 
				!array_key_exists($_POST['fieldSave'],$LETHE_SUBSCRIBE_SAVE_FIELDS) && 
					($_POST['fieldSave']!='recaptcha' && $_POST['fieldSave']!='addremove')){$errText.='* '. subscribers_incorrect_field_save_area .'<br>';}
		
		if($errText==''){
		
			/* Type Spec */
			if($_POST['fieldChooser']=='date'){
				if(isset($_POST['isDatepicker']) && $_POST['isDatepicker']=='YES'){
					$_POST['f_field_data']='YES';
				}
			}
		
			$newField = 'Lethe_'.$_POST['fieldChooser'].'_'. substr(md5(time().rand().uniqid(true)),0,5);
			
			# Use Google Response Field
			if(isset($_POST['fieldChooser']) && $_POST['fieldChooser']=='recaptcha'){
				$newField = 'g-recaptcha-response';
			}
			
					
			$addField = $myconn->prepare("INSERT INTO 
														". db_table_pref ."subscribe_form_fields 
												  SET
														OID=". $this->OID .",
														FID=". $FID .",
														field_label=?,
														field_name='". $newField ."',
														field_type=?,
														field_required=?,
														field_pattern=?,
														field_placeholder=?,
														field_data=?,
														field_save=?,
														field_error=?
												  ") or die(mysqli_error($myconn));
			$addField->bind_param('ssisssss',
												$_POST['f_field_label'],
												$_POST['fieldChooser'],
												$_POST['isRequired'],
												$_POST['f_field_pattern'],
												$_POST['f_field_placeholder'],
												$_POST['f_field_data'],
												$_POST['fieldSave'],
												$_POST['f_field_error']												
									);
			$addField->execute();
			$addField->close();
		
			$errText = errMod(letheglobal_recorded_successfully,'success');
			
			/* Make Selected First Option */
			$errText.='<script>
							$("#fieldChooser option:selected").prop("selected",false);
							$("#fieldChooser option:first").prop("selected",true);
					   </script>';
			
			/* Disable Options */
			if($_POST['fieldChooser']=='recaptcha'){
				$errText.='<script>					
								$("#fieldChooser").find("option[value=\"recaptcha\"]").attr("disabled",true);
								$("#fieldChooser").find("option[value=\"recaptcha\"]").css("color","#CCC");
							</script>';
			}
			else if($_POST['fieldChooser']=='addremove'){
				$errText.='<script>					
								$("#fieldChooser").find("option[value=\"addremove\"]").attr("disabled",true);
								$("#fieldChooser").find("option[value=\"addremove\"]").css("color","#CCC");
							</script>';
			}
			
		}else{
			$errText = errMod($errText,'danger');
		}
		
		
		return $errText;
	
	}

}

/* Form Designer */
class letheForms{

	public $OID=0;
	public $formID = 0;
	public $isPreviewForm = false;

	/* Field Model */
	public function drawField($dr){
	
		$renderData = '';
		
		/* Text */
		if($dr['field_type']=='text'){
			$renderData .= '<input type="text" name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" value="" class="form-control"'. (($dr['field_required']!=0) ? ' required':'') .''. ((isset($dr['field_placeholder']) && $dr['field_placeholder']!='') ? ' placeholder="'. showIn($dr['field_placeholder'],'input') .'"':'') .''. ((isset($dr['field_pattern']) && $dr['field_pattern']!='') ? ' pattern="'. showIn($dr['field_pattern'],'input') .'"':'') .'>';
		}
		/* E-Mail */
		else if($dr['field_type']=='email'){
			$renderData .= '<input type="email" name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" value="" class="form-control"'. (($dr['field_required']!=0) ? ' required':'') .''. ((isset($dr['field_placeholder']) && $dr['field_placeholder']!='') ? ' placeholder="'. showIn($dr['field_placeholder'],'input') .'"':'') .''. ((isset($dr['field_pattern']) && $dr['field_pattern']!='') ? ' pattern="'. showIn($dr['field_pattern'],'input') .'"':'') .'>';
		}
		/* Phone */
		else if($dr['field_type']=='phone'){
			$renderData .= '<input type="phone" name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" value="" class="form-control"'. (($dr['field_required']!=0) ? ' required':'') .''. ((isset($dr['field_placeholder']) && $dr['field_placeholder']!='') ? ' placeholder="'. showIn($dr['field_placeholder'],'input') .'"':'') .''. ((isset($dr['field_pattern']) && $dr['field_pattern']!='') ? ' pattern="'. showIn($dr['field_pattern'],'input') .'"':'') .'>';
		}
		/* Number */
		else if($dr['field_type']=='number'){
			$renderData .= '<input type="number" name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" value="" class="form-control"'. (($dr['field_required']!=0) ? ' required':'') .''. ((isset($dr['field_placeholder']) && $dr['field_placeholder']!='') ? ' placeholder="'. showIn($dr['field_placeholder'],'input') .'"':'') .''. ((isset($dr['field_pattern']) && $dr['field_pattern']!='') ? ' pattern="'. showIn($dr['field_pattern'],'input') .'"':'') .'>';
		}
		/* Date */
		else if($dr['field_type']=='date'){
			$renderData .= '<input type="text" name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" value="" class="form-control"'. (($dr['field_required']!=0) ? ' required':'') .''. ((isset($dr['field_placeholder']) && $dr['field_placeholder']!='') ? ' placeholder="'. showIn($dr['field_placeholder'],'input') .'"':'') .''. ((isset($dr['field_pattern']) && $dr['field_pattern']!='') ? ' pattern="'. showIn($dr['field_pattern'],'input') .'"':'') .'>';
			if($dr['field_data']=='YES'){
				$renderData .= '<script>$(document).ready(function(){$("#'. $dr['field_name'] .'").datepicker({dateFormat:"mm/dd/yy"});});</script>';
			}
		}
		/* Textarea */
		else if($dr['field_type']=='textarea'){
			$renderData .= '<textarea name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" class="form-control"'. (($dr['field_required']!=0) ? ' required':'') .''. ((isset($dr['field_placeholder']) && $dr['field_placeholder']!='') ? ' placeholder="'. showIn($dr['field_placeholder'],'input') .'"':'') .''. ((isset($dr['field_pattern']) && $dr['field_pattern']!='') ? ' pattern="'. showIn($dr['field_pattern'],'input') .'"':'') .'></textarea>';
		}
		/* Selectbox */
		else if($dr['field_type']=='select'){
			$renderData .= '<select name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" class="form-control"'. (($dr['field_required']!=0) ? ' required':'') .'>';
			if(isset($dr['field_data'])){
				$selectSet = explode(',',$dr['field_data']);
				foreach($selectSet as $rk=>$rv){
					$selectData = explode(':',$rv);
					$renderData .='<option value="'. $selectData[0] .'">'. $selectData[1] .'</option>';
				}
			}
			$renderData .='</select>';
		}
		/* Checkbox */
		else if($dr['field_type']=='checkbox'){
			if(isset($dr['field_data'])){
				$selectSet = explode(',',$dr['field_data']);
				$renderData .='<span class="clearfix"></span>';
				foreach($selectSet as $rk=>$rv){
					$selectData = explode(':',$rv);
					$renderData .='<label for="chkB'. $rk .'"><input type="checkbox" name="'. $dr['field_name'] .'[]" id="chkB'. $rk .'" value="'. $selectData[0] .'"'. (($dr['field_required']!=0) ? ' required':'') .'> '. $selectData[1] .'</label> ';
				}
			}
		}
		/* Radio */
		else if($dr['field_type']=='radio'){
			if(isset($dr['field_data'])){
				$radioSet = explode(',',$dr['field_data']);
				$renderData .='<span class="clearfix"></span>';
				foreach($radioSet as $rk=>$rv){
					$radioData = explode(':',$rv);
					$renderData .= '<label for="'. $dr['field_name'] .$rk.'"><input type="radio" name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .$rk.'" value="'. $radioData[0] .'"'. (($dr['field_required']!=0) ? ' required':'') .'>'. $radioData[1] .'</label> ';
				}
			}
		}
		/* URL */
		else if($dr['field_type']=='url'){
			$renderData .= '<input type="url" name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" value="" class="form-control"'. (($dr['field_required']!=0) ? ' required':'') .''. ((isset($dr['field_placeholder']) && $dr['field_placeholder']!='') ? ' placeholder="'. showIn($dr['field_placeholder'],'input') .'"':'') .''. ((isset($dr['field_pattern']) && $dr['field_pattern']!='') ? ' pattern="'. showIn($dr['field_pattern'],'input') .'"':'') .'>';
		}
		/* Add / Remove */
		else if($dr['field_type']=='addremove'){
			if(isset($dr['field_data'])){
				$radioSet = explode('[@]',$dr['field_data']);
				$renderData .= '<label for="letheForm_addrem0"><input type="radio" value="ADD" name="'. $dr['field_name'] .'" id="letheForm_addrem0" required> '. showIn($radioSet[0],'page') .'</label> ';
				$renderData .= '<label for="letheForm_addrem1"><input type="radio" value="REM" name="'. $dr['field_name'] .'" id="letheForm_addrem1" required> '. showIn($radioSet[1],'page') .'</label> ';
			}
		}
		/* Recaptcha */
		else if($dr['field_type']=='recaptcha'){
			
			$reCaptMode = explode("@",$dr['field_data']);
			
			if($reCaptMode[0]=='v2'){
			# API V2
			
			if(!array_key_exists(1,$reCaptMode)){$reCaptMode[1]='en';}
			if(!array_key_exists(2,$reCaptMode)){$reCaptMode[2]='light';}
			if(!array_key_exists(3,$reCaptMode)){$reCaptMode[3]='image';}
			$renderData .= '
				<div class="g-recaptcha" data-theme="'. $reCaptMode[2] .'" data-type="'. $reCaptMode[3] .'" data-sitekey="'. lethe_google_recaptcha_public .'"></div>
				<script src="//www.google.com/recaptcha/api.js?hl='. $reCaptMode[1] .'" async defer></script> 
			';			
			
			}else{
			# API V1
			$renderData .= '
					<link rel="stylesheet" href="'. lethe_root_url .'lib/reCaptcha/recaptcha.style.css">
				    <div class="clearfix form-group">
				        <!-- reCaptcha -->
						<script type="text/javascript">
						var RecaptchaOptions = {
						theme : "custom",
						custom_theme_widget: "recaptcha_widget"
						};
						</script>
						<div id="recaptcha_widget" style="display:none">
							<div id="recaptcha_image"></div>
							<div class="recaptcha_only_if_incorrect_sol" style="color:red">reCaptcha Error!</div>
							<div class="input-group"><input class="form-control" type="text" id="recaptcha_response_field" name="recaptcha_response_field" placeholder="'. ((isset($dr['field_placeholder']) && $dr['field_placeholder']!='') ? ' placeholder="'. showIn($dr['field_placeholder'],'input'):'') .'"><span class="input-group-btn"><button onclick="javascript:Recaptcha.reload();" class="btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button></span></div>
						</div>
						<script type="text/javascript" src="//www.google.com/recaptcha/api/challenge?k='. lethe_google_recaptcha_public .'"></script>
						<noscript>
							<iframe src="//www.google.com/recaptcha/api/noscript?k='. lethe_google_recaptcha_public .'" height="200" width="200" frameborder="0"></iframe>
						<br><textarea name="recaptcha_challenge_field" rows="3" cols="20"></textarea>
							<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
						</noscript>

						<!-- reCaptcha -->
				    </div>
			';
			}
		}
		/* Submit */
		else if($dr['field_type']=='submit'){
			$renderData .= '<button type="submit" name="'. $dr['field_name'] .'" id="'. $dr['field_name'] .'" class="btn btn-primary">'. showIn($dr['field_label'],'page') .'</button>';
		}
		
		else{
			$renderData.='';
		}
		
		$renderData = $renderData.PHP_EOL;
		return $renderData;
	
	}
	
	/* Form Designer */
	public function formDesigner($fr){
	
		$renderData = '';
	
		
			/* Vertical */
			if($fr['form_view']==0){
				foreach($fr['form_fields'] as $a=>$b){
					$renderData.='<div class="form-group">'.PHP_EOL;
						$renderData.= (($b['field_type']!='submit' && $b['field_type']!='addremove') ? '<label for="'. $b['field_name'] .'">'. showIn($b['field_label'],'page') .'</label>':'');
						
						$renderData.= $this->drawField(
														array('field_id'=>$b['field_id'],
															  'field_name'=>$b['field_name'],
															  'field_type'=>$b['field_type'],
															  'field_required'=>intval($b['field_required']),
															  'field_pattern'=>$b['field_pattern'],
															  'field_placeholder'=>$b['field_placeholder'],
															  'field_data'=>$b['field_data'],
															  'field_label'=>$b['field_label']
															  )
														);
					$renderData.='</div>'.PHP_EOL;
				}
			}
			/* Horizontal */
			else if($fr['form_view']==1){
				foreach($fr['form_fields'] as $a=>$b){
					$renderData.='<div '. (($b['field_type']!='recaptcha') ? 'class="form-group"':'') .'>'.PHP_EOL;
						$renderData.= (($b['field_type']!='radio' && $b['field_type']!='submit' && $b['field_type']!='addremove') ? '<label for="'. $b['field_name'] .'">'. showIn($b['field_label'],'page') .'</label> ':'');
						
						$renderData.= $this->drawField(
														array('field_id'=>$b['field_id'],
															  'field_name'=>$b['field_name'],
															  'field_type'=>$b['field_type'],
															  'field_required'=>intval($b['field_required']),
															  'field_pattern'=>$b['field_pattern'],
															  'field_placeholder'=>$b['field_placeholder'],
															  'field_data'=>$b['field_data'],
															  'field_label'=>$b['field_label']
															  )
														);
					$renderData.='</div>'.PHP_EOL;
				}
			}
			/* Table */
			else if($fr['form_view']==2){
			
				$renderData .= '<table style="height: 44px;" width="250"><tbody>';
			
				foreach($fr['form_fields'] as $a=>$b){
					$renderData.='<tr>'.PHP_EOL;
						$renderData.= '<td style="padding:5px;">'.(($b['field_type']!='radio' && $b['field_type']!='submit' && $b['field_type']!='addremove') ? '<label for="'. $b['field_name'] .'">'. showIn($b['field_label'],'page') .'</label></td><td style="padding:5px;">:</td>':'<td></td>');
						$renderData.= '<td style="padding:3px;">'.$this->drawField(
														array('field_id'=>$b['field_id'],
															  'field_name'=>$b['field_name'],
															  'field_type'=>$b['field_type'],
															  'field_required'=>intval($b['field_required']),
															  'field_pattern'=>$b['field_pattern'],
															  'field_placeholder'=>$b['field_placeholder'],
															  'field_data'=>$b['field_data'],
															  'field_label'=>$b['field_label']
															  )
														).'</td>';
					$renderData.='</tr>'.PHP_EOL;
				}
				$renderData .= '</tbody></table>';
			}
			
		
		return $renderData;
		
	
	}
	
	/* Form Builder */
	public function buildForm(){
	
		global $myconn;
		$formData = '';
		
		if($this->formID==0){$formIDs = 1;}else{$formIDs = $this->formID;}
		
		$opForms = $myconn->prepare("SELECT * FROM ". db_table_pref ."subscribe_forms WHERE OID=". $this->OID ." AND ". ((!$this->formID) ? 'isSystem=?':'ID=?') ."") or die(mysqli_error($myconn));
		$opForms->bind_param('i',$formIDs);
		$opForms->execute();
		$opForms->store_result();
		if($opForms->num_rows==0){$opForms->close();return errMod('Subscribe Form Error!','danger');die();}
		$sr = new Statement_Result($opForms);
		$opForms->fetch();
		$opForms->close();
		$formFields = array(
							'form_view'=>$sr->Get('form_view'),
							'form_fields'=>array()
							);
		
		$formData .=	'<div id="lethe-result"></div><form '. (($sr->Get('form_view')==1) ? 'class="form-inline"':'') .' name="'. $sr->Get('form_id') .'" id="'. $sr->Get('form_id') .'" method="POST" enctype="application/x-www-form-urlencoded" action="javascript:;">'.PHP_EOL;
		$formData .=    '<input type="hidden" id="lethe_form" name="lethe_form" value="'. $sr->Get('form_id') .'">';
		$formData .=    '<input type="hidden" id="lethe_oid" name="lethe_oid" value="'. set_org_public_key .'">';
			$opFields = $myconn->query("SELECT * FROM ". db_table_pref ."subscribe_form_fields WHERE FID=". $sr->Get('ID') ." ORDER BY sorting ASC") or die(mysqli_error($myconn));
			if(mysqli_num_rows($opFields)==0){$formData .= errMod('Form fields can not be found','danger');}else{
				while($opFieldsRs = $opFields->fetch_assoc()){
					$formFields['form_fields'][] = array(
														 'field_id'=>$opFieldsRs['ID'],
														 'field_name'=>$opFieldsRs['field_name'],
														 'field_label'=>$opFieldsRs['field_label'],
														 'field_type'=>$opFieldsRs['field_type'],
														 'field_required'=>$opFieldsRs['field_required'],
														 'field_pattern'=>$opFieldsRs['field_pattern'],
														 'field_placeholder'=>$opFieldsRs['field_placeholder'],
														 'field_data'=>$opFieldsRs['field_data']
														);
				} 
							
				$formData .= $this->formDesigner($formFields);
				
				$opFields->free();
				
			}
		
		$formData .=	'</form>'.PHP_EOL;
		
		/* Ajax Code */
		$formData .=	'<script type="text/javascript">'.PHP_EOL;
		$formData .=	'
							$("#'. $sr->Get('form_id') .'").on("submit",function(){
							$.ajax({
								url: "'. lethe_root_url .'lethe.newsletter.php?pos=subscribe",
								type: "POST",
								contentType: "application/x-www-form-urlencoded",
								crossDomain: true,
								data: $("#'. $sr->Get('form_id') .'").serialize(),
								success: function(data){
									$("#lethe-result").html(data);
									$("html,body").animate({scrollTop: $("#lethe-result").offset().top},"slow");
								},
								error: function(){
									$("#lethe-result").html("<div class=\"alert alert-danger\">'. subscribers_there_is_error_while_submit .'</div>");
								}
							});});'.PHP_EOL;
		$formData .=	'</script>'.PHP_EOL;
		
		if(!$this->isPreviewForm){
			/* Add JQuery */
			if($sr->Get('include_jquery')){$formData .=	'<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>'.PHP_EOL;}
			/* Add JQuery UI */
			if($sr->Get('include_jqueryui')){$formData .=	'
															<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
															<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>'.PHP_EOL;
											}
		}
		$formData = preg_replace('/\s+/', ' ', $formData);
		$formDataAll = '<!-- LETHE NEWSLETTER -->'.PHP_EOL;
		$formDataAll .= $formData.PHP_EOL;
		$formDataAll .= '<!-- LETHE NEWSLETTER -->'.PHP_EOL;
		
		return $formDataAll;
	
	}
}

function PaginateArray($input, $page, $show_per_page) {

  $page = $page < 1 ? 1 : $page;

  $start = ($page - 1) * ($show_per_page);
  $offset = $show_per_page;

  $outArray = array_slice($input, $start, $offset);

  return $outArray;
 } 
 
/* CSV Importer */ 
class CsvImporter
{
    private $fp;
    private $parse_header;
    private $header;
    private $delimiter;
    private $length;
    //--------------------------------------------------------------------
    function __construct($file_name, $parse_header=false, $delimiter="\t", $length=8000,$lines=null)
    {
        $this->fp = fopen($file_name, "r");
        $this->parse_header = $parse_header;
        $this->delimiter = $delimiter;
        $this->length = $length;
        $this->lines = $lines;

        if ($this->parse_header)
        {
           $this->header = fgetcsv($this->fp, $this->length, $this->delimiter);
        }

    }
    //--------------------------------------------------------------------
    function __destruct()
    {
        if ($this->fp)
        {
            fclose($this->fp);
        }
    }
    //--------------------------------------------------------------------
    function get($max_lines=0)
    {
        //if $max_lines is set to 0, then get all the data

        $data = array();

        if ($max_lines > 0)
            $line_count = 0;
        else
            $line_count = -1; // so loop limit is ignored

        while ($line_count < $max_lines && ($row = fgetcsv($this->fp, $this->length, $this->delimiter)) !== FALSE)
        {
            if ($this->parse_header)
            {
                foreach ($this->header as $i => $heading_i)
                {
                    $row_new[$heading_i] = $row[$i];
                }
                $data[] = $row_new;
            }
            else
            {
                $data[] = $row;
            }

            if ($max_lines > 0)
                $line_count++;
        }
        return $data;
    }
    //--------------------------------------------------------------------

}

/* Import Model Modifier */
function replaceImportContent($fl,$mod,$sep){
	
$sepMod = array('sep1'=>',','sep2'=>';','sep3'=>"\r\n");
	$sep = $sepMod[$sep];
	
	if($mod=='model4'){
		# name{SEPARATOR}mail
		$new_data = array();
		$fl = set_org_resource.'/expimp/'.$fl;
		$f = file_get_contents($fl);
		$new_f = explode($sep,$f);
		$cn = 0;
		foreach($new_f as $k=>$v){
			if(!mailVal($v)){
				$new_data[$cn] = '"'. $v .'" ';
			}else{
				$new_data[$cn] = $new_data[$cn].'<'. $v .'>';
				$cn++;
			}			
		}
		
		$nwf = implode($sep,$new_data);
		
		# Replace Content
		$myfile = fopen($fl, "w");
		fwrite($myfile, $nwf);
		fclose($myfile);
		
		return true;
		
	}
	else if($mod=='model5'){
		# mail{SEPARATOR}name{SEPARATOR}surname
		$new_data = array();
		$fl = set_org_resource.'/expimp/'.$fl;
		$f = file_get_contents($fl);
		$new_f = explode($sep,$f);
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
		$nwf = implode($sep,$new_data);
		
		# Replace Content
		$myfile = fopen($fl, "w");
		fwrite($myfile, $nwf);
		fclose($myfile);
		
		return true;
	}
	
}
?>