<?php

define ('GEO_TOP_PATH',  	dirname(__FILE__));
define ('GEO_BASE_NAME', 	basename(GEO_TOP_PATH));

define ('GEO_DATA_PATH',	'/home/apache/gsigo/5m');		// �ǡ����Υǥ��쥯�ȥ�
define ('GEO_DATA_EXT',   	'lem');		// �ǡ����ե�����γ�ĥ��

// MAP DATA
define ('GEO_MAP_WIDTH',  	'400');		// ����Υǡ����β����Υݥ���ȿ�
define ('GEO_MAP_HEIGHT', 	'300');		// ����Υǡ����νĤΥݥ���ȿ�
define ('GEO_SEAMAP_DATA', '-9999');	// �ޥå׾�Ǥγ�����Ӥʤɤ�ɸ��*10

define ('GEO_HEAD_LINE',  	'0');		// �إå��ιԿ����ɤ����Ф���
define ('GEO_DATA_HEAD',  	'10'); 		// ��Ƭ�ǡ����γ��ϰ��֡ʥХ���-1��
define ('GEO_DATA_LEN',   	'5');		// ��ĤΥǡ�����Ĺ��
define ('GEO_DATA_MAG',  	'0.2');		// �⤵��Ĵ����1/5
define ('GEO_MAP_FAC',      '10');      // �ǡ�����ɸ�����Ψ��������Ψ�ǥե��������¸����Ƥ���
define ('GEO_MAP_XYRATE',  	'1.24');	// �ǡ����νĲ��桥

// OpenSim
define ('GEO_SEA_DEEP',    '-10');		// ���ο�����GEO_SEAMAP_DATA ���Ѵ���
define ('GEO_NOMAP_DATA',  '-10');		// �ޥåץǡ����Τʤ���������ɸ��
define ('GEO_SEA_LEVEL',  	'20');		// OpenSim�� sea��٥�Υǥե����


// Map Texture
define ('GOOGLE_MAP_API', 'AIzaSyD42KykjyGCWeRdf0Z9K4-Zpt--AKZP-1w');
define ('YAHOO_MAP_API',  'dj0zaiZpPUgxR3RpS2VmdnhpQyZzPWNvbnN1bWVyc2VjcmV0Jng9ODg-');
