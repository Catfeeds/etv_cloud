<?php

namespace app\admin\validate;

use think\Validate;

class DeviceBasics extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'custom_id'     =>  'require|integer',
        'mac'           =>  'require|max:40',
        'room'          =>  'require|max:40',
        'room_remark'   =>  'require|max:60',
        'model'         =>  'require|max:60',
        'firmware_version'=>'require|max:60',
        'status'        =>  'in:normal,hidden'

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
        'add'  => ['custom_id', 'mac', 'room', 'room_remark', 'model', 'firmware_version', 'status'],
        'edit' => ['room', 'room_remark'],
    ];
    
}
