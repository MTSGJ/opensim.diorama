<?php

require_once './common_tool.php';

//
$maptype = 'srtm3';
if (isset($_GET["type"])) {
	$maptype = $_GET["type"];
}
if (!file_exists($maptype.'/config.php')) exit('ERROR: invalid map type name!');

require_once($maptype.'/config.php');
require_once('./tex_tool.php');
require_once($maptype.'/map_tool.php');
require_once($maptype.'/map_texture.php');

//
$site  = 'google';
//
$rgnsz = 256;       // constant
$code  = '';
$rate  = 1.0;
$textype = 'photo';
$channel = -1;

//
if (isset($_GET['code'])) {
	$code = $_GET['code'];
}
if (isset($_GET['site'])) {
	$site = $_GET['site'];
}
if (isset($_GET['tex'])) {
	$textype = $_GET['tex'];
}
if (isset($_GET['rate'])) {
	$rate = $_GET['rate'];
}
if (isset($_GET['ch'])) {
	$channel = $_GET['ch'];
}

if ($rate<=0.0) $rate  = 1.0;
if ($channel>3) $channel  = -1;

//
if ($channel<0) {
	$wpos = $rgnsz/2.0;
	$hpos = $wpos;
}
else {
	$qrtsz = $rgnsz/4.0;
	if ($channel==0) {
		$wpos = $qrtsz;
		$hpos = $qrtsz;
	} 
	else if ($channel==1) {
		$wpos = $qrtsz*3;
		$hpos = $qrtsz;
	}
	else if ($channel==2) {
		$wpos = $qrtsz;
		$hpos = $qrtsz*3;
	}
	else if ($channel==3) {
		$wpos = $qrtsz*3;
		$hpos = $qrtsz*3;
	}
}

//
$filename = get_texture_file($code, $site, $textype, $rgnsz, $wpos, $hpos, $rate, $channel);

if ($filename!=null) {
	output_texture($filename, TEX_IMG_TYPE);
	if (!USE_TEX_CACHE) unlink($filename);
}

