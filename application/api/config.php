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
		'column'    =>  60,
		'resource'  =>  5,
		'popup'     =>  60,

		'device_order'      =>  30,

	],

	'audit_group'   =>  3,          // 审核发布账号所在组
	'audit_failure_times'   =>  5, //审核发布账号登录错误次数上限
	'audit_login_time_out'  =>  7200,   //审核发布账号登录超时时间,单位秒
	// 审核或者发布操作的表
	'module_welcome'        =>  ['release_type'=>'welcome_custom', 'egis_type'=>'welcome_resource'],
	'module_jump'           =>  ['release_type'=>'jump_custom', 'egis_type'=>'jump_resource'],
	'module_propaganda'     =>  ['release_type'=>'propaganda_custom', 'egis_type'=>'propaganda_resource'],
	'module_popup'          =>  ['release_type'=>'popup_setting', 'egis_type'=>'popup_resource'],
	'module_simplead'       =>  ['release_type'=>'simplead_custom', 'egis_type'=>'simplead_resource'],
];
