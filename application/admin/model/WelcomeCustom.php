<?php

namespace app\admin\model;

use think\Model;

class WelcomeCustom extends Model
{
    // 表名
    protected $name = 'welcome_custom';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $updateTime = 'updatetime';

    public function custom(){
        return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
    }

    public function resource(){
        return $this->belongsTo('Welcome_resource', 'rid', 'id')->setEagerlyType(1);
    }

}
