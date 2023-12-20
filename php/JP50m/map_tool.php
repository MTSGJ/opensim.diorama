<?php

//
// map_tool.php
//


/* 
  座標入力の場合は（N36.0xE148.2など），座標を中心点と見なしてデータの始点の座標を計算する
*/
function  get_origin_coord($code, $rgnsz, $rate)
{
	$deg = array();

	// マップコード
	if (strpos($code, 'x')===false) {
		$la = intval(substr($code, 0, 2));
		$ln = intval(substr($code, 2, 2));
		$hh = intval(substr($code, 4, 1));
		$ww = intval(substr($code, 5, 1));
		
		$deg[0] = $ln + 100. + $ww/8.;					// 経度
		$deg[1] = $la/1.5	+ $hh/12. + 0.0833333;		// 緯度  + 2/3/8
		$deg[2] = cos($deg[1]*DEG2RAD)*GEO_MAP_XYRATE;	// 始点での距離のXY比 
		$deg[3] = 'E';
		$deg[4] = 'N';
	}
	// 緯度経度
	else {
		//
		$deg = explode_origin_coord($code, $rgnsz, $rate);
	}
	
	return $deg;
}


function  get_mapcode($coord)
{
	$lon  = $coord[0];
	$lat  = $coord[1];
	
	$latf = $lat*1.5;
	$lats = intval($latf);
	$lons = intval($lon);

	$lat2 = intval(($latf-$lats)*8);
	$lon2 = intval(($lon -$lons)*8);
	$lons = $lons - 100;

	$code = strval($lats*10000 + $lons*100 + $lat2*10 + $lon2);

	return $code;
}

