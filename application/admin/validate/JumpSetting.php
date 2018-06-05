<?php

namespace app\admin\validate;

use think\Db;
use think\Validate;

class JumpSetting extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'play_set'      =>  'in:1,2,3,4,5',
	    'save_set'      =>  'in:1,2',
	    'custom_id'     =>  'check_existence',
    ];
    /**
     * 提示消息
     */
    protected $message = [
	    'custom_id'     =>  'Customid Tips'
    ];

	public function check_existence($value, $rule, $data){
		$where['custom_id'] = array('eq', $data['custom_id']);
		$jump_info = Db::name('jump_setting')->where($where)->field('custom_id')->find();
		if(!empty($jump_info)){
			return false;
		}else{
			return true;
		}
	}

    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['play_set', 'save_set', 'custom_id'],
        'edit' => ['play_set', 'save_set'],
    ];
    
}
