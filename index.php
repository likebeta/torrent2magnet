<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BT种子转磁性链接 | 磁性链接转BT种子</title>
<meta name="description" content="Torrent to Magnet online converter">
<meta name="author" content="likebeta">
<script type="text/javascript" src="uploadify/jquery-1.7.2.min.js"></script>
<script src="uploadify/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="uploadify/uploadify.css" />
<style type="text/css">
body {
	font: 13px Arial, Helvetica, Sans-serif;
	padding: 20px 50px;
}
#torrent2magnet,#magnet2torrent
{
	width: 50%;
	float: left;
}
#copyright {
	position:fixed;
	left: 35%;
	bottom: 1%;
	width: 50%;
}
</style>
</head>

<body>
	<div id="torrent2magnet">
		<h1>torrent to magnet</h1>
		<form>
			<div id="queue"></div>
			<input id="file_upload" name="file_upload" type="file" multiple="false" />
		</form>
		<div id="result_left"></div>
	</div>
	<div id="magnet2torrent">
		<h1>magnet to torrent</h1>
		<form method="post" action="magnet2torrent.php">
			<input id="magnet" name="magnet" type="text" />
			<input id="submit" name="submit" type="button" value="submit" />
		</form>
		<div id="result_right"></div>
	</div>
	
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
					var result = document.getElementById('result_left');
					if (response)
					{
						var obj = eval ("(" + data + ")");
						if (obj.result)
						{
							result.innerHTML = '<a href="' + obj.url + '">' + obj.url + '</br>';
						}
						else
						{
							result.innerHTML = 'The file ' + file.name + ' is vailed! ';
						}
					}
					else
					{
						result.innerHTML = 'The file ' + file.name + ' is vailed! ';
					}
				}
			});
		});

		$(document).ready(function(){
			$("#submit").click(function(){
				var magneturl = $("#magnet").attr("value");;
				$.get("magnet2torrent.php", { magnet: encodeURIComponent(magneturl) },function(data){
					var result = document.getElementById('result_right');
					var obj = eval ("(" + data + ")");
					if (obj.result) {
						result.innerHTML = '<a href="' + obj.url + '">' + obj.url + '</br>';
					}
					else {
						result.innerHTML = 'The url ' + magneturl + ' is vailed! ';
					}
				});// get  
			});// click
		});// ready
</script>
<div id="footer">
	<div id="copyright"><p>Copyright &copy; <a href="https://github.com/messycode" target="_blank">github</a>, All Rights Reserved,Host by <a href="https://www.openshift.com" target="_blank">openshift</a>,Design by <a href="http://www.ixxoo.me" target="_blank">likebeta</a></div>
	<div style="clear:both"></p></div>
</div>
</body>
</html>