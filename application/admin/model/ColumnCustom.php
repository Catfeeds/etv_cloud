<?php

namespace app\admin\model;

use think\Model;

class ColumnCustom extends Model
{
    // 表名
    protected $name = 'column_custom';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
}
