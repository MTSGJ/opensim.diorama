<?php

define ('GEO_TOP_PATH',  	dirname(__FILE__));
define ('GEO_BASE_NAME', 	basename(GEO_TOP_PATH));


define ('GEO_DATA_PATH',	'/home/apache/gsigo/50m');		// データのディレクトリ
define ('GEO_DATA_EXT',   	'mem');		// データファイルの拡張子

define ('GEO_MAP_WIDTH',  	'200');		// 一区画のデータの横幅のポイント数
define ('GEO_MAP_HEIGHT', 	'200');		// 一区画のデータの縦のポイント数
define ('GEO_LON_NUM',  	'1600');	// 経度１度のデータ数
define ('GEO_LAT_NUM', 		'2400');	// 緯度１度のデータ数
define ('GEO_NON_DATA', '   -9999');	// 有効なデータの無い場所．JP50mの場合は海や湖，池，川．

// MAP DATA
define ('GEO_HEAD_LINE',  	'1');		// ヘッダの行数．読み飛ばす．
define ('GEO_DATA_HEAD',  	'9'); 		// レコードの先頭データのスキップ数
define ('GEO_DATA_LEN',   	'5');		// 一個のデータの長さ
define ('GEO_DATA_MAG',  	'0.0216');	// 高さの調整 (dot/m)．緯度方向 1/(10000000m/90/60/60*1.5)  
define ('GEO_MAP_FAC',  	'10');		// データの標高の倍率．この倍率でファイルに保存されている．
define ('GEO_MAP_XYRATE',  	'1.5');		// データの縦横比 (lon/xdot)/(lat/ydot)．2.25s/1.5s
define ('GEO_NOMAP_DATA',  '-40');      // マップデータのない場所(GEO_NON_DATA)の代替標高 (m)
