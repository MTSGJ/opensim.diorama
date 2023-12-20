<?php
 
//
// map_tool.php
//


/*
  中心の座標から始点の座標を計算する
*/
function  get_origin_coord($code, $rgnsz, $rate)
{
	$ret = explode_origin_coord($code, $rgnsz, $rate);

	return $ret;
}

