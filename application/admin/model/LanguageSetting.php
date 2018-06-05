<?php

namespace app\admin\model;

use think\Model;
use think\Db;

class LanguageSetting extends Model
{
    // 表名
    protected $name = 'language_setting';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

    public function custom(){
        return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
    }

}
