<?php

require_once './common_tool.php';

//
$maptype = 'srtm3';
if (isset($_GET["type"])) {
	$maptype = $_GET["type"];
}
if (!file_exists($maptype.'/config.php')) exit('ERROR: invalid map type name!');

//
require_once $maptype.'/config.php';
require_once './geo_tool.php';
require_once $maptype.'/map_tool.php';
require_once $maptype.'/map_height.php';

//
$rgnsz = 256;		// constant
$code  = '';
$rate  = 1.0;
$seal  = GEO_SEA_LEVEL;

//
if (isset($_GET['code'])) {
	$code = $_GET['code'];
}
if (isset($_GET['sea'])) {
	$seal = $_GET['sea'];
}
if (isset($_GET['rate'])) {
	$rate = $_GET['rate'];
}

if (!ctype_digit($seal))  error_proc('sea level.');
if (!is_numeric ($rate))  error_proc('map ratio.');
if ($rate<=0.0) $rate  = 1.0;

//
$data = get_geo_data($code, $rgnsz, $rate);
print_geo_data($data, $rgnsz, $seal);

