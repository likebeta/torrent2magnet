<?php
// begin
$verifyToken = md5('unique_salt' . $_POST['timestamp']);
if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	
	// Validate the file type
	$fileTypes = array('torrent');
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		require('lightbenc.php');
		$info = Lightbenc::bdecode_getinfo($tempFile);
		if (isset($info['info_hash'])) {
			success($info['info_hash']);
		}
		else {
			failed();	
		}
	} 
	else {
		failed();
	}
}
// end	
	
function success($info_hash)
{
	$result = array('result'=>1,'url'=>'magnet:?xt=urn:btih:'.strtoupper($info_hash));
	$json = json_encode($result);
	if ($json)
	{
		echo $json;
	}
}

function failed()
{
	$result = array('result'=>0,'url'=>null);
	$json = json_encode($result);
	if ($json)
	{
		echo $json;
	}
}
?>