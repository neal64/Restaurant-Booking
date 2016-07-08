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
include_once('classes/Zebra_Pagination.php');
$pg = ((!isset($_GET['pg']) || !is_numeric($_GET['pg'])) ? 1:intval($_GET['pg']));
$pf=((!isset($_GET['pf']) || empty($_GET['pf'])) ? '':trim($_GET['pf']));
$pm=((!isset($_GET['pm']) || empty($_GET['pm'])) ? 'default':trim($_GET['pm']));
$pp=((!isset($_GET['pp']) || empty($_GET['pp'])) ? 'tinymce':trim($_GET['pp']));
$o=((!isset($_GET['o']) || empty($_GET['o'])) ? 'fancybox':trim($_GET['o']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>LeUpload Lethe Newsletter Upload Plugin</title>

<!-- styles -->
<link rel="stylesheet" href="../../bootstrap/dist/css/bootstrap.min.css">
<link href="../../css/footable.core.css" rel="stylesheet" type="text/css">
<link href="../../css/footable.standalone.min.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/leupload.css">
<link href="css/dropzone.css" type="text/css" rel="stylesheet">
<!-- Scripts -->
<script src="../../Scripts/jquery-1.11.1.min.js"></script>
</head>
<body>

<!-- page content -->
<div class="row">
	<div class="container-fluid">
		<div class="col-sm-2 col-md-2"><span class="logo-text">LeUpload <small>v.1.0</small><span>Lethe Newsletter Upload Manager</span></span></div>
		<div class="col-sm-10 col-md-10">
			<div id="upload-container" class="text-right">
				<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#uplMod">
				  <span class="glyphicon glyphicon-cloud-upload"></span> Upload
				</button>
			</div>
		</div><span class="clearfix"></span>
		<hr>
	</div>
</div>

<div class="row">
	<div class="container-fluid">
		<div class="col-md-12">
			<div class="info-bar text-muted text-right">
				<small>
					<span class="label label-info">Max: <?php echo(formatBytes($LEUPLOAD_MAX_UPL));?></span>
					<span class="label label-warning">Allowed: <?php echo(implode(',',$LEUPLOAD_INFO_FILES));?></span>
					<span class="label label-success">Size: <?php echo(formatBytes(LEUPLOAD_STORAGE_SIZE));?></span>
					<span class="label label-danger">Files: <?php echo(LEUPLOAD_STORAGE_FILE_COUNT);?></span>
				</small>
			</div>
		</div>
	</div>
</div>

<!-- FILES -->
<div class="row">
	<div class="container-fluid">
		<div class="col-md-12">
			<h3>Files <small><a href="javascript:;" onclick="refreshPage();"><span class="glyphicon glyphicon-refresh text-success"></span></a></small></h3>

			<table class="footable table">
				<thead>
					<tr>
						<th>File</th>
						<th data-hide="phone">Size</th>
						<th>Type</th>
						<th data-hide="phone,tablet">Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
<?php 
$ignored_childs = array('expimp');
$dir_files = getDirFiles(LEUPLOAD_STORE,1,2,$ignored_childs);
$records_per_page = $LEUPLOAD_PERPAGE_LIST;
$pagination = new Zebra_Pagination();
$pagination->records(count($dir_files));
$pagination->records_per_page($records_per_page);
$pagination->labels('<span class="glyphicon glyphicon-chevron-left"></span>', '<span class="glyphicon glyphicon-chevron-right"></span>');
$dir_files = array_slice(
    $dir_files,
    (($pagination->get_page() - 1) * $records_per_page),
    $records_per_page
);
foreach($dir_files as $k=>$v){
?>
					<tr>
						<td><a href="javascript:;" class="leupload_link" data-leupload-form="<?php echo($pf);?>" data-leupload-link-model="<?php echo($pm);?>" data-leupload-link="<?php echo(set_org_resource_url.'/'.$v['file_name']);?>" data-leupload-file-type="<?php echo((($v['file_type']) ? 'img':'doc'));?>" data-leupload-platform="<?php echo($pp);?>" data-leupload-opener="<?php echo($o);?>"><?php echo($v['file_name']);?></a></td>
						<td><?php echo(formatBytes($v['file_size']));?></td>
						<td><?php echo($v['file_ext']);?></td>
						<td><?php echo(date('d.m.Y H:i:s A',$v['file_date']));?></td>
						<td><a href="javascript:;" class="remfile" data-fn="<?php echo($v['file_name']);?>"><span class="glyphicon glyphicon-remove text-danger"></span></a></td>
					</tr>
<?php }?>
				</tbody>
				<tfoot class="hide-if-no-paging">
					<tr>
						<td colspan="5">
							<div class="pagination pagination-centered"><?php $pagination->render();?></div>
						</td>
					</tr>
				</tfoot>
			</table>
				
			<script type="text/javascript">
				$(document).ready(function(){
					$('.footable').footable();
					
					/* Remove File */
					$(".remfile").click(function(){
						var fn = $(this).data('fn');
						$.ajax({

							url : 'pg.xmlhttp.php?pos=remfile&fn=' + fn,
							type : 'POST',
							success : function(data) {              
								refreshPage();
							},
							error : function(data)
							{
								alert('Error Occured!');
							}
						});
					});
				});
			</script>
			
			
		</div>
	</div>
</div>
<!-- FILES -->

<!-- UPLOAD -->
<div class="modal fade" id="uplMod">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">UPLOAD</h4>
      </div>
      <div class="modal-body">

		<form id="myAwesomeDropzone" action="upload.php" method="post" enctype="multipart/form-data" class="dropzone" data-fsize="<?php echo(formatBytes($LEUPLOAD_MAX_UPL,0,0,1024,0));?>" data-ftypes="<?php echo(implode(',',$LEUPLOAD_MIMES));?>">
		  <input type="file" name="file">
		</form>
	  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- UPLOAD -->

<div class="row">
	<div class="container-fluid">
		<hr>
		<div class="col-md-12">
			<span class="text-muted"><small>LeUpload v.1.0 &copy; 2015. <a href="http://www.artlantis.net/" target="_blank">Artlantis Design Studio</a></small></span>
		</div>
	</div>
</div>
<!-- page content -->
	
<script src="../../bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../../Scripts/footable.min.js"></script>
<script src="../../Scripts/footable.sort.min.js"></script>
<script src="Scripts/dropzone.js"></script>
<script src="Scripts/leupload.js"></script>
</body>
</html>