<?php

namespace app\admin\validate;

use think\Validate;

class PopupResource extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'     => 'require|max:40',
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
	    'add'  => ['title', 'filepath'],
	    'edit' => ['title', 'filepath'],
    ];
    
}
