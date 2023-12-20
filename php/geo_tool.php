<?php

//
// テキストデータの読み込み
//
function read_geo_data($mapfiles, $wsize, $hsize)
{
	global $mapdata;

	read_geo_file($mapfiles, 0, $wsize, $hsize);
	if ($wsize>GEO_MAP_WIDTH) {
		read_geo_file($mapfiles, 1, $wsize, $hsize);
	}
	if ($hsize>GEO_MAP_HEIGHT) {
		read_geo_file($mapfiles, 2, $wsize, $hsize);
	}
	if ($wsize>GEO_MAP_WIDTH and $hsize>GEO_MAP_HEIGHT) {
		read_geo_file($mapfiles, 3, $wsize, $hsize);
	}

	return $mapdata;
}


//
// テキストファイルの読み込み
//
function  read_geo_file($mapfiles, $k, $wsize, $hsize)
{
	global $mapdata;

	$filename = GEO_DATA_PATH."/".$mapfiles[$k].".".GEO_DATA_EXT;

	if (is_readable($filename)) {
		$data = file($filename);
		$j = 0;
		foreach ($data as $line) {
			if ($j>=GEO_HEAD_LINE) {		// ヘッダの読み飛ばし
				$height = GEO_MAP_HEIGHT*((int)($k/2)) + $j - GEO_HEAD_LINE;
				if ($height>=$hsize) break;

				for ($i=0; $i<GEO_MAP_WIDTH; $i++) {
					$width = GEO_MAP_WIDTH*($k%2) + $i;
					if ($width>=$wsize) break;
					$mapdata[$height][$width] = substr($line, $i*GEO_DATA_LEN+GEO_DATA_HEAD, GEO_DATA_LEN);
				}
			}
			$j++;
		}
	}
	else {
		// 読み込み失敗
		for ($j=0; $j<GEO_MAP_HEIGHT; $j++) {
			$height = GEO_MAP_HEIGHT*((int)($k/2)) + $j;
			if ($height>=$hsize) break;

			for ($i=0; $i<GEO_MAP_WIDTH; $i++) {
				$width = GEO_MAP_WIDTH*($k%2) + $i;
				if ($width>=$wsize) break;
				$mapdata[$height][$width] = GEO_NOMAP_DATA*GEO_MAP_FAC;
			}
		}
	}
}


/**
  データ形式の変換．2次元配列 [lon][lat] を返す．
*/
function  data_to_array2($data, $wsize, $hsize)
{
	if (is_string($data)) {
		$array_data = split("\n", $data);
	}
	else if (is_array($data)) {
		$array_data = $data;
	}
	else {
		return null;
	}

	$j = 0;
	foreach ($array_data as $line) {
		if ($j>=GEO_HEAD_LINE) {		// ヘッダの読み飛ばし
			$height = $j - GEO_HEAD_LINE;
			if ($height>=$hsize) break;
			for ($i=0; $i<$wsize; $i++) {
				$mapdata[$height][$i] = floatval(substr($line, $i*GEO_DATA_LEN+GEO_DATA_HEAD, GEO_DATA_LEN));
			}
		}
		$j++;
	}
	return $mapdata;
}


//
// size x size のデータを出力
//
function  print_geo_data($d, $size, $seal)
{
	$mgnf = GEO_DATA_MAG;
	//
	for ($j=0; $j<$size; $j++) {
		for ($i=0; $i<$size; $i++) {
			$h = $d[$j][$i];
			if ($h==GEO_NON_DATA) $h = GEO_NOMAP_DATA*$mgnf;
			else $h = $h/GEO_MAP_FAC*$mgnf;
			$h = $h + $seal;
			$r = round($h, 1);
			if ($h>$seal and $r==$seal) $r += 0.1;
			print(' '.$r);
		}		 
		print "\n";
	}
}


//
// 線型補間
//
function  resize_map($map, $rsize, $wsize, $hsize, $dwsz, $dhsz, $rate)
{
	$ws = $wsize/floatval($rsize);  // 倍率
	$hs = $hsize/floatval($rsize);

	if ($rate<=0) $rate = $rsize/$hsize;

	for ($j=0; $j<$rsize; $j++) {
		$fj = $j*$hs + $dhsz;
		$jj = intval($fj);
		$an = $fj - $jj;
		$ar = 1. - $an;
		for ($i=0; $i<$rsize; $i++) {
			$fi = $i*$ws + $dwsz;
			$ii = intval($fi);
			$bn = $fi - $ii;
			$br = 1. - $bn;
			//
			$a = $map[$jj][$ii]	   *$ar*$br;
			$b = $map[$jj][$ii+1]  *$ar*$bn;
			$c = $map[$jj+1][$ii]  *$an*$br;
			$d = $map[$jj+1][$ii+1]*$an*$bn;
			$mapdata[$j][$i] = ($a + $b + $c + $d)*$rate;
		}
	}

	return $mapdata;
}


