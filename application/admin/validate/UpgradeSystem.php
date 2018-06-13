<?php

namespace app\admin\validate;

use think\Validate;

class UpgradeSystem extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
		'name'          =>  'require|max:40',
	    'discription'   =>  'max:250',
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
        'add'  => ['name', 'discription'],
        'edit' => ['name', 'discription'],
    ];
    
}
