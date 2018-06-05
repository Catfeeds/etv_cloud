<?php

namespace app\admin\model;

use think\Model;

class SimpleadResource extends Model
{
    // 表名
    protected $name = 'simplead_resource';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';

	public function simplead_custom(){
		return $this->hasMany('simplead_custom', 'id', 'id');
	}

}
