<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>BT种子转磁性链接 | 磁性链接转BT种子</title>
	<meta name="description" content="Torrent to Magnet online converter">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script src="//cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
	<script src="//cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
	<script src="//cdn.jsdelivr.net/gh/cdnjs/cdnjs/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
	<link href="//cdn.jsdelivr.net/gh/cdnjs/cdnjs/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<link href="//cdn.jsdelivr.net/gh/cdnjs/cdnjs/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen" />
	<link href="asset/base.css" rel="stylesheet" media="screen" />
	<script src="asset/uploadify/jquery.uploadifive.min.js" type="text/javascript"></script>
	<link href="asset/uploadify/uploadifive.css" rel="stylesheet" type="text/css" />
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
								<input id="file_upload" name="file_upload" type="file" multiple="false" />
								<div id="queue"></div>
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
	$('#file_upload').uploadifive({
		'multi': false,
		'formData'         : {
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
		},
		'queueID'          : 'queue',
		'uploadScript'     : 'torrent2magnet.php',
		'onUploadComplete' : function(file, data, response) {
			var result = $('#torrent2magnet .info');
			var strhtml = 'The file ' + file.name + ' is vailed! ';
			var obj = eval ("(" + data + ")");
			if (obj.result)
			{
				strhtml = '<a href="' + obj.url + '" target="_blank">' + obj.url;
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
				strhtml = '<a href="' + obj.url + '">' + obj.url;
			}
			result.html('<div class="alert"><button type="button" class="close" data-dismiss="alert">×</button>' + strhtml + '</div>');
		});
	});
});
</script>
</body>
</html>
