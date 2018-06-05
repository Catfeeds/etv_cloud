<?php

namespace app\admin\validate;

use think\Validate;

class WelcomeCustom extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'     =>  'require|max:40',
        'stay_set'  =>  'in:1,2',
        'stay_time' =>  'integer',
        'weigh'     =>  'number|gt:0'
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
        'edit' => ['title', 'stay_set', 'stay_time', 'weigh'],
    ];
    
}
