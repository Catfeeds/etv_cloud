<?php

namespace app\admin\model;

use think\Model;

class SimpleadCustom extends Model
{
    // 表名
    protected $name = 'simplead_custom';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = 'updatetime';

    public function custom(){
        return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
    }

	public function resource(){
		return $this->belongsTo('Simplead_resource', 'rid', 'id')->setEagerlyType(1);
	}
}
