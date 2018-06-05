<?php

namespace app\admin\validate;

use think\Validate;

class Column extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'pid'       => 'require|number',
        'title'     => 'require|max:40',
        'filepath'  => 'max:100',
        'language_type' =>  'in:chinese,englist',
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
        'add'  => ['pid', 'title', 'filepath', 'language_type'],
        'edit' => ['title', 'filepath'],
    ];
    
}
