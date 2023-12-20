<?php

//
// get_geo_data() の実装
//

function  get_geo_data($code, $rgnsz, $rate)
{
	$stcrd = get_origin_coord($code, $rgnsz, $rate);		// 始点

	$lonst = floatval($stcrd[0]);
	$latst = floatval($stcrd[1]);

	$latsz = $rgnsz/$rate;
	$lonsz = intval($latsz/$stcrd[2]) + 1;					// サイズ
	$latsz = intval($latsz) + 1;

	$latsz++;
	$lonsz++;
	$params = ' -lat '.$latst.' -lon '.$lonst.' -hsz '.$latsz.' -wsz '.$lonsz.' -dat '.GEO_DATA_PATH;
	exec(GEO_TOP_PATH.'/read_srtm'.$params , $str);
	//
	$map = data_to_array2($str, $lonsz, $latsz);
	$dat = resize_map($map, $rgnsz, $lonsz, $latsz, 0, 0, $rate);

	return $dat;
}

