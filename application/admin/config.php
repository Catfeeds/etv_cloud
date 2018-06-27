<?php

//配置文件
return [
    'url_common_param'       => true,
    'url_html_suffix'        => '',
    'controller_auto_search' => true,

	// 客户类型
	'custom_type_arr'        => ['hospital'=> '医院', 'hotel'=> '酒店'],
	// 控制台,可查看所有客户,设备的组别
	'dashboard_lookall_id'   => ['2'],
	// 可查看所有附件的账号id
	'attachment_lookall_id'  => ['1'],
	// 密码含有账号的字符次数
	'allow_login_char_count' => 3,
	//获取所有标识
	'get all'                => 'GET ALL',
	// 图片类型
	'picture_type'           => ['jpg', 'jpeg', 'png', 'bmp', 'gif'],
	// 视频类型
	'video_type'             => ['mp4', 'avi'],
	// 语言类型
	'language_type'          => ["chinese"=>"Chinese", "english"=>"English"],
	// 资源类型
	'resources_type'         => ["video"=>"Video", "image"=>"Image", "url"=>"Url"],
	// 最低能含子级的层级
	'lowest_haschild_level'  => '2',
	// 能拥有资源的层级
	'had_resource_level'     => [3],
	// 欢迎停留设置选项
	'welcome_stay_set'       => [1=>'Stay set text1', 2=>'Stay set text2'],
	// 跳转设置播放设置
	'jump_play_set'          => [1=>'Play set text1', 2=>'Play set text2', 3=>'Play set text3', 4=>'Play set text4', 5=>'Play set text5'],
	// 保存地址(本地,SD卡)
	'save_set'               => [1=>'Save_set_text1', 2=>'Save_set_text2'],
	// 广告类型 (视频,图片,文字)
	'ad_type'                => ['video'=>'Video', 'image'=>'Image', 'word'=>'Word'],
	// 重复设置(不重复,每天,周几,自定义)
	'repeat_set'             => ['no-repeat'=>'No-repeat', 'everyday'=>'Everyday', 'm-f'=>'Mon through Fri', 'user-defined'=>'User-defined'],
	// 周期 周几
	'weekday'                => [1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=>'Friday', 6=>'Saturday', 7=>'Sunday'],
	// 弹窗广告退出设置, 禁止  允许
	'popup_break_set'        => [1=>'Break_set_text1', 2=>'Break_set_text2'],
	// 弹窗广告位置
	'popup_position_set'     => [1=>'Position UL', 2=>'Position UR', 3=>'Position LL', 4=>'Position LR'],
	// APP定时结束跳转至
	'app_break_out_to'       => ['no jump'=>'No jump', 'home page'=>'Home page', 'app list'=>'App list', 'jump broadcast'=>'Jump broadcast'],
	// 消息通知 推送类别 [立即通知,自定义]
	'message_push_type'      => ['immediate'=>'Immediate', 'user defined'=>'User Defined'],
	// 设备用途 [正式设备,测试设备]
	'device usage'           => ['official'=>__('Official'), 'test'=>__('Test')],
	// 休眠背景图名称
	'sleep_image_title'      => ['black'=>'Black', 'blue'=>'Blue'],
	// APP类型
	'app_type_info'          => ['system'=>'System', 'common'=>'Common'],
	// 审核
	'audit_status'           => ['unaudited'=>'Unaudited', 'egis'=>'Egis', 'no egis'=>'No egis', 'release'=>'Release', 'no release'=> 'No release'],
];