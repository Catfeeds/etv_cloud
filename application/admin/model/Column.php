<?php

namespace app\admin\model;

use think\Model;

class Column extends Model
{
    // 表名
    protected $name = 'column';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

}
