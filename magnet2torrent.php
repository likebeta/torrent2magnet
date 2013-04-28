<?php
// 哈希方法：BTIH,SHA1,MD5等  加密后的位数不同
// urn: Uniform Resource Name, 统一资源名称
// url：Uniform Resource Locator， 统一资源定位
//$toTorrent = 'http://bt.box.n0808.com/05/A5/05153F611B337A378F73F0D32D2C16D362D06BA5.torrent';
//$newString = 'magnet:?xt=urn:sha1:YNCKHTQCWBTRNJIV4WNAE52SJUQCZO5C&dn=TheCroods.avi';

$newString = $_GET['magnet'];
$magnetHead = substr($newString, 0 ,8);
if($magnetHead !== 'magnet:?') {
	convertFalse();	// convert failed
	return;
	//...
}
$posOfxt = strpos($newString, 'xt');
if ($posOfxt === false) {
	convertFalse();	// convert failed
	return;
	//...
}
$posOfand = strpos($newString, '&', $posOfxt);
if ($posOfand === false) // not find '&' after 'xt'
{
	$posOfmaohao = strrpos($newString, ':', $posOfxt);
	if ($posOfmaohao === false){
		convertFalse();
		return;
	}
	$hashEncode = substr($newString, $posOfmaohao+1); 
	$url = toTorrent($hashEncode);
	convertOk($url);
	return;
	// ...
}else {
	$offset = strlen($newString) - ($posOfand+1);
	$offset = 0 - $offset;
	$posOfmaohao = strrpos($newString, ':', $offset);
	if ($posOfmaohao === false){
		convertFalse();
		return;
	}
	$hashLen = $posOfand - $posOfmaohao - 1;
	$hashEncode = substr($newString, $posOfmaohao+1, $hashLen);
	$url = toTorrent($hashEncode);
	convertOk($url);
	return;
	// ...
}

function toTorrent(&$hashEncode)
{
	$hashHead = substr($hashEncode, 0, 2);
	$hashTail = substr($hashEncode, -2);
	$forTorrent = 'http://bt.box.n0808.com/'.$hashHead.'/'.$hashTail.'/'.$hashEncode.'.torrent';
//	echo "$forTorrent<hr/>"; 
	return $forTorrent;
}

function convertOk(&$url){
	$arr = array('result'=>1, 'url'=>$url);
	$json_return = json_encode($arr);
	echo $json_return;
}

function convertFalse(){
	$arr = array('result'=>'0', 'url'=>null);
	$json_return = json_encode($arr);
	echo $json_return;
}

?>   
<strong>Now is good.</strong>

