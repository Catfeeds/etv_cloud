<?php

namespace app\admin\model;

use think\Model;

class WelcomeResource extends Model
{
    // 表名
    protected $name = 'welcome_resource';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function welcome_custom(){
        return $this->hasMany('welcome_custom', 'id', 'id');
    }

}
