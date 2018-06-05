<?php

namespace app\admin\validate;

use think\Validate;

class SimpleadCustom extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'     =>  'require|max:40',
        'url_to|跳转地址'    =>  'max:100|url',
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
        'add'  => ['title', 'url_to'],
        'edit' => ['title', 'url_to'],
    ];
    
}
