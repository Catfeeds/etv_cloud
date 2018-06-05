<?php

namespace app\admin\model;

use think\Model;

class PropagandaCustom extends Model
{
    // 表名
    protected $name = 'propaganda_custom';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    public function custom(){
        return $this->belongsTo('Custom', 'custom_id', 'id')->setEagerlyType(0);
    }

	public function resource(){
		return $this->belongsTo('Propaganda_resource', 'rid', 'id')->setEagerlyType(1);
	}
}
