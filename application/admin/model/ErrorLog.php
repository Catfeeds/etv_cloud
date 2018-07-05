<?php

namespace app\admin\model;

use think\Model;

class ErrorLog extends Model
{
    // 表名
    protected $name = 'error_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'error_time_text'
    ];
    

    



    public function getErrorTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['error_time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setErrorTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
