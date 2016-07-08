/* +------------------------------------------------------------------------+ */
/* | Artlantis CMS Solutions                                                | */
/* +------------------------------------------------------------------------+ */
/* | LeUpload - Lethe Newsletter Upload Plugin                              | */
/* | Copyright (c) Artlantis Design Studio 2015. All rights reserved.       | */
/* | Version       1.0                                                      | */
/* | Last modified 07.01.2015                                               | */
/* | Email         developer@artlantis.net                                  | */
/* | Web           http://www.artlantis.net                                 | */
/* +------------------------------------------------------------------------+ */

function refreshPage(){
	location.reload();
}

var ftypes = document.getElementById("myAwesomeDropzone").getAttribute('data-ftypes');
var fsize = document.getElementById("myAwesomeDropzone").getAttribute('data-fsize');

Dropzone.options.myAwesomeDropzone  = {
    paramName: "file", // Must match the name of the HttpPostedFileBase argument that the Upload action expects.
    dictDefaultMessage: "",
    acceptedFiles: ftypes,
	maxFilesize:fsize,
	autoDiscover : false,
	  init: function() {
		this.on("addedfile", function(file) {
			
		});
	  }
};

/* Link Clicks */
$(document).ready(function(){
	$(".leupload_link").click(function(){
		var f = $(this).data('leupload-form');
		var m = $(this).data('leupload-link-model');
		var l = $(this).data('leupload-link');
		var t = $(this).data('leupload-file-type');
		var p = $(this).data('leupload-platform');
		var o = $(this).data('leupload-opener');
		
		if (window.location.protocol === 'http:') {
			//l = 'http:' + l;
		}else{
			//l = 'https:' + l;
		}
		
		pan(f,m,l,t,p,o);
	});
});

// Link Function

	function pan(f,m,l,t,p,o){

		// f - Form Field
		// m - Link Model
		// l - File Link
		// t - File Type
		// p - Platform (normal,tinymce,ckeditor)
		// o - Opener (normal,fancybox)
		
		if(m=='default' || m==''){ // Default Link & HTML Codes *************
			if(p=='normal' || p==''){ // Normal Form Field
				if(t=='img'){ // Image Link
					if(o=='normal'){ // Normal Popup
						$('#'+f,window.opener.document).val(l);
					}else{
						$('#'+f,window.parent.document).val(l);
						parent.$.fancybox.close();
					}
				}else{ // Document Link
					if(o=='normal'){ // Normal Popup
						$('#'+f,window.opener.document).val(l);
					}else{
						$('#'+f,window.parent.document).val(l);
						parent.$.fancybox.close();
					}
				}
			}else if(p=='tinymce'){ // TinyMCE
				if(t=='img'){ // Image Link
					var link_styler = '<img src="'+ l +'" alt="">';
					if(o=='normal'){ // Normal Popup
						var ed = window.opener.tinyMCE.activeEditor;
						var marker = ed.dom.get(f);
						ed.selection.select(marker, false);
						ed.selection.setContent(link_styler);
					}else{
						var ed = window.parent.tinyMCE.activeEditor;
						var marker = ed.dom.get(f);
						ed.selection.select(marker, false);
						ed.selection.setContent(link_styler);
						parent.$.fancybox.close();
					}
				}else{ // Document Link
					var link_styler = '<a href="'+ l +'">'+ l.replace(/^.*[\\\/]/, '') +'</a>';
					if(o=='normal'){ // Normal Popup
						var ed = window.opener.tinyMCE.activeEditor;
						var marker = ed.dom.get(f);
						ed.selection.select(marker, false);
						ed.selection.setContent(link_styler);
					}else{
						var ed = window.parent.tinyMCE.activeEditor;
						var marker = ed.dom.get(f);
						ed.selection.select(marker, false);
						ed.selection.setContent(link_styler);
						parent.$.fancybox.close();
					}
				}
			}else if(p=='ckeditor'){ // CKEditor (Set by CKEditor fileBrowser Function)
				if(t=='img'){ // Image Link
					var CKEditorFuncNum = 1;
					window.opener.CKEDITOR.tools.callFunction( CKEditorFuncNum, l, '' );
					self.close();
				}else{
					var CKEditorFuncNum = 1;
					window.opener.CKEDITOR.tools.callFunction( CKEditorFuncNum, l, '' );
					self.close();				
				}
			}
		}else{ // Only Links **********************
		
			if(p=='normal' || p==''){ // Normal Form Field
				if(t=='img'){ // Image Link
					if(o=='normal'){ // Normal Popup
						$('#'+f,window.opener.document).val(l);
					}else{
						$('#'+f,window.parent.document).val(l);
						parent.$.fancybox.close();
					}
				}else{ // Document Link
					if(o=='normal'){ // Normal Popup
						$('#'+f,window.opener.document).val(l);
					}else{
						$('#'+f,window.parent.document).val(l);
						parent.$.fancybox.close();
					}
				}
			}else if(p=='tinymce'){ // TinyMCE
				if(t=='img'){ // Image Link
					var link_styler = l;
					if(o=='normal'){ // Normal Popup
						var ed = window.opener.tinyMCE.activeEditor;
						var marker = ed.dom.get(f);
						ed.selection.select(marker, false);
						ed.selection.setContent(link_styler);
					}else{
						var ed = window.parent.tinyMCE.activeEditor;
						var marker = ed.dom.get(f);
						ed.selection.select(marker, false);
						ed.selection.setContent(link_styler);
						parent.$.fancybox.close();
					}
				}else{ // Document Link
					var link_styler = l;
					if(o=='normal'){ // Normal Popup
						var ed = window.opener.tinyMCE.activeEditor;
						var marker = ed.dom.get(f);
						ed.selection.select(marker, false);
						ed.selection.setContent(link_styler);
					}else{
						var ed = window.parent.tinyMCE.activeEditor;
						var marker = ed.dom.get(f);
						ed.selection.select(marker, false);
						ed.selection.setContent(link_styler);
						parent.$.fancybox.close();
					}
				}
			}else if(p=='ckeditor'){ // CKEditor (Set by CKEditor fileBrowser Function)
				if(t=='img'){ // Image Link
					var CKEditorFuncNum = 1;
					window.opener.CKEDITOR.tools.callFunction( CKEditorFuncNum, l, '' );
					self.close();
				}else{
					var CKEditorFuncNum = 1;
					window.opener.CKEDITOR.tools.callFunction( CKEditorFuncNum, l, '' );
					self.close();				
				}
			}
		
		} // end link model
	}