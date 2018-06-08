<?php

namespace app\admin\validate;

use think\Validate;

class MessageNotice extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'custom_id'     =>  'require|number',
	    'title'         =>  'require|max:100',
	    'content'       =>  'require|max:256',
	    'push_type'     =>  'in:immediate,user defined',
	    'push_end_time' =>  'require|date|check_time',
	    'push_start_time'=> 'date',
	    'status'        =>  'in:normal,hidden',
	    'mac_ids'       =>  'check_mac',

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

	// 校验推送时间不能大于结束时间
	public function check_time($value, $rule, $data) {
		$end_time = strtotime($value);
		$start_time = $data['push_type'];
		if($start_time>$end_time){
			return __('Push time is later than the end time');
		}else{
			return true;
		}
	}

	/**
	 * 判断MAC列表
	 */
	public function check_mac($value)
	{
		if(strpos($value, ",") === false){
			if(is_numeric($value)){
				return true;
			}else{
				return __('Mac parameter error');
			}
		}else{
			$maclist = explode(",", $value);
			foreach($maclist as $value){
				if(!is_numeric($value)){
					return __('Mac parameter error');
				}
			}
			return true;
		}

	}
    
}
