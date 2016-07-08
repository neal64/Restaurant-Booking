// Custom Settings
var leupload_style = "background: #feccb1; background: -moz-linear-gradient(top,  #feccb1 0%, #f17432 50%, #ea5507 51%, #fb955e 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#feccb1), color-stop(50%,#f17432), color-stop(51%,#ea5507), color-stop(100%,#fb955e)); background: -webkit-linear-gradient(top,  #feccb1 0%,#f17432 50%,#ea5507 51%,#fb955e 100%); background: -o-linear-gradient(top,  #feccb1 0%,#f17432 50%,#ea5507 51%,#fb955e 100%); background: -ms-linear-gradient(top,  #feccb1 0%,#f17432 50%,#ea5507 51%,#fb955e 100%); background: linear-gradient(to bottom,  #feccb1 0%,#f17432 50%,#ea5507 51%,#fb955e 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#feccb1', endColorstr='#fb955e',GradientType=0 ); ";
var customButPAN = false;
var miniPanBut = '';
if(miniPAN){miniPanBut=' | minipan';}
var windowW = $(window).width();
var loremIpsum = true;
var loremContent = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum felis eros, fermentum vel dolor fermentum, dictum rutrum lectus. Suspendisse iaculis augue metus. Donec varius, nulla at pretium ornare, quam lorem mollis libero, eu lacinia nibh arcu eget nulla. Mauris tincidunt nunc diam. Phasellus sagittis nulla quis pulvinar congue. Donec viverra malesuada est, ut luctus augue dictum ac. Nam nec viverra ipsum. Phasellus hendrerit consequat massa, et bibendum augue adipiscing sed. Maecenas aliquet, ligula at feugiat luctus, erat quam dictum sapien, at aliquam risus dui non lacus. Nullam eget nibh cursus, bibendum mi quis, porta dolor.</p>  <p>Donec accumsan felis eget cursus pulvinar. Curabitur tincidunt bibendum dolor, nec luctus lectus. Nunc eget nibh neque. Etiam adipiscing sapien at velit fermentum, et pretium ante sollicitudin. Proin sollicitudin at justo eget blandit. Cras hendrerit posuere dui. Aenean vitae turpis at mauris pharetra sollicitudin at a odio. Praesent eleifend venenatis justo. Integer ornare gravida malesuada. Proin in convallis felis, sed tincidunt mi. Pellentesque felis eros, aliquam nec volutpat quis, pellentesque sed purus.</p>  <p>Nullam libero orci, gravida et facilisis vel, cursus eu enim. In luctus pulvinar tellus vel tincidunt. Donec non vulputate est, vitae scelerisque urna. Sed placerat, metus a faucibus rhoncus, enim elit porttitor est, at fermentum velit dolor id turpis. Proin vel eleifend purus. Donec sed facilisis tellus, at auctor sem. Nunc auctor pharetra eleifend. Morbi consequat elementum felis, placerat pharetra orci. Praesent blandit metus tellus, sed placerat nibh dignissim in. Curabitur id magna vel lacus pretium dignissim venenatis eu risus.</p>  <p>Ut in nibh et massa ullamcorper placerat. Sed laoreet eros non turpis tincidunt, eu ultrices diam venenatis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer id augue vitae metus consectetur porttitor in sed nisi. Sed sodales imperdiet orci, vel condimentum eros pellentesque vestibulum. Suspendisse ut sem ante. In justo sem, luctus eget elit id, sollicitudin egestas dolor. Ut sem nisl, vestibulum nec commodo id, accumsan a enim. Sed mi risus, sollicitudin ut imperdiet quis, eleifend sed elit. Maecenas cursus urna id sapien pretium, vel suscipit arcu pulvinar. Duis eget posuere nulla. Nam gravida interdum tempor. Vestibulum sit amet porta ipsum.</p>  <p>Etiam consectetur bibendum feugiat. Nunc pretium odio vestibulum ligula tincidunt fringilla. Nunc congue odio in hendrerit feugiat. Quisque eu congue erat, id sagittis leo. Maecenas ac nibh at nisl hendrerit varius in varius tellus. Duis euismod orci nunc, a auctor nunc posuere a. Mauris eu malesuada erat, ut suscipit lectus. Ut consequat mauris a justo eleifend ornare. </p>';


	var tlbar1 = 'formatselect | fontselect | fontsizeselect | forecolor backcolor | clear';
	var tlbar2 = 'bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote';
	var tlbar3 = 'link unlink image | searchreplace charmap hr | table | preview fullscreen code lorem' + miniPanBut;

/* Custom Formats */

tinymce.init({
	selector:'.mceEditor',
	theme: 'modern',
	language:customMCEchar,
	relative_urls: false,
	remove_script_host: false,
	menubar:false,
	invalid_elements : "script",
	plugins: 'advlist,textcolor,link,lists,image,charmap,code,table,emoticons,fullpage,fullscreen,importcss,autolink,insertdatetime,contextmenu,visualblocks,preview,searchreplace,autoresize,hr,colorpicker',
	toolbar1: tlbar1+' | '+tlbar2,
	//toolbar2: ,
	toolbar2: tlbar3,
	entity_encoding : "raw",
	autoresize_min_height : '300',
	autoresize_min_width  : '200',
	autoresize_max_height : '400',
	//content_css: "Scripts/tinymce/css/lethe.conf.css",
	
	
//this is how you will get your custom menu like in the above image
	
	    setup : function(ed) {
					
			if(miniPAN){
				// Add miniPAN Button
				ed.addButton('minipan', {
				icon: 'mce-ico mce-i-browse',
				title : 'LeUpload',
				style: leupload_style,
				onclick : function(e) {
										// Fancybox Example
										$.fancybox({
											autoSize : true,
											type     : 'iframe',
											href     : 'plugins/LeUpload/index.php?pf='+ ed.id +'&pm=default&pp=tinymce&o=fancybox',
											height   : 600,
											width    : 900
										});
										// ******					
					},
				});
			}
			
			if(loremIpsum){
				// Add Lorem Ipsum Button
				ed.addButton('lorem', {
				icon: '',
				title : 'Lorem Ipsum',
				text  : 'ipsum',
				onclick : function(e) {
										ed.setContent(loremContent);
										// ******					
					},
				});
			}

			
    }
	


});