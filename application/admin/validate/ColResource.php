<?php

namespace app\admin\validate;

use think\Validate;

class ColResource extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'     =>  'require|max:40',
        'describe'  =>  'max:128',
        'resource_type' =>  'in:video,image,url',
        'resource'  =>  'require|max:100'
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
        'add'  => ['title', 'describe', 'resource_type', 'resource'],
        'edit' => ['title', 'describe', 'resource_type', 'resource'],
    ];
    
}
