<?php

//配置文件
return [
    'url_common_param'       => true,
    'url_html_suffix'        => '',
    'controller_auto_search' => true,

	'custom_type_arr'        => ['hospital'=> '医院', 'hotel'=> '酒店'],  // 客户类型
	'attachment_lookall_id'  => ['1'],      // 可查看所有附件的账号id
	'allow_login_char_count' => 3,      // 密码含有账号的字符次数
	'get all'                => 'GET ALL',  //获取所有标识
	'picture_type'           => ['jpg', 'jpeg', 'png', 'bmp', 'gif'], // 图片类型
	'video_type'             => ['mp4', 'avi'],  // 视频类型
	'language_type'          => ["chinese"=>"Chinese", "english"=>"English"], // 语言类型
	'resources_type'         => ["video"=>"Video", "image"=>"Image", "url"=>"Url"], // 资源类型
	'lowest_haschild_level'  => '2',     // 最低能含子级的层级
	'had_resource_level'     => [3],     // 能拥有资源的层级
	'welcome_stay_set'       => [1=>'Stay set text1', 2=>'Stay set text2'],  // 欢迎停留设置选项
	'jump_play_set'          => [1=>'Play set text1', 2=>'Play set text2', 3=>'Play set text3', 4=>'Play set text4', 5=>'Play set text5'], //跳转设置播放设置
	'save_set'               => [1=>'Save_set_text1', 2=>'Save_set_text2'],  //保存地址(本地,SD卡)
	'ad_type'                => ['video'=>'Video', 'image'=>'Image', 'word'=>'Word'],  //广告类型
	'repeat_set'             => ['no-repeat'=>'No-repeat', 'everyday'=>'Everyday', 'm-f'=>'Mon through Fri', 'user-defined'=>'User-defined'], //重复设置
	'weekday'                => [1=>'Monday', 2=>'Tuesday', 3=>'Wednesday', 4=>'Thursday', 5=>'Friday', 6=>'Saturday', 7=>'Sunday'],  //周期 周几
	'popup_break_set'        => [1=>'Break_set_text1', 2=>'Break_set_text2'],  //弹窗广告退出设置, 禁止  允许
	'popup_position_set'     => [1=>'Position UL', 2=>'Position UR', 3=>'Position LL', 4=>'Position LR'], //弹窗广告位置
	'app_break_out_to'       => ['no jump'=>'No jump', 'home page'=>'Home page', 'app list'=>'App list', 'jump broadcast'=>'Jump broadcast'],
	'message_push_type'      => ['immediate'=>'Immediate', 'user defined'=>'User Defined'], //消息通知 推送类别 [立即通知,自定义]
	'device usage'           => ['official'=>__('Official'), 'test'=>__('Test')],  //设备用途 [正式设备,测试设备]
	'sleep_image_title'      => ['black'=>'Black', 'blue'=>'Blue'],                //休眠背景图名称
	'app_type_info'          => ['system'=>'System', 'common'=>'Common'],
	'audit_status'           => ['unaudited'=>'Unaudited', 'egis'=>'Egis', 'no egis'=>'No egis']
];