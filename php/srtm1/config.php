<?php

define ('GEO_TOP_PATH',  	dirname(__FILE__));
define ('GEO_BASE_NAME', 	basename(GEO_TOP_PATH));


define ('GEO_DATA_PATH',	'/Data/SRTM1');		// データのディレクトリ

define ('GEO_MAP_WIDTH',  	'3600');	// 一区画のデータの横幅のポイント数  3600
define ('GEO_MAP_HEIGHT', 	'3600');	// 一区画のデータの縦のポイント数    3600
define ('GEO_LON_NUM',  	'3600');	// 経度１度のデータ数  3600
define ('GEO_LAT_NUM', 		'3600');	// 緯度１度のデータ数  3600
define ('GEO_SEAMAP_DATA',  '-32768'); 	// 有効なデータの無い場所．欠損データ．

// MAP DATA
define ('GEO_HEAD_LINE',  	'0');		// ヘッダの行数．読み飛ばす．
define ('GEO_DATA_HEAD',  	'0'); 		// レコードの先頭データのスキップ数.
define ('GEO_DATA_LEN',   	'8');		// 一個のデータの長さ see read_srtm.c
define ('GEO_DATA_MAG',  	'0.0324');	// 高さの調整．緯度方向 1/(10000000m/90/60/60)
define ('GEO_MAP_FAC',  	'1');		// データの標高の倍率．この倍率でファイルに保存されている．
define ('GEO_MAP_XYRATE',  	'1.0');		// データの縦横比．(lon/xdot)/(lat/ydot)．1s/1s
define ('GEO_NOMAP_DATA',  '-40');      // マップデータのない場所(GEO_NON_DATA)の代替標高 (m)
