<?php

//
// get_geo_data() の実装
//

function  get_geo_data($code, $rgnsz, $rate)
{
	$coord = get_origin_coord($code, $rgnsz, $rate);	// 始点の座標（緯度経度）
	$mcode = get_mapcode($coord);						// 始点を含むマップのコード
	$stcrd = get_origin_coord($mcode, $rgnsz, $rate);	// マップのコードの始点の座標（緯度経度）

	$dwsz  = intval(($coord[0] - $stcrd[0])*GEO_LON_NUM);
	$dhsz  = intval(($stcrd[1] - $coord[1])*GEO_LAT_NUM);

	$latsz = $rgnsz/$rate;
	$lonsz = intval($latsz/$coord[2]) + 1;
	$latsz = intval($latsz) + 1;

	$files = get_mapfiles($mcode);
	if (!isset($files)) error_proc('get_mapfiles.');
	
	$map = read_geo_data($files, $dwsz+$lonsz+1, $dhsz+$latsz+1);
	$dat = resize_map($map, $rgnsz, $lonsz, $latsz, $dwsz, $dhsz, $rate);

	return $dat;
}



//////////////////////////////////////////////////////////////////

//
// 50mメッシュの隣接ファイル名の生成．４個生成する．
//
function  get_mapfiles($stmap)
{
	if (!isset($stmap)) return;

	$len = strlen($stmap);
	$stmap = substr($stmap, $len-6, 6);
	if (!preg_match('/^[1-9][0-9][1-9][0-9][0-7][0-7]$/', $stmap)) return;

	for ($i=0; $i<4; $i++) $mapcode[$i] = $stmap;

	$mapcode[1][5] = inc_char($mapcode[1][5]);
	$mapcode[2][4] = dec_char($mapcode[2][4]);
	$mapcode[3][5] = inc_char($mapcode[3][5]);
	$mapcode[3][4] = dec_char($mapcode[3][4]);
 
	if ($mapcode[1][5]=='8') {
		$mapcode[1][5] = '0';
		$num = substr($mapcode[1], 2, 2);
		$num = $num + 1;
		$mapcode[1] = substr($mapcode[1], 0, 2).$num.substr($mapcode[1], 4, 2);
	}
	if ($mapcode[2][4]=='9') {
		$mapcode[2][4] = '7';
		$num = substr($mapcode[2], 0, 2);
		$num = $num - 1;
		$mapcode[2] = $num.substr($mapcode[2], 2, 4);
	}
	if ($mapcode[3][5]=='8') {
		$mapcode[3][5] = '0';
		$num = substr($mapcode[3], 2, 2);
		$num = $num + 1;
		$mapcode[3] = substr($mapcode[3], 0, 2).$num.substr($mapcode[3], 4, 2);
	}
	if ($mapcode[3][4]=='9') {
		$mapcode[3][4] = '7';
		$num = substr($mapcode[3], 0, 2);
		$num = $num - 1;
		$mapcode[3] = $num.substr($mapcode[3], 2, 4);
	}

	for ($i=0; $i<4; $i++) $mapcode[$i] = substr($mapcode[$i], 0, 4)."/".$mapcode[$i];

	return $mapcode;
}

