<?php

namespace app\admin\validate;

use think\Validate;

class WelcomeResource extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'     => 'require|max:40',
	    'describe'  => 'max:160',
	    'filepath'  => 'require|max:100',
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
        'add'  => ['title', 'describe', 'filepath'],
        'edit' => ['title', 'describe', 'filepath'],
    ];
    
}
