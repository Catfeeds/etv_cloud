<?php

namespace app\admin\validate;

use think\Validate;

class PopupSetting extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'custom_id'     =>  'require|number',
        'ad_type'       =>  'require|in:video,image,word|ad_type_options_judgment',
        'save_set'      =>  'require|in:1,2',
        'repeat_set'    =>  'require|in:no-repeat,everyday,m-f,user-defined|repeat_set_options_judgment',
        'break_set'     =>  'require|in:1,2|stay_time_judgment',
        'no_repeat_date'=>  'date',
        'start_time'    =>  'dateFormat: h:i:s',
        'stay_time'     =>  'number',
	    'position'      =>  'in:0,1,2,3,4',
	    'words_tips'    =>  'max:200',
	    'status'        =>  'require|in:normal,hidden',
	    'image_resource_id'   =>  'number',
		'video_resource_id'   =>  'number',
    ];
    /**
     * 提示消息
     */
    protected $message = [
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['custom_id', 'ad_type', 'save_set', 'repeat_set', 'break_set', 'no_repeat_date',
	        'start_time', 'stay_time', 'position', 'words_tips', 'image_resource_id', 'video_resource_id', 'status'
        ],
        'edit' => ['ad_type', 'save_set', 'repeat_set', 'break_set', 'no_repeat_date',
	        'start_time', 'stay_time', 'position', 'words_tips', 'image_resource_id', 'video_resource_id', 'status'],
    ];

	/**
	 * 停留时间判断
	 */
	public function stay_time_judgment($params, $rule, $data){
		if($data['break_set'] == '2'){
			if(empty($data['stay_time'])){
				return __('Stay time is need');
			}else{
				return true;
			}
		}else{
			return true;
		}
	}

	/**
	 * 重复设置选项判断
	 */
	public function repeat_set_options_judgment($params, $rule, $data){
		if($data['repeat_set'] == 'no-repeat'){
			$no_repeat_date = $data['no_repeat_date'];
			if(empty($no_repeat_date)){
				return __('Time is need');
			}
			$post_time = intval(strtotime($no_repeat_date));
			$now_time = intval(time());
			if($post_time < $now_time){
				return __('Date range is wrong');
			}else{
				return true;
			}
		}elseif($data['repeat_set'] == 'user-defined'){
			$weekday = $data['weekday'];
			if(empty($weekday)){
				return __('Weekday range is wrong');
			}
			$all_weekday = ['1', '2', '3', '4', '5', '6', '7'];
			if(array_diff(explode(",", $weekday), $all_weekday)){
				return __('Weekday range is wrong');
			}else{
				return true;
			}
		}elseif($data['repeat_set'] == 'everyday' || $data['repeat_set'] == 'm-f'){
			if(empty($data['start_time'])){
				return __('Time is need');
			}else{
				return true;
			}
		}else{
			return __('Repeat set option is wrong');
		}

	}

	public function ad_type_options_judgment($params, $rule, $data){
		if($data['ad_type'] == 'video'){
			if(empty($data['video_resource_id'])){
				return __('Resource option is wrong');
			}else{
				return true;
			}
		}elseif($data['ad_type'] == 'image'){
			if(empty($data['image_resource_id'])){
				return __('Resource option is wrong');
			}else{
				return true;
			}
		}else{
			return true;
		}
	}
    
}
