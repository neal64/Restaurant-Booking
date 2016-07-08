/*  
 +------------------------------------------------------------------------+
 | Artlantis CMS Solutions                                                |
 +------------------------------------------------------------------------+
 | Lethe Newsletter & Mailing System                                      |
 | Copyright (c) Artlantis Design Studio 2014. All rights reserved.       |
 | File Version  1.0                                                      |
 | Last modified 13.11.14                                                 |
 | Email         developer@artlantis.net                                  |
 | Developer     http://www.artlantis.net                                 |
 +------------------------------------------------------------------------+ 
*/

$(document).ready(function(){
	
	// ION Check
	$('.ionc').ionCheckRadio();
	
	/* Delete Checkbox Alerts */
	$('input[type=checkbox][name=del]').change(function(){
	
		if(!$(this).data('alert-dialog-text')){
			$(this).attr('data-alert-dialog-text','Are you sure to delete?');
		}
	
		if($(this).is(':checked')){
			if(confirm($(this).attr('data-alert-dialog-text'))){
				$(this).attr('checked',true);
			}else{
				$(this).attr('checked',false);
			}
		}
	});
	
	// Switch Button
	$(".letheSwitch").switchButton({
	  labels_placement: "right"
	});
	
	/* Tooltip */
	$(".tooltips, [data-toggle=tooltip]").tooltip();
	
	/* Mail Method Changer */
	$("#send_method").on('change',function(){
	
		$(".mailMethods").hide();
		$(".mailMethod"+$(this).val()).fadeIn('fast');
	
	});
	
	/* Load Selected Method */
	$(".mailMethods").hide();
	$(".mailMethod"+$("#send_method option:selected").val()).show();
	
	/* Short Code Opener */
	$(".sc-opener").click(function(){
		if($(this).find('span,i').hasClass("glyphicon-chevron-down")){
			$(this).find('span,i').removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
		}else{$(this).find('span,i').removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");}
		$("#sc-box").slideToggle();
	});
	
	/* Short Code Insert */
	$(".lethe-sc").click(function(){
		var myField = tinyMCE.get($(this).data('lethe-scf'));
			if (document.selection) {
				myField.focus();
				sel = document.selection.createRange();
				sel.text = $(this).find('span').html();
			}
			else if (document.getSelection) {
				tinyMCE.activeEditor.selection.setContent($(this).find('span').html());
				myField.focus();
			}
	});
	
	/* FancyBox */
	$('.fancybox').fancybox({
		autoSize:true,
		width:'80%',
		maxWidth:1200
	});
	
	/* Sidera Helper Opener */
	$(".shd-mh").bind('click',function(){
		var shd_key = $(this).data("shd-key");
		$.fancybox({
						 autoSize   : true,
						 type       : "iframe",
						 href       : sidera_helper_uri + shd_key
						 });
	});
	
	/* Preview Content */
    $(".LethePreview").fancybox({
		autoSize : true,
		type     : 'inline',
        beforeLoad : function() {
			this.content = tinyMCE.get('details').getContent();
            this.width  = 1000;  
            this.height = 800;
        }
    });	
	
	/* Checkbox Selector */
	  $("#checkAll").on('change',function(){
		if($(this).is(":checked")){
		  $(".checkRow").each(function(){
			$(".checkRow").prop("checked", true);
			$("span.icr.enabled").addClass("checked");
		  });         
		}else{
		  $(".checkRow").each(function(){
			$(".checkRow").prop("checked", false);
			$("span.icr.enabled").removeClass("checked");
		  });
		}
	  });
	  
	  /* Remove All Selector */
	  $(".checkRow").change(function(){
		if(!$(this).is(":checked")){
			
				$("#checkAll").prop("checked", false);
				$("#checkAll").parent().next("span.icr").removeClass("checked");
		}
	  });
	  
	  /* Toggle Opener */
	  $(".toggler").click(function(){
			var spanClass = $(this).find("span");
			$($(this).data("target")).slideToggle();
			if(spanClass.hasClass("glyphicon-chevron-down")){$(this).find("span.glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");}
			else{$(this).find("span.glyphicon-chevron-up").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");}

	  });
	  	
});

function getAjax(div,url,text){
	$(div).html(text);
	$.ajax({
	type: 'GET',
	url: url,
	success: function(data){
			$(div).html(data);
		},
	error: function(data){
		$(div).html('Error Occured!');
	}
	});
}

function textCopier(source,destination){
	
	var sourceText = $(source).val();
	var destArea = destination.split(',');
	
	for (i = 0; i < destArea.length; ++i) {
		if($(destArea[i]).val()==''){
			$(destArea[i]).val(sourceText);
		}
	}
	
}

/* Validate Number */
function validateNumber(evt) {
    var e = evt || window.event;
    var key = e.keyCode || e.which;

    if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
    // numbers   
    key >= 48 && key <= 57 ||
    // Numeric keypad
    key >= 96 && key <= 105 ||
    // Backspace and Tab and Enter
    key == 8 || key == 9 || key == 13 ||
    // Home and End
    key == 35 || key == 36 ||
    // left and right arrows
    key == 37 || key == 39 ||
    // Del and Ins
    key == 46 || key == 45) {
        // input is VALID
    }
    else {
        // input is INVALID
        e.returnValue = false;
        if (e.preventDefault) e.preventDefault();
    }
}

function specChars(c){
      var cz = new String();
      var chars = c.split("");
      for (i = 0; i < chars.length; i++) {
          chars[i]=chars[i].replace("Ç", "C");
          chars[i]=chars[i].replace("ç", "c");
          chars[i]=chars[i].replace("Ğ", "G");
          chars[i]=chars[i].replace("ğ", "g");
          chars[i]=chars[i].replace("İ", "I");
          chars[i]=chars[i].replace("ı", "i");
          chars[i]=chars[i].replace("Ö", "O");
          chars[i]=chars[i].replace("ö", "o");
          chars[i]=chars[i].replace("Ş", "S");
          chars[i]=chars[i].replace("ş", "s");
          chars[i]=chars[i].replace("Ü", "U");
          chars[i]=chars[i].replace("ü", "u");
		  chars[i]=chars[i].replace("-", "_");
		  chars[i]=chars[i].replace(/ /g, "_");
          cz += chars[i];
      }
	  cz = cz.replace(/_+/g, '_');
	  if(cz.slice(-1)=='_'){cz = cz.substring(0,cz.length-1);}
	  
	  
	return cz;
}

/* Short Code Maker */
function shortCodeMaker(v){
	var codeKey = document.getElementById(v).value;
	codeKey = codeKey.toLowerCase(); // lowercase
	codeKey = specChars(codeKey);
	codeKey = codeKey.replace(/\s+/gi, '_');
	codeKey = codeKey.replace(/[^a-zA-Z0-9\-_]/gi, '');
	document.getElementById(v).value = codeKey;
}

// ** Multi Select List
function listbox_moveacross(sourceID, destID) {
    var src = document.getElementById(sourceID);
    var dest = document.getElementById(destID);
 
    for(var count=0; count < src.options.length; count++) {
 
        if(src.options[count].selected == true) {
                var option = src.options[count];
 
                var newOption = document.createElement("option");
                newOption.value = option.value;
                newOption.text = option.text;
                newOption.selected = true;
                try {
                         dest.add(newOption, null); //Standard
                         src.remove(count, null);
                 }catch(error) {
                         dest.add(newOption); // IE only
                         src.remove(count);
                 }
                count--;
        }
    }
}

// Move Listbox Item
function listbox_move(listID, direction, errText) {
 
	if(errText==''){errText='Please select an option to move.';}
    var listbox = document.getElementById(listID);
    var selIndex = listbox.selectedIndex;
 
    if(-1 == selIndex) {
        alert(errText);
        return;
    }
 
    var increment = -1;
    if(direction == 'up')
        increment = -1;
    else
        increment = 1;
 
    if((selIndex + increment) < 0 ||
        (selIndex + increment) > (listbox.options.length-1)) {
        return;
    }
 
    var selValue = listbox.options[selIndex].value;
    var selText = listbox.options[selIndex].text;
    listbox.options[selIndex].value = listbox.options[selIndex + increment].value
    listbox.options[selIndex].text = listbox.options[selIndex + increment].text
 
    listbox.options[selIndex + increment].value = selValue;
    listbox.options[selIndex + increment].text = selText;
 
    listbox.selectedIndex = selIndex + increment;
}

/* Remove Listbox Item */
function listbox_remove(sourceID,errText) {
 
	if(errText==''){errText='Please select a option to remove';}
    //get the listbox object from id.
    var src = document.getElementById(sourceID);
	var slCnt = 0;
  
    //iterate through each option of the listbox
    for(var count= src.options.length-1; count >= 0; count--) {
 
         //if the option is selected, delete the option
        if(src.options[count].selected == true) {
  
                try {
                         src.remove(count, null);
                         
                 } catch(error) {
                         
                         src.remove(count);
                }
				
				slCnt++;
        }
    }
	
	if(slCnt==0){alert(errText);}
	
}

// ** Select All Listbox
    function listbox_selectall(listID, isSelect) {
        var listbox = document.getElementById(listID);
        for(var count=0; count < listbox.options.length; count++) {
            listbox.options[count].selected = isSelect;
    }
}

/* Percentange Color */
function getGreenToRed(percent){
			var r = '#acc0c6';
            if(percent<0){r = '#acc0c6';}
            else if(percent>0 && percent<25){r = '#ff0000';}
			else if(percent>25 && percent<50){r = '#ff6600';}
			else if(percent>50 && percent<75){r = '#ffc000';}
			else if(percent>75 && percent<100){r = '#92d050';}
			else if(percent>100){r = '#00b050';}
			return r;
        }
		
/* Template Loader */
function loadTemplates(pg,limit,style){
	$("#tempAPI").html('<div class="col-md-12"><span class="spin glyphicon glyphicon-refresh"></span></div>');
	$.ajax({
		url : "modules/lethe.templates/act.xmlhttp.php?pos=tempload&pgGo="+ pg +"&pgLimit="+limit+"&showStyle="+style,
		type: "POST",
		contentType: "application/x-www-form-urlencoded",
		success: function(data, textStatus, jqXHR)
		{
			$("#tempAPI").html(data);
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
	 
		}
	});
}

/* Download Template */
function downTemplates(tempID,selector){
	selector = "."+selector;
	$(selector).html('<span class="spin glyphicon glyphicon-refresh"></span>');
	$.ajax({
		url : "modules/lethe.templates/act.xmlhttp.php?pos=tempdown&tempID="+tempID,
		type: "POST",
		contentType: "application/x-www-form-urlencoded",
		success: function(data, textStatus, jqXHR)
		{
			$(selector).html(data);
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			$(selector).html('<span class="text-danger glyphicon glyphicon-remove"></span>');
		}
	});
}
