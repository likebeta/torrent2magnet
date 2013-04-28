<?php
	include "lightbenc.php";
	$file = "1.torrent";   // 种子文件的路径
	// 可以用以下函数获取种子文件所有信息
	$info = Lightbenc::bdecode_getinfo($file);
//	echo var_dump($info);
//	// 下面是我总结的一些
//	echo $info['info']['name'].'<br />';  //获取种子文件名
//	// 读取Tracker服务器列表。$info['announce-list'][/*服务器列表中的第几个*/][0]
//	echo $info['announce-list'][1][0].'<br />';
//    // 文件信息相关，文件长度，文件路径。$info['info']['files'][第几个文件]['path'][0表示CD1，CD2等，跟第几个文件对应；1表示对应的路径]
//	//echo var_dump($info['info']['files'][0]);
//	echo $info['info']['files'][0]['length'].'<br />';
//	echo $info['info']['files'][0]['path'][0].':'.$info['info']['files'][0]['path'][1].'<br />';
	// 获取BT文件hash值
	echo $info['info_hash'];
?>