<?php

namespace app\admin\validate;

use think\Config;
use think\Validate;

class TimingAppSetting extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
    	'custom_id'     =>  'require|number',
	    'title'         =>  'require|max:40',
	    'data_params'   =>  'max:256',
	    'repeat_set'    =>  'require|repeat_set_options_judgment',
	    'weekday'       =>  'require',
	    'start_time'    =>  'require|dateFormat:H:i:s',
	    'end_time'      =>  'require|dateFormat:H:i:s',
	    'no_repeat_date'=>  'dateFormat:Y-m-d',
	    'out_to'        =>  'require|out_to_options_judgment',
	    'status'        =>  'in:hidden,normal',
	    'mac_ids'       =>  'mac_ids_options_judgment',
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
        'add'  => [],
        'edit' => [],
    ];

    // 重复设置选项判断
    public function repeat_set_options_judgment($value, $rule, $data){
    	$repeat_set_options = array_keys(Config::get('repeat_set'));
    	if(!in_array($value, $repeat_set_options))
    		return __('Repeat set option error');
    	if('no-repeat' == $data['repeat_set']){
			$no_repeat_date = $data['no_repeat_date'];
			if(empty($no_repeat_date))
				return __('Date is need');
	    }elseif('weekday' == $data['repeat_set']){
    		if(empty($data['weekday']))
    			return __('Weekday set option error');

		    $weekday = $data['weekday'];
		    $all_weekday = Config::get('weekday');
		    if (array_diff($weekday, $all_weekday)){
			    return __('Weekday set option error');
		    }
	    }
	    return true;
    }

    // 跳转设置选项判断
    public function out_to_options_judgment($value, $rule, $data){
    	$out_to_info = Config::get('app_break_out_to');
    	$out_to_option = array_keys($out_to_info);
    	if(!in_array($data['out_to'], $out_to_option)){
			return __('Out to set option error');
	    }
	    return true;
    }

    // Mac设置选项判断
    public function mac_ids_options_judgment($value){
    	if(empty($value))
    		return __('Mac ids set option error');

    	$mac_list = explode(",", $value);
    	foreach ($mac_list as $key=>$value){
    		if(!is_numeric($value)){
    			return __('Mac选择设置错误');
		    }
	    }
	    return true;
    }
}
