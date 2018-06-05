<?php

namespace app\admin\model;

use think\Model;

class DeviceWifiset extends Model
{
    // 表名
    protected $name = 'device_wifiset';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    public function device_basics() {
        return $this->hasMany('device_basics', 'mac', 'mac');
    }



}
