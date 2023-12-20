<?php

require_once './config.php';
require_once '../func.php';
require_once './map_tool.php';


$rgnsz = 256;
$code  = "09ld371";
$seal  = 20.0;
$mgnf  = 1.0;


/*
if (isset($_GET["rgnsz"])) {
	$rgnsz = $_GET["rgnsz"];
}
if (isset($_GET["code"])) {
	$code = $_GET["code"];
}
*/

if (!ctype_digit($rgnsz)) error_proc("size.");
if (!ctype_alnum($code)) error_proc("file code. no alnum.");
if ($rgnsz<=0)  $rgnsz = 256;

$len = strlen($code);
if ($len!=7) error_proc("file code. incorrect length.");

$code = strtolower($code);
if (!preg_match('/^[0-9][0-9][a-z][a-z][0-9][0-9][1-4]$/', $code)) error_proc("file code. incorrect code.");


$m = make_mapcode($code);

print $m[0]."<br />\n";
print $m[1]."<br />\n";
print $m[2]."<br />\n";
print $m[3]."<br />\n";

$d = read_geo_data($m, $rgnsz, GEO_MAP_XYRATE);
print_geo_data($d, $rgnsz, $seal, $mgnf);

