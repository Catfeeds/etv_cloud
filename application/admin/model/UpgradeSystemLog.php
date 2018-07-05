<?php

namespace app\admin\model;

use think\Model;

class UpgradeSystemLog extends Model
{
    // 表名
    protected $name = 'upgrade_system_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    public function custom(){
	    return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
    }

    public function mac(){
	    return $this->belongsTo('device_basics', 'mac_id', 'id')->setEagerlyType(0);
    }

}
