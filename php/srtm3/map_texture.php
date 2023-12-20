<?php

//
// get_texture_file() の実装
//

function  get_texture_file($code, $site, $textype, $rgnsz, $wpos, $hpos, $rate, $ch)
{
	$filename = null;

	$crd = get_origin_coord($code, $rgnsz, $rate);
	$wld = coord_correction($crd, $wpos, $hpos, $rate);

	if ($site=='google') {	
		$zoom  = 12;
		$lata  = abs($crd[4]);
		$lata2 = $lata*$lata;
		$mrate = 0.000000051663965*$lata2*$lata2 - 0.000001763143345*$lata*$lata2 + 0.000176091372830*$lata2 - 0.000014009976697*$lata + 0.967283446967423;
		//
		$filename = save_texture_data_google($wld, $textype, $rate, $ch, $zoom, $mrate);
	}

	return $filename;
}


