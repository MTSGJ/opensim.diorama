<?php

//
// OpenSim Diorama System
//

// Parameters

// OpenSim
define ('GEO_SEA_LEVEL',   '20');	// OpenSimの Seaレベルのデフォルト (m)

// Map Texture
define ('GOOGLE_MAP_URL',  'http://maps.googleapis.com/maps/api/staticmap');
define ('GOOGLE_MAP_API',  'AIzaSyD42KykjyGCWeRdf0Z9K4-Zpt--AKZP-1w');

define ('YAHOO_MAP_URL',   'http://map.olp.yahooapis.jp/OpenLocalPlatform/V1/static');
define ('YAHOO_MAP_API',   'dj0zaiZpPUgxR3RpS2VmdnhpQyZzPWNvbnN1bWVyc2VjcmV0Jng9ODg-');

//
define ('USE_TEX_CACHE',   '1');	// if you don't want to use texture cache, please set 0
define ('TEX_IMG_TYPE',    'png');


//
define('PI',      	'3.14159265359');
define('DEG2RAD', 	'0.017453292519943');




////////////////////////////////////////////////////////////////////////////////////////////////

//
// 測地系
//
function  world2jp($deg)
{
	$jpn = array();

	$jpn[0] = $deg[0] + 0.000083049*$deg[0] + 0.000046047*$deg[1] - 0.010041;	// lon
	$jpn[1] = $deg[1] - 0.000017467*$deg[0] + 0.00010696 *$deg[1] - 0.0046020;	// lat

	return $jpn;
}


function  jp2world($deg)
{
	$wld = array();

	$wld[0] = $deg[0] - 0.000083043*$deg[0] - 0.000046038*$deg[1] + 0.010040;	// lon
	$wld[1] = $deg[1] + 0.000017464*$deg[0] - 0.00010695 *$deg[1] + 0.0046017;	// lat

	return $wld;	
}



/**
  データの原点の座標を返す

  $code の形式は 36.0x-139.0 等
*/
function  explode_origin_coord($code, $rgnsz, $rate)
{
	$indx = explode('x', $code);

	$lat  = floatval($indx[0]);
	$lon  = floatval($indx[1]);

	$hpt = $rgnsz/$rate/2.0;
	$hwr = cos($lat*DEG2RAD)*GEO_MAP_XYRATE;
	$wpt = $hpt/$hwr;

	$crd[0] = $lon - $wpt/GEO_LON_NUM;				// 経度始点 
	$crd[1] = $lat + $hpt/GEO_LAT_NUM;				// 緯度始点
	$crd[2] = $hwr;									// 距離のXY比
	$crd[3] = $lon;
	$crd[4] = $lat;

	return $crd;
}


/**
  座標の補正
*/
function  coord_correction($coord, $xs, $ys, $rate)
{
	if ($rate<=0.0) $rate = 1.0;

	$coord[1] -= $ys/$rate/GEO_LAT_NUM;
	$coord[2] = cos($coord[1]*DEG2RAD)*GEO_MAP_XYRATE;
	$coord[0] += $xs/$rate/$coord[2]/GEO_LON_NUM;

	return $coord;
}




////////////////////////////////////////////////////////////////////////////////////////////////

// 0 1 2 3 4 5 6 7 8 9 0 1 2 ...
function  inc_char($char)
{
	$char++;
	if ($char=='10') $char = '0';
	return $char;
}


function  dec_char($char)
{
	$char--;
	if ($char=='-1') $char = '9';
	return $char;
}


function  error_proc($mesg)
{
	exit('ERROR: '.$mesg);
}


