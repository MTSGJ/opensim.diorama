<?php

//
// get_geo_data() の実装
//

function  get_geo_data($code, $rgnsz, $rate)
{
	$m[] = $code;
	$map = read_geo_data($m, $rgnsz/$rate, $rgnsz/$rate);

	return $map;
}

