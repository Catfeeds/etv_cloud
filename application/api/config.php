<?php

//配置文件
return [
    'exception_handle'        => '\\app\\api\\library\\ExceptionHandle',
	// 缓存时间  单位s
	'api_cache_time'    =>  [
		'skin'      =>  60,
		'welcome'   =>  60,
		'language'  =>  60,
		'notice'    =>  60,
		'jump'      =>  60,
		'propaganda'=>  60,
		'simplead'  =>  10,

	],
];
