<?php

require_once './config.php';
require_once '../func.php';
require_once './map_tool.php';


$rgnsz = 256;
$code  = "";
$mgnf  = 1.0;
$rate  = 0.0;
$seal  = GEO_SEA_LEVEL;


if (isset($_GET["rgnsz"])) {
	$rgnsz = $_GET["rgnsz"];
}
if (isset($_GET["code"])) {
	$code = $_GET["code"];
}
if (isset($_GET["mag"])) {
	$mgnf = $_GET["mag"];
}
if (isset($_GET["sea"])) {
	$seal = $_GET["sea"];
}
if (isset($_GET["rate"])) {
	$rate = $_GET["rate"];
}

if (!ctype_digit($rgnsz)) error_proc("region size.");
if (!ctype_digit($seal))  error_proc("sea level.");
if (!is_numeric ($mgnf))  error_proc("magnification.");
if (!is_numeric ($rate))  error_proc("X Y rate.");
if (!ctype_alnum($code))  error_proc("file code. no alnum.");
if ($rgnsz<=0)  $rgnsz = 256;
if ($mgnf<=0.0) $mgnf  = 1.0;         
if ($rate<=0.0) $rate  = GEO_MAP_XYRATE;

$len = strlen($code);
if ($len!=7) error_proc("file code. incorrect length.");

$code = strtolower($code);
if (!preg_match('/^[0-9][0-9][a-z][a-z][0-9][0-9][1-4]$/', $code)) error_proc("file code. incorrect code.");


$m = make_mapcode($code);
if (!isset($m)) error_proc("make_mapcode.");

$d = read_geo_data($m, $rgnsz, $rate);
print_geo_data($d, $rgnsz, $seal, $mgnf, $rate);

