<?php

namespace app\admin\validate;

use think\Validate;

class TimingAppResource extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'title'         =>  'require|max:50',
        'classname'     =>  'require|max:100',
        'packagename'   =>  'require|max:100',
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
        'add'  => ['title', 'classname', 'packagename'],
        'edit' => ['title', 'classname', 'packagename'],
    ];
    
}
