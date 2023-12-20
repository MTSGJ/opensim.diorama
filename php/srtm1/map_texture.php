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
		$zoom  = 13;
		$lata  = abs($crd[4]);
		$lata2 = $lata*$lata;
		$mrate = 0.000000045004006*$lata2*$lata2 - 0.000001914071869*$lata*$lata2 + 0.000121932632144*$lata2 + 0.00036602047798*$lata + 0.642877430553033;
		$zoom++;
		$mrate *= 2;
		//
		$filename = save_texture_data_google($wld, $textype, $rate, $ch, $zoom, $mrate);
	}

	return $filename;
}

