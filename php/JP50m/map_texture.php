<?php

//
// get_texture_file() の実装
//

function  get_texture_file($code, $site, $textype, $rgnsz, $xpos, $ypos, $rate, $ch)
{
	$filename = null;

	$crd = get_origin_coord($code, $rgnsz, $rate);
	$jpn = coord_correction($crd, $xpos, $ypos, $rate);
	$wld = jp2world($jpn);

	if ($site=='google') {
		//$zoom  = 12;
		//$mrate = 0.6;
		$zoom  = 13;
		$mrate = 1.2;
		//
		$filename = save_texture_data_google($wld, $textype, $rate, $ch, $zoom, $mrate);
	}
	else if ($site=='yahoo') {
    	//$zoom  = 14;
    	//$msize = 640;
    	$zoom  = 15;
    	$msize = 1280;
		//
    	$filename = save_texture_data_yahoo($wld, $textype, $rate, $ch, $zoom, $msize);
	}

	return $filename;
}

