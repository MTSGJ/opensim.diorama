<?php

//
function  output_texture($filename, $type)
{
	if (file_exists($filename)) {
		header('Content-type: image/'.$type);
		readfile($filename);
	}
}


function  download_texture($filenm, $mapurl, $mapsize, $maprate)
{
	$diff = (int)($mapsize*(1.0-$maprate)/2);
	$rtsz = (int)($mapsize - 2*$diff);

	$imgdata = file_get_contents($mapurl);	  // chek allow_url_fopen in php.ini 
	if ($imgdata!==false) {
		$orgdata = ImageCreateFromString($imgdata);
		$outdata = ImageCreateTrueColor($rtsz, $rtsz);
		ImageCopyResampled($outdata, $orgdata, 0, 0, $diff, $diff, $rtsz, $rtsz, $rtsz, $rtsz);
		ImagePNG($outdata, $filenm);
		ImageDestroy($orgdata);
		ImageDestroy($outdata);
	}
	//print_r($response_header);
}



function  save_texture_data_google($crd, $type, $rate, $ch, $zoom, $mrate)
{
	if      ($type=='photo')  $type = 'satellite';
	else if ($type=='map')    $type = 'roadmap';
	else if ($type=='hybrid') $type = 'hybrid';
	else $type = 'roadmap';

	//
	$mrate /= $rate;
	if ($ch>=0 and $ch<4) {
		$mrate /= 2.0;
	}

	$msize = 1280;
	$scale = 2;
	$url   = GOOGLE_MAP_URL;
	$api   = GOOGLE_MAP_API;
	$cfn   = GEO_TOP_PATH.'/../cache/google';

	$crd[0] = round($crd[0], 9);
	$crd[1] = round($crd[1], 9);
	$filenm = $cfn.'_'.$crd[1].'_'.$crd[0].'_size_'.$msize.'_zoom_'.$zoom.'_mrate_'.$mrate.'_type_'.$type;

	if (!file_exists($filenm) or !USE_TEX_CACHE) {
		$mapurl = $url.'?center='.$crd[1].','.$crd[0].'&size='.$msize.'x'.$msize.'&scale='.$scale.'&zoom='.$zoom.'&maptype='.$type.'&key='.$api;
		download_texture($filenm, $mapurl, $msize, $mrate);
	}

	return $filenm;
}



function  save_texture_data_yahoo($crd, $type, $rate, $ch, $zoom, $msize)
{
	if      ($type=='photo')   $type = 'photo';
	else if ($type=='roadmap') $type = 'map';
	else if ($type=='hybrid')  $type = 'hybrid';
	else $type = 'map';

	//
	if ($ch>=0 and $ch<4) {
		$mrate = 1.2/$rate;
		$mrate /= 2.0*1.01;   // 1.01 補正ファクター
	}
	else {
		$mrate = 1.0/$rate;
		$msize *= 1.2;
	}

	//
	$url   = YAHOO_MAP_URL.'?scalebar=off';
	$api   = YAHOO_MAP_API;
	$cfn   = GEO_TOP_PATH.'/../cache/yahoo';

	$crd[0] = round($crd[0], 9);
	$crd[1] = round($crd[1], 9);
	$filenm = $cfn.'_'.$crd[1].'_'.$crd[0].'_size_'.$msize.'_zoom_'.$zoom.'_mrate_'.$mrate.'_type_'.$type;

	if (!file_exists($filenm) or !USE_TEX_CACHE) {
		$mapurl = $url.'&lat='.$crd[1].'&lon='.$crd[0].'&width='.$msize.'&height='.$msize.'&z='.$zoom.'&mode='.$type.'&appid='.$api;
		download_texture($filenm, $mapurl, $msize, $mrate);
	}

	return $filenm;
}


