<?php

namespace app\admin\model;

use think\Model;

class DeviceSleep extends Model
{
    // 表名
    protected $name = 'device_sleep';

    public function deviceBasics(){
        return $this->belongsTo('device_basics', 'mac', 'mac')->setEagerlyType(0);
    }

}
