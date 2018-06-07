<?php

namespace app\admin\validate;

use think\Validate;

class DeviceSleep extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'sleep_time_start'      =>  'regex:\d{2}:\d{2}',
        'sleep_time_end'        =>  'regex:\d{2}:\d{2}',
        'sleep_marked_word'     =>  'max:100',
        'sleep_countdown_time'  =>  'number',
        'sleep_image'           =>  'in:black,blue',
        'status'                =>  'in:normal,hidden',
        'mac_ids'               =>  'check_mac_ids',
    ];
    /**
     * 提示消息
     */
    protected $message = [

    ];

    public function check_mac_ids($params, $rule, $data){
        $mac_ids = explode(",", $data['mac_ids']);
        foreach($mac_ids as $value) {
            if(!is_numeric($value) || $value<1){
                return __('Mac_tips');
            }
        }
        return true;
    }



    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['sleep_time_start', 'sleep_time_end', 'sleep_marked_word', 'sleep_countdown_time', 'sleep_image', 'status'],
        'edit' => [],
    ];
    
}
