<?php

namespace app\admin\model;

use think\Model;

class DeviceParaLog extends Model
{
    // 表名
    protected $name = 'device_para_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'runtime_text'
    ];
    

    



    public function getRuntimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['runtime'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setRuntimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
