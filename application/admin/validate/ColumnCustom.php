<?php

namespace app\admin\validate;

use think\Validate;

class ColumnCustom extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'weigh'     =>  'integer',
        'status'    =>  'require|in:hidden,normal',
        'save_set'  =>  'in:1,2',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'weigh'     =>  '权重需为整数',
        'status'    =>  '状态有误',
        'save_set'  =>  '存储地址有误'
    ];
    /**
     * 验证场景
     */
    protected $scene = [
    ];
    
}
