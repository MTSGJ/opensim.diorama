<?php

define ('GEO_TOP_PATH',  	dirname(__FILE__));
define ('GEO_BASE_NAME', 	basename(GEO_TOP_PATH));

define ('GEO_DATA_PATH',	'/home/apache/gsigo/5m');		// データのディレクトリ
define ('GEO_DATA_EXT',   	'lem');		// データファイルの拡張子

// MAP DATA
define ('GEO_MAP_WIDTH',  	'400');		// 一区画のデータの横幅のポイント数
define ('GEO_MAP_HEIGHT', 	'300');		// 一区画のデータの縦のポイント数
define ('GEO_SEAMAP_DATA', '-9999');	// マップ上での海や川，池などの標高*10

define ('GEO_HEAD_LINE',  	'0');		// ヘッダの行数．読み飛ばす．
define ('GEO_DATA_HEAD',  	'10'); 		// 先頭データの開始位置（バイト-1）
define ('GEO_DATA_LEN',   	'5');		// 一個のデータの長さ
define ('GEO_DATA_MAG',  	'0.2');		// 高さの調整．1/5
define ('GEO_MAP_FAC',      '10');      // データの標高の倍率．この倍率でファイルに保存されている
define ('GEO_MAP_XYRATE',  	'1.24');	// データの縦横比．

// OpenSim
define ('GEO_SEA_DEEP',    '-10');		// 海の深さ．GEO_SEAMAP_DATA の変換先
define ('GEO_NOMAP_DATA',  '-10');		// マップデータのない場所の代替標高
define ('GEO_SEA_LEVEL',  	'20');		// OpenSimの seaレベルのデフォルト


// Map Texture
define ('GOOGLE_MAP_API', 'AIzaSyD42KykjyGCWeRdf0Z9K4-Zpt--AKZP-1w');
define ('YAHOO_MAP_API',  'dj0zaiZpPUgxR3RpS2VmdnhpQyZzPWNvbnN1bWVyc2VjcmV0Jng9ODg-');
