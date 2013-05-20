<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="likebeta" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>修改money</title>
	<!--[if lt IE 9]>
	<script src="http://lib.ixxoo.me/html5/html5.js" ></script>
	<![endif]-->
	<script src="http://lib.ixxoo.me/jquery/1.9.1/jquery.min.js"></script>
	<script src="http://lib.ixxoo.me/bootstrap/2.3.1/js/bootstrap.min.js"></script>
	<script src="http://lib.ixxoo.me/html5/html5shiv.js"></script>
	<link href="http://lib.ixxoo.me/bootstrap/2.3.1/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<link href="http://lib.ixxoo.me/bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen" />
	<link href="base.css" rel="stylesheet" media="screen" />
	<script src="uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
	<link href="uploadify/uploadify.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include('header.php');?>

<div id="body">
<div class="container">
	<div id="container">
		<div class="row">
			<div class="span8 offset2">
				<div class="tabbable">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#torrent2magnet" data-toggle="tab">种子转磁链</a></li>
						<li><a href="#magnet2torrent" data-toggle="tab">磁链转种子</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="torrent2magnet">
							<form>
								<div id="queue"></div>
								<input id="file_upload" name="file_upload" type="file" multiple="false" />
							</form>
							<div class="info"></div>
						</div>
						<div class="tab-pane" id="magnet2torrent">
							<form class="form-search">
								<input type="text" id="magnet" name="magnet" class="span6" placeholder="type magnet link here" /> 
								<input type="button" class="btn btn-primary" id="submit" name="submit" value="submit" />
							</form>
							<div  class="info"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php include('footer.php');?>
<script type="text/javascript">
<?php $timestamp = time();?>
$(function() {
	$('#file_upload').uploadify({
		'formData'  : {
		'timestamp' : '<?php echo $timestamp;?>',
		'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
		},
		'multi'    : false,
		'swf'      : 'uploadify/uploadify.swf',
		'uploader' : 'torrent2magnet.php',
		'onUploadSuccess' : function(file, data, response) {
			var result = $('#torrent2magnet .info');
			var strhtml = 'The file ' + file.name + ' is vailed! ';
			if (response)
			{
				var obj = eval ("(" + data + ")");
				if (obj.result)
				{
					strhtml = '<a href="' + obj.url + '">' + obj.url + '</br>';
				}
			}
			result.html('<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button>' + strhtml + '</div>');
		}
	});
});

$(document).ready(function(){
	$("#submit").click(function(){
		var magneturl = $("#magnet")[0].value;
		$.get("magnet2torrent.php", { magnet: encodeURIComponent(magneturl) },function(data){
			var result = $('#magnet2torrent .info');
			var obj = eval ("(" + data + ")");
			var strhtml = 'The url ' + magneturl + ' is vailed! ';
			if (obj.result) {
				strhtml = '<a href="' + obj.url + '">' + obj.url + '</br>';
			}
			result.html('<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button>' + strhtml + '</div>');
		});// get  
	});// click
});// ready
</script>
</body>
</html>